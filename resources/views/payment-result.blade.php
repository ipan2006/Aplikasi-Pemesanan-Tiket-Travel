<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">

    <h2 class="text-2xl font-bold text-center mb-6">💳 Rincian Pembayaran</h2>

    <div class="space-y-3 text-gray-700">
        <p><strong>Order ID:</strong> {{ $transaction->order_id }}</p>
        <p><strong>Bank:</strong> {{ strtoupper($transaction->bank) }}</p>
        <p><strong>VA Number:</strong> {{ $transaction->va_number }}</p>

        <p>
            <strong>Status:</strong>
            <span class="
                px-3 py-1 rounded-full text-white
                {{ $transaction->status == 'success' ? 'bg-green-500' : '' }}
                {{ $transaction->status == 'pending' ? 'bg-yellow-500' : '' }}
                {{ $transaction->status == 'expired' ? 'bg-red-500' : '' }}
            ">
                {{ $transaction->status }}
            </span>
        </p>
    </div>

    <hr class="my-5">

    <h3 class="font-semibold mb-3">Pilih Metode Pembayaran</h3>

    <div class="grid grid-cols-3 gap-3">

        <div class="border rounded-lg p-3 text-center cursor-pointer hover:bg-gray-100">
            BCA
        </div>

        <div class="border rounded-lg p-3 text-center cursor-pointer hover:bg-gray-100 opacity-50">
            BNI
        </div>

        <div class="border rounded-lg p-3 text-center cursor-pointer hover:bg-gray-100 opacity-50">
            BRI
        </div>

        <div class="border rounded-lg p-3 text-center cursor-pointer hover:bg-gray-100 opacity-50">
            Mandiri
        </div>

        <div class="border rounded-lg p-3 text-center cursor-pointer hover:bg-gray-100 opacity-50">
            QRIS
        </div>

    </div>

    <p class="text-xs text-gray-400 mt-4 text-center">
        *Saat ini hanya BCA yang aktif (simulasi)
    </p>

</div>
<script>
@if($transaction->status == 'pending')
setInterval(() => {
    location.reload();
}, 5000);
@endif
</script>
</body>
</html>