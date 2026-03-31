@extends('layouts.admin')

@section('title', 'Kelola Alat')

@section('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg text-white p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">Kelola Alat Laboratorium</h1>
                <p class="text-blue-100">Manajemen inventaris alat laboratorium fisika komputasi</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ $stats['total_alat'] }}</div>
                <div class="text-blue-100">Total Alat</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Tersedia</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['total_tersedia'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-hand-holding text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Dipinjam</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['total_dipinjam'] }}</p>
                </div>
            </div>
        </div>

        <div class="    bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-wrench text-red-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Rusak</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['total_rusak'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-list text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-700">Kategori</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $kategoris->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions and Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
            <!-- Search and Filters -->
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                <form method="GET" class="flex space-x-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari alat..."
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                    <select name="kategori" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->nama_kategori }}"
                                    {{ request('kategori') == $kategori->nama_kategori ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search"></i>
                    </button>

                    @if(request()->hasAny(['search', 'kategori', 'status']))
                        <a href="{{ route('admin.equipment.index') }}"
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-2">
                <a href="{{ route('admin.equipment.create') }}"
                   class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition shadow-lg font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Alat
                </a>
            </div>
        </div>


    </div>

    <!-- Equipment Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($alats as $alat)
            <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                <div class="relative">
                    <img src="{{ $alat->image_asset }}"
                         alt="{{ $alat->nama }}"
                         class="w-full h-48 object-cover"
                         onerror="this.src='{{ asset('images/equipment/default.jpg') }}'">

                    <!-- Status Badge -->
                    <div class="absolute top-2 right-2">
                        @php
                            $status = $alat->getStatusForFilter();
                            $badgeClass = match($status) {
                                'available' => 'bg-green-500 text-white',
                                'borrowed' => 'bg-yellow-500 text-white',
                                'maintenance' => 'bg-red-500 text-white',
                                'unavailable' => 'bg-gray-500 text-white',
                                default => 'bg-gray-500 text-white'
                            };
                        @endphp
                        <span class="status-badge {{ $badgeClass }}">
                            <i class="fas fa-{{ $alat->getStatusIcon() }} mr-1"></i>
                            {{ $alat->getStatusLabel() }}
                        </span>
                    </div>


                </div>

                <div class="p-4">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-semibold text-gray-800 line-clamp-2 flex-1">{{ $alat->nama }}</h3>
                        <i class="fas fa-{{ $alat->getCategoryIcon() }} text-blue-500 ml-2"></i>
                    </div>

                    <p class="text-sm text-gray-600 mb-2">{{ $alat->kode }}</p>

                    <div class="flex items-center text-xs text-gray-500 mb-3">
                        <i class="fas fa-tag mr-1"></i>
                        {{ $alat->nama_kategori }}
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-xs mb-4">
                        <div class="text-center">
                            <div class="font-semibold text-green-600">{{ $alat->jumlah_tersedia }}</div>
                            <div class="text-gray-500">Tersedia</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-yellow-600">{{ $alat->jumlah_dipinjam }}</div>
                            <div class="text-gray-500">Dipinjam</div>
                        </div>
                        <div class="text-center">
                            <div class="font-semibold text-red-600">{{ $alat->jumlah_rusak }}</div>
                            <div class="text-gray-500">Rusak</div>
                        </div>
                    </div>

                    @if($alat->harga)
                        <div class="text-sm font-semibold text-blue-600 mb-3">
                            Rp {{ number_format($alat->harga, 0, ',', '.') }}
                        </div>
                    @endif

                    <div class="flex space-x-2">
                        <a href="{{ route('admin.equipment.show', $alat) }}"
                           class="flex-1 px-3 py-2 bg-blue-600 text-white text-center text-sm rounded hover:bg-blue-700 transition">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </a>
                        <a href="{{ route('admin.equipment.edit', $alat) }}"
                           class="flex-1 px-3 py-2 bg-yellow-500 text-white text-center text-sm rounded hover:bg-yellow-600 transition">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <i class="fas fa-tools text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada alat ditemukan</h3>
                    <p class="text-gray-500 mb-6">Mulai tambahkan alat laboratorium atau ubah filter pencarian</p>
                    <a href="{{ route('admin.equipment.create') }}"
                       class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Alat Pertama
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($alats->hasPages())
        <div class="flex justify-center">
            {{ $alats->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Bulk selection functionality
    let selectedIds = [];

    $('.equipment-checkbox').change(function() {
        const id = $(this).val();
        const isChecked = $(this).is(':checked');

        if (isChecked) {
            selectedIds.push(id);
        } else {
            selectedIds = selectedIds.filter(selectedId => selectedId !== id);
        }

        updateBulkActions();
    });

    function updateBulkActions() {
        const count = selectedIds.length;
        $('#selected-count').text(count);
        $('#selected-ids').val(selectedIds.join(','));

        if (count > 0) {
            $('#bulk-actions').removeClass('hidden');
        } else {
            $('#bulk-actions').addClass('hidden');
        }
    }

    // Show/hide category selector based on action
    $('#bulk-action').change(function() {
        if ($(this).val() === 'change_category') {
            $('#new-category').removeClass('hidden');
        } else {
            $('#new-category').addClass('hidden');
        }
    });

    // Confirm bulk actions
    $('#bulk-form').submit(function(e) {
        const action = $('#bulk-action').val();
        if (!action) {
            e.preventDefault();
            alert('Pilih aksi terlebih dahulu');
            return;
        }

        const count = selectedIds.length;
        let message = '';

        switch(action) {
            case 'delete':
                message = `Apakah Anda yakin ingin menghapus ${count} alat?`;
                break;
            case 'change_category':
                const newCategory = $('#new-category').val();
                message = `Apakah Anda yakin ingin memindahkan ${count} alat ke kategori ${newCategory}?`;
                break;
        }

        if (!confirm(message)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
