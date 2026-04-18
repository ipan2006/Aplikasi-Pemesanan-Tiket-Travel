<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PemesananApiController extends Controller
{
    public function cuaca(Request $request)
    {
        $kota = $request->get('kota', 'Purwokerto');

        $response = Http::get(config('services.openweather.base_url') . '/weather', [
            'q'     => $kota . ',ID',
            'appid' => config('services.openweather.key'),
            'units' => 'metric',
            'lang'  => 'id',
        ]);

        $data = $response->json();

        return response()->json([
            'status'  => 'success',
            'kota'    => $data['name'] ?? $kota,
            'cuaca'   => [
                'deskripsi'  => $data['weather'][0]['description'] ?? '-',
                'suhu'       => round($data['main']['temp'] ?? 0) . '°C',
                'terasa'     => round($data['main']['feels_like'] ?? 0) . '°C',
                'kelembapan' => ($data['main']['humidity'] ?? 0) . '%',
                'angin'      => round(($data['wind']['speed'] ?? 0) * 3.6) . ' km/jam',
                'icon'       => 'https://openweathermap.org/img/wn/' . ($data['weather'][0]['icon'] ?? '01d') . '@2x.png',
            ],
        ]);
    }

    public function index()
    {
        $pemesanan = Pemesanan::with(['jadwal', 'dataDiri'])->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar Pemesanan',
            'data'    => $pemesanan,
        ], 200);
    }
    public function store(Request $request)
    {
        $request['status_pemesanan'] = 'pending_pembayaran'; // ← sesuai kolom di db kamu

        $pemesanan = Pemesanan::create($request->all());

        return response()->json([
            'status'  => 'success',
            'message' => 'Pemesanan berhasil dibuat',
            'data'    => $pemesanan,
        ], 201);
    }
}