<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | Sistem Informasi Senam BSC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e2e8f0' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2v-4h4v-2H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10">

<div class="flex flex-col lg:flex-row bg-white shadow-xl rounded-[24px] overflow-hidden max-w-5xl w-full border border-gray-100 my-auto">

    <!-- Bagian Kiri: Logo & Info -->
    <div class="w-full lg:w-5/12 bg-gray-50/50 flex flex-col items-center justify-center p-6 sm:p-10 border-b lg:border-b-0 lg:border-r border-gray-100">
        <div class="mb-4 lg:mb-6">
            <img src="{{ asset('images/logo_senam.png') }}"
                 alt="Logo Senam"
                 class="w-24 h-24 sm:w-32 sm:h-32 lg:w-44 lg:h-44 object-contain">
        </div>
        <h1 class="text-xs sm:text-sm lg:text-base font-semibold text-gray-500 tracking-[0.2em] uppercase text-center leading-relaxed">
            Sistem Informasi<br class="hidden sm:inline"> Senam BSC
        </h1>
    </div>

    <!-- Bagian Kanan: Form Registrasi -->
    <div class="w-full lg:w-7/12 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">

        <!-- JUDUL REGISTRASI (Sudah di-center penuh) -->
        <div class="mb-6 text-center">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Registrasi</h2>
            
        </div>

        {{-- NOTIFIKASI SUKSES --}}
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl flex items-start gap-3 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-left">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- NOTIFIKASI ERROR VALIDASI --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl shadow-sm text-left">
                <div class="font-semibold mb-1">Terjadi kesalahan:</div>
                <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            {{-- NAMA --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">
                    Nama Lengkap
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2.5 bg-white border
                    @error('name') border-red-500 focus:ring-red-500/20 focus:border-red-500
                    @else border-gray-200 focus:ring-blue-500/10 focus:border-blue-500
                    @enderror
                    rounded-xl outline-none transition-all text-sm shadow-sm"
                    placeholder="Masukkan nama lengkap">
            </div>

            {{-- ALAMAT --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">
                    Alamat
                </label>
                <textarea name="address" required rows="2"
                    class="w-full px-4 py-2.5 bg-white border
                    @error('address') border-red-500 focus:ring-red-500/20 focus:border-red-500
                    @else border-gray-200 focus:ring-blue-500/10 focus:border-blue-500
                    @enderror
                    rounded-xl outline-none transition-all text-sm shadow-sm resize-none"
                    placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
            </div>

            {{-- PHONE & EMAIL --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- PHONE --}}
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">
                        No. HP
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                        class="w-full px-4 py-2.5 bg-white border
                        @error('phone') border-red-500 focus:ring-red-500/20 focus:border-red-500
                        @else border-gray-200 focus:ring-blue-500/10 focus:border-blue-500
                        @enderror
                        rounded-xl outline-none transition-all text-sm shadow-sm"
                        placeholder="0812xxxx">
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 bg-white border
                        @error('email') border-red-500 focus:ring-red-500/20 focus:border-red-500
                        @else border-gray-200 focus:ring-blue-500/10 focus:border-blue-500
                        @enderror
                        rounded-xl outline-none transition-all text-sm shadow-sm"
                        placeholder="nama@gmail.com">
                </div>
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">
                    Password
                </label>
                <div class="relative w-full flex items-center">
                    <input type="password" id="passwordField" name="password" required
                        class="w-full px-4 py-2.5 pr-12 bg-white border
                        @error('password') border-red-500 focus:ring-red-500/20 focus:border-red-500
                        @else border-gray-200 focus:ring-blue-500/10 focus:border-blue-500
                        @enderror
                        rounded-xl outline-none transition-all text-sm shadow-sm"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword('passwordField','eyeIcon1')" 
                        class="absolute right-3 text-gray-400 hover:text-gray-600 flex items-center justify-center h-full">
                        <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- KONFIRMASI PASSWORD --}}
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">
                    Konfirmasi Password
                </label>
                <div class="relative w-full flex items-center">
                    <input type="password" id="confirmPasswordField" name="password_confirmation" required
                        class="w-full px-4 py-2.5 pr-12 bg-white border
                        @error('password_confirmation') border-red-500 focus:ring-red-500/20 focus:border-red-500
                        @else border-gray-200 focus:ring-blue-500/10 focus:border-blue-500
                        @enderror
                        rounded-xl outline-none transition-all text-sm shadow-sm"
                        placeholder="Ulangi password">
                    <button type="button" onclick="togglePassword('confirmPasswordField','eyeIcon2')" 
                        class="absolute right-3 text-gray-400 hover:text-gray-600 flex items-center justify-center h-full">
                        <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- BUTTON DAFTAR --}}
            <button type="submit"
                class="w-full bg-[#1e293b] hover:bg-slate-800 text-white text-sm font-bold py-3.5 rounded-xl shadow-lg transition-all active:scale-[0.98] mt-4">
                Daftar Akun
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-xs sm:text-sm text-gray-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline ml-1">Login</a>
            </p>
        </div>

    </div>
</div>

<script>
function togglePassword(fieldId, iconId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(iconId);

    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.235-3.345m1.69-1.69A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.202 2.477M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`; 
    } else {
        passwordField.type = "password";
        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
    }
}
</script>
</body>
</html>