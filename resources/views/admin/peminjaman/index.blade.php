{{-- resources/views/admin/peminjaman/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Peminjaman Alat')

@section('styles')
<style>
    .status-badge {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold;
    }
    .status-PENDING { @apply bg-yellow-100 text-yellow-800 border border-yellow-200; }
    .status-APPROVED { @apply bg-green-100 text-green-800 border border-green-200; }
    .status-ACTIVE { @apply bg-blue-100 text-blue-800 border border-blue-200; }
    .status-COMPLETED { @apply bg-purple-100 text-purple-800 border border-purple-200; }
    .status-CANCELLED { @apply bg-red-100 text-red-800 border border-red-200; }

    .borrower-usk { @apply bg-blue-100 text-blue-800 border border-blue-200; }
    .borrower-external { @apply bg-purple-100 text-purple-800 border border-purple-200; }

    .priority-high { @apply bg-red-50 border-l-4 border-red-500; }
    .priority-medium { @apply bg-yellow-50 border-l-4 border-yellow-500; }
    .priority-low { @apply bg-gray-50; }

    .stats-card {
        transition: all 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg text-white p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Kelola Peminjaman Alat</h1>
                    <p class="text-blue-100">Pantau dan kelola semua peminjaman alat laboratorium</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ $statistics['total'] }}</div>
                <div class="text-sm text-blue-100">Total Peminjaman</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['pending'] }}</div>
                    <div class="text-sm text-gray-600">Menunggu</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['approved'] }}</div>
                    <div class="text-sm text-gray-600">Disetujui</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-hand-holding text-blue-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['active'] }}</div>
                    <div class="text-sm text-gray-600">Dipinjam</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-check-circle text-purple-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['completed'] }}</div>
                    <div class="text-sm text-gray-600">Selesai</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['overdue'] }}</div>
                    <div class="text-sm text-gray-600">Terlambat</div>
                </div>
            </div>
        </div>

        <div class="stats-card bg-white rounded-lg shadow-md p-4 border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chart-line text-gray-600"></i>
                </div>
                <div>
                    <div class="text-2xl font-bold text-gray-900">{{ $statistics['total'] }}</div>
                    <div class="text-sm text-gray-600">Total</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Peminjaman</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nama, HP, atau tujuan..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Menunggu</option>
                        <option value="APPROVED" {{ request('status') === 'APPROVED' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ACTIVE" {{ request('status') === 'ACTIVE' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="COMPLETED" {{ request('status') === 'COMPLETED' ? 'selected' : '' }}>Selesai</option>
                        <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Borrower Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Peminjam</label>
                    <select name="borrower_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Tipe</option>
                        <option value="1" {{ request('borrower_type') === '1' ? 'selected' : '' }}>Mahasiswa USK</option>
                        <option value="0" {{ request('borrower_type') === '0' ? 'selected' : '' }}>Eksternal</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pinjam</label>
                    <div class="flex space-x-2">
                        <input type="date"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <input type="date"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </a>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.peminjaman.export', request()->query()) }}"
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions" class="hidden bg-blue-50 rounded-lg p-4 border border-blue-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <span class="text-blue-800 font-medium mr-4">
                    <span id="selected-count">0</span> peminjaman dipilih
                </span>
            </div>
            <div class="flex space-x-2">
                <select id="bulk-status" class="px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Status</option>
                    <option value="APPROVED">Setujui</option>
                    <option value="ACTIVE">Tandai Diambil</option>
                    <option value="CANCELLED">Batalkan</option>
                </select>
                <button onclick="applyBulkAction()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Terapkan
                </button>
                <button onclick="clearSelection()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Peminjaman List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Peminjaman</h3>
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $peminjamans->firstItem() ?? 0 }} - {{ $peminjamans->lastItem() ?? 0 }} dari {{ $peminjamans->total() }} data
                </div>
            </div>
        </div>

        @if($peminjamans->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left">
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Alat</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                   @foreach($peminjamans as $peminjaman)
                    @php
                        // Determine priority based on peminjaman status and dates, NOT equipment condition
                        $priorityClass = 'priority-low';

                        if ($peminjaman->is_overdue && $peminjaman->status === 'ACTIVE') {
                            $priorityClass = 'priority-high';
                        } elseif ($peminjaman->status === 'ACTIVE' && $peminjaman->days_until_return <= 2) {
                            $priorityClass = 'priority-medium';
                        } elseif ($peminjaman->status === 'PENDING') {
                            $priorityClass = 'priority-medium';
                        }
                    @endphp
                    <tr class="hover:bg-gray-50 {{ $priorityClass }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox"
                                   class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   value="{{ $peminjaman->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $peminjaman->namaPeminjam }}</div>
                                    <div class="text-sm text-gray-500">{{ $peminjaman->noHp }}</div>
                                    <div class="mt-1">
                                        <span class="status-badge {{ $peminjaman->is_mahasiswa_usk ? 'borrower-usk' : 'borrower-external' }}">
                                            {{ $peminjaman->borrower_type }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <div><i class="fas fa-play text-green-500 mr-1"></i>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</div>
                                <div><i class="fas fa-stop text-red-500 mr-1"></i>{{ $peminjaman->tanggal_pengembalian->format('d M Y') }}</div>
                            </div>
                            @if($peminjaman->is_overdue)
                                <div class="text-xs text-red-600 font-medium mt-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat {{ abs($peminjaman->days_until_return) }} hari
                                </div>
                            @elseif($peminjaman->status === 'ACTIVE' && $peminjaman->days_until_return <= 2)
                                <div class="text-xs text-yellow-600 font-medium mt-1">
                                    <i class="fas fa-clock mr-1"></i>Jatuh tempo {{ $peminjaman->days_until_return }} hari
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                <div class="font-medium">{{ $peminjaman->total_types }} jenis, {{ $peminjaman->total_quantity }} unit</div>
                                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">
                                    {{ $peminjaman->getEquipmentSummaryText() }}
                                </div>

                                <!-- Equipment Availability Status (only for PENDING/APPROVED status) -->
                                @if(in_array($peminjaman->status, ['PENDING', 'APPROVED']))
                                    @php
                                        $unavailableItems = [];
                                        $partiallyAvailableItems = [];

                                        foreach($peminjaman->items as $item) {
                                            $alat = $item->alat;
                                            $needed = $item->jumlah;
                                            $available = $alat->jumlah_tersedia;

                                            if ($available == 0) {
                                                $unavailableItems[] = $alat->nama;
                                            } elseif ($available < $needed) {
                                                $partiallyAvailableItems[] = "{$alat->nama} ({$available}/{$needed})";
                                            }
                                        }
                                    @endphp

                                    @if(!empty($unavailableItems))
                                        <div class="text-xs text-red-600 mt-1">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Tidak tersedia: {{ implode(', ', array_slice($unavailableItems, 0, 2)) }}
                                            @if(count($unavailableItems) > 2)
                                                +{{ count($unavailableItems) - 2 }} lainnya
                                            @endif
                                        </div>
                                    @elseif(!empty($partiallyAvailableItems))
                                        <div class="text-xs text-yellow-600 mt-1">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Terbatas: {{ implode(', ', array_slice($partiallyAvailableItems, 0, 2)) }}
                                            @if(count($partiallyAvailableItems) > 2)
                                                +{{ count($partiallyAvailableItems) - 2 }} lainnya
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-xs text-green-600 mt-1">
                                            <i class="fas fa-check-circle mr-1"></i>Semua alat tersedia
                                        </div>
                                    @endif
                                @endif

                                @if($peminjaman->tujuanPeminjaman)
                                <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">
                                    <i class="fas fa-info-circle mr-1"></i>{{ $peminjaman->tujuanPeminjaman }}
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge status-{{ $peminjaman->status }}">
                                <i class="fas fa-{{ $peminjaman->getStatusIcon() }} mr-1"></i>
                                {{ $peminjaman->status_name }}
                            </span>
                        </td>
                            {{-- isi aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.peminjaman.show', $peminjaman) }}"
                                    class="text-blue-600 hover:text-blue-900 transition">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($peminjaman->canBeApproved())
                                    <button onclick="updateStatus('{{ $peminjaman->id }}', 'APPROVED')"
                                            class="text-green-600 hover:text-green-900 transition"
                                            title="Setujui">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif

                                    @if($peminjaman->status === 'APPROVED')
                                    <button onclick="updateStatus('{{ $peminjaman->id }}', 'ACTIVE')"
                                            class="text-blue-600 hover:text-blue-900 transition"
                                            title="Tandai Diambil">
                                        <i class="fas fa-hand-holding"></i>
                                    </button>
                                    @endif

                                    @if($peminjaman->canBeCompleted())
                                    <button onclick="showCompleteModal('{{ $peminjaman->id }}')"
                                            class="text-purple-600 hover:text-purple-900 transition"
                                            title="Selesaikan">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                    @endif

                                    @if($peminjaman->canBeCancelled())
                                    <button onclick="updateStatus('{{ $peminjaman->id }}', 'CANCELLED')"
                                            class="text-red-600 hover:text-red-900 transition"
                                            title="Batalkan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif

                                    @if(in_array($peminjaman->status, ['COMPLETED', 'CANCELLED']))
                                    <button onclick="deletePeminjaman('{{ $peminjaman->id }}')"
                                            class="text-red-600 hover:text-red-900 transition"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                            <script>
                            window.peminjamanItemsMap = window.peminjamanItemsMap || {};
                            window.peminjamanItemsMap['{{ $peminjaman->id }}'] =
                            {!! $peminjaman->items
                                    ->map(function($it){
                                        return [
                                            'alat_id'          => $it->alat_id,
                                            'nama'             => $it->alat->nama,
                                            'kode'             => $it->alat->kode,
                                            'nama_kategori'    => $it->alat->nama_kategori,
                                            'deskripsi'        => $it->alat->deskripsi,
                                            'jumlah'           => $it->jumlah,
                                            'stok'             => $it->alat->stok,
                                            'jumlah_tersedia'  => $it->alat->jumlah_tersedia,
                                            'image_url'        => $it->alat->image_asset,
                                            'category_icon'    => method_exists($it->alat, 'getCategoryIcon') ? $it->alat->getCategoryIcon() : 'tools',
                                        ];
                                    })
                                    ->values()
                                    ->toJson(JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)
                            !!};
                            </script>

                        </tr>

                        @endforeach
                    </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $peminjamans->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exchange-alt text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data peminjaman</h3>
            <p class="text-gray-600">Data peminjaman akan muncul di sini setelah ada yang mengajukan.</p>
        </div>
        @endif
    </div>
</div>

<!-- Complete Modal (verifikasi kondisi per alat) -->
<div id="completeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeCompleteModal()"></div>
    <div class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Selesaikan Peminjaman</h3>
        <button onclick="closeCompleteModal()" class="text-gray-400 hover:text-gray-600">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <p class="text-gray-600 mb-6">Tentukan kondisi dan jumlah setiap alat saat dikembalikan:</p>

      <form id="completeForm" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="COMPLETED">

        <!-- Diisi dinamis oleh JS -->
        <div id="conditionsContainer" class="space-y-6 mb-6"></div>

        <div class="flex justify-end space-x-3">
          <button type="button" onclick="closeCompleteModal()"
                  class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
            Batal
          </button>
          <button type="submit" id="completeSubmitBtn"
                  class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
            <i class="fas fa-check mr-2"></i>Selesaikan Peminjaman
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
let selectedRows = new Set();

// Select All functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.row-checkbox');
    const isChecked = this.checked;

    checkboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
        if (isChecked) {
            selectedRows.add(checkbox.value);
        } else {
            selectedRows.delete(checkbox.value);
        }
    });

    updateBulkActions();
});

// Individual checkbox handling
document.querySelectorAll('.row-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            selectedRows.add(this.value);
        } else {
            selectedRows.delete(this.value);
        }
        updateBulkActions();
    });
});

function updateBulkActions() {
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');

    if (selectedRows.size > 0) {
        bulkActions.classList.remove('hidden');
        selectedCount.textContent = selectedRows.size;
    } else {
        bulkActions.classList.add('hidden');
    }
}

function clearSelection() {
    selectedRows.clear();
    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('select-all').checked = false;
    updateBulkActions();
}

function applyBulkAction() {
    const status = document.getElementById('bulk-status').value;
    if (!status) {
        alert('Pilih status terlebih dahulu');
        return;
    }

    if (selectedRows.size === 0) {
        alert('Pilih peminjaman terlebih dahulu');
        return;
    }

    if (confirm(`Apakah Anda yakin ingin mengubah status ${selectedRows.size} peminjaman?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.peminjaman.bulk-update") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'bulk_status';
        statusInput.value = status;
        form.appendChild(statusInput);

        selectedRows.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'peminjaman_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
}

function updateStatus(peminjamanId, status) {
    let message = '';
    switch(status) {
        case 'APPROVED':
            message = 'Apakah Anda yakin ingin menyetujui peminjaman ini?';
            break;
        case 'ACTIVE':
            message = 'Apakah Anda yakin ingin menandai peminjaman ini sebagai sudah diambil?';
            break;
        case 'CANCELLED':
            message = 'Apakah Anda yakin ingin membatalkan peminjaman ini?';
            break;
        default:
            message = 'Apakah Anda yakin ingin mengubah status peminjaman ini?';
    }

    if (confirm(message)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/peminjaman/${peminjamanId}/status`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);

        document.body.appendChild(form);
        form.submit();
    }
}



function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function deletePeminjaman(peminjamanId) {
    if (confirm('Apakah Anda yakin ingin menghapus data peminjaman ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/peminjaman/${peminjamanId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-opacity-75')) {
        closeCompleteModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCompleteModal();
    }
});

/** ===============================
 *  Modal verifikasi kondisi (Index)
 *  =============================== */
(function(){
  let activeLoanId = null;

  // BUKA modal + render form kondisi per alat
  function showCompleteModal(peminjamanId) {
    activeLoanId = peminjamanId;
    const form = document.getElementById('completeForm');
    const container = document.getElementById('conditionsContainer');
    const btn = document.getElementById('completeSubmitBtn');
    const modal = document.getElementById('completeModal');

    if (!form || !container || !btn || !modal) return;

    // set action endpoint (konsisten dengan halaman show)
    form.action = `/admin/peminjaman/${peminjamanId}/status`;

    // render kartu item
    const items = (window.peminjamanItemsMap && window.peminjamanItemsMap[peminjamanId]) || [];
    container.innerHTML = items.map(renderItemCard).join('');

    // tampilkan modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // validasi awal
    validateAllTotals();
  }

  // TUTUP modal
  function closeCompleteModal() {
    const modal = document.getElementById('completeModal');
    if (modal) {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }
  }

  // Template kartu satu alat
  function renderItemCard(item) {
    const img = item.image_url
      ? `<img src="${item.image_url}" alt="${escapeHtml(item.nama)}" class="w-full h-full object-cover">`
      : `<div class="w-full h-full flex items-center justify-center">
           <i class="fas fa-${item.category_icon || 'tools'} text-gray-400 text-xl"></i>
         </div>`;

    return `
    <div class="border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-4">
          <div class="w-16 h-16 bg-white rounded-lg overflow-hidden border">${img}</div>
          <div>
            <h4 class="font-semibold text-gray-900 text-lg">${escapeHtml(item.nama)}</h4>
            <p class="text-sm text-gray-600">Kode: ${escapeHtml(item.kode || '-')}</p>
            <p class="text-sm font-medium text-blue-600">Total dipinjam: ${item.jumlah} unit</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg p-4 border">
        <h5 class="font-medium text-gray-800 mb-3">Kondisi Pengembalian:</h5>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- BAIK -->
          <div class="border-2 border-green-200 rounded-lg p-4 bg-green-50">
            <div class="flex items-center mb-3">
              <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
              <div>
                <div class="font-medium text-green-700">Kondisi Baik</div>
                <div class="text-xs text-gray-600">Dapat digunakan kembali</div>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <label class="text-sm font-medium text-gray-700">Jumlah:</label>
              <input type="number"
                     name="item_conditions[${item.alat_id}][baik]"
                     min="0" max="${item.jumlah}" value="${item.jumlah}"
                     class="w-20 px-3 py-1 border border-green-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                     oninput="updateConditionTotals(${item.alat_id}, ${item.jumlah})">
              <span class="text-sm text-gray-500">unit</span>
            </div>
          </div>

          <!-- RUSAK -->
          <div class="border-2 border-red-200 rounded-lg p-4 bg-red-50">
            <div class="flex items-center mb-3">
              <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
              <div>
                <div class="font-medium text-red-700">Kondisi Rusak</div>
                <div class="text-xs text-gray-600">Perlu perbaikan/penggantian</div>
              </div>
            </div>
            <div class="flex items-center space-x-2">
              <label class="text-sm font-medium text-gray-700">Jumlah:</label>
              <input type="number"
                     name="item_conditions[${item.alat_id}][rusak]"
                     min="0" max="${item.jumlah}" value="0"
                     class="w-20 px-3 py-1 border border-red-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
                     oninput="updateConditionTotals(${item.alat_id}, ${item.jumlah})">
              <span class="text-sm text-gray-500">unit</span>
            </div>
          </div>
        </div>

        <div class="mt-3 p-2 bg-gray-100 rounded-md">
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Total unit yang dikembalikan:</span>
            <span id="total-${item.alat_id}" class="font-medium text-green-600">${item.jumlah}/${item.jumlah}</span>
          </div>
          <div id="error-${item.alat_id}" class="text-red-600 text-xs mt-1 hidden">
            Total harus sama dengan ${item.jumlah} unit
          </div>
        </div>
      </div>
    </div>`;
  }

  // ===== Validasi per item & agregat =====
  function updateConditionTotals(alatId, maxTotal) {
    const baikInput = document.querySelector(`input[name="item_conditions[${alatId}][baik]"]`);
    const rusakInput = document.querySelector(`input[name="item_conditions[${alatId}][rusak]"]`);
    const totalDisplay = document.getElementById(`total-${alatId}`);
    const errorDisplay = document.getElementById(`error-${alatId}`);
    if (!baikInput || !rusakInput || !totalDisplay || !errorDisplay) return;

    const baik = parseInt(baikInput.value) || 0;
    const rusak = parseInt(rusakInput.value) || 0;
    const total = baik + rusak;

    totalDisplay.textContent = `${total}/${maxTotal}`;
    totalDisplay.className = total === maxTotal ? 'font-medium text-green-600' : 'font-medium text-red-600';

    if (total !== maxTotal) {
      errorDisplay.classList.remove('hidden');
      errorDisplay.textContent = total > maxTotal
        ? `Total tidak boleh melebihi ${maxTotal} unit`
        : `Total harus sama dengan ${maxTotal} unit`;
    } else {
      errorDisplay.classList.add('hidden');
    }

    validateAllTotals();
  }

  function validateAllTotals() {
    const btn = document.getElementById('completeSubmitBtn');
    if (!btn || !activeLoanId) return;

    const items = (window.peminjamanItemsMap && window.peminjamanItemsMap[activeLoanId]) || [];
    let ok = true;
    for (const item of items) {
      const baik = parseInt(document.querySelector(`input[name="item_conditions[${item.alat_id}][baik]"]`)?.value) || 0;
      const rusak = parseInt(document.querySelector(`input[name="item_conditions[${item.alat_id}][rusak]"]`)?.value) || 0;
      if (baik + rusak !== item.jumlah) { ok = false; break; }
    }

    btn.disabled = !ok;
    btn.className = ok
      ? 'px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center'
      : 'px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center';
  }

  // ===== Util kecil =====
  function escapeHtml(s){ return (s||'').toString()
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
    .replace(/"/g,'&quot;').replace(/'/g,'&#039;'); }

  // ===== Expose ke global (dipakai onclick tombol) =====
  window.showCompleteModal = showCompleteModal;
  window.closeCompleteModal = closeCompleteModal;
  window.updateConditionTotals = updateConditionTotals;
  window.validateAllTotals = validateAllTotals;

  // Tutup modal via backdrop/Escape (selaras dengan pola sebelumnya)
  document.addEventListener('click', e => {
    if (e.target.classList.contains('bg-opacity-75')) closeCompleteModal();
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeCompleteModal();
  });

  // Cegah submit jika invalid
  document.addEventListener('submit', function(e){
    if (e.target && e.target.id === 'completeForm') {
      const btn = document.getElementById('completeSubmitBtn');
      if (btn && btn.disabled) {
        e.preventDefault();
        alert('Mohon periksa kembali total kondisi setiap alat');
      }
    }
  });
})();
</script>
@endsection
