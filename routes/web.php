<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JWisataController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\VotingController;
use App\Http\Controllers\Instruktur\InstrukturController;
use App\Http\Controllers\Anggota\DaftarWisataController;
use App\Http\Controllers\Anggota\PembayaranIuranController;

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
        Route::post('/pendaftaran/{id}/status', [PendaftaranController::class, 'updateStatus'])->name('pendaftaran.updateStatus');
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

        // 🔥 VOTING (ADMIN)
        Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
        Route::get('/voting/create', [VotingController::class, 'create'])->name('voting.create');
        Route::post('/voting/store', [VotingController::class, 'store'])->name('voting.store');

        Route::post('/voting/buka/{id}', [VotingController::class, 'buka'])->name('voting.buka');
        Route::post('/voting/tutup/{id}', [VotingController::class, 'tutup'])->name('voting.tutup');
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

        Route::get('/jadwal-senam', [InstrukturController::class, 'jadwalAnggota'])
            ->name('jsenam.index');
                //  IURAN SENAM
        Route::get('/iuran', [PembayaranIuranController::class, 'index'])
            ->name('iuran.index');

        Route::post('/iuran/bayar/{id_senam}', [PembayaranIuranController::class, 'bayar'])
            ->name('iuran.bayar');
});
Route::post('/midtrans/callback', [PembayaranIuranController::class, 'callback']);