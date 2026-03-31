{{-- resources/views/services/equipment-detail.blade.php --}}
@extends('layouts.app')

@section('title', $equipment['name'] . ' - Detail Alat Laboratorium')

@section('content')
<!-- Equipment Detail Section -->
<section class="pt-24 pb-16 bg-gradient-to-br from-gray-50 to-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <div class="mb-8 scroll-animate" data-animation="fade-down">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                            <i class="fas fa-home mr-2"></i>Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <a href="{{ route('equipment.loan') }}" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Peminjaman Alat</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                            <span class="text-gray-900 font-medium">{{ $equipment['name'] }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Equipment Image -->
            <div class="scroll-animate" data-animation="slide-left">
                <div class="relative">
                    <div class="aspect-w-4 aspect-h-3 rounded-2xl overflow-hidden shadow-2xl">
                        <img src="{{ $equipment['image'] }}"
                             alt="{{ $equipment['name'] }}"
                             class="w-full h-full object-cover"
                             onerror="this.src='{{ asset('images/equipment/default.jpg') }}'">
                    </div>

                    <!-- Status Badge -->
                    <div class="absolute top-6 left-6">
                        @if($equipment['status'] === 'available')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200 shadow-lg">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            Tersedia
                        </span>
                        @elseif($equipment['status'] === 'maintenance')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800 border border-red-200 shadow-lg">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            Maintenance
                        </span>
                        @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-lg">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                            Habis
                        </span>
                        @endif
                    </div>

                    <!-- Category Icon -->
                    <div class="absolute top-6 right-6">
                        <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="{{ $equipment['icon'] ?? 'fas fa-cog' }} text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment Details -->
            <div class="scroll-animate" data-animation="slide-right" data-delay="200">
                <!-- Header -->
                <div class="mb-8">
                    <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-semibold mb-4 border border-blue-200">
                        {{ $equipment['category'] }}
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">{{ $equipment['name'] }}</h1>
                    <p class="text-xl text-gray-600 font-medium mb-4">Kode: {{ $equipment['model'] ?? 'Model tidak tersedia' }}</p>
                </div>

                <!-- Availability Info -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 mb-8 border border-blue-200">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="text-sm text-blue-600 font-semibold mb-1">Ketersediaan</div>
                            <div class="text-2xl font-bold text-blue-700">
                                {{ $equipment['quantity_available'] }}/{{ $equipment['quantity_total'] }} Unit
                            </div>
                            @if($equipment['quantity_available'] == 0)
                                <div class="text-xs text-red-600 mt-1">Stok habis</div>
                            @elseif($equipment['quantity_available'] <= 2)
                                <div class="text-xs text-yellow-600 mt-1">Stok terbatas</div>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm text-blue-600 font-semibold mb-1">Durasi Peminjaman</div>
                            <div class="text-2xl font-bold text-blue-700">{{ $equipment['loan_duration'] ?? 'Fleksibel' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="{{ route('equipment.loan') }}"
                       class="flex-1 bg-blue-500 text-white px-8 py-4 rounded-2xl font-semibold hover:bg-blue-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <i class="fas fa-hand-holding mr-3"></i>
                        Ajukan Peminjaman Sekarang
                    </a>
                </div>
            </div>
        </div>

        <!-- Full Description & Specifications -->
        <div class="mt-16 scroll-animate" data-animation="fade-up" data-delay="400">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Detail & Spesifikasi Alat</h3>

                <!-- Full Description -->
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($equipment['description'])) !!}
                </div>

                <!-- Additional Equipment Info -->
                @if(isset($equipment['price']) && $equipment['price'])
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl font-bold text-gray-900">{{ $equipment['quantity_total'] }}</div>
                            <div class="text-sm text-gray-600">Total Unit</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl font-bold text-green-600">{{ $equipment['quantity_available'] }}</div>
                            <div class="text-sm text-gray-600">Unit Tersedia</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl font-bold text-blue-600">Rp {{ number_format($equipment['price'], 0, ',', '.') }}</div>
                            <div class="text-sm text-gray-600">Nilai Aset</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- General Guidelines -->
        <div class="mt-12 scroll-animate" data-animation="fade-up" data-delay="600">
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Panduan Umum Penggunaan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-play-circle text-blue-500 mr-2"></i>
                            Sebelum Penggunaan
                        </h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1 text-sm"></i>
                                Periksa kelengkapan alat dan aksesoris
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1 text-sm"></i>
                                Pastikan kondisi alat dalam keadaan baik
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1 text-sm"></i>
                                Ikuti briefing dari staff laboratorium
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1 text-sm"></i>
                                Siapkan area kerja yang bersih dan aman
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                            Keselamatan & Pengembalian
                        </h4>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1 text-sm"></i>
                                Gunakan APD yang sesuai
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1 text-sm"></i>
                                Jangan operasikan tanpa pengawasan
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1 text-sm"></i>
                                Bersihkan alat setelah penggunaan
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1 text-sm"></i>
                                Laporkan kerusakan segera ke staff
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 p-6 bg-red-50 rounded-xl border border-red-200">
                    <h4 class="font-semibold text-red-900 mb-3">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Penting untuk Diperhatikan
                    </h4>
                    <p class="text-sm text-red-700">
                        Setiap alat memiliki karakteristik dan prosedur khusus.
                        <strong>Wajib mengikuti briefing dari staff laboratorium</strong> sebelum menggunakan alat untuk memastikan keselamatan dan penggunaan yang optimal.
                        Hubungi staff laboratorium jika ada pertanyaan atau kendala saat penggunaan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
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

.scroll-animate[data-animation="slide-left"] {
    transform: translateX(-100px);
}

.scroll-animate[data-animation="slide-right"] {
    transform: translateX(100px);
}

.scroll-animate.animate {
    opacity: 1;
    transform: translateY(0) translateX(0);
}

/* Image aspect ratio */
.aspect-w-4 {
    position: relative;
    padding-bottom: 75%; /* 4:3 aspect ratio */
}

.aspect-w-4 > * {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}

/* Prose styling for description */
.prose {
    max-width: none;
}

.prose p {
    margin-bottom: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize scroll animations
    initScrollAnimations();
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
</script>
@endsection
