<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JWisata; 

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahAnggota = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'anggota');
        })->count();

        $jumlahInstruktur = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'instruktur');
        })->count();

        $totalWisata = JWisata::count();

        return view('admin.dashboard.index', compact(
            'jumlahAnggota',
            'jumlahInstruktur',
            'totalWisata'
        ));
    }
}