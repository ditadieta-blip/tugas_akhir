<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Informasi Senam BSC</title>
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
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

<div class="flex flex-col md:flex-row bg-white shadow-xl rounded-2xl overflow-hidden max-w-4xl w-full border border-gray-100">

    <!-- LEFT SIDE -->
    <div class="w-full md:w-1/2 bg-gray-50/50 flex flex-col items-center justify-center p-10 border-b md:border-b-0 md:border-r border-gray-100">
        <div class="mb-6">
            <img src="{{ asset('images/logo_senam.png') }}"
                 alt="Logo Senam"
                 class="w-32 h-32 md:w-40 md:h-40 object-contain">
        </div>
        <h1 class="text-sm md:text-base font-medium text-gray-600 tracking-widest uppercase text-center leading-relaxed">
            Sistem Informasi<br>Senam BSC
        </h1>
    </div>

    <!-- RIGHT SIDE -->
    <div class="w-full md:w-1/2 p-10 md:p-16 flex flex-col justify-center bg-white">

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Login</h2>
        </div>

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- LOGIN FAILED --}}
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
            @csrf

            <!-- EMAIL -->
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">
                    Email Address
                </label>
                <input type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 bg-white border 
                    @error('email') border-red-500 focus:ring-red-500/20 focus:border-red-500
                    @else border-gray-200 focus:ring-blue-500/20 focus:border-blue-500
                    @enderror
                    rounded-lg outline-none transition-all text-sm shadow-sm"
                    placeholder="nama@email.com">

                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">
                    Password
                </label>

                <div class="relative">
                    <input type="password"
                        id="passwordField"
                        name="password"
                        class="w-full px-4 py-3 bg-white border 
                        @error('password') border-red-500 focus:ring-red-500/20 focus:border-red-500
                        @else border-gray-200 focus:ring-blue-500/20 focus:border-blue-500
                        @enderror
                        rounded-lg outline-none transition-all text-sm shadow-sm"
                        placeholder="••••••••">

                    <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTON -->
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-3 rounded-lg shadow-md hover:shadow-lg transition-all active:scale-[0.98]">
                Masuk
            </button>
            <div class="text-center mt-6 text-sm text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}"
                class="text-blue-600 font-semibold hover:text-blue-700 hover:underline transition">
                    Daftar di sini!
                </a>
            </div>
        </form>

    </div>
</div>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }
</script>

</body>
</html>