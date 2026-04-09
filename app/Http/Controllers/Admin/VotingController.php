<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voting;

class VotingController extends Controller
{
    // list voting
    public function index()
    {
        $voting = Voting::latest()->get();
        return view('admin.voting.index', compact('voting'));
    }

    // form tambah
    public function create()
    {
        return view('admin.voting.create');
    }

    // simpan
    public function store(Request $request)
    {
        Voting::create([
            'judul' => $request->judul,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'is_active' => false
        ]);

        return redirect()->route('admin.voting');
    }

    // buka voting
    public function buka($id)
    {
        Voting::where('id_voting', $id)->update([
            'is_active' => true
        ]);

        return back();
    }

    // tutup voting
    public function tutup($id)
    {
        Voting::where('id_voting', $id)->update([
            'is_active' => false
        ]);

        return back();
    }
}