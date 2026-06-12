<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JWisataController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\VotingWisataController;
use App\Http\Controllers\Admin\PembayaranWisataController as AdminPembayaranWisataController;
use App\Http\Controllers\Admin\PembayaranTunaiController;
use App\Http\Controllers\Admin\LaporanKeuanganController;
use App\Http\Controllers\Instruktur\InstrukturController;
use App\Http\Controllers\Instruktur\AbsensiSenamController;
use App\Http\Controllers\Anggota\DaftarWisataController;
use App\Http\Controllers\Anggota\PembayaranIuranController;
use App\Http\Controllers\Anggota\SaldoController;
use App\Http\Controllers\Anggota\PembayaranWisataController;
use App\Http\Controllers\Anggota\VoteWisataController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
});

// Route::get('/landing', function () {
//     return view('landing');
// });

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

/*
|--------------------------------------------------------------------------
| REDIRECT SETELAH LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/redirect', function () {

    $user = auth()->user();

    if (!$user->role) {
        abort(403, 'User belum punya role');
    }

    $role = strtolower($user->role->nama_role);

    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($role === 'instruktur') {
        return redirect()->route('instruktur.dashboard');
    }

    if ($role === 'anggota') {
        return redirect()->route('anggota.dashboard');
    }

    abort(403, 'Role tidak dikenali: ' . $role);

})->middleware('auth');

/*
|--------------------------------------------------------------------------
| PROFIL (SEMUA ROLE)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Route GET ini bisa dihapus jika kamu tidak ingin ada halaman profil terpisah
    // Route::get('/profil', [UserController::class, 'editProfil'])->name('profil.edit');

    // Route UTAMA untuk proses update dari Modal
    Route::put('/profil/update', [UserController::class, 'updateProfil'])
        ->name('profil.update');
});

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // PENDAFTARAN
        Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
        Route::get('/pendaftaran/{id}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
        // Route::post('/pendaftaran/{id}/status', [PendaftaranController::class, 'updateStatus'])->name('pendaftaran.updateStatus');
        Route::delete('/pendaftaran/{id}', [PendaftaranController::class, 'destroy'])->name('pendaftaran.destroy');

        // USER
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

        // JWISATA
        Route::get('/jwisata', [JWisataController::class, 'index'])->name('jwisata.index');
        Route::get('/jwisata/create', [JWisataController::class, 'create'])->name('jwisata.create');
        Route::post('/jwisata/store', [JWisataController::class, 'store'])->name('jwisata.store');
        Route::get('/jwisata/{id}/edit', [JWisataController::class, 'edit'])->name('jwisata.edit');
        Route::put('/jwisata/{id}', [JWisataController::class, 'update'])->name('jwisata.update');
        Route::delete('/jwisata/{id}', [JWisataController::class, 'destroy'])->name('jwisata.destroy');
        Route::post('/jwisata/toggle/{id}', [JWisataController::class, 'toggle'])->name('jwisata.toggle');

        //VOTING
        Route::get('/voting-wisata', [VotingWisataController::class, 'index'])->name('voting-wisata.index');
        Route::get('/voting-wisata/create', [VotingWisataController::class, 'create'])->name('voting-wisata.create');
        Route::post('/voting-wisata/store', [VotingWisataController::class, 'store'])->name('voting-wisata.store');
        Route::get('/voting-wisata/{id}', [VotingWisataController::class, 'show'])->name('voting-wisata.show');
        Route::get('/voting-wisata/{id}/edit', [VotingWisataController::class, 'edit'])->name('voting-wisata.edit');
        Route::put('/voting-wisata/{id}', [VotingWisataController::class, 'update'])->name('voting-wisata.update');
        Route::delete('/voting-wisata/{id}', [VotingWisataController::class, 'destroy'])->name('voting-wisata.destroy');
        Route::patch('/voting-wisata/{id}/status', [VotingWisataController::class, 'updateStatus'])->name('voting-wisata.updateStatus');
        Route::post('/voting-wisata/{id}/selesaikan', [VotingWisataController::class, 'selesaikanVoting'])->name('voting-wisata.selesaikan');

        //TRANSAKSI PEMBAYARAN
        Route::view('/transaksi', 'admin.transaksi.index')->name('transaksi.index');

        Route::get('/transaksi/tunai', [PembayaranTunaiController::class, 'index'])->name('tunai.index');
        Route::get('/transaksi/tunai/{id}', [PembayaranTunaiController::class, 'detail'])->name('tunai.detail');
        Route::post('/transaksi/tunai/store', [PembayaranTunaiController::class, 'store'])->name('tunai.store');
        Route::post('/transaksi/tunai/multi-bayar', [PembayaranTunaiController::class, 'multiBayar'])->name('tunai.multi-bayar');

        Route::get('/transaksi/wisata', [AdminPembayaranWisataController::class, 'wisata'])->name('wisata.index');
        Route::get('/transaksi/wisata/{id}', [AdminPembayaranWisataController::class, 'show'])->name('wisata.show');
        Route::post('/transaksi/wisata/{id}/bayar', [AdminPembayaranWisataController::class, 'bayarCash'])->name('wisata.bayar');
        Route::get('/transaksi/wisata/{id}/histori', [AdminPembayaranWisataController::class, 'getHistoriJson'])->name('wisata.histori');
        Route::get('/transaksi/wisata/nota/{id_pembayaran}', [AdminPembayaranWisataController::class, 'cetakNota'])->name('wisata.nota');

        Route::get('/laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan.keuangan');
        Route::get('/laporan/keaktifan', [LaporanKeuanganController::class, 'keaktifan'])->name('laporan.keaktifan');
        Route::get('/laporan/tagihan', [LaporanKeuanganController::class, 'tagihan'])->name('laporan.tagihan');
        Route::get('/laporan/tagihan/pdf', [LaporanKeuanganController::class, 'exportPdf'])->name('laporan.tagihan.pdf');
    Route::get(
        '/laporan/tagihan/whatsapp/{id}',
        [LaporanKeuanganController::class, 'kirimWaTagihan']
    )->name('laporan.tagihan.whatsapp');
    Route::get('/laporan/tagihan/whatsapp-all', [LaporanKeuanganController::class, 'sendWhatsappAll'])
    ->name('laporan.tagihan.whatsapp.all');

});

/*
|--------------------------------------------------------------------------
| INSTRUKTUR AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:instruktur'])
    ->prefix('instruktur')
    ->name('instruktur.')
    ->group(function () {

        Route::get('/dashboard', [InstrukturController::class, 'dashboard'])->name('dashboard');

        Route::get('/jsenam', [InstrukturController::class, 'index'])->name('jsenam.index');
        Route::get('/jsenam/create', [InstrukturController::class, 'create'])->name('jsenam.create');
        Route::post('/jsenam/store', [InstrukturController::class, 'store'])->name('jsenam.store');
        Route::get('/jsenam/{id}/edit', [InstrukturController::class, 'edit'])->name('jsenam.edit');
        Route::put('/jsenam/{id}', [InstrukturController::class, 'update'])->name('jsenam.update');
        Route::delete('/jsenam/{id}', [InstrukturController::class, 'destroy'])->name('jsenam.destroy');
        Route::get('/absensi/{id_senam}', [AbsensiSenamController::class, 'show'])->name('absensi.show');
});

/*
|--------------------------------------------------------------------------
| ANGGOTA AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:anggota'])
    ->prefix('anggota')
    ->name('anggota.')
    ->group(function () {

        Route::get('/dashboard', [DaftarWisataController::class, 'dashboard'])->name('dashboard');
        Route::get('/dafwisata', [DaftarWisataController::class, 'index'])->name('dafwisata.index');
        Route::post('/dafwisata/daftar', [DaftarWisataController::class, 'store'])->name('dafwisata.store');
        Route::get('/jadwal-senam', [InstrukturController::class, 'jadwalAnggota'])->name('jsenam.index');
        Route::post('/absensi/{id_senam}', [AbsensiSenamController::class, 'store'])->name('absensi.store');
        Route::get('/saldo', [SaldoController::class, 'index'])->name('saldo');
        Route::post('/saldo/topup', [SaldoController::class, 'topup'])->name('saldo.topup');

        // VOTE WISATA
        Route::get('/vote-wisata', [VoteWisataController::class, 'index'])->name('vote-wisata.index');
        Route::get('/vote-wisata/{id}', [VoteWisataController::class, 'show'])->name('vote-wisata.show');
        Route::post('/vote-wisata/{id}', [VoteWisataController::class, 'store'])->name('vote-wisata.store');

        // BAYAR IURAN SENAM
        Route::get('/iuran/{id_senam}', [PembayaranIuranController::class, 'show'])->name('iuran.show');
        Route::post('/iuran/bayar/{id_senam}', [PembayaranIuranController::class, 'bayar'])->name('iuran.bayar');

        // BAYAR WISATA
        Route::get('/pembayaran-wisata', [PembayaranWisataController::class, 'index'])->name('pembayaran-wisata.index');
        Route::get('/pembayaran-wisata/{id}', [PembayaranWisataController::class, 'show'])->name('pembayaran-wisata.show');
        Route::post('/pembayaran-wisata/{id}/bayar', [PembayaranWisataController::class, 'bayar'])->name('pembayaran-wisata.bayar');
        Route::get('/pembayaran-wisata/nota/{id_pembayaran}', [PembayaranWisataController::class, 'cetakNota'])->name('pembayaran-wisata.nota');
    });

// CALLBACK MIDTRANS
Route::post('/midtrans/callback', [PembayaranIuranController::class, 'callback']);
Route::post('/midtrans/saldo/callback', [SaldoController::class, 'callback']);
