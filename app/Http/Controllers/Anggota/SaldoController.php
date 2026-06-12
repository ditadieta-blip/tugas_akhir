<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;

class SaldoController extends Controller
{
    public function index()
    {
        return view('anggota.jadwal.saldo');
    }

    public function topup(Request $request)
{
    try {

        $request->validate([
            'nominal' => 'required|numeric|min:1000'
        ]);

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'TOPUP-' . time() . '-' . Auth::id();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->nominal,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->nama_user,
                'email' => Auth::user()->email,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snap_token' => $snapToken,
            'order_id' => $orderId
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}

public function callback(Request $request)
{
    \Log::info('MIDTRANS CALLBACK MASUK', $request->all());

    $orderId = $request->order_id;
    $status  = $request->transaction_status;

    if ($status == 'settlement') {

        $gross = (int)$request->gross_amount;

        $userId = explode('-', $orderId)[2] ?? null;

        $user = User::where('id_user', $userId)->first();

        if (!$user) {

            \Log::info('USER TIDAK DITEMUKAN', [
                'user_id' => $userId
            ]);

            return response()->json(['success' => false]);
        }

        \Log::info('SALDO SEBELUM', [
            'saldo' => $user->saldo_iuran
        ]);

        $user->update([
            'saldo_iuran' => $user->saldo_iuran + $gross
        ]);

        $user->refresh();

        \Log::info('SALDO SESUDAH', [
            'saldo' => $user->saldo_iuran
        ]);
    }

    return response()->json(['success' => true]);
}
}