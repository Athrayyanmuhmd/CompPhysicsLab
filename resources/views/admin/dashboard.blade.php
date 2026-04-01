@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--accent-color);
    }

    .stat-card.blue { --accent-color: #3b82f6; }
    .stat-card.green { --accent-color: #10b981; }
    .stat-card.purple { --accent-color: #8b5cf6; }
    .stat-card.orange { --accent-color: #f59e0b; }
    .stat-card.indigo { --accent-color: #6366f1; }

    .activity-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }

    .activity-item {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s ease;
    }

    .activity-item:hover {
        background-color: #f8fafc;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-approved {
        background: #d1fae5;
        color: #059669;
    }

    .status-processing {
        background: #dbeafe;
        color: #2563eb;
    }

    .status-completed {
        background: #d1fae5;
        color: #059669;
    }

    .status-rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    .icon-container {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fade-up {
        animation: fadeUp 0.6s ease-out;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .quick-action-card {
        background: linear-gradient(135deg, rgba(102, 126, 234, 1) 0%, rgba(30, 58, 138, 1) 0%);
        border-radius: 12px;
        color: white;
        transition: transform 0.2s ease;
    }

    .quick-action-card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="space-y-6 w-full px-0 md:px-2 lg:px-4">
    <!-- Header Section -->
    <div class="fade-up">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Selamat datang di sistem manajemen Laboratorium Fisika Komputasi</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6">
        <!-- Peminjaman Pending -->
        <div class="stat-card blue p-6 hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Peminjaman Pending</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        @php
                            $peminjamanPending = 0;
                            try {
                                $peminjamanPending = \App\Models\Peminjaman::where('status', 'pending')->count();
                            } catch (Exception $e) {
                                // Handle if model doesn't exist
                            }
                        @endphp
                        {{ $peminjamanPending }}
                    </p>
                </div>
                <div class="icon-container bg-blue-50">
                    <i class="fas fa-exchange-alt text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4 hidden">
                <a href="{{ route('admin.peminjaman.index') }}"
                   class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat semua →
                </a>
            </div>
        </div>

        <!-- Kunjungan Pending -->
        <div class="stat-card purple p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Kunjungan Pending</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        @php
                            $kunjunganPending = 0;
                            try {
                                $kunjunganPending = \App\Models\Kunjungan::where('status', 'pending')->count();
                            } catch (Exception $e) {
                                // Handle if model doesn't exist
                            }
                        @endphp
                        {{ $kunjunganPending }}
                    </p>
                </div>
                <div class="icon-container bg-purple-50">
                    <i class="fas fa-calendar-check text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.visits.index') }}"
                   class="text-sm text-blue-900 hover:text-blue-800 font-medium">
                    Lihat semua →
                </a>
            </div>
        </div>

        <!-- Total Equipment -->
        <div class="stat-card green p-6 hidden">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Peralatan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        @php
                            $totalAlat = 0;
                            try {
                                $totalAlat = \App\Models\Alat::count();
                            } catch (Exception $e) {
                                // Handle if model doesn't exist
                            }
                        @endphp
                        {{ $totalAlat }}
                    </p>
                </div>
                <div class="icon-container bg-green-50">
                    <i class="fas fa-tools text-green-600"></i>
                </div>
            </div>
            <div class="mt-4 hidden">
                <a href="{{ route('admin.equipment.index') }}"
                   class="text-sm text-green-600 hover:text-green-700 font-medium">
                    Kelola peralatan →
                </a>
            </div>
        </div>

        <!-- Total Staff -->
        <div class="stat-card orange p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Staff</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        @php
                            $totalStaff = 0;
                            try {
                                $totalStaff = \App\Models\BiodataPengurus::count();
                            } catch (Exception $e) {
                                // Handle if model doesn't exist
                            }
                        @endphp
                        {{ $totalStaff }}
                    </p>
                </div>
                <div class="icon-container bg-orange-50">
                    <i class="fas fa-users text-orange-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.staff.index') }}"
                   class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                    Kelola staff →
                </a>
            </div>
        </div>

        <!-- Total Articles -->
        <div class="stat-card blue p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Artikel</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
                        @php
                            $totalArtikel = 0;
                            try {
                                $totalArtikel = \App\Models\Artikel::count();
                            } catch (Exception $e) {
                                // Handle if model doesn't exist
                            }
                        @endphp
                        {{ $totalArtikel }}
                    </p>
                </div>
                <div class="icon-container bg-indigo-50">
                    <i class="fas fa-newspaper text-indigo-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.articles.index') }}"
                   class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                    Kelola artikel →
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
        <div class="quick-action-card p-6 w-full">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Kelola Jadwal</h3>
                    <p class="text-white/80 text-sm mt-1">Atur jadwal available lab</p>
                </div>
                <i class="fas fa-calendar-alt text-2xl text-white/80"></i>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.schedule.index') }}"
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Buka Jadwal
                </a>
            </div>
        </div>

        <div class="quick-action-card p-6 w-full" style="background: linear-gradient(135deg, rgba(102, 126, 234, 1) 0%, rgba(30, 58, 138, 1) 0%)">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Kelola Artikel</h3>
                    <p class="text-white/80 text-sm mt-1">Publish artikel terbaru</p>
                </div>
                <i class="fas fa-newspaper text-2xl text-white/80"></i>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.articles.index') }}"
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Kelola Artikel
                </a>
            </div>
        </div>

        @if(Auth::user()->role === 'super_admin')
        <div class="quick-action-card p-6" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%)">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Kelola Admin</h3>
                    <p class="text-white/80 text-sm mt-1">Manage admin users</p>
                </div>
                <i class="fas fa-user-shield text-2xl text-white/80"></i>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}"
                   class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Kelola Admin
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Peminjaman -->
        <div class="activity-card hidden w-full">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Peminjaman Terbaru</h3>
                    <i class="fas fa-exchange-alt text-blue-500"></i>
                </div>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @php
                    $recentPeminjaman = collect();
                    try {
                        $recentPeminjaman = \App\Models\Peminjaman::with('user')->latest()->take(5)->get();
                    } catch (Exception $e) {
                        // Handle if model doesn't exist
                    }
                @endphp

                @forelse($recentPeminjaman as $peminjaman)
                <div class="activity-item flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-blue-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">
                                {{ $peminjaman->user->name ?? 'Unknown User' }}
                            </p>
                            <p class="text-gray-500 text-xs">
                                {{ $peminjaman->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <span class="status-badge status-{{ $peminjaman->status }}">
                        {{ ucfirst($peminjaman->status) }}
                    </span>
                </div>
                @empty
                <div class="p-8 text-center">
                    <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                    <p class="text-gray-500 text-sm">Belum ada data peminjaman</p>
                </div>
                @endforelse
            </div>
            @if($recentPeminjaman->count() > 0)
            <div class="p-4 border-t bg-gray-50 hidden">
                <a href="{{ route('admin.peminjaman.index') }}"
                   class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat semua peminjaman →
                </a>
            </div>
            @endif
        </div>

        <!-- Recent Kunjungan -->
        <div class="activity-card w-full">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Kunjungan Terbaru</h3>
                    <i class="fas fa-calendar-check text-blue-600"></i>
                </div>
            </div>
            <div class="max-h-80 overflow-y-auto">
                @php
                    $recentKunjungan = collect();
                    try {
                        $recentKunjungan = \App\Models\Kunjungan::latest()->take(5)->get();
                    } catch (Exception $e) {
                        // Handle if model doesn't exist
                    }
                @endphp

                @forelse($recentKunjungan as $kunjungan)
                <div class="activity-item flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-blue-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">
                                {{ $kunjungan->namaPengunjung ?? 'Unknown Visitor' }}
                            </p>
                            <p class="text-gray-500 text-xs">
                                @if($kunjungan->tanggal_kunjungan)
                                    {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y') }}
                                @else
                                    {{ $kunjungan->created_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <span class="status-badge status-{{ $kunjungan->status }}">
                        {{ ucfirst($kunjungan->status) }}
                    </span>
                </div>
                @empty
                <div class="p-8 text-center">
                    <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                    <p class="text-gray-500 text-sm">Belum ada data kunjungan</p>
                </div>
                @endforelse
            </div>
            @if($recentKunjungan->count() > 0)
            <div class="p-4 border-t bg-gray-50">
                <a href="{{ route('admin.visits.index') }}"
                   class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat semua kunjungan →
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- System Info -->
    <div class="bg-white rounded-lg border p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sistem</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-server text-blue-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-900">Server Status</p>
                <p class="text-xs text-green-600 mt-1">Online</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-database text-green-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-900">Database</p>
                <p class="text-xs text-green-600 mt-1">Connected</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-clock text-purple-600"></i>
                </div>
                <p class="text-sm font-medium text-gray-900">Last Update</p>
                <p class="text-xs text-gray-600 mt-1">{{ now()->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple fade-in animation
    const cards = document.querySelectorAll('.stat-card, .activity-card, .quick-action-card');

    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease';

        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection
