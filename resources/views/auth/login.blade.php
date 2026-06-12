<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Informasi Senam BSC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght=300;400;500;600&display=swap" rel="stylesheet">
    <!-- Tambahan CDN SweetAlert2 untuk Pop-up Alert yang Keren -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e2e8f0' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2v-4h4v-2H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        /* Kustomisasi font tombol SweetAlert2 biar serasi dengan Inter */
        .swal2-styled {
            font-family: 'Inter', sans-serif !important;
            border-radius: 12px !important;
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-10">

<div class="flex flex-col lg:flex-row bg-white shadow-xl rounded-[24px] overflow-hidden max-w-4xl w-full border border-gray-100 my-auto">

    <!-- LEFT SIDE: Logo & Judul Sistem -->
    <div class="w-full lg:w-1/2 bg-gray-50/50 flex flex-col items-center justify-center p-6 sm:p-10 border-b lg:border-b-0 lg:border-r border-gray-100">
        <div class="mb-4 lg:mb-6">
            <img src="{{ asset('images/logo_senam.png') }}"
                 alt="Logo Senam"
                 class="w-24 h-24 sm:w-32 sm:h-32 lg:w-40 lg:h-40 object-contain">
        </div>
        <h1 class="text-xs sm:text-sm lg:text-base font-semibold text-gray-500 tracking-[0.2em] uppercase text-center leading-relaxed">
            Sistem Informasi<br class="hidden sm:inline"> Senam BSC
        </h1>
    </div>

    <!-- RIGHT SIDE: Form Login -->
    <div class="w-full lg:w-1/2 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">

        <div class="mb-6 text-center">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Login</h2>
            <p class="text-xs text-gray-400 mt-1">Silakan masuk ke akun Anda</p>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl shadow-sm">
                <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm text-left">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- LOGIN FAILED --}}
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl shadow-sm text-left">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- EMAIL -->
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">
                    Email Address
                </label>
                <input type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-2.5 bg-white border 
                    @error('email') border-red-500 focus:ring-red-500/20 focus:border-red-500
                    @else border-gray-200 focus:ring-blue-500/10 focus:border-blue-500
                    @enderror
                    rounded-xl outline-none transition-all text-sm shadow-sm"
                    placeholder="nama@email.com">

                @error('email')
                    <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5 ml-1">
                    Password
                </label>

                <div class="relative w-full flex items-center">
                    <input type="password"
                        id="passwordField"
                        name="password"
                        required
                        class="w-full px-4 py-2.5 pr-12 bg-white border 
                        @error('password') border-red-500 focus:ring-red-500/20 focus:border-red-500
                        @else border-gray-200 focus:ring-blue-500/10 focus:border-blue-500
                        @enderror
                        rounded-xl outline-none transition-all text-sm shadow-sm"
                        placeholder="••••••••">

                    <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 text-gray-400 hover:text-blue-600 transition-colors flex items-center justify-center h-full">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTON MASUK -->
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-3.5 rounded-xl shadow-md hover:shadow-lg transition-all active:scale-[0.98] mt-2">
                Masuk
            </button>
            
            <div class="text-center mt-6">
                <p class="text-xs sm:text-sm text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}"
                       class="text-blue-600 font-bold hover:text-blue-700 hover:underline transition ml-1">
                        Daftar di sini!
                    </a>
                </p>
            </div>
        </form>

    </div>
</div>

<script>
    // SCRIPT TOGGLE SHOW/HIDE PASSWORD
    function togglePassword() {
        const passwordField = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 012.235-3.345m1.69-1.69A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.202 2.477M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
        } else {
            passwordField.type = 'password';
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
        }
    }

    // TRIGGER POP-UP ALERT SAAT ADA SESSION SUCCESS
    @if(session('success'))
        Swal.fire({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            confirmButtonText: "Oke",
            confirmButtonColor: "#2563eb", // Menyelaraskan warna tombol dengan tema biru Tailwind
            customClass: {
                popup: 'rounded-[24px]' // Menyelaraskan kebulatan sudut pop-up dengan kartu login
            }
        });
    @endif
</script>

</body>
</html>