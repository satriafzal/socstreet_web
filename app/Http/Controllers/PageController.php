<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the Socstreet landing homepage.
     */
    public function home(): View
    {
        // Showcase data for the single field: Socstreet Main Arena
        $arena = [
            'name' => 'Socstreet Main Arena',
            'tagline' => 'Standar Lapangan Minisoccer Kompetisi Resmi',
            'dimensions' => '42 × 25 Meter (Standar WMF)',
            'grass_type' => 'Monofilament 50mm + Eco Infill Rubber',
            'lighting' => 'LED High-Output 500 Lux Anti-Glare',
            'price_per_hour' => 250000,
            'is_available_today' => true,
            'features' => [
                'Rumput Sintetis Monofilament Premium',
                'Pencahayaan LED 500 Lux Siap Siang & Malam',
                'Ruang Ganti Lengkap & Hot Water Shower',
                'Free High-Speed Wi-Fi & Lounge Atlet',
                'Tribun Penonton Kapasitas 100 Orang',
                'Area Parkir Luas & Keamanan 24 Jam',
            ],
        ];

        // Facilities list
        $facilities = [
            [
                'title' => 'Rumput Sintetis FIFA Pro',
                'desc' => 'Serat monofilament dengan peredam benturan optimal, mencegah cedera ligamen.',
                'icon' => 'soccer',
            ],
            [
                'title' => 'Lampu LED Match Standard',
                'desc' => 'Pencahayaan rata 500 Lux tanpa titik buta untuk kenyamanan bermain malam.',
                'icon' => 'lighting',
            ],
            [
                'title' => 'Loker & Hot Shower',
                'desc' => 'Ruang bilas bersih dengan air hangat, loker penyimpanan barang berkunci aman.',
                'icon' => 'shower',
            ],
            [
                'title' => 'Lounge & Free Wi-Fi',
                'desc' => 'Ruang santai ber-AC dengan koneksi internet cepat untuk pemain dan penonton.',
                'icon' => 'wifi',
            ],
            [
                'title' => 'Parkir Luas & Terjaga',
                'desc' => 'Area parkir mobil dan motor memadai dengan pantauan CCTV 24 jam nonstop.',
                'icon' => 'car',
            ],
            [
                'title' => 'Cafe & Refreshment',
                'desc' => 'Menyediakan minuman elektrolit dingin, suplemen olahraga, dan makanan ringan.',
                'icon' => 'coffee',
            ],
        ];

        // Booking steps
        $steps = [
            [
                'step' => '01',
                'title' => 'Cek & Pilih Jadwal',
                'desc' => 'Lihat ketersediaan jam bermain di Socstreet Main Arena secara real-time.',
            ],
            [
                'step' => '02',
                'title' => 'Tentukan Tipe Bayar',
                'desc' => 'Fleksibel dengan opsi Down Payment (DP 50%) atau bayar Lunas 100%.',
            ],
            [
                'step' => '03',
                'title' => 'Pembayaran Instan',
                'desc' => 'Gunakan QRIS, Transfer Bank (BCA/Mandiri/BRI), atau E-Wallet favorit Anda.',
            ],
            [
                'step' => '04',
                'title' => 'Konfirmasi & Bertanding',
                'desc' => 'Invoice diterbitkan otomatis, tunjukkan ID Booking saat tiba di arena.',
            ],
        ];

        return view('pages.home', compact('arena', 'facilities', 'steps'));
    }
}

