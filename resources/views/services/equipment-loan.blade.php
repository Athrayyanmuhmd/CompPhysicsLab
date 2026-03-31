{{-- resources/views/services/equipment-loan.blade.php --}}
@extends('layouts.app')

@section('title', 'Layanan Peminjaman Alat - Laboratorium Fisika Komputasi')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Gradient -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.jpg') }}"
             alt="Layanan Peminjaman Alat"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-700/90 via-blue-800/80 to-blue-900/70"></div>
    </div>

    <!-- Content -->
    <div class="relative z-20 mx-6 px-4 sm:px-6 lg:px-8 text-center max-w-6xl">
        <!-- Breadcrumb -->
        <div class="scroll-animate mb-8 opacity-0" data-animation="fade-down">
            <nav class="flex justify-center" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 bg-white/10 backdrop-blur-sm rounded-full px-6 py-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-blue-200 hover:text-white transition-colors duration-200 flex items-center">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-blue-300 mx-3"></i>
                            <span class="text-blue-200">Layanan</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-blue-300 mx-3"></i>
                            <span class="text-white font-medium">Peminjaman Alat</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Main Title -->
        <div class="scroll-animate mb-8 opacity-0" data-animation="fade-up" data-delay="200">
            <h1 class="font-poppins text-5xl md:text-7xl font-bold leading-tight mb-6">
                <span class="text-white">Layanan</span>
                <span class="bg-clip-text text-transparent drop-shadow-lg"
                    style="background-image: linear-gradient(90deg, rgba(250, 204, 21, 1) 0%, rgba(255, 255, 255, 1) 0%); -webkit-background-clip: text; color: transparent;">
                    Peminjaman
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                Akses mudah ke peralatan laboratorium berkualitas tinggi untuk mendukung penelitian dan praktikum Anda
            </p>
        </div>
    </div>
</section>

<!-- Success/Error Messages -->
@if(session('success'))
<section class="py-8 bg-green-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="bg-green-100 border border-green-200 rounded-2xl p-6 flex items-start space-x-4">
            <div class="w-8 h-8 bg-green-200 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <div>
                <h3 class="text-green-800 font-semibold text-lg mb-2">Berhasil!</h3>
                <div class="text-green-700 whitespace-pre-line">{{ session('success') }}</div>
            </div>
        </div>
    </div>
</section>
@endif

@if(session('error') || $errors->any())
<section class="py-8 bg-red-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="bg-red-100 border border-red-200 rounded-2xl p-6 flex items-start space-x-4">
            <div class="w-8 h-8 bg-red-200 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <div>
                <h3 class="text-red-800 font-semibold text-lg mb-2">Terjadi Kesalahan!</h3>
                @if(session('error'))
                    <div class="text-red-700 whitespace-pre-line">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                    <ul class="text-red-700 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- Equipment Section -->
<section class="py-24 bg-gradient-to-b from-gray-50 to-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16 scroll-animate" data-animation="fade-down">
            <div class="inline-flex items-center px-6 py-3 bg-blue-50 border border-blue-200 rounded-full text-blue-700 text-sm font-semibold mb-6 shadow-sm">
                <i class="fas fa-tools mr-2"></i>
                Katalog Peralatan
            </div>
            <h2 class="font-poppins text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                Peralatan <span class="bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Laboratorium</span>
            </h2>
            <p class="text-gray-600 text-lg md:text-xl max-w-4xl mx-auto leading-relaxed">
                Jelajahi koleksi lengkap peralatan laboratorium modern yang tersedia untuk mendukung kegiatan akademik dan penelitian Anda.
            </p>
        </div>

        <!-- How to Borrow Info -->
        <div class="mb-16 scroll-animate" data-animation="fade-up" data-delay="100">
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-8 border border-yellow-200">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-yellow-800 mb-3 flex items-center justify-center">
                        <i class="fas fa-info-circle mr-3"></i>
                        Cara Meminjam Alat Laboratorium
                    </h3>
                    <p class="text-yellow-700">Ikuti langkah-langkah mudah berikut untuk meminjam alat laboratorium</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-yellow-300">
                            <span class="text-xl font-bold text-yellow-600">1</span>
                        </div>
                        <h4 class="font-semibold text-yellow-800 mb-2">Pilih Alat</h4>
                        <p class="text-sm text-yellow-700">Centang kotak pada alat yang ingin dipinjam</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-yellow-300">
                            <span class="text-xl font-bold text-yellow-600">2</span>
                        </div>
                        <h4 class="font-semibold text-yellow-800 mb-2">Atur Jumlah</h4>
                        <p class="text-sm text-yellow-700">Tentukan jumlah unit yang diperlukan</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-yellow-300">
                            <span class="text-xl font-bold text-yellow-600">3</span>
                        </div>
                        <h4 class="font-semibold text-yellow-800 mb-2">Ajukan</h4>
                        <p class="text-sm text-yellow-700">Klik "Ajukan Peminjaman" dan isi formulir</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-yellow-300">
                            <span class="text-xl font-bold text-yellow-600">4</span>
                        </div>
                        <h4 class="font-semibold text-yellow-800 mb-2">Tunggu Konfirmasi</h4>
                        <p class="text-sm text-yellow-700">Staff lab akan konfirmasi dalam 1x24 jam</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-yellow-300">
                            <span class="text-xl font-bold text-yellow-600">5</span>
                        </div>
                        <h4 class="font-semibold text-yellow-800 mb-2">Briefing & Ambil</h4>
                        <p class="text-sm text-yellow-700">Ikuti briefing lalu ambil alat sesuai jadwal</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-yellow-100 rounded-xl border border-yellow-300">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-lightbulb text-yellow-600 mt-1"></i>
                        <div class="text-sm text-yellow-700">
                            <strong>Tips:</strong> Pastikan membawa kartu identitas, surat pengajuan, dan siap mengikuti briefing penggunaan alat untuk keamanan bersama. surat pengajuan kunjungan merujuk pada <a href="https://drive.google.com/file/d/1UMECW8-I1haaMoSVezYNgUbGNmWRr-5k/view?usp=sharing" target="_blank" rel="noopener noreferrer"><strong>SOP Laboratorium FISIKA KOMPUTASI</strong></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selection Controls -->
        <div class="mb-12 scroll-animate" data-animation="fade-up" data-delay="100">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex flex-col lg:flex-row gap-6 items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="select-all-btn"
                                class="px-4 py-2 bg-blue-50 text-blue-600 border border-blue-200 rounded-xl hover:bg-blue-100 transition-colors duration-200 flex items-center">
                            <i class="fas fa-check-square mr-2"></i>
                            Pilih Semua
                        </button>
                        <button id="clear-selection-btn"
                                class="px-4 py-2 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors duration-200 flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Hapus Pilihan
                        </button>
                        <div class="text-sm text-gray-500">
                            <span id="selection-count">0</span> jenis alat, <span id="total-quantity">0</span> unit dipilih
                        </div>
                    </div>
                    <button id="bulk-loan-btn"
                            class="px-6 py-3 bg-blue-500 text-white rounded-xl font-semibold hover:bg-blue-600 transition-colors duration-200 flex items-center disabled:bg-gray-300 disabled:cursor-not-allowed"
                            disabled>
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Ajukan Peminjaman (<span id="cart-count">0</span> unit)
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="mb-12 scroll-animate" data-animation="fade-up" data-delay="200">
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex flex-col lg:flex-row gap-6 items-center">
                    <!-- Category Filter -->
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Filter Kategori</label>
                        <div class="flex flex-wrap gap-2" id="category-filter">
                            @foreach($categories as $key => $category)
                            <button class="category-btn px-4 py-2 rounded-full border transition-all duration-300
                                {{ $key === 'all' ? 'bg-blue-500 text-white border-blue-500' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600' }}"
                                data-category="{{ $key }}">
                                {{ $category }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="flex-1 lg:max-w-md">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Cari Alat</label>
                        <div class="relative">
                            <input type="text"
                                   id="search-input"
                                   placeholder="Masukkan nama alat..."
                                   class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Status</label>
                        <select id="status-filter" class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Semua Status</option>
                            <option value="available">Tersedia</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="unavailable">Habis</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Equipment Grid -->
    <!-- Equipment Grid -->
        <div id="equipment-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($equipments as $index => $equipment)
            <!-- Equipment Card with Fixed Status Logic -->
            <div class="equipment-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 scroll-animate"
                 data-animation="fade-up"
                 data-delay="{{ $index * 100 }}"
                 data-category="{{ $equipment['category'] }}"
                 data-status="{{ $equipment['status'] }}"
                 data-name="{{ strtolower($equipment['name']) }}"
                 data-equipment-id="{{ $equipment['id'] }}">

                <!-- Selection Checkbox & Quantity - Only show if available -->
                <div class="absolute top-4 left-4 z-10">
                    @if($equipment['status'] === 'available' && $equipment['quantity_available'] > 0)
                    <div class="flex flex-col space-y-2">
                        <label class="equipment-checkbox-container">
                            <input type="checkbox"
                                   class="equipment-checkbox"
                                   data-equipment-id="{{ $equipment['id'] }}"
                                   onchange="toggleEquipmentSelection('{{ $equipment['id'] }}')">
                            <span class="checkmark bg-white/90 backdrop-blur-sm"></span>
                        </label>
                        <div class="quantity-selector hidden bg-white/90 backdrop-blur-sm rounded-lg p-2 shadow-lg"
                             id="quantity-selector-{{ $equipment['id'] }}">
                            <label class="text-xs font-semibold text-gray-700 block mb-1">Jumlah:</label>
                            <input type="number"
                                   class="quantity-input w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   min="1"
                                   max="{{ $equipment['quantity_available'] }}"
                                   value="1"
                                   data-equipment-id="{{ $equipment['id'] }}"
                                   onchange="updateEquipmentQuantity('{{ $equipment['id'] }}', this.value)">
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Image -->
                <div class="relative overflow-hidden h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                    @if(!empty($equipment['image']))
                        <img src="{{ $equipment['image'] }}"
                            alt="{{ $equipment['name'] }}"
                            class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-700"
                            onerror="this.src='{{ asset('images/equipment/default.jpg') }}'">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <div class="text-center text-gray-400">
                                <i class="fas fa-image text-4xl mb-2"></i>
                                <p class="text-sm">Tidak ada gambar</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status Badge with Updated Logic -->
                    <div class="absolute top-4 right-4">
                        @if($equipment['status'] === 'available')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                Tersedia
                            </span>
                        @elseif($equipment['status'] === 'borrowed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                Dipinjam
                            </span>
                        @elseif($equipment['status'] === 'maintenance')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                Maintenance
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 border border-gray-200">
                                <div class="w-2 h-2 bg-gray-500 rounded-full mr-2"></div>
                                Tidak Tersedia
                            </span>
                        @endif
                    </div>

                    <!-- Category Icon -->
                    <div class="absolute bottom-4 left-4">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="{{ $equipment['icon'] ?? 'fas fa-cog' }} text-white text-sm"></i>
                        </div>
                    </div>

                    <!-- Stock Information with Enhanced Details -->
                    <div class="absolute bottom-4 right-4">
                        <div class="bg-white/90 backdrop-blur-sm rounded-lg px-3 py-2 shadow-lg">
                            @if($equipment['status'] === 'available')
                                <div class="text-xs text-gray-600">Tersedia</div>
                                <div class="text-sm font-bold text-green-600">
                                    {{ $equipment['quantity_available'] }}/{{ $equipment['quantity_total'] }}
                                </div>
                            @elseif($equipment['status'] === 'borrowed')
                                <div class="text-xs text-gray-600">Dipinjam</div>
                                <div class="text-sm font-bold text-yellow-600">
                                    {{ $equipment['quantity_borrowed'] }}/{{ $equipment['quantity_total'] }}
                                </div>
                            @elseif($equipment['status'] === 'maintenance')
                                <div class="text-xs text-gray-600">Rusak</div>
                                <div class="text-sm font-bold text-red-600">
                                    {{ $equipment['quantity_damaged'] }}/{{ $equipment['quantity_total'] }}
                                </div>
                            @else
                                <div class="text-xs text-gray-600">Stok</div>
                                <div class="text-sm font-bold text-gray-600">
                                    0/{{ $equipment['quantity_total'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Header -->
                    <div class="mb-4">
                        <div class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold mb-3 border border-blue-200">
                            {{ $equipment['category'] }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight">{{ $equipment['name'] }}</h3>
                        <p class="text-sm text-gray-500 font-medium">{{ $equipment['model'] ?? 'Model tidak tersedia' }}</p>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 text-sm leading-relaxed mb-4 line-clamp-3">
                        {{ $equipment['description'] }}
                    </p>

                    <!-- Enhanced Stock Status Display -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <h4 class="text-xs font-semibold text-gray-700 mb-2">Status Stok Detail</h4>

                        @if(isset($equipment['detailed_status']))
                            <div class="text-xs text-gray-600 mb-2">{{ $equipment['detailed_status'] }}</div>
                        @endif

                        <!-- Stock Breakdown -->
                        <div class="grid grid-cols-3 gap-2 text-xs">
                            <div class="text-center">
                                <div class="font-semibold text-green-600">{{ $equipment['quantity_available'] }}</div>
                                <div class="text-gray-500">Tersedia</div>
                            </div>
                            <div class="text-center">
                                <div class="font-semibold text-yellow-600">{{ $equipment['quantity_borrowed'] }}</div>
                                <div class="text-gray-500">Dipinjam</div>
                            </div>
                            <div class="text-center">
                                <div class="font-semibold text-red-600">{{ $equipment['quantity_damaged'] }}</div>
                                <div class="text-gray-500">Rusak</div>
                            </div>
                        </div>

                        <!-- Borrowing Status Info -->
                        @if(isset($equipment['borrowing_status']))
                            <div class="mt-2 text-xs">
                                @if($equipment['borrowing_status']['can_borrow'])
                                    <span class="text-green-600 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ $equipment['borrowing_status']['message'] }}
                                    </span>
                                @else
                                    <span class="text-red-600 font-medium">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        {{ $equipment['borrowing_status']['message'] }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Loan Duration -->
                    <div class="flex items-center justify-between text-sm mb-6">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            <span>{{ $equipment['loan_duration'] ?? 'Sesuai kesepakatan' }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('equipment.detail', $equipment['id']) }}"
                           class="w-full bg-blue-500 text-white px-4 py-3 rounded-xl font-semibold hover:bg-blue-600 transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-eye mr-2"></i>
                            Lihat Detail
                        </a>

                        @if($equipment['status'] === 'available' && $equipment['quantity_available'] > 0)
                            <button onclick="quickAddToSelection('{{ $equipment['id'] }}')"
                                    class="w-full border-2 border-blue-500 text-blue-500 px-4 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah ke Pilihan
                            </button>
                        @elseif($equipment['status'] === 'borrowed')
                            <button disabled
                                    class="w-full border-2 border-yellow-300 text-yellow-600 px-4 py-3 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center">
                                <i class="fas fa-hand-holding mr-2"></i>
                                Sedang Dipinjam
                            </button>
                        @elseif($equipment['status'] === 'maintenance')
                            <button disabled
                                    class="w-full border-2 border-red-300 text-red-600 px-4 py-3 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center">
                                <i class="fas fa-wrench mr-2"></i>
                                Sedang Maintenance
                            </button>
                        @else
                            <button disabled
                                    class="w-full border-2 border-gray-300 text-gray-400 px-4 py-3 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center">
                                <i class="fas fa-times-circle mr-2"></i>
                                Tidak Tersedia
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="hidden text-center py-16">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada alat ditemukan</h3>
                <p class="text-gray-600">Coba ubah filter atau kata kunci pencarian Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- Floating Cart Button (Mobile) -->
<div id="floating-cart" class="fixed bottom-6 right-6 z-40 lg:hidden">
    <button onclick="openBulkLoanModal()"
            class="bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600 transition-all duration-200 transform hover:scale-105 disabled:bg-gray-400"
            disabled>
        <i class="fas fa-shopping-cart text-xl"></i>
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-semibold" id="floating-cart-count">0</span>
    </button>
</div>

<!-- Bulk Loan Request Modal -->
<div id="bulkLoanModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeBulkLoanModal()"></div>

        <!-- Modal content -->
        <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Ajukan Peminjaman Alat</h3>
                <button onclick="closeBulkLoanModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Selected Equipment List -->
            <div class="mb-8">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">
                    Alat yang Dipilih (<span id="modal-equipment-count">0</span> jenis, <span id="modal-total-quantity">0</span> unit)
                </h4>
                <div id="selected-equipment-list" class="space-y-4 max-h-60 overflow-y-auto">
                    <!-- Equipment items will be populated by JavaScript -->
                </div>

                <!-- Summary -->
                <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-semibold text-blue-900">Total Jenis Alat:</span>
                            <span class="text-blue-700" id="summary-equipment-types">0</span>
                        </div>
                        <div>
                            <span class="font-semibold text-blue-900">Total Unit:</span>
                            <span class="text-blue-700" id="summary-total-units">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="bulkLoanForm" action="{{ route('equipment.request') }}" method="POST" class="space-y-6">
    @csrf

    <!-- Status Peminjam -->
    <div class="grid grid-cols-1 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-3">Status Peminjam *</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50 transition-colors duration-200">
                    <input type="radio" name="borrower_type" value="mahasiswa_usk" class="mr-3 text-blue-600" checked
                           onchange="toggleBorrowerFields()">
                    <div>
                        <div class="font-semibold text-gray-900">Mahasiswa USK</div>
                        <div class="text-sm text-gray-600">Mahasiswa Universitas Syiah Kuala</div>
                    </div>
                </label>
                <label class="flex items-center p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-blue-50 transition-colors duration-200">
                    <input type="radio" name="borrower_type" value="eksternal" class="mr-3 text-blue-600"
                           onchange="toggleBorrowerFields()">
                    <div>
                        <div class="font-semibold text-gray-900">Eksternal</div>
                        <div class="text-sm text-gray-600">Mahasiswa luar USK / Umum</div>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Personal Info -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
            <input type="text" name="name" required value="{{ old('name') }}"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <span id="student-id-label">NIM *</span>
            </label>
            <input type="text" name="student_id" required value="{{ old('student_id') }}"
                   id="student-id-input"
                   placeholder="Masukkan NIM Anda"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <div id="student-id-help" class="text-xs text-gray-500 mt-1">
                Format NIM USK: contoh 1908107010001
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
            <input type="email" name="email" required value="{{ old('email') }}"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon *</label>
            <input type="tel" name="phone" required value="{{ old('phone') }}"
                   placeholder="Contoh: 081234567890"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Instansi untuk Eksternal -->
        <div id="instansi-field" class="hidden md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Instansi/Universitas *</label>
            <input type="text" name="instansi" value="{{ old('instansi') }}"
                   placeholder="Nama instansi atau universitas asal"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai *</label>
            <input type="date" name="start_date" required value="{{ old('start_date') }}"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai *</label>
            <input type="date" name="end_date" required value="{{ old('end_date') }}"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Penggunaan *</label>
        <textarea name="purpose" rows="4" required
                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Jelaskan tujuan dan rencana penggunaan alat-alat tersebut...">{{ old('purpose') }}</textarea>
    </div>

    <!-- Persyaratan Tambahan -->
    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
        <h4 class="font-semibold text-blue-900 mb-3">
            <i class="fas fa-clipboard-list mr-2"></i>Persyaratan Dokumen
        </h4>
        <div id="requirements-list">
            <!-- Will be populated by JavaScript based on borrower type -->
        </div>
    </div>

    <!-- Hidden fields for selected equipment data -->
    <input type="hidden" name="equipment_ids" id="equipment-ids-input">
    <input type="hidden" name="equipment_quantities" id="equipment-quantities-input">

    <!-- Terms -->
    <div class="flex items-start space-x-3">
        <input type="checkbox" id="bulk-terms" required class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
        <label for="bulk-terms" class="text-sm text-gray-600">
            Saya menyetujui <a href="#" class="text-blue-600 hover:underline">syarat dan ketentuan</a> peminjaman alat laboratorium, akan mengikuti briefing yang diberikan, dan bertanggung jawab atas alat yang dipinjam.
        </label>
    </div>

    <!-- Submit -->
    <div class="flex space-x-4">
        <button type="button" onclick="closeBulkLoanModal()"
                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors duration-200">
            Batal
        </button>
        <button type="submit" id="submit-btn"
                class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors duration-200">
            Kirim Permohonan
        </button>
    </div>
</form>
        </div>
    </div>
</div>

<style>
/* Line clamp for text truncation */
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom Checkbox Styles */
.equipment-checkbox-container {
    position: relative;
    display: block;
    cursor: pointer;
    user-select: none;
}

.equipment-checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 28px;
    width: 28px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.equipment-checkbox-container:hover input ~ .checkmark {
    border-color: #3b82f6;
    transform: scale(1.05);
}

.equipment-checkbox-container input:checked ~ .checkmark {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.equipment-checkbox-container input:checked ~ .checkmark:after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: white;
    font-size: 14px;
}

/* Quantity Selector Styles */
.quantity-selector {
    min-width: 80px;
    animation: slideDown 0.3s ease-out;
}

.quantity-selector.hidden {
    display: none;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.quantity-input, .modal-quantity-input {
    text-align: center;
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button,
.modal-quantity-input::-webkit-outer-spin-button,
.modal-quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Selected card highlight */
.equipment-card.selected {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-4px);
}

/* Scroll animations */
.scroll-animate {
    opacity: 0;
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.scroll-animate[data-animation="fade-down"] {
    transform: translateY(-60px);
}

.scroll-animate[data-animation="fade-up"] {
    transform: translateY(60px);
}

.scroll-animate.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Enhanced gradient text */
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Modal animations */
#bulkLoanModal.show {
    display: flex !important;
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .scroll-animate[data-animation="fade-down"],
    .scroll-animate[data-animation="fade-up"] {
        transform: translateY(40px);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Equipment data for JavaScript
    const equipments = @json($equipments);

    // Selected equipment array with quantities
    window.selectedEquipment = {};

    // Initialize scroll animations
    initScrollAnimations();

    // Initialize filters
    initFilters();

    // Initialize selection controls
    initSelectionControls();

    // Set minimum date for date inputs
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');

    if (startDateInput) {
        startDateInput.setAttribute('min', today);
        startDateInput.addEventListener('change', function() {
            const startDate = this.value;
            if (endDateInput) {
                endDateInput.setAttribute('min', startDate);
            }
        });
    }
});

function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.scroll-animate');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = entry.target.dataset.delay || 0;
                setTimeout(() => {
                    entry.target.classList.add('animate');
                }, delay);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

function initFilters() {
    const categoryBtns = document.querySelectorAll('.category-btn');
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');

    // Category filter
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            categoryBtns.forEach(b => {
                b.classList.remove('bg-blue-500', 'text-white', 'border-blue-500');
                b.classList.add('bg-gray-50', 'text-gray-700', 'border-gray-200');
            });
            this.classList.remove('bg-gray-50', 'text-gray-700', 'border-gray-200');
            this.classList.add('bg-blue-500', 'text-white', 'border-blue-500');

            filterEquipment();
        });
    });

    // Search filter
    searchInput.addEventListener('input', filterEquipment);

    // Status filter
    statusFilter.addEventListener('change', filterEquipment);
}

function initSelectionControls() {
    // Select all button
    document.getElementById('select-all-btn').addEventListener('click', function() {
        const availableEquipment = document.querySelectorAll('.equipment-checkbox:not(:disabled)');
        availableEquipment.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.checked = true;
                toggleEquipmentSelection(checkbox.dataset.equipmentId);
            }
        });
    });

    // Clear selection button
    document.getElementById('clear-selection-btn').addEventListener('click', function() {
        const selectedCheckboxes = document.querySelectorAll('.equipment-checkbox:checked');
        selectedCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
            toggleEquipmentSelection(checkbox.dataset.equipmentId);
        });
        window.selectedEquipment = {};
        updateSelectionUI();
    });

    // Bulk loan button
    document.getElementById('bulk-loan-btn').addEventListener('click', openBulkLoanModal);
}

function filterEquipment() {
    const activeCategory = document.querySelector('.category-btn.bg-blue-500').dataset.category;
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const statusFilter = document.getElementById('status-filter').value;

    const equipmentCards = document.querySelectorAll('.equipment-card');
    let visibleCount = 0;

    equipmentCards.forEach(card => {
        const category = card.dataset.category;
        const name = card.dataset.name;
        const status = card.dataset.status;

        const categoryMatch = activeCategory === 'all' || category === activeCategory;
        const searchMatch = name.includes(searchTerm);
        const statusMatch = statusFilter === 'all' || status === statusFilter;

        if (categoryMatch && searchMatch && statusMatch) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Show/hide no results message
    const noResults = document.getElementById('no-results');
    if (visibleCount === 0) {
        noResults.classList.remove('hidden');
    } else {
        noResults.classList.add('hidden');
    }
}

function toggleEquipmentSelection(equipmentId) {
    const equipments = @json($equipments);
    const equipment = equipments.find(eq => eq.id === equipmentId);
    const card = document.querySelector(`[data-equipment-id="${equipmentId}"]`);
    const checkbox = document.querySelector(`.equipment-checkbox[data-equipment-id="${equipmentId}"]`);
    const quantitySelector = document.getElementById(`quantity-selector-${equipmentId}`);

    if (checkbox.checked) {
        // Add to selection with default quantity of 1
        window.selectedEquipment[equipmentId] = {
            ...equipment,
            quantity: 1
        };
        card.classList.add('selected');
        quantitySelector.classList.remove('hidden');
    } else {
        // Remove from selection
        delete window.selectedEquipment[equipmentId];
        card.classList.remove('selected');
        quantitySelector.classList.add('hidden');
        // Reset quantity input
        const quantityInput = document.querySelector(`.quantity-input[data-equipment-id="${equipmentId}"]`);
        if (quantityInput) {
            quantityInput.value = 1;
        }
    }

    updateSelectionUI();
}

function updateEquipmentQuantity(equipmentId, quantity) {
    if (window.selectedEquipment[equipmentId]) {
        const qty = parseInt(quantity);
        const equipment = window.selectedEquipment[equipmentId];

        // Validate quantity
        if (qty > equipment.quantity_available) {
            alert(`Jumlah maksimal untuk ${equipment.name} adalah ${equipment.quantity_available} unit`);
            const quantityInput = document.querySelector(`.quantity-input[data-equipment-id="${equipmentId}"]`);
            quantityInput.value = equipment.quantity_available;
            window.selectedEquipment[equipmentId].quantity = equipment.quantity_available;
        } else if (qty < 1) {
            const quantityInput = document.querySelector(`.quantity-input[data-equipment-id="${equipmentId}"]`);
            quantityInput.value = 1;
            window.selectedEquipment[equipmentId].quantity = 1;
        } else {
            window.selectedEquipment[equipmentId].quantity = qty;
        }

        updateSelectionUI();
    }
}

function quickAddToSelection(equipmentId) {
    const checkbox = document.querySelector(`.equipment-checkbox[data-equipment-id="${equipmentId}"]`);
    if (checkbox && !checkbox.checked) {
        checkbox.checked = true;
        toggleEquipmentSelection(equipmentId);
    }
}

function updateSelectionUI() {
    const selectedEquipmentArray = Object.values(window.selectedEquipment);
    const count = selectedEquipmentArray.length;
    const totalQuantity = selectedEquipmentArray.reduce((sum, eq) => sum + eq.quantity, 0);

    // Update selection count
    document.getElementById('selection-count').textContent = count;
    document.getElementById('total-quantity').textContent = totalQuantity;
    document.getElementById('cart-count').textContent = totalQuantity;

    const floatingCartCount = document.getElementById('floating-cart-count');
    if (floatingCartCount) {
        floatingCartCount.textContent = totalQuantity;
    }

    // Enable/disable bulk loan button
    const bulkLoanBtn = document.getElementById('bulk-loan-btn');
    const floatingCart = document.querySelector('#floating-cart button');

    if (count > 0) {
        bulkLoanBtn.disabled = false;
        if (floatingCart) floatingCart.disabled = false;
        bulkLoanBtn.classList.remove('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
        if (floatingCart) floatingCart.classList.remove('disabled:bg-gray-400');
    } else {
        bulkLoanBtn.disabled = true;
        if (floatingCart) floatingCart.disabled = true;
        bulkLoanBtn.classList.add('disabled:bg-gray-300', 'disabled:cursor-not-allowed');
        if (floatingCart) floatingCart.classList.add('disabled:bg-gray-400');
    }
}

function openBulkLoanModal() {
    const selectedEquipmentArray = Object.values(window.selectedEquipment);
    if (selectedEquipmentArray.length === 0) {
        alert('Pilih alat terlebih dahulu sebelum mengajukan peminjaman.');
        return;
    }

    // Populate selected equipment list
    const equipmentList = document.getElementById('selected-equipment-list');
    const equipmentCount = document.getElementById('modal-equipment-count');
    const totalQuantity = document.getElementById('modal-total-quantity');
    const summaryTypes = document.getElementById('summary-equipment-types');
    const summaryUnits = document.getElementById('summary-total-units');

    const totalUnits = selectedEquipmentArray.reduce((sum, eq) => sum + eq.quantity, 0);

    equipmentCount.textContent = selectedEquipmentArray.length;
    totalQuantity.textContent = totalUnits;
    summaryTypes.textContent = selectedEquipmentArray.length;
    summaryUnits.textContent = totalUnits;

    equipmentList.innerHTML = selectedEquipmentArray.map(equipment => `
        <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                    <i class="${equipment.icon || 'fas fa-cog'} text-white"></i>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-gray-900">${equipment.name}</div>
                    <div class="text-sm text-gray-600">${equipment.model || 'Model tidak tersedia'}</div>
                    <div class="text-sm text-blue-600">Max: ${equipment.loan_duration || 'Sesuai kesepakatan'}</div>
                </div>
                <div class="text-right">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Jumlah:</label>
                        <input type="number"
                               class="modal-quantity-input w-16 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                               min="1"
                               max="${equipment.quantity_available}"
                               value="${equipment.quantity}"
                               data-equipment-id="${equipment.id}"
                               onchange="updateModalQuantity('${equipment.id}', this.value)">
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Tersedia: ${equipment.quantity_available}</div>
                </div>
            </div>
            <button onclick="removeFromSelection('${equipment.id}')"
                    class="text-red-500 hover:text-red-700 p-2 ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');

    // Set equipment data in hidden inputs
    document.getElementById('equipment-ids-input').value = JSON.stringify(selectedEquipmentArray.map(eq => eq.id));
    document.getElementById('equipment-quantities-input').value = JSON.stringify(
        selectedEquipmentArray.reduce((obj, eq) => {
            obj[eq.id] = eq.quantity;
            return obj;
        }, {})
    );

    // Show modal
    document.getElementById('bulkLoanModal').classList.remove('hidden');
    document.getElementById('bulkLoanModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeBulkLoanModal() {
    document.getElementById('bulkLoanModal').classList.add('hidden');
    document.getElementById('bulkLoanModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

function updateModalQuantity(equipmentId, quantity) {
    if (window.selectedEquipment[equipmentId]) {
        const qty = parseInt(quantity);
        const equipment = window.selectedEquipment[equipmentId];

        // Validate quantity
        if (qty > equipment.quantity_available) {
            alert(`Jumlah maksimal untuk ${equipment.name} adalah ${equipment.quantity_available} unit`);
            const modalInput = document.querySelector(`.modal-quantity-input[data-equipment-id="${equipmentId}"]`);
            modalInput.value = equipment.quantity_available;
            window.selectedEquipment[equipmentId].quantity = equipment.quantity_available;
        } else if (qty < 1) {
            const modalInput = document.querySelector(`.modal-quantity-input[data-equipment-id="${equipmentId}"]`);
            modalInput.value = 1;
            window.selectedEquipment[equipmentId].quantity = 1;
        } else {
            window.selectedEquipment[equipmentId].quantity = qty;
        }

        // Update quantity selector on main page
        const quantityInput = document.querySelector(`.quantity-input[data-equipment-id="${equipmentId}"]`);
        if (quantityInput) {
            quantityInput.value = window.selectedEquipment[equipmentId].quantity;
        }

        updateSelectionUI();

        // Update modal summary
        const selectedEquipmentArray = Object.values(window.selectedEquipment);
        const totalUnits = selectedEquipmentArray.reduce((sum, eq) => sum + eq.quantity, 0);

        document.getElementById('modal-total-quantity').textContent = totalUnits;
        document.getElementById('summary-total-units').textContent = totalUnits;

        // Update hidden inputs
        document.getElementById('equipment-quantities-input').value = JSON.stringify(
            selectedEquipmentArray.reduce((obj, eq) => {
                obj[eq.id] = eq.quantity;
                return obj;
            }, {})
        );
    }
}

function removeFromSelection(equipmentId) {
    const checkbox = document.querySelector(`.equipment-checkbox[data-equipment-id="${equipmentId}"]`);
    if (checkbox) {
        checkbox.checked = false;
        toggleEquipmentSelection(equipmentId);
    }

    // Refresh modal content if modal is open
    if (!document.getElementById('bulkLoanModal').classList.contains('hidden')) {
        openBulkLoanModal();
    }
}

function toggleBorrowerFields() {
    const borrowerType = document.querySelector('input[name="borrower_type"]:checked').value;
    const studentIdLabel = document.getElementById('student-id-label');
    const studentIdInput = document.getElementById('student-id-input');
    const studentIdHelp = document.getElementById('student-id-help');
    const instansiField = document.getElementById('instansi-field');
    const requirementsList = document.getElementById('requirements-list');

    if (borrowerType === 'mahasiswa_usk') {
        // Mahasiswa USK
        studentIdLabel.textContent = 'NIM *';
        studentIdInput.placeholder = 'Masukkan NIM Anda';
        studentIdInput.setAttribute('pattern', '[0-9]{13}');
        studentIdHelp.textContent = 'Format NIM USK: contoh 1908107010001';
        instansiField.classList.add('hidden');
        instansiField.querySelector('input').removeAttribute('required');

        requirementsList.innerHTML = `
            <ul class="text-sm text-blue-700 space-y-1">
                <li class="flex items-center"><i class="fas fa-check mr-2"></i>Kartu Mahasiswa USK</li>
                <li class="flex items-center"><i class="fas fa-check mr-2"></i>Surat pengantar dari dosen/pembimbing</li>
                <li class="flex items-center"><i class="fas fa-check mr-2"></i>Mengikuti briefing keselamatan</li>
            </ul>
        `;
    } else {
        // Eksternal
        studentIdLabel.textContent = 'NIM/NIP *';
        studentIdInput.placeholder = 'Masukkan NIM atau NIP Anda';
        studentIdInput.removeAttribute('pattern');
        studentIdHelp.textContent = 'Masukkan NIM/NIP dari instansi asal';
        instansiField.classList.remove('hidden');
        instansiField.querySelector('input').setAttribute('required', 'required');

        requirementsList.innerHTML = `
            <ul class="text-sm text-blue-700 space-y-1">
                <li class="flex items-center"><i class="fas fa-check mr-2"></i>Kartu identitas/mahasiswa</li>
                <li class="flex items-center"><i class="fas fa-check mr-2"></i>Surat pengantar resmi dari instansi</li>
                <li class="flex items-center"><i class="fas fa-check mr-2"></i>Surat permohonan ke laboratorium</li>
                <li class="flex items-center"><i class="fas fa-check mr-2"></i>Mengikuti briefing keselamatan</li>
            </ul>
        `;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleBorrowerFields();
});

// Handle form submission
document.getElementById('bulkLoanForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Prevent default form submission

    const selectedEquipmentArray = Object.values(window.selectedEquipment);
    if (selectedEquipmentArray.length === 0) {
        showToast('Pilih alat terlebih dahulu sebelum mengajukan peminjaman.', 'warning');
        return;
    }

    // Validate form first
    if (!this.checkValidity()) {
        this.reportValidity();
        return;
    }

    // Check required fields
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.focus();
            return;
        }
    });

    if (!isValid) {
        showToast('Mohon lengkapi semua field yang wajib diisi.', 'warning');
        return;
    }

    // Disable submit button
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Memproses...';

    // Collect form data
    const formData = new FormData(this);

    try {
        // Submit form to server first
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success) {
            // Show success message
            showToast('Permohonan peminjaman berhasil disimpan! Sekarang akan membuka WhatsApp untuk menghubungi admin.', 'success');

            // Generate and send WhatsApp message using backend data
            const whatsappData = generateWhatsAppMessage(data.loan_data, data.tracking_url);

            // Open WhatsApp after a short delay
            setTimeout(() => {
                openWhatsApp(whatsappData.phone, whatsappData.message);

                // Show completion message
                setTimeout(async () => {
                    const referenceId = data.reference_id || 'Akan diberikan melalui WhatsApp';
                    showToast(`Permohonan berhasil dikirim! ID Peminjaman: ${referenceId}`, 'success', 8000);

                    // Ask about tracking page if URL available
                    if (data.tracking_url) {
                        const showTracking = await showConfirmDialog(
                            'Apakah Anda ingin melihat halaman tracking peminjaman?',
                            'Lihat Status Peminjaman',
                            'Ya, Lihat Status',
                            'Nanti Saja'
                        );

                        if (showTracking) {
                            window.location.href = data.tracking_url;
                        } else {
                            showToast('Terima kasih! Anda akan diarahkan ke halaman peminjaman.', 'info', 3000);
                            setTimeout(() => {
                                window.location.href = "{{ route('equipment.loan') }}";
                            }, 2000);
                        }
                    }
                }, 2000);
            }, 1000);

        } else {
            // Show error message
            showToast('Terjadi kesalahan: ' + (data.message || 'Silakan coba lagi.'), 'error');

            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-3"></i>Kirim Permohonan';
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Terjadi kesalahan sistem. Silakan coba lagi.', 'error');

        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-3"></i>Kirim Permohonan';
    }
});

// Function to generate WhatsApp message for equipment loan
function generateWhatsAppMessage(loanData, trackingUrl) {
    const adminPhone = '6287801482963'; // Ganti dengan nomor admin yang sebenarnya

    let message = "*🔧 PERMOHONAN PEMINJAMAN ALAT LABORATORIUM*\n\n";
    message += "📋 *INFORMASI PEMINJAM*\n";
    message += `👤 Nama: ${loanData.name}\n`;
    message += `🎓 ${loanData.is_mahasiswa_usk ? 'NIM' : 'NIM/NIP'}: ${loanData.student_id}\n`;
    if (loanData.instansi) {
        message += `🏢 Instansi: ${loanData.instansi}\n`;
    }
    message += `📧 Email: ${loanData.email}\n`;
    message += `📱 Telepon: ${loanData.phone}\n\n`;

    message += "🔧 *DETAIL PEMINJAMAN*\n";
    message += `📅 Periode: ${loanData.start_date} - ${loanData.end_date}\n`;
    message += `🔢 Jumlah: ${loanData.equipment_count} jenis alat (${loanData.total_units} unit)\n`;
    message += `📝 Alat: ${loanData.equipment_summary}\n\n`;

    message += "🎯 *TUJUAN PENGGUNAAN*\n";
    message += `${loanData.purpose}\n\n`;

    if (trackingUrl) {
        message += "🔗 *LINK TRACKING*\n";
        message += `${trackingUrl}\n\n`;
    }

    message += "⚠️ *MOHON DIPROSES SEGERA*\n";
    message += "Status: MENUNGGU PERSETUJUAN\n";
    message += `ID Peminjaman: ${loanData.reference_number}\n`;
    message += `Waktu Pengajuan: ${loanData.created_at}\n\n`;

    message += "Terima kasih! 🙏";

    return {
        phone: adminPhone,
        message: message
    };
}

// Function to open WhatsApp
function openWhatsApp(phone, message) {
    const encodedMessage = encodeURIComponent(message);
    const whatsappUrl = `https://wa.me/${phone}?text=${encodedMessage}`;
    window.open(whatsappUrl, '_blank');
}

// Toast notification system (jika belum ada)
function createToastContainer() {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-4';
        document.body.appendChild(container);
    }
    return container;
}

function showToast(message, type = 'info', duration = 5000) {
    const container = createToastContainer();

    const toast = document.createElement('div');
    toast.className = `toast-notification transform translate-x-full opacity-0 transition-all duration-300 ease-out`;

    const styles = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };

    const icons = {
        success: 'fa-check-circle text-green-500',
        error: 'fa-exclamation-circle text-red-500',
        warning: 'fa-exclamation-triangle text-yellow-500',
        info: 'fa-info-circle text-blue-500'
    };

    toast.innerHTML = `
        <div class="flex items-start p-4 rounded-xl border shadow-lg backdrop-blur-sm max-w-sm ${styles[type]}">
            <div class="flex-shrink-0">
                <i class="fas ${icons[type]} text-lg"></i>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium leading-5">${message}</p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200" onclick="dismissToast(this)">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 100);

    if (duration > 0) {
        setTimeout(() => {
            dismissToast(toast.querySelector('button'));
        }, duration);
    }

    return toast;
}

function dismissToast(button) {
    const toast = button.closest('.toast-notification');
    if (toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }
}

function showConfirmDialog(message, title = 'Konfirmasi', confirmText = 'Ya', cancelText = 'Batal') {
    return new Promise((resolve) => {
        const backdrop = document.createElement('div');
        backdrop.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
        backdrop.style.backdropFilter = 'blur(4px)';

        const modal = document.createElement('div');
        modal.className = 'bg-white rounded-2xl shadow-2xl max-w-md w-full transform scale-95 opacity-0 transition-all duration-300';

        modal.innerHTML = `
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-question-circle text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">${title}</h3>
                </div>
                <p class="text-gray-600 mb-6 leading-relaxed">${message}</p>
                <div class="flex justify-end space-x-3">
                    <button class="cancel-btn px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium">
                        ${cancelText}
                    </button>
                    <button class="confirm-btn px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                        ${confirmText}
                    </button>
                </div>
            </div>
        `;

        backdrop.appendChild(modal);
        document.body.appendChild(backdrop);

        setTimeout(() => {
            modal.classList.remove('scale-95', 'opacity-0');
            modal.classList.add('scale-100', 'opacity-100');
        }, 100);

        const confirmBtn = modal.querySelector('.confirm-btn');
        const cancelBtn = modal.querySelector('.cancel-btn');

        function cleanup() {
            modal.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                if (backdrop.parentNode) {
                    backdrop.parentNode.removeChild(backdrop);
                }
            }, 300);
        }

        confirmBtn.addEventListener('click', () => {
            cleanup();
            resolve(true);
        });

        cancelBtn.addEventListener('click', () => {
            cleanup();
            resolve(false);
        });

        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                cleanup();
                resolve(false);
            }
        });

        confirmBtn.focus();
    });
}
</script>
@endsection
