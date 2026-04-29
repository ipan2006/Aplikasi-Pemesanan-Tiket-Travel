<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function pay()
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');

        $order_id = uniqid();

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => 10000,
            ],
            'payment_type' => 'bank_transfer',
            'bank_transfer' => [
                'bank' => 'bca'
            ]
        ];

        try {
            $response = \Midtrans\CoreApi::charge($params);

            $va = $response->va_numbers[0]->va_number ?? null;
            $bank = $response->va_numbers[0]->bank ?? null;

            $transaction = Transaction::create([
                'order_id' => $order_id,
                'amount' => 10000,
                'status' => 'pending',
                'va_number' => $va,
                'bank' => $bank,
            ]);

            return view('payment-result', compact('transaction'));

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Midtrans error',
                'message' => $e->getMessage()
            ]);
        }
    }
public function callback(Request $request)
{
    Log::info('MIDTRANS CALLBACK:', $request->all());

    $data = $request->all();

    $transaction = Transaction::where('order_id', $data['order_id'] ?? null)->first();

    if ($transaction) {

        switch ($data['transaction_status']) {
            case 'settlement':
                $transaction->status = 'success';
                break;

            case 'pending':
                $transaction->status = 'pending';
                break;

            case 'expire':
                $transaction->status = 'expired';
                break;

            case 'cancel':
                $transaction->status = 'cancelled';
                break;

            default:
                $transaction->status = 'pending';
        }

        $transaction->save();
    }

    return response()->json(['status' => 'ok']);
}
}