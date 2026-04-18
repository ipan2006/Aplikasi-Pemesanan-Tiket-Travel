<x-layouts.app>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h2 class="text-3xl font-bold text-center mb-8">Dashboard Admin</h2>

        <!-- Statistik Pemesanan -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <h6 class="text-gray-500">Total Pemesanan</h6>
                <p class="text-2xl font-bold text-gray-800">{{ $totalPemesanan }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <h6 class="text-gray-500">Sudah Dibayar</h6>
                <p class="text-2xl font-bold text-green-600">{{ $totalPaid }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <h6 class="text-gray-500">Menunggu Pembayaran</h6>
                <p class="text-2xl font-bold text-yellow-500">{{ $totalPending }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <h6 class="text-gray-500">Dibatalkan</h6>
                <p class="text-2xl font-bold text-red-600">{{ $totalCancelled }}</p>
            </div>
        </div>

        <!-- Tabel Pemesanan -->
        <h4 class="text-xl font-semibold mb-4">Daftar Pemesanan</h4>
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">ID</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Nama</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Rute</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Tanggal</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Bukti Transfer</th>
                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <tr>
                            <td class="px-4 py-2">{{ $order->id_pemesanan }}</td>
                            <td class="px-4 py-2">{{ $order->dataDiri->nama ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $order->jadwal->asal ?? '-' }} → {{ $order->jadwal->tujuan ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $order->tanggal_berangkat }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    @if($order->status_pembayaran=='approved') bg-green-100 text-green-700
                                    @elseif($order->status_pembayaran=='pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ strtoupper($order->status_pembayaran) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                @if($order->bukti_transfer)
                                    <a href="{{ asset('storage/'.$order->bukti_transfer) }}" target="_blank"
                                    class="text-blue-600 hover:underline text-sm">Lihat Bukti</a>
                                @else
                                    <span class="text-gray-400 text-sm">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                @if($order->status_pembayaran == 'pending')
                                    <form action="{{ route('admin.pemesanan.acc', $order->id_pemesanan) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs px-3 py-1 rounded">Terima</button>
                                    </form>
                                    <form action="{{ route('admin.pemesanan.reject', $order->id_pemesanan) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded">Tolak</button>
                                    </form>
                                @elseif($order->status_pembayaran == 'approved')
                                    <span class="text-green-600 text-sm font-semibold">Sudah Diterima</span>
                                @elseif($order->status_pembayaran == 'rejected')
                                    <span class="text-red-600 text-sm font-semibold">Ditolak</span>
                                @else
                                    <span class="text-gray-400 text-sm">Status tidak dikenal</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Navigasi Admin -->
        <div class="mt-6 flex justify-center space-x-4">
            <a href="{{ route('admin.pemesanan.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Kelola Pemesanan</a>
    </div>
</x-layouts.app>