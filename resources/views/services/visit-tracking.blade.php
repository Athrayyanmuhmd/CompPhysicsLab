    {{-- resources/views/services/visit-tracking.blade.php --}}
    @extends('layouts.app')

    @section('title', 'Lacak Pengajuan - Laboratorium Fisika Komputasi')

    @section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[50vh] flex items-center justify-center overflow-hidden">
        <!-- Background Image with Blue Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero.jpg') }}"
                alt="Lacak Pengajuan"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-700/90 via-blue-800/80 to-blue-900/70"></div>
        </div>

        <!-- Content -->
        <div class="relative z-20 mx-6 px-4 sm:px-6 lg:px-8 text-center max-w-4xl">
            <!-- Main Title -->
            <div class="scroll-animate mb-8 opacity-0" data-animation="fade-up">
                <h1 class="font-poppins text-4xl md:text-6xl font-bold leading-tight mb-6">
                    <span class="text-white">Lacak</span>
                <span class="bg-clip-text text-transparent drop-shadow-lg"
                    style="background-image: linear-gradient(90deg, rgba(250, 204, 21, 1) 0%, rgba(255, 255, 255, 1) 0%); -webkit-background-clip: text; color: transparent;">
                    Pengajuan
                </span>
                </h1>
                <p class="text-lg md:text-xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                    Pantau status pengajuan kunjungan laboratorium Anda secara real-time
                </p>
            </div>
        </div>
    </section>

    <!-- Tracking Content -->
    <section class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            <!-- Status Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 mb-8 scroll-animate" data-animation="fade-up">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center
                        {{ $kunjungan->status === 'PENDING' ? 'bg-yellow-100' : '' }}
                        {{ $kunjungan->status === 'PROCESSING' ? 'bg-blue-100' : '' }}
                        {{ $kunjungan->status === 'COMPLETED' ? 'bg-green-100' : '' }}
                        {{ $kunjungan->status === 'CANCELLED' ? 'bg-red-100' : '' }}">
                        <i class="fas
                            {{ $kunjungan->status === 'PENDING' ? 'fa-clock text-yellow-600' : '' }}
                            {{ $kunjungan->status === 'PROCESSING' ? 'fa-cog fa-spin text-blue-600' : '' }}
                            {{ $kunjungan->status === 'COMPLETED' ? 'fa-check text-green-600' : '' }}
                            {{ $kunjungan->status === 'CANCELLED' ? 'fa-times text-red-600' : '' }}
                            text-2xl"></i>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-900 mb-3">
                        Status: <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $kunjungan->status_badge_color }}">
                            {{ $kunjungan->status_text }}
                        </span>
                    </h2>

                    <p class="text-gray-600 text-lg">
                        ID Pengajuan: <strong>{{ substr($kunjungan->id, 0, 8) }}</strong>
                    </p>
                </div>

                <!-- Progress Steps -->
                <div class="mb-12">
                    <div class="flex items-center justify-between relative">
                        <!-- Progress Line -->
                        <div class="absolute top-4 left-0 w-full h-1 bg-gray-200 rounded-full">
                            <div class="h-full bg-blue-600 rounded-full transition-all duration-500"
                                style="width: {{ $kunjungan->status === 'PENDING' ? '25%' : ($kunjungan->status === 'PROCESSING' ? '75%' : '100%') }}"></div>
                        </div>

                        <!-- Step 1: Submitted -->
                        <div class="relative flex flex-col items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div class="mt-2 text-center">
                                <p class="text-sm font-semibold text-gray-900">Dikirim</p>
                                <p class="text-xs text-gray-500">{{ $kunjungan->created_at->format('d M H:i') }}</p>
                            </div>
                        </div>

                        <!-- Step 2: Processing -->
                        <div class="relative flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full border-4 border-white shadow-lg flex items-center justify-center
                                {{ in_array($kunjungan->status, ['PROCESSING', 'COMPLETED']) ? 'bg-blue-600' : 'bg-gray-300' }}">
                                <i class="fas {{ in_array($kunjungan->status, ['PROCESSING', 'COMPLETED']) ? 'fa-check text-white' : 'fa-clock text-gray-600' }} text-sm"></i>
                            </div>
                            <div class="mt-2 text-center">
                                <p class="text-sm font-semibold {{ in_array($kunjungan->status, ['PROCESSING', 'COMPLETED']) ? 'text-gray-900' : 'text-gray-500' }}">Diproses</p>
                                <p class="text-xs text-gray-500">
                                    {{ in_array($kunjungan->status, ['PROCESSING', 'COMPLETED']) ? $kunjungan->updated_at->format('d M H:i') : 'Menunggu' }}
                                </p>
                            </div>
                        </div>

                        <!-- Step 3: Completed -->
                        <div class="relative flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full border-4 border-white shadow-lg flex items-center justify-center
                                {{ $kunjungan->status === 'COMPLETED' ? 'bg-green-600' : 'bg-gray-300' }}">
                                <i class="fas {{ $kunjungan->status === 'COMPLETED' ? 'fa-check text-white' : 'fa-flag text-gray-600' }} text-sm"></i>
                            </div>
                            <div class="mt-2 text-center">
                                <p class="text-sm font-semibold {{ $kunjungan->status === 'COMPLETED' ? 'text-gray-900' : 'text-gray-500' }}">Selesai</p>
                                <p class="text-xs text-gray-500">
                                    {{ $kunjungan->status === 'COMPLETED' ? $kunjungan->updated_at->format('d M H:i') : 'Belum selesai' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visit Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user text-blue-600 mr-3"></i>
                                Informasi Pengunjung
                            </h3>
                            <div class="space-y-3 pl-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium">{{ $kunjungan->namaPengunjung }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Institusi:</span>
                                    <span class="font-medium">{{ $kunjungan->institution }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ $kunjungan->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Telepon:</span>
                                    <span class="font-medium">{{ $kunjungan->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                                Detail Kunjungan
                            </h3>
                            <div class="space-y-3 pl-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tujuan:</span>
                                    <span class="font-medium">{{ $kunjungan->tujuan }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal:</span>
                                    <span class="font-medium">{{ $kunjungan->formatted_date }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Waktu:</span>
                                    <span class="font-medium">{{ $kunjungan->formatted_time }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Peserta:</span>
                                    <span class="font-medium">{{ $kunjungan->jumlahPengunjung }} orang</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                @if($kunjungan->catatan_tambahan)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-sticky-note text-blue-600 mr-3"></i>
                        Catatan Tambahan
                    </h3>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-700 leading-relaxed">{{ $kunjungan->catatan_tambahan }}</p>
                    </div>
                </div>
                @endif

                <!-- Document -->
                @if($kunjungan->dokumen_surat)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                        Dokumen Pengajuan
                    </h3>
                    <div class="bg-blue-50 rounded-xl p-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-600 text-2xl mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-900">Surat Pengajuan</p>
                                <p class="text-sm text-gray-600">{{ basename($kunjungan->dokumen_surat) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('visit.document', $kunjungan) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                            <i class="fas fa-download mr-2"></i>
                            Download
                        </a>
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('home') }}"
                        class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-all duration-200 flex items-center justify-center">
                            <i class="fas fa-home mr-2"></i>
                            Kembali ke Beranda
                        </a>

                        @if($kunjungan->status === 'PENDING')
                        <button onclick="shareWhatsApp()"
                                class="bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 transition-all duration-200 flex items-center justify-center">
                            <i class="fab fa-whatsapp mr-2"></i>
                            Hubungi Admin
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Status Messages -->
            @if($kunjungan->status === 'PENDING')
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-8 scroll-animate" data-animation="fade-up" data-delay="200">
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="text-yellow-800 font-semibold text-lg mb-2">Pengajuan Sedang Diproses</h3>
                        <p class="text-yellow-700">Tim kami akan menghubungi Anda dalam 1-2 hari kerja untuk konfirmasi jadwal kunjungan.</p>
                    </div>
                </div>
            </div>
            @elseif($kunjungan->status === 'PROCESSING')
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8 scroll-animate" data-animation="fade-up" data-delay="200">
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cog text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="text-blue-800 font-semibold text-lg mb-2">Kunjungan Telah Disetujui</h3>
                        <p class="text-blue-700">Selamat! Pengajuan kunjungan Anda telah disetujui. Silakan datang sesuai jadwal yang telah ditentukan.</p>
                    </div>
                </div>
            </div>
            @elseif($kunjungan->status === 'COMPLETED')
            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8 scroll-animate" data-animation="fade-up" data-delay="200">
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-green-800 font-semibold text-lg mb-2">Kunjungan Telah Selesai</h3>
                        <p class="text-green-700">Terima kasih telah mengunjungi laboratorium kami. Semoga pengalaman ini bermanfaat!</p>
                    </div>
                </div>
            </div>
            @elseif($kunjungan->status === 'CANCELLED')
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8 scroll-animate" data-animation="fade-up" data-delay="200">
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                    <div>
                        <h3 class="text-red-800 font-semibold text-lg mb-2">Pengajuan Dibatalkan</h3>
                        <p class="text-red-700">Mohon maaf, pengajuan kunjungan Anda tidak dapat diproses. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <style>
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Scroll Animations */
    .scroll-animate {
        opacity: 0;
        transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .scroll-animate[data-animation="fade-up"] {
        transform: translateY(60px);
    }

    .scroll-animate.animate {
        opacity: 1;
        transform: translateY(0);
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Intersection Observer for scroll animations
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

        // Auto-refresh every 30 seconds if status is PENDING
        @if($kunjungan->status === 'PENDING')
            setTimeout(() => {
                location.reload();
            }, 30000);
        @endif
    });

    function shareWhatsApp() {
        const message = `Halo Admin Laboratorium Fisika Komputasi,\n\nSaya ingin menanyakan status pengajuan kunjungan dengan:\n\nID: {{ substr($kunjungan->id, 0, 8) }}\nNama: {{ $kunjungan->namaPengunjung }}\nInstitusi: {{ $kunjungan->institution }}\nTanggal: {{ $kunjungan->formatted_date }}\n\nMohon informasi lebih lanjut.\n\nTerima kasih.`;
        const phoneNumber = '6281234567890'; // Sesuaikan dengan nomor admin
        const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }
    </script>
    @endsection
