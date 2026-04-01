{{-- resources/views/services/equipment-tracking.blade.php --}}
@extends('layouts.app')

@section('title', 'Lacak Peminjaman - Laboratorium Fisika Komputasi')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[50vh] flex items-center justify-center overflow-hidden">
    <!-- Background Image with Blue Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero.jpg') }}"
             alt="Lacak Peminjaman"
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
                    Peminjaman
                </span>
            </h1>
            <p class="text-lg md:text-xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                Pantau status peminjaman alat laboratorium Anda secara real-time
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
                    {{ $peminjaman->status === 'PENDING' ? 'bg-yellow-100' : '' }}
                    {{ $peminjaman->status === 'APPROVED' ? 'bg-blue-100' : '' }}
                    {{ $peminjaman->status === 'ACTIVE' ? 'bg-green-100' : '' }}
                    {{ $peminjaman->status === 'COMPLETED' ? 'bg-purple-100' : '' }}
                    {{ $peminjaman->status === 'REJECTED' ? 'bg-red-100' : '' }}">
                    <i class="fas
                        {{ $peminjaman->status === 'PENDING' ? 'fa-clock text-yellow-600' : '' }}
                        {{ $peminjaman->status === 'APPROVED' ? 'fa-check text-blue-600' : '' }}
                        {{ $peminjaman->status === 'ACTIVE' ? 'fa-tools fa-spin text-green-600' : '' }}
                        {{ $peminjaman->status === 'COMPLETED' ? 'fa-check-double text-purple-600' : '' }}
                        {{ $peminjaman->status === 'REJECTED' ? 'fa-times text-red-600' : '' }}
                        text-2xl"></i>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-3">
                    Status: <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $peminjaman->status_badge_color }}">
                        {{ $peminjaman->status_text }}
                    </span>
                </h2>

                <p class="text-gray-600 text-lg">
                    ID Peminjaman: <strong>{{ $peminjaman->reference_number }}</strong>
                </p>
            </div>

            <!-- Progress Steps -->
            <div class="mb-12">
                <div class="flex items-center justify-between relative">
                    <!-- Progress Line -->
                    <div class="absolute top-4 left-0 w-full h-1 bg-gray-200 rounded-full">
                        <div class="h-full bg-blue-600 rounded-full transition-all duration-500"
                             style="width: {{ $peminjaman->progress_percentage }}%"></div>
                    </div>

                    <!-- Step 1: Submitted -->
                    <div class="relative flex flex-col items-center">
                        <div class="w-8 h-8 bg-blue-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-sm font-semibold text-gray-900">Dikirim</p>
                            <p class="text-xs text-gray-500">{{ $peminjaman->created_at->format('d M H:i') }}</p>
                        </div>
                    </div>

                    <!-- Step 2: Approved -->
                    <div class="relative flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full border-4 border-white shadow-lg flex items-center justify-center
                            {{ in_array($peminjaman->status, ['APPROVED', 'ACTIVE', 'COMPLETED']) ? 'bg-blue-600' : 'bg-gray-300' }}">
                            <i class="fas {{ in_array($peminjaman->status, ['APPROVED', 'ACTIVE', 'COMPLETED']) ? 'fa-check text-white' : 'fa-clock text-gray-600' }} text-sm"></i>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-sm font-semibold {{ in_array($peminjaman->status, ['APPROVED', 'ACTIVE', 'COMPLETED']) ? 'text-gray-900' : 'text-gray-500' }}">Disetujui</p>
                            <p class="text-xs text-gray-500">
                                {{ in_array($peminjaman->status, ['APPROVED', 'ACTIVE', 'COMPLETED']) ? 'Disetujui' : 'Menunggu' }}
                            </p>
                        </div>
                    </div>

                    <!-- Step 3: Active (Diambil) -->
                    <div class="relative flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full border-4 border-white shadow-lg flex items-center justify-center
                            {{ in_array($peminjaman->status, ['ACTIVE', 'COMPLETED']) ? 'bg-green-600' : 'bg-gray-300' }}">
                            <i class="fas {{ in_array($peminjaman->status, ['ACTIVE', 'COMPLETED']) ? 'fa-check text-white' : 'fa-tools text-gray-600' }} text-sm"></i>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-sm font-semibold {{ in_array($peminjaman->status, ['ACTIVE', 'COMPLETED']) ? 'text-gray-900' : 'text-gray-500' }}">Diambil</p>
                            <p class="text-xs text-gray-500">
                                {{ in_array($peminjaman->status, ['ACTIVE', 'COMPLETED']) ? 'Alat diambil' : 'Belum diambil' }}
                            </p>
                        </div>
                    </div>

                    <!-- Step 4: Completed -->
                    <div class="relative flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full border-4 border-white shadow-lg flex items-center justify-center
                            {{ $peminjaman->status === 'COMPLETED' ? 'bg-purple-600' : 'bg-gray-300' }}">
                            <i class="fas {{ $peminjaman->status === 'COMPLETED' ? 'fa-check text-white' : 'fa-undo text-gray-600' }} text-sm"></i>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-sm font-semibold {{ $peminjaman->status === 'COMPLETED' ? 'text-gray-900' : 'text-gray-500' }}">Dikembalikan</p>
                            <p class="text-xs text-gray-500">
                                {{ $peminjaman->status === 'COMPLETED' ? 'Selesai' : 'Belum dikembalikan' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user text-blue-600 mr-3"></i>
                            Informasi Peminjam
                        </h3>
                        <div class="space-y-3 pl-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-medium">{{ $peminjaman->namaPeminjam }}</span>
                            </div>
                            @if($peminjaman->student_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ $peminjaman->is_mahasiswa_usk ? 'NIM' : 'NIM/NIP' }}:</span>
                                <span class="font-medium">{{ $peminjaman->student_id }}</span>
                            </div>
                            @endif
                            @if($peminjaman->instansi)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Instansi:</span>
                                <span class="font-medium">{{ $peminjaman->instansi }}</span>
                            </div>
                            @endif
                            @if($peminjaman->email)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $peminjaman->email }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Telepon:</span>
                                <span class="font-medium">{{ $peminjaman->noHp }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                            Detail Peminjaman
                        </h3>
                        <div class="space-y-3 pl-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Mulai:</span>
                                <span class="font-medium">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Selesai:</span>
                                <span class="font-medium">{{ $peminjaman->tanggal_pengembalian->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Durasi:</span>
                                <span class="font-medium">{{ $peminjaman->duration_days }} hari</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status Mahasiswa:</span>
                                <span class="font-medium">{{ $peminjaman->is_mahasiswa_usk ? 'Mahasiswa USK' : 'Eksternal' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment List -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tools text-blue-600 mr-3"></i>
                    Alat yang Dipinjam ({{ $peminjaman->total_types }} jenis, {{ $peminjaman->total_quantity }} unit)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($peminjaman->items as $item)
                    <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-cog text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $item->alat->nama }}</div>
                                <div class="text-sm text-gray-600">{{ $item->alat->kode ?? 'Model tidak tersedia' }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-blue-600">{{ $item->jumlah }} unit</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Purpose -->
            @if($peminjaman->tujuanPeminjaman)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                    <i class="fas fa-sticky-note text-blue-600 mr-3"></i>
                    Tujuan Peminjaman
                </h3>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-gray-700 leading-relaxed">{{ $peminjaman->tujuanPeminjaman }}</p>
                </div>
            </div>
            @endif

            <!-- Return Warning (if active and near due date) -->
            @if($peminjaman->status === 'ACTIVE' && $peminjaman->days_until_return <= 2)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 flex items-start space-x-3">
                    <i class="fas fa-exclamation-triangle text-orange-600 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-orange-800 mb-1">Pengingat Pengembalian</h4>
                        <p class="text-orange-700 text-sm">
                            @if($peminjaman->days_until_return < 0)
                                Terlambat {{ abs($peminjaman->days_until_return) }} hari. Segera kembalikan alat!
                            @elseif($peminjaman->days_until_return === 0)
                                Hari terakhir peminjaman. Harap kembalikan hari ini.
                            @else
                                Sisa waktu {{ $peminjaman->days_until_return }} hari lagi.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('equipment.loan') }}"
                       class="bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Peminjaman
                    </a>

                    @if($peminjaman->status === 'PENDING')
                    <button onclick="shareWhatsApp()"
                            class="bg-green-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-700 transition-all duration-200 flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Hubungi Admin
                    </button>
                    @endif

                    @if($peminjaman->status === 'APPROVED')
                    <button onclick="shareReminderWhatsApp()"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-all duration-200 flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Konfirmasi Pengambilan
                    </button>
                    @endif

                    @if($peminjaman->status === 'ACTIVE')
                    <button onclick="shareReminderWhatsApp()"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-all duration-200 flex items-center justify-center">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Konfirmasi Status
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Messages -->
        @if($peminjaman->status === 'PENDING')
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-8 scroll-animate" data-animation="fade-up" data-delay="200">
            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <h3 class="text-yellow-800 font-semibold text-lg mb-2">Permohonan Sedang Diproses</h3>
                    <p class="text-yellow-700">Tim kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi dan penjadwalan briefing keselamatan.</p>
                </div>
            </div>
        </div>
        @elseif($peminjaman->status === 'APPROVED')
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8 scroll-animate" data-animation="fade-up" data-delay="200">
            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div>
                    <h3 class="text-green-800 font-semibold text-lg mb-2">Peminjaman Disetujui</h3>
                    <p class="text-green-700">Selamat! Permohonan peminjaman Anda telah disetujui. Silakan datang ke laboratorium untuk mengambil alat sesuai jadwal yang telah ditentukan.</p>
                </div>
            </div>
        </div>
        @elseif($peminjaman->status === 'ACTIVE')
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-8 scroll-animate" data-animation="fade-up" data-delay="200">
            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-tools text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-blue-800 font-semibold text-lg mb-2">Peminjaman Aktif</h3>
                    <p class="text-blue-700">Alat sedang dalam masa peminjaman. Harap kembalikan sesuai jadwal yang telah ditentukan.</p>
                </div>
            </div>
        </div>
        @elseif($peminjaman->status === 'COMPLETED')
        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6 mb-8 scroll-animate" data-animation="fade-up" data-delay="200">
            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-double text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-purple-800 font-semibold text-lg mb-2">Peminjaman Selesai</h3>
                    <p class="text-purple-700">Terima kasih! Peminjaman telah selesai dan alat telah dikembalikan. Semoga alat yang dipinjam bermanfaat untuk kegiatan Anda.</p>
                </div>
            </div>
        </div>
        @elseif($peminjaman->status === 'CANCELLED')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8 scroll-animate" data-animation="fade-up" data-delay="200">
            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-times text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-red-800 font-semibold text-lg mb-2">Permohonan Ditolak</h3>
                    <p class="text-red-700">Mohon maaf, permohonan peminjaman Anda tidak dapat disetujui. Silakan hubungi admin untuk informasi lebih lanjut atau ajukan permohonan baru.</p>
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
    @if($peminjaman->status === 'PENDING')
        setTimeout(() => {
            location.reload();
        }, 30000);
    @endif
});

function shareWhatsApp() {
    const message = `Halo Admin Laboratorium Fisika Komputasi,\n\nSaya ingin menanyakan status permohonan peminjaman alat dengan:\n\nID: {{ $peminjaman->reference_number }}\nNama: {{ $peminjaman->namaPeminjam }}\nTanggal Mulai: {{ $peminjaman->tanggal_pinjam->format('d M Y') }}\nJumlah Alat: {{ $peminjaman->total_types }} jenis ({{ $peminjaman->total_quantity }} unit)\n\nMohon informasi lebih lanjut.\n\nTerima kasih.`;
    const phoneNumber = '6287801482963'; // Sesuaikan dengan nomor admin
    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function shareReminderWhatsApp() {
    let message = `Halo Admin Laboratorium Fisika Komputasi,\n\nSaya ingin mengkonfirmasi status peminjaman alat:\n\nID: {{ $peminjaman->reference_number }}\nNama: {{ $peminjaman->namaPeminjam }}\nStatus: {{ $peminjaman->status_text }}\n`;

    @if($peminjaman->status === 'APPROVED')
        message += `\nKapan saya bisa mengambil alat yang sudah disetujui?\n`;
    @elseif($peminjaman->status === 'ACTIVE')
        message += `\nAlat sedang dipinjam, apakah ada info penting mengenai pengembalian?\n`;
        @if($peminjaman->days_until_return <= 2)
            message += `Sisa waktu: {{ $peminjaman->days_until_return }} hari\n`;
        @endif
    @endif

    message += `\nTerima kasih.`;

    const phoneNumber = '6287801482963'; // Sesuaikan dengan nomor admin
    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}
// JavaScript untuk halaman tracking peminjaman alat
// Tambahkan di equipment-tracking.blade.php

document.addEventListener('DOMContentLoaded', function() {
    // Configuration
    const LOAN_ID = '{{ $peminjaman->id }}';
    const CHECK_INTERVAL = 30000; // 30 seconds
    const ADMIN_PHONE = '6287801482963';

    let lastChecked = new Date().toISOString();
    let checkInterval;

    // Initialize tracking features
    initializeTracking();
    startStatusChecking();

    function initializeTracking() {
        // Initialize scroll animations
        initScrollAnimations();

        // Show initial notification based on status
        showInitialNotification();

        // Setup action buttons
        setupActionButtons();

        // Check for overdue status
        checkOverdueStatus();

        // Setup auto-refresh for pending status
        setupAutoRefresh();
    }

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

    function showInitialNotification() {
        const status = '{{ $peminjaman->status }}';
        const daysUntilReturn = {{ $peminjaman->days_until_return ?? 'null' }};

        setTimeout(() => {
            switch (status) {
                case 'PENDING':
                    showToast('Status peminjaman sedang diproses. Kami akan menghubungi Anda segera.', 'info', 5000);
                    break;
                case 'APPROVED':
                    showToast('Peminjaman disetujui! Silakan ambil alat sesuai jadwal yang telah ditentukan.', 'success', 5000);
                    break;
                case 'ACTIVE':
                    if (daysUntilReturn !== null) {
                        if (daysUntilReturn < 0) {
                            showToast(`Peminjaman terlambat ${Math.abs(daysUntilReturn)} hari. Segera kembalikan alat!`, 'error', 8000);
                        } else if (daysUntilReturn <= 2) {
                            showToast(`Pengingat: Sisa waktu ${daysUntilReturn} hari lagi.`, 'warning', 6000);
                        }
                    }
                    break;
                case 'COMPLETED':
                    showToast('Peminjaman telah selesai. Terima kasih telah menggunakan layanan kami!', 'success', 5000);
                    break;
            }
        }, 1000);
    }

    function setupActionButtons() {
        // WhatsApp buttons with different message types
        const statusInquiryBtn = document.getElementById('status-inquiry-btn');
        const reminderBtn = document.getElementById('reminder-btn');
        const confirmationBtn = document.getElementById('confirmation-btn');

        if (statusInquiryBtn) {
            statusInquiryBtn.addEventListener('click', () => generateWhatsAppMessage('status_inquiry'));
        }

        if (reminderBtn) {
            reminderBtn.addEventListener('click', () => generateWhatsAppMessage('reminder'));
        }

        if (confirmationBtn) {
            confirmationBtn.addEventListener('click', () => generateWhatsAppMessage('confirmation'));
        }
    }

    function checkOverdueStatus() {
        const isOverdue = {{ $peminjaman->is_overdue ? 'true' : 'false' }};
        const status = '{{ $peminjaman->status }}';

        if (isOverdue && status === 'ACTIVE') {
            // Add overdue styling
            document.body.classList.add('overdue-loan');

            // Show persistent overdue notification
            setTimeout(() => {
                showPersistentNotification(
                    'Peminjaman Terlambat',
                    'Alat sudah melewati batas waktu pengembalian. Harap segera menghubungi admin untuk perpanjangan atau pengembalian.',
                    'error'
                );
            }, 2000);
        }
    }

    function setupAutoRefresh() {
        const status = '{{ $peminjaman->status }}';

        // Auto-refresh for pending status
        if (status === 'PENDING') {
            setTimeout(() => {
                location.reload();
            }, 60000); // Refresh after 1 minute for pending status
        }
    }

    function startStatusChecking() {
        // Check status every 30 seconds
        checkInterval = setInterval(checkStatusUpdate, CHECK_INTERVAL);

        // Check immediately
        setTimeout(checkStatusUpdate, 5000);
    }

    async function checkStatusUpdate() {
        try {
            const response = await fetch(`/equipment/check-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    loan_id: LOAN_ID,
                    last_checked: lastChecked
                })
            });

            if (response.ok) {
                const data = await response.json();

                if (data.success) {
                    handleStatusUpdate(data);
                    lastChecked = data.loan.updated_at;
                }
            }
        } catch (error) {
            console.error('Error checking status:', error);
            // Reduce check frequency on error
            clearInterval(checkInterval);
            checkInterval = setInterval(checkStatusUpdate, CHECK_INTERVAL * 2);
        }
    }

    function handleStatusUpdate(data) {
        const { loan, notifications } = data;

        // Show notifications
        notifications.forEach(notification => {
            showToast(notification.message, notification.type, 6000);
        });

        // Check if page needs refresh for major status changes
        const currentStatus = '{{ $peminjaman->status }}';
        if (loan.status !== currentStatus) {
            showToast('Status peminjaman telah diperbarui. Halaman akan dimuat ulang...', 'info', 3000);
            setTimeout(() => location.reload(), 3000);
        }
    }

    async function generateWhatsAppMessage(messageType) {
        try {
            showToast('Menyiapkan pesan WhatsApp...', 'info', 2000);

            const response = await fetch('/equipment/generate-whatsapp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    loan_id: LOAN_ID,
                    message_type: messageType
                })
            });

            const data = await response.json();

            if (data.success) {
                // Open WhatsApp
                window.open(data.whatsapp_url, '_blank');
                showToast('WhatsApp telah dibuka. Silakan kirim pesan ke admin.', 'success', 4000);
            } else {
                throw new Error(data.message || 'Gagal membuat pesan WhatsApp');
            }
        } catch (error) {
            console.error('WhatsApp message error:', error);
            showToast('Gagal membuat pesan WhatsApp: ' + error.message, 'error');

            // Fallback to manual WhatsApp
            openManualWhatsApp(messageType);
        }
    }

    function openManualWhatsApp(messageType) {
        const loanData = {
            reference: '{{ $peminjaman->reference_number }}',
            name: '{{ $peminjaman->namaPeminjam }}',
            status: '{{ $peminjaman->status_text }}',
            startDate: '{{ $peminjaman->formatted_start_date }}',
            equipmentCount: '{{ $peminjaman->total_types }}',
            totalUnits: '{{ $peminjaman->total_quantity }}'
        };

        let message = `Halo Admin Laboratorium Fisika Komputasi,\n\n`;

        switch (messageType) {
            case 'status_inquiry':
                message += `Saya ingin menanyakan status permohonan peminjaman alat:\n\n` +
                          `ID: ${loanData.reference}\n` +
                          `Nama: ${loanData.name}\n` +
                          `Status: ${loanData.status}\n` +
                          `Tanggal Mulai: ${loanData.startDate}\n` +
                          `Jumlah Alat: ${loanData.equipmentCount} jenis (${loanData.totalUnits} unit)\n\n` +
                          `Mohon informasi lebih lanjut.\n\nTerima kasih.`;
                break;
            case 'reminder':
            case 'confirmation':
                message += `Saya ingin mengkonfirmasi status peminjaman:\n\n` +
                          `ID: ${loanData.reference}\n` +
                          `Nama: ${loanData.name}\n` +
                          `Status: ${loanData.status}\n\n` +
                          `Terima kasih.`;
                break;
        }

        const whatsappUrl = `https://wa.me/${ADMIN_PHONE}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }

    function showPersistentNotification(title, message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full mx-4`;

        const typeClasses = {
            error: 'bg-red-50 border-red-200 text-red-800',
            warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800',
            success: 'bg-green-50 border-green-200 text-green-800'
        };

        const typeIcons = {
            error: 'fa-exclamation-triangle text-red-500',
            warning: 'fa-exclamation-triangle text-yellow-500',
            info: 'fa-info-circle text-blue-500',
            success: 'fa-check-circle text-green-500'
        };

        notification.innerHTML = `
            <div class="border rounded-xl shadow-lg backdrop-blur-sm p-4 ${typeClasses[type]}">
                <div class="flex items-start space-x-3">
                    <i class="fas ${typeIcons[type]} text-lg mt-1 flex-shrink-0"></i>
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm mb-1">${title}</h4>
                        <p class="text-sm leading-relaxed">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()"
                            class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto-remove after 30 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 30000);
    }

    // Toast notification system
    function showToast(message, type = 'info', duration = 5000) {
        const container = getToastContainer();
        const toast = createToastElement(message, type);

        container.appendChild(toast);
        animateToastIn(toast);

        if (duration > 0) {
            setTimeout(() => dismissToast(toast), duration);
        }
    }

    function getToastContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed bottom-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }
        return container;
    }

    function createToastElement(message, type) {
        const toast = document.createElement('div');
        toast.className = 'toast-notification transform translate-x-full opacity-0 transition-all duration-300';

        const typeClasses = {
            success: 'bg-green-50 border-green-200 text-green-800',
            error: 'bg-red-50 border-red-200 text-red-800',
            warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };

        const typeIcons = {
            success: 'fa-check-circle text-green-500',
            error: 'fa-exclamation-circle text-red-500',
            warning: 'fa-exclamation-triangle text-yellow-500',
            info: 'fa-info-circle text-blue-500'
        };

        toast.innerHTML = `
            <div class="flex items-start p-3 rounded-lg border shadow-lg backdrop-blur-sm max-w-sm ${typeClasses[type]}">
                <i class="fas ${typeIcons[type]} text-sm mt-0.5 mr-3 flex-shrink-0"></i>
                <p class="text-sm font-medium leading-5 flex-1">${message}</p>
                <button onclick="dismissToast(this.closest('.toast-notification'))"
                        class="ml-3 text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `;

        return toast;
    }

    function animateToastIn(toast) {
        setTimeout(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        }, 100);
    }

    window.dismissToast = function(toast) {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    };

    // Global functions for template
    window.shareWhatsApp = function() {
        generateWhatsAppMessage('status_inquiry');
    };

    window.shareReminderWhatsApp = function() {
        generateWhatsAppMessage('reminder');
    };

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (checkInterval) {
            clearInterval(checkInterval);
        }
    });

    // Handle visibility change to pause/resume checking
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            if (checkInterval) {
                clearInterval(checkInterval);
            }
        } else {
            startStatusChecking();
        }
    });
});

// CSS untuk overdue styling
const overdueStyles = `
    <style>
        .overdue-loan .relative.z-20 h1 {
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0%, 100% {
                text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
            }
            50% {
                text-shadow: 0 0 20px rgba(239, 68, 68, 0.8);
            }
        }

        .overdue-loan .bg-white.rounded-3xl {
            border: 2px solid #fca5a5;
            box-shadow: 0 0 30px rgba(239, 68, 68, 0.1);
        }
    </style>
`;

document.head.insertAdjacentHTML('beforeend', overdueStyles);
</script>
@endsection
