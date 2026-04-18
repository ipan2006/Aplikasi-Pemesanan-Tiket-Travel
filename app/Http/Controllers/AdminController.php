<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DataDiri;
use App\Models\JadwalTravel;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
{
    $totalPemesanan = Pemesanan::count();
    $totalPaid      = Pemesanan::where('status_pembayaran', 'approved')->count();
    $totalPending   = Pemesanan::where('status_pembayaran', 'pending')->count();
    $totalCancelled = Pemesanan::where('status_pembayaran', 'rejected')->count();

    $orders = Pemesanan::with(['jadwal','dataDiri'])
        ->orderBy('created_at','desc')
        ->get();

    return view('admin.dashboard', compact(
        'totalPemesanan','totalPaid','totalPending','totalCancelled','orders'
    ));
}

public function acc($id)
{
    $order = Pemesanan::findOrFail($id);
    $order->status_pembayaran = 'approved';
    $order->save();

    return redirect()->route('admin.dashboard')->with('success', 'Pemesanan berhasil di-ACC');
}

public function reject($id)
{
    $order = Pemesanan::findOrFail($id);
    $order->status_pembayaran = 'rejected';
    $order->save();

    return redirect()->route('admin.dashboard')->with('error', 'Pemesanan ditolak');
}

public function pemesanan()
{
    $orders = Pemesanan::with(['jadwal','dataDiri'])->latest()->get();

    // Hitung statistik
    $totalPemesanan = Pemesanan::count();
    $totalPaid      = Pemesanan::where('status_pembayaran', 'approved')->count();
    $totalPending   = Pemesanan::where('status_pembayaran', 'pending')->count();
    $totalCancelled = Pemesanan::where('status_pembayaran', 'rejected')->count();

    return view('admin.kelola_pemesanan.index', compact(
        'orders',
        'totalPemesanan',
        'totalPaid',
        'totalPending',
        'totalCancelled'
    ));
}

public function ubahJadwal(Request $request, $id)
{
    $order = Pemesanan::findOrFail($id);
    $order->tanggal_berangkat = $request->tanggal_berangkat;
    $order->jadwal_id = $request->jadwal_id; // jika ada relasi jadwal
    $order->save();

    return redirect()->route('admin.pemesanan.index')->with('success','Jadwal berhasil diubah');
}

public function batal($id)
{
    $order = Pemesanan::findOrFail($id);
    $order->status_pembayaran = 'rejected'; // atau status khusus "dibatalkan"
    $order->save();

    return redirect()->route('admin.pemesanan.index')->with('success','Pesanan berhasil dibatalkan');
}

public function hapus($id)
{
    // Hapus anak dulu
    DataDiri::where('id_pemesanan', $id)->delete();

    // Baru hapus induk
    $order = Pemesanan::findOrFail($id);
    $order->delete();

    return redirect()->route('admin.pemesanan.index')->with('success','Pesanan berhasil dihapus');
}
public function jadwal()
{
    $jadwalList = JadwalTravel::all(); // ambil semua jadwal

    return view('admin.jadwal.index', compact('jadwalList'));
}


public function rute()
{
    // return view rute admin
}

public function updateJam(Request $request, $id)
{
    $request->validate([
        'jam' => 'required|date_format:H:i',
    ]);

    $jadwal = JadwalTravel::findOrFail($id);
    $jadwal->jam = $request->jam;
    $jadwal->save();

    return redirect()->route('admin.jadwal.index')->with('success', 'Jam berhasil diubah');
}
public function uploadBukti(Request $request, $id)
{
    $request->validate([
        'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $order = Pemesanan::findOrFail($id);

    // Simpan file ke storage/app/public/bukti_transfer
    $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

    // Update kolom bukti_transfer di database
    $order->bukti_transfer = $path;
    $order->save();

    return redirect()->route('pembayaran.show')->with('success', 'Bukti transfer berhasil diupload');
}
}