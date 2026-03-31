<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Panel Laboratorium Fisika Dasar</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            min-height: calc(100vh - 72px);
        }

        .nav-link {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.3), rgba(168, 85, 247, 0.2));
            transform: translateX(4px);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.4), rgba(168, 85, 247, 0.3));
            border-left: 4px solid #f59e0b;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .super-admin-section {
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            margin-top: 1rem;
            padding-top: 1rem;
            position: relative;
        }

        .super-admin-section::before {
            content: 'SUPER ADMIN';
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            background: #1e3a8a;
            color: #fbbf24;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .super-admin-nav {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(245, 158, 11, 0.2));
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .super-admin-nav:hover {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(245, 158, 11, 0.3));
            border-color: rgba(245, 158, 11, 0.5);
        }

        .super-admin-nav.active {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.4), rgba(245, 158, 11, 0.4));
            border-left: 4px solid #f59e0b;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .user-dropdown {
            position: relative;
        }

        .user-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            z-index: 1000;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .user-menu.active {
            display: block;
        }

        .notification-badge {
            background: linear-gradient(135deg, #ef4444, #f97316);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            position: absolute;
            top: -8px;
            right: -8px;
        }

        .admin-header-role {
            background: linear-gradient(135deg, rgba(139, 92, 246, 1) 0%, rgba(37, 99, 235, 1) 0%);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .super-admin-role {
            background: linear-gradient(135deg, #ef4444, #f59e0b);
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 text-white shadow-lg relative">
        <div class="w-full max-w-[1880px] px-4 py-4 flex justify-between items-center gap-0 mx-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-4 shadow-lg">
                    <i class="fas fa-atom text-blue-600"></i>
                </div>
                <div>
                    <h1 class="text-xl font-semibold">Admin Panel</h1>
                    <p class="text-blue-100 text-sm">Laboratorium Fisika Dasar</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- User Role Badge -->
                <span class="admin-header-role {{ Auth::user()->role === 'super_admin' ? 'super-admin-role' : '' }}">
                    {{ Auth::user()->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                </span>

                <!-- User Info with Dropdown -->
                <div class="user-dropdown">
                    <button onclick="toggleUserMenu()" class="flex items-center space-x-3 text-blue-100 hover:text-white transition-colors cursor-pointer">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-blue-200">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform" id="userMenuChevron"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="user-menu" id="userMenu">
                        <div class="p-3 border-b">
                            <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                            <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ Auth::user()->role === 'super_admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    <i class="fas {{ Auth::user()->role === 'super_admin' ? 'fa-crown' : 'fa-user-shield' }} mr-1"></i>
                                    {{ Auth::user()->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-2">
                            @if(Auth::user()->role === 'super_admin')
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="fas fa-user-shield mr-2 w-4"></i>
                                Kelola Admin
                            </a>
                            @endif
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">
                                <i class="fas fa-tachometer-alt mr-2 w-4"></i>
                                Dashboard
                            </a>
                            <hr class="my-2">
                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-3 py-2 text-sm text-red-700 hover:bg-red-50 rounded-md">
                                    <i class="fas fa-sign-out-alt mr-2 w-4"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Quick Logout Button -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition shadow-lg font-medium">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-blue-900 text-white w-64 sidebar sticky top-0 h-screen">
            <nav class="p-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.staff.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                            <i class="fas fa-users mr-3 w-5"></i>
                            <span>Kelola Staff</span>
                        </a>
                    </li>
                    {{-- Kelola Alat & Peminjaman: disembunyikan sementara, aktifkan kembali jika diperlukan --}}
                    {{-- <li>
                        <a href="{{ route('admin.equipment.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.equipment.*') ? 'active' : '' }}">
                            <i class="fas fa-tools mr-3 w-5"></i>
                            <span>Kelola Alat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.peminjaman.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                            <i class="fas fa-exchange-alt mr-3 w-5"></i>
                            <span>Peminjaman Alat</span>
                        </a>
                    </li> --}}
                    <li>
                        <a href="{{ route('admin.visits.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.visits.*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check mr-3 w-5"></i>
                            <span>Kelola Kunjungan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.schedule.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.schedule.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-times mr-3 w-5"></i>
                            <span>Kelola Jadwal Available</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.articles.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                            <i class="fas fa-newspaper mr-3 w-5"></i>
                            <span>Kelola Artikel</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.visimisi.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.visimisi.*') ? 'active' : '' }}">
                            <i class="fas fa-bullseye mr-3 w-5"></i>
                            <span>Kelola Visi Misi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.facilities.index') }}" class="nav-link flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                            <i class="fas fa-building mr-3 w-5"></i>
                            <span>Kelola Fasilitas</span>
                        </a>
                    </li>

                    <!-- Super Admin Section -->
                    @if(Auth::user()->role === 'super_admin')
                    <li class="super-admin-section">
                        <a href="{{ route('admin.users.index') }}" class="nav-link super-admin-nav flex items-center py-3 px-4 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-user-shield mr-3 w-5 text-yellow-300"></i>
                            <span class="font-semibold">Kelola Admin</span>
                            @if(request()->routeIs('admin.users.*'))
                            <div class="ml-auto">
                                <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
                            </div>
                            @endif
                        </a>

                        <!-- Future Super Admin Features -->
                        {{--
                        <a href="{{ route('admin.system.settings') }}" class="nav-link super-admin-nav flex items-center py-2 px-4 rounded-lg mt-1">
                            <i class="fas fa-cogs mr-3 w-5 text-yellow-300"></i>
                            <span class="text-sm">Pengaturan Sistem</span>
                        </a>
                        <a href="{{ route('admin.system.logs') }}" class="nav-link super-admin-nav flex items-center py-2 px-4 rounded-lg mt-1">
                            <i class="fas fa-file-alt mr-3 w-5 text-yellow-300"></i>
                            <span class="text-sm">Log Sistem</span>
                        </a>
                        --}}
                    </li>
                    @endif
                </ul>

            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 min-h-screen bg-gray-50">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-gradient-to-r from-emerald-50 to-green-50 border-l-4 border-emerald-500 text-emerald-800 p-4 mb-6 rounded-r-lg shadow-lg animate-fade-in-down" role="alert">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 ml-4">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-r-lg shadow-lg animate-fade-in-down" role="alert">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800 ml-4">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-6 rounded-r-lg shadow-lg animate-fade-in-down" role="alert">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-exclamation text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ session('warning') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-yellow-600 hover:text-yellow-800 ml-4">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 text-blue-800 p-4 mb-6 rounded-r-lg shadow-lg animate-fade-in-down" role="alert">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-info text-white text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ session('info') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-blue-600 hover:text-blue-800 ml-4">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Breadcrumb Navigation -->
            @if(!request()->routeIs('admin.dashboard'))
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-chevron-right text-xs mx-2"></i>
                        <span class="font-medium">@yield('breadcrumb', 'Current Page')</span>
                    </li>
                </ol>
            </nav>
            @endif

            <!-- Main Content Area -->
            @yield('content')
        </main>
    </div>

    <!-- Loading Overlay (Global) -->
    <div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700 font-medium">Loading...</span>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Toggle User Menu
        function toggleUserMenu() {
            const menu = document.getElementById('userMenu');
            const chevron = document.getElementById('userMenuChevron');

            if (menu.classList.contains('active')) {
                menu.classList.remove('active');
                chevron.style.transform = 'rotate(0deg)';
            } else {
                menu.classList.add('active');
                chevron.style.transform = 'rotate(180deg)';
            }
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.user-dropdown');
            const menu = document.getElementById('userMenu');
            const chevron = document.getElementById('userMenuChevron');

            if (!dropdown.contains(event.target)) {
                menu.classList.remove('active');
                chevron.style.transform = 'rotate(0deg)';
            }
        });

        // Global Loading Functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });

        // Enhanced form submission with loading
        function submitFormWithLoading(formId) {
            const form = document.getElementById(formId);
            if (form) {
                showLoading();
                form.submit();
            }
        }

        // Confirmation dialog helper
        function confirmAction(message, callback) {
            if (confirm(message)) {
                showLoading();
                callback();
            }
        }

        // Toast notification helper
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            const bgColors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            toast.className = `fixed top-4 right-4 ${bgColors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'} mr-2"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                toast.style.transform = 'translateX(full)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
