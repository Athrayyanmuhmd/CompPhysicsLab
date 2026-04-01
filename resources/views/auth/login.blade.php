<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Panel Laboratorium Fisika Komputasi</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-image: url('{{ asset('images/hero-1.jpg') }}');
            background-size: cover;
            background-position: center;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.6);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="overlay absolute inset-0"></div>
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md z-10">
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-fisika.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Admin Panel Laboratorium Fisika Komputasi</h1>
            <p class="text-gray-600 mt-2">Silakan login untuk melanjutkan</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-lock text-gray-400"></i>
                    </span>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="mr-2">
                    <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition">
                Login
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</body>
</html>
