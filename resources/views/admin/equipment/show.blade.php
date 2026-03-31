{{-- resources/views/admin/equipment/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Alat - ' . $equipment->nama)

@section('styles')
<style>
    .detail-card {
        transition: all 0.3s ease;
    }
    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg text-white p-6">
        <div class="flex justify-between items-start">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center mr-6">
                    <i class="fas fa-{{ $equipment->getCategoryIcon() }} text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $equipment->nama }}</h1>
                    <div class="flex items-center space-x-4 text-blue-100">
                        <span><i class="fas fa-barcode mr-2"></i>{{ $equipment->kode }}</span>
                        <span><i class="fas fa-tag mr-2"></i>{{ $equipment->nama_kategori }}</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                @php
                    $status = $equipment->getStatusForFilter();
                    $statusColor = match($status) {
                        'available' => 'bg-green-500',
                        'borrowed' => 'bg-yellow-500',
                        'maintenance' => 'bg-red-500',
                        'unavailable' => 'bg-gray-500',
                        default => 'bg-gray-500'
                    };
                @endphp
                <div class="flex items-center justify-end mb-2">
                    <span class="status-indicator {{ $statusColor }}"></span>
                    <span class="font-medium">{{ $equipment->getStatusLabel() }}</span>
                </div>
                <div class="text-sm text-blue-100">Status Alat</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Equipment Image -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="aspect-video bg-gray-100 flex items-center justify-center">
                    @if($equipment->image_url)
                        <img src="{{ $equipment->image_asset }}"
                             alt="{{ $equipment->nama }}"
                             class="max-w-full max-h-full object-contain"
                             onerror="this.src='{{ asset('images/equipment/default.jpg') }}'">
                    @else
                        <div class="text-center text-gray-400">
                            <i class="fas fa-image text-6xl mb-4"></i>
                            <p>Tidak ada gambar tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>Deskripsi
                </h3>
                <div class="prose prose-sm max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $equipment->deskripsi }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Stok</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-3"></i>
                            <span class="text-green-800 font-medium">Tersedia</span>
                        </div>
                        <span class="text-2xl font-bold text-green-600">{{ $equipment->jumlah_tersedia }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-hand-holding text-yellow-600 mr-3"></i>
                            <span class="text-yellow-800 font-medium">Dipinjam</span>
                        </div>
                        <span class="text-2xl font-bold text-yellow-600">{{ $equipment->jumlah_dipinjam }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-wrench text-red-600 mr-3"></i>
                            <span class="text-red-800 font-medium">Rusak</span>
                        </div>
                        <span class="text-2xl font-bold text-red-600">{{ $equipment->jumlah_rusak }}</span>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-700">Total Stok</span>
                            <span class="text-xl font-bold text-gray-800">{{ $equipment->stok }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Alat</h3>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode:</span>
                        <span class="font-medium">{{ $equipment->kode }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Kategori:</span>
                        <span class="font-medium">{{ $equipment->nama_kategori }}</span>
                    </div>

                    @if($equipment->harga)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Harga:</span>
                        <span class="font-medium text-blue-600">Rp {{ number_format($equipment->harga, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-600">Ditambahkan:</span>
                        <span class="font-medium">{{ $equipment->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Terakhir Update:</span>
                        <span class="font-medium">{{ $equipment->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>

                <div class="space-y-3">
                    <a href="{{ route('admin.equipment.edit', $equipment) }}"
                       class="w-full px-4 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition block">
                        <i class="fas fa-edit mr-2"></i>Edit Alat
                    </a>

                    <a href="{{ route('admin.equipment.index') }}"
                       class="w-full px-4 py-3 bg-gray-500 text-white text-center rounded-lg hover:bg-gray-600 transition block">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>

                    <button onclick="confirmDelete()"
                            class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i>Hapus Alat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-800">Konfirmasi Penghapusan</h3>
            </div>

            <p class="text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus alat <strong>{{ $equipment->nama }}</strong>?
                Tindakan ini tidak dapat dibatalkan.
            </p>

            @if($equipment->jumlah_dipinjam > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                        <span class="text-yellow-800 font-medium">
                            Peringatan: Alat ini sedang dipinjam ({{ $equipment->jumlah_dipinjam }} unit).
                        </span>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
            <button type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                Batal
            </button>
            <form action="{{ route('admin.equipment.destroy', $equipment) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                        {{ $equipment->jumlah_dipinjam > 0 ? 'disabled' : '' }}>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
