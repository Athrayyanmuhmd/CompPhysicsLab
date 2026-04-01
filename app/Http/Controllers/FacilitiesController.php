<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class FacilitiesController extends Controller
{
    public function index()
    {
        $facility = Facility::first();

        // Jika belum ada data fasilitas, buat default
        if (!$facility) {
            $facility = new Facility();
            $facility->title = 'Fasilitas Laboratorium Fisika Komputasi';
            $facility->description = 'Laboratorium Fisika Komputasi dilengkapi dengan berbagai fasilitas modern untuk mendukung kegiatan praktikum dan pembelajaran mahasiswa.';
            $facility->facility_points = [
                'Ruang laboratorium yang luas dan nyaman',
                'Peralatan praktikum lengkap dan modern',
                'Kapasitas hingga 40 mahasiswa'
            ];
            $facility->images = [];
        }

        return view('facilities', compact('facility'));
    }
}
