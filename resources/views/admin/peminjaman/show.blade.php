{{-- FILE LENGKAP: resources/views/admin/peminjaman/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Detail Peminjaman - ' . $peminjaman->namaPeminjam)

@section('styles')
<style>
    .detail-card {
        transition: all 0.3s ease;
    }
    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .status-badge {
        @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold;
    }
    .status-PENDING { @apply bg-yellow-100 text-yellow-800 border border-yellow-200; }
    .status-APPROVED { @apply bg-green-100 text-green-800 border border-green-200; }
    .status-ACTIVE { @apply bg-blue-100 text-blue-800 border border-blue-200; }
    .status-COMPLETED { @apply bg-purple-100 text-purple-800 border border-purple-200; }
    .status-CANCELLED { @apply bg-red-100 text-red-800 border border-red-200; }

    .priority-high { @apply bg-red-50 border-l-4 border-red-500; }
    .priority-medium { @apply bg-yellow-50 border-l-4 border-yellow-500; }
    .priority-low { @apply bg-gray-50; }
</style>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg text-white p-6">
        <div class="flex justify-between items-start">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center mr-6">
                    <i class="fas fa-{{ $peminjaman->getStatusIcon() }} text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $peminjaman->namaPeminjam }}</h1>
                    <div class="flex items-center space-x-4 text-blue-100">
                        <span><i class="fas fa-phone mr-2"></i>{{ $peminjaman->noHp }}</span>
                        <span><i class="fas fa-user-tag mr-2"></i>{{ $peminjaman->borrower_type }}</span>
                        <span><i class="fas fa-calendar mr-2"></i>{{ $peminjaman->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <span class="status-badge status-{{ $peminjaman->status }} text-sm">
                    <i class="fas fa-{{ $peminjaman->getStatusIcon() }} mr-1"></i>
                    {{ $peminjaman->status_name }}
                </span>
                @if($peminjaman->is_overdue)
                    <div class="text-red-200 text-sm mt-1">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat {{ abs($peminjaman->days_until_return) }} hari
                    </div>
                @elseif($peminjaman->status === 'ACTIVE' && $peminjaman->days_until_return <= 2)
                    <div class="text-yellow-200 text-sm mt-1">
                        <i class="fas fa-clock mr-1"></i>Jatuh tempo {{ $peminjaman->days_until_return }} hari lagi
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Equipment List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">
                        <i class="fas fa-tools mr-2 text-blue-600"></i>Alat yang Dipinjam
                    </h3>
                    <div class="text-sm text-gray-600">
                        Total: {{ $peminjaman->total_types }} jenis alat, {{ $peminjaman->total_quantity }} unit
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach($peminjaman->items as $item)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-4">
                            <!-- Equipment Image -->
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden">
                                    @if($item->alat->image_url)
                                        <img src="{{ $item->alat->image_asset }}"
                                             alt="{{ $item->alat->nama }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-{{ $item->alat->getCategoryIcon() }} text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Equipment Details -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $item->alat->nama }}</h4>
                                        <p class="text-sm text-gray-600">Kode: {{ $item->alat->kode }}</p>
                                        <p class="text-sm text-gray-600">Kategori: {{ $item->alat->nama_kategori }}</p>
                                        <p class="text-sm text-gray-500 mt-2 max-w-md">{{ $item->alat->deskripsi }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-blue-600">{{ $item->jumlah }}</div>
                                        <div class="text-sm text-gray-500">unit dipinjam</div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            Tersedia: {{ $item->alat->jumlah_tersedia }}/{{ $item->alat->stok }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Loan Purpose -->
            @if($peminjaman->tujuanPeminjaman)
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-clipboard-list mr-2 text-blue-600"></i>Tujuan Peminjaman
                </h3>
                <div class="prose prose-sm max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $peminjaman->tujuanPeminjaman }}</p>
                </div>
            </div>
            @endif

            <!-- Return Conditions (if completed) -->
            @if($peminjaman->status === 'COMPLETED' && $peminjaman->kondisi_pengembalian)
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-clipboard-check mr-2 text-green-600"></i>Kondisi Pengembalian
                </h3>
                @php
                    $conditions = json_decode($peminjaman->kondisi_pengembalian, true) ?? [];
                @endphp
                <div class="space-y-4">
                    @foreach($peminjaman->items as $item)
                        @php
                            $itemConditions = $conditions[$item->alat_id] ?? [];
                            $baikQty = $itemConditions['baik'] ?? $item->jumlah;
                            $rusakQty = $itemConditions['rusak'] ?? 0;
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900">{{ $item->alat->nama }}</h4>
                                <span class="text-sm text-gray-500">Total: {{ $item->jumlah }} unit</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                @if($baikQty > 0)
                                <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                                    <div>
                                        <div class="font-medium text-green-700">{{ $baikQty }} unit</div>
                                        <div class="text-xs text-gray-600">Kondisi Baik</div>
                                    </div>
                                </div>
                                @endif

                                @if($rusakQty > 0)
                                <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                                    <div>
                                        <div class="font-medium text-red-700">{{ $rusakQty }} unit</div>
                                        <div class="text-xs text-gray-600">Kondisi Rusak</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- WhatsApp Communication Section -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fab fa-whatsapp text-green-600 mr-3"></i>
                    Komunikasi WhatsApp
                </h3>

                <div class="space-y-4">
                    <!-- Send Update to Borrower -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Kirim Update ke Peminjam</h4>
                        <p class="text-sm text-gray-600 mb-3">Kirim update status peminjaman langsung ke WhatsApp peminjam</p>

                        <div class="flex flex-wrap gap-2">
                            @if($peminjaman->status === 'PENDING')
                            <button onclick="sendStatusUpdate('approved')"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Kirim Persetujuan
                            </button>
                            <button onclick="sendStatusUpdate('rejected')"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Kirim Penolakan
                            </button>
                            @endif

                            @if($peminjaman->status === 'APPROVED')
                            <button onclick="sendStatusUpdate('pickup')"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Siap Diambil
                            </button>
                            @endif

                            @if($peminjaman->status === 'ACTIVE')
                            <button onclick="sendStatusUpdate('reminder')"
                                    class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors duration-200 flex items-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Kirim Pengingat
                            </button>
                            @endif

                            @if($peminjaman->status === 'COMPLETED')
                            <button onclick="sendStatusUpdate('completed')"
                                    class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex items-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Terima Kasih
                            </button>
                            @endif

                            <button onclick="sendCustomMessage()"
                                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Pesan Custom
                            </button>
                        </div>
                    </div>

                    <!-- Tracking Link -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Link Tracking</h4>
                        <p class="text-sm text-gray-600 mb-2">Link untuk peminjam memantau status peminjaman:</p>
                        <div class="flex items-center space-x-2">
                            <input type="text"
                                   value="{{ route('equipment.track', $peminjaman->id) }}"
                                   readonly
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm">
                            <button onclick="copyToClipboard('{{ route('equipment.track', $peminjaman->id) }}')"
                                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center">
                                <i class="fas fa-copy mr-2"></i>
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Jenis Alat:</span>
                        <span class="font-semibold text-gray-900">{{ $peminjaman->total_types }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Unit:</span>
                        <span class="font-semibold text-gray-900">{{ $peminjaman->total_quantity }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Durasi:</span>
                        <span class="font-semibold text-gray-900">
                            {{ $peminjaman->tanggal_pinjam->diffInDays($peminjaman->tanggal_pengembalian) + 1 }} hari
                        </span>
                    </div>
                    @if($peminjaman->status === 'ACTIVE')
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Sisa Waktu:</span>
                        <span class="font-semibold {{ $peminjaman->is_overdue ? 'text-red-600' : ($peminjaman->days_until_return <= 2 ? 'text-yellow-600' : 'text-green-600') }}">
                            @if($peminjaman->is_overdue)
                                Terlambat {{ abs($peminjaman->days_until_return) }} hari
                            @else
                                {{ $peminjaman->days_until_return }} hari lagi
                            @endif
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Loan Details -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Peminjaman</h3>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID:</span>
                        <span class="font-medium text-sm">{{ substr($peminjaman->id, 0, 8) }}...</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Tipe Peminjam:</span>
                        <span class="font-medium">{{ $peminjaman->borrower_type }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Pengajuan:</span>
                        <span class="font-medium">{{ $peminjaman->created_at->format('d M Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Pinjam:</span>
                        <span class="font-medium">{{ $peminjaman->tanggal_pinjam->format('d M Y H:i') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Kembali:</span>
                        <span class="font-medium">{{ $peminjaman->tanggal_pengembalian->format('d M Y H:i') }}</span>
                    </div>

                    @if($peminjaman->updated_at != $peminjaman->created_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Terakhir Update:</span>
                        <span class="font-medium">{{ $peminjaman->updated_at->format('d M Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>

                <div class="space-y-3">
                    @if($peminjaman->canBeApproved())
                    <button onclick="updateStatus('{{ $peminjaman->id }}', 'APPROVED')"
                            class="w-full px-4 py-3 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-check mr-2"></i>Setujui Peminjaman
                    </button>
                    @endif

                    @if($peminjaman->status === 'APPROVED')
                    <button onclick="updateStatus('{{ $peminjaman->id }}', 'ACTIVE')"
                            class="w-full px-4 py-3 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-hand-holding mr-2"></i>Tandai Sudah Diambil
                    </button>
                    @endif

                    @if($peminjaman->canBeCompleted())
                    <button onclick="showCompleteModal('{{ $peminjaman->id }}')"
                            class="w-full px-4 py-3 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-check-double mr-2"></i>Selesaikan Peminjaman
                    </button>
                    @endif

                    @if($peminjaman->canBeCancelled())
                    <button onclick="showCancelModal('{{ $peminjaman->id }}')"
                            class="w-full px-4 py-3 bg-red-600 text-white text-center rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-times mr-2"></i>Batalkan Peminjaman
                    </button>
                    @endif

                    <a href="{{ route('admin.peminjaman.index') }}"
                       class="w-full px-4 py-3 bg-gray-500 text-white text-center rounded-lg hover:bg-gray-600 transition block">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                    </a>

                    @if(in_array($peminjaman->status, ['COMPLETED', 'CANCELLED']))
                    <button onclick="deletePeminjaman('{{ $peminjaman->id }}')"
                            class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i>Hapus Data
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Modal dengan Partial Conditions -->
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

            <form id="completeForm" method="POST" action="{{ route('admin.peminjaman.update-status', $peminjaman) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="COMPLETED">

                <div class="space-y-6 mb-6">
                    @foreach($peminjaman->items as $item)
                    <div class="border-2 border-gray-200 rounded-lg p-6 bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <!-- Equipment Image -->
                                <div class="w-16 h-16 bg-white rounded-lg overflow-hidden border">
                                    @if($item->alat->image_url)
                                        <img src="{{ $item->alat->image_asset }}"
                                             alt="{{ $item->alat->nama }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-{{ $item->alat->getCategoryIcon() }} text-gray-400 text-xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <div>
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $item->alat->nama }}</h4>
                                    <p class="text-sm text-gray-600">Kode: {{ $item->alat->kode }}</p>
                                    <p class="text-sm font-medium text-blue-600">Total dipinjam: {{ $item->jumlah }} unit</p>
                                </div>
                            </div>
                        </div>

                        <!-- Condition Input Section -->
                        <div class="bg-white rounded-lg p-4 border">
                            <h5 class="font-medium text-gray-800 mb-3">Kondisi Pengembalian:</h5>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- BAIK Section -->
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
                                               name="item_conditions[{{ $item->alat_id }}][baik]"
                                               min="0"
                                               max="{{ $item->jumlah }}"
                                               value="{{ $item->jumlah }}"
                                               class="w-20 px-3 py-1 border border-green-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                               onchange="updateConditionTotals('{{ $item->alat_id }}', {{ $item->jumlah }})">
                                        <span class="text-sm text-gray-500">unit</span>
                                    </div>
                                </div>

                                <!-- RUSAK Section -->
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
                                               name="item_conditions[{{ $item->alat_id }}][rusak]"
                                               min="0"
                                               max="{{ $item->jumlah }}"
                                               value="0"
                                               class="w-20 px-3 py-1 border border-red-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                               onchange="updateConditionTotals('{{ $item->alat_id }}', {{ $item->jumlah }})">
                                        <span class="text-sm text-gray-500">unit</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Validation Display -->
                            <div class="mt-3 p-2 bg-gray-100 rounded-md">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Total unit yang dikembalikan:</span>
                                    <span id="total-{{ $item->alat_id }}" class="font-medium">{{ $item->jumlah }}/{{ $item->jumlah }}</span>
                                </div>
                                <div id="error-{{ $item->alat_id }}" class="text-red-600 text-xs mt-1 hidden">
                                    Total harus sama dengan {{ $item->jumlah }} unit
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCompleteModal()"
                            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                        <i class="fas fa-check mr-2"></i>Selesaikan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div id="cancelModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeCancelModal()"></div>
        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Batalkan Peminjaman</h3>
                <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <p class="text-gray-600 mb-4">Apakah Anda yakin ingin membatalkan peminjaman ini?</p>

            <form id="cancelForm" method="POST" action="{{ route('admin.peminjaman.update-status', $peminjaman) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="CANCELLED">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan (Opsional)</label>
                    <textarea name="cancel_reason" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Masukkan alasan pembatalan..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCancelModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-times mr-2"></i>Batalkan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Message Modal -->
<div id="customMessageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-medium">Kirim Pesan Custom</h3>
            <button onclick="closeCustomMessage()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kepada:</label>
                <div class="p-2 bg-gray-50 rounded-lg text-sm">
                    <strong>{{ $peminjaman->namaPeminjam }}</strong> - {{ $peminjaman->noHp }}
                </div>
            </div>
            <div class="mb-4">
                <label for="customMessageText" class="block text-sm font-medium text-gray-700 mb-2">Pesan:</label>
                <textarea id="customMessageText"
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Tulis pesan untuk peminjam..."></textarea>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" id="includeTrackingLink" checked class="mr-2">
                    <span class="text-sm text-gray-700">Sertakan link tracking</span>
                </label>
            </div>
            <div class="flex justify-end space-x-2">
                <button onclick="closeCustomMessage()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg">
                    Batal
                </button>
                <button onclick="sendMessage()"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                    <i class="fab fa-whatsapp mr-2"></i>
                    Kirim
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- ============================================ --}}
{{-- SCRIPTS SECTION - REPLACE EXISTING SCRIPTS --}}
{{-- ============================================ --}}
@section('scripts')
<script>
// Global variables
window.peminjamanFunctions = window.peminjamanFunctions || {};

// Condition totals validation
function updateConditionTotals(alatId, maxTotal) {
    const baikInput = document.querySelector(`input[name="item_conditions[${alatId}][baik]"]`);
    const rusakInput = document.querySelector(`input[name="item_conditions[${alatId}][rusak]"]`);
    const totalDisplay = document.getElementById(`total-${alatId}`);
    const errorDisplay = document.getElementById(`error-${alatId}`);

    if (!baikInput || !rusakInput || !totalDisplay || !errorDisplay) {
        console.error('Required elements not found for alat ID:', alatId);
        return;
    }

    const baikValue = parseInt(baikInput.value) || 0;
    const rusakValue = parseInt(rusakInput.value) || 0;
    const total = baikValue + rusakValue;

    // Update display
    totalDisplay.textContent = `${total}/${maxTotal}`;
    totalDisplay.className = total === maxTotal ? 'font-medium text-green-600' : 'font-medium text-red-600';

    // Show/hide error
    if (total !== maxTotal) {
        errorDisplay.classList.remove('hidden');
        errorDisplay.textContent = total > maxTotal
            ? `Total tidak boleh melebihi ${maxTotal} unit`
            : `Total harus sama dengan ${maxTotal} unit`;
    } else {
        errorDisplay.classList.add('hidden');
    }

    // Validate all items
    validateAllTotals();
}

function validateAllTotals() {
    const submitBtn = document.getElementById('submitBtn');
    if (!submitBtn) return;

    let allValid = true;

    // Check all condition inputs
    @foreach($peminjaman->items as $item)
    try {
        const baikInput{{ $loop->index }} = document.querySelector(`input[name="item_conditions[{{ $item->alat_id }}][baik]"]`);
        const rusakInput{{ $loop->index }} = document.querySelector(`input[name="item_conditions[{{ $item->alat_id }}][rusak]"]`);

        if (baikInput{{ $loop->index }} && rusakInput{{ $loop->index }}) {
            const baik{{ $loop->index }} = parseInt(baikInput{{ $loop->index }}.value) || 0;
            const rusak{{ $loop->index }} = parseInt(rusakInput{{ $loop->index }}.value) || 0;
            const total{{ $loop->index }} = baik{{ $loop->index }} + rusak{{ $loop->index }};

            if (total{{ $loop->index }} !== {{ $item->jumlah }}) {
                allValid = false;
            }
        }
    } catch (error) {
        console.error('Error validating item {{ $loop->index }}:', error);
        allValid = false;
    }
    @endforeach

    // Enable/disable submit button
    submitBtn.disabled = !allValid;
    submitBtn.className = allValid
        ? 'px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center'
        : 'px-6 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed flex items-center';
}

// Status update function - FIXED VERSION
function updateStatus(peminjamanId, status) {
    console.log('updateStatus called with:', { peminjamanId, status });

    if (!peminjamanId || !status) {
        console.error('Missing required parameters');
        showAlert('error', 'Parameter tidak lengkap');
        return;
    }

    let message = '';
    let confirmTitle = '';

    switch(status) {
        case 'APPROVED':
            message = 'Apakah Anda yakin ingin menyetujui peminjaman ini?';
            confirmTitle = 'Setujui Peminjaman';
            break;
        case 'ACTIVE':
            message = 'Apakah Anda yakin ingin menandai peminjaman ini sebagai sudah diambil?';
            confirmTitle = 'Tandai Sudah Diambil';
            break;
        case 'CANCELLED':
            message = 'Apakah Anda yakin ingin membatalkan peminjaman ini?';
            confirmTitle = 'Batalkan Peminjaman';
            break;
        default:
            message = 'Apakah Anda yakin ingin mengubah status peminjaman ini?';
            confirmTitle = 'Ubah Status';
    }

    if (confirm(`${confirmTitle}\n\n${message}`)) {
        try {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/peminjaman/${peminjamanId}/status`;
            form.style.display = 'none';

            // CSRF Token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            // Status
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            form.appendChild(statusInput);

            // Submit form
            document.body.appendChild(form);
            console.log('Submitting form:', form);
            form.submit();

        } catch (error) {
            console.error('Error submitting form:', error);
            showAlert('error', 'Terjadi kesalahan saat memproses permintaan');
        }
    }
}

// Modal functions
function showCompleteModal(peminjamanId) {
    console.log('showCompleteModal called:', peminjamanId);
    const modal = document.getElementById('completeModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Initialize validation
        setTimeout(validateAllTotals, 100);
    } else {
        console.error('Complete modal not found');
    }
}

function closeCompleteModal() {
    const modal = document.getElementById('completeModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

function showCancelModal(peminjamanId) {
    console.log('showCancelModal called:', peminjamanId);
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        console.error('Cancel modal not found');
    }
}

function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

function deletePeminjaman(peminjamanId) {
    console.log('deletePeminjaman called:', peminjamanId);

    if (!peminjamanId) {
        showAlert('error', 'ID peminjaman tidak valid');
        return;
    }

    const confirmMessage = 'Apakah Anda yakin ingin menghapus data peminjaman ini?\n\nTindakan ini tidak dapat dibatalkan.';

    if (confirm(confirmMessage)) {
        try {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/peminjaman/${peminjamanId}`;
            form.style.display = 'none';

            // CSRF Token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // DELETE Method
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();

        } catch (error) {
            console.error('Error deleting peminjaman:', error);
            showAlert('error', 'Terjadi kesalahan saat menghapus data');
        }
    }
}

// WhatsApp Communication Functions
function sendStatusUpdate(type) {
    console.log('sendStatusUpdate called:', type);

    const borrowerPhone = '{{ $peminjaman->noHp }}';
    const borrowerName = '{{ $peminjaman->namaPeminjam }}';
    const trackingUrl = '{{ route("equipment.track", $peminjaman->id) }}';
    const loanDate = '{{ $peminjaman->tanggal_pinjam->format("d M Y") }}';
    const returnDate = '{{ $peminjaman->tanggal_pengembalian->format("d M Y H:i") }}';
    const equipmentList = getEquipmentListText();

    let message = '';

    switch(type) {
        case 'approved':
            message = `Halo ${borrowerName},\n\n✅ *PEMINJAMAN ALAT DISETUJUI*\n\nPeminjaman alat Anda di Laboratorium Fisika Komputasi telah disetujui!\n\n📅 Periode Peminjaman:\n• Mulai: ${loanDate}\n• Berakhir: ${returnDate}\n\n🔧 Alat yang Dipinjam:\n${equipmentList}\n\n📋 *PENTING - Silakan ambil alat dengan membawa:*\n• Kartu identitas (KTM/KTP)\n• Screenshot pesan ini\n• Mengikuti protokol keselamatan lab\n\n⚠️ Harap ambil alat sesuai jadwal yang telah ditentukan.\n\n🔗 Pantau status: ${trackingUrl}\n\nTerima kasih!\nLab Fisika Komputasi`;
            break;

        case 'rejected':
            message = `Halo ${borrowerName},\n\n❌ *PEMINJAMAN TIDAK DAPAT DIPROSES*\n\nMohon maaf, pengajuan peminjaman alat Anda tidak dapat kami proses saat ini.\n\n🔧 Alat yang Diminta:\n${equipmentList}\n\n💡 *Kemungkinan penyebab:*\n• Alat tidak tersedia pada periode tersebut\n• Kapasitas peminjaman penuh\n• Data pengajuan tidak lengkap\n\nSilakan hubungi admin untuk informasi lebih lanjut atau ajukan ulang dengan jadwal yang berbeda.\n\n🔗 Status pengajuan: ${trackingUrl}\n\nTerima kasih atas pengertiannya.\nLab Fisika Komputasi`;
            break;

        case 'pickup':
            message = `Halo ${borrowerName},\n\n📦 *ALAT SIAP DIAMBIL*\n\nAlat laboratorium yang Anda pinjam sudah siap untuk diambil!\n\n🔧 Alat yang Siap Diambil:\n${equipmentList}\n\n📍 *Lokasi:* Lab Fisika Komputasi - Gedung Akademik\n⏰ *Jam Operasional:* 08:00 - 16:00 WIB\n\n📋 *Jangan lupa bawa:*\n• Kartu identitas (KTM/KTP)\n• Screenshot pesan ini\n\n📅 *Batas Pengembalian:* ${returnDate}\n\n🔗 Pantau status: ${trackingUrl}\n\nTerima kasih!\nLab Fisika Komputasi`;
            break;

        case 'reminder':
            const daysLeft = {{ $peminjaman->days_until_return ?? 0 }};
            const urgencyLevel = daysLeft <= 0 ? 'TERLAMBAT' : (daysLeft == 1 ? 'HARI INI' : `H-${daysLeft}`);
            const urgencyIcon = daysLeft <= 0 ? '🚨' : (daysLeft <= 1 ? '⚠️' : '⏰');

            message = `Halo ${borrowerName},\n\n${urgencyIcon} *PENGINGAT PENGEMBALIAN ALAT - ${urgencyLevel}*\n\n`;

            if (daysLeft <= 0) {
                message += `‼️ Peminjaman Anda sudah TERLAMBAT ${Math.abs(daysLeft)} hari!\n\n`;
            } else {
                message += `Batas waktu pengembalian alat tinggal ${daysLeft} hari lagi.\n\n`;
            }

            message += `🔧 Alat yang Harus Dikembalikan:\n${equipmentList}\n\n📅 *Batas Pengembalian:* ${returnDate}\n\n📍 *Segera kembalikan ke:*\nLab Fisika Komputasi - Gedung Akademik\nJam operasional: 08:00 - 16:00 WIB\n\n⚠️ Keterlambatan dapat mempengaruhi peminjaman selanjutnya.\n\n🔗 Status: ${trackingUrl}\n\nTerima kasih atas perhatiannya!\nLab Fisika Komputasi`;
            break;

        case 'completed':
            message = `Halo ${borrowerName},\n\n✅ *PEMINJAMAN SELESAI*\n\nTerima kasih! Peminjaman alat Anda telah selesai:\n\n🔧 Alat yang Dikembalikan:\n${equipmentList}\n\n📊 *Status:* Lengkap dan Selesai\n\n🙏 Terima kasih telah:\n• Menggunakan fasilitas lab dengan baik\n• Mengembalikan alat tepat waktu\n• Menjaga kondisi alat\n\nKami berharap dapat melayani Anda kembali di masa mendatang.\n\n🔗 Riwayat: ${trackingUrl}\n\nSalam,\nLab Fisika Komputasi`;
            break;

        default:
            console.error('Unknown status update type:', type);
            showAlert('error', 'Tipe update tidak dikenal');
            return;
    }

    try {
        // Format phone number and open WhatsApp
        const formattedPhone = borrowerPhone.replace(/^0/, '62');
        const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');

        // Show success message
        showAlert('success', 'WhatsApp terbuka! Pesan siap dikirim.');
    } catch (error) {
        console.error('Error opening WhatsApp:', error);
        showAlert('error', 'Gagal membuka WhatsApp');
    }
}

function sendCustomMessage() {
    const modal = document.getElementById('customMessageModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeCustomMessage() {
    const modal = document.getElementById('customMessageModal');
    if (modal) {
        modal.classList.add('hidden');
        document.getElementById('customMessageText').value = '';
        document.body.style.overflow = 'auto';
    }
}

function sendMessage() {
    const messageInput = document.getElementById('customMessageText');
    if (!messageInput) {
        showAlert('error', 'Input pesan tidak ditemukan');
        return;
    }

    const message = messageInput.value.trim();
    if (!message) {
        showAlert('warning', 'Silakan tulis pesan terlebih dahulu');
        return;
    }

    const borrowerPhone = '{{ $peminjaman->noHp }}';
    const borrowerName = '{{ $peminjaman->namaPeminjam }}';
    const includeTrackingCheckbox = document.getElementById('includeTrackingLink');
    const includeTracking = includeTrackingCheckbox ? includeTrackingCheckbox.checked : true;
    const trackingUrl = '{{ route("equipment.track", $peminjaman->id) }}';

    let fullMessage = `Halo ${borrowerName},\n\n${message}`;

    if (includeTracking) {
        fullMessage += `\n\n🔗 Pantau status peminjaman: ${trackingUrl}`;
    }

    fullMessage += `\n\nSalam,\nAdmin Laboratorium Fisika Komputasi`;

    try {
        const formattedPhone = borrowerPhone.replace(/^0/, '62');
        const whatsappUrl = `https://wa.me/${formattedPhone}?text=${encodeURIComponent(fullMessage)}`;
        window.open(whatsappUrl, '_blank');

        closeCustomMessage();
        showAlert('success', 'WhatsApp terbuka! Pesan custom siap dikirim.');
    } catch (error) {
        console.error('Error sending custom message:', error);
        showAlert('error', 'Gagal mengirim pesan');
    }
}

function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            showAlert('success', 'Link berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Clipboard API failed:', err);
            fallbackCopyToClipboard(text);
        });
    } else {
        fallbackCopyToClipboard(text);
    }
}

function fallbackCopyToClipboard(text) {
    try {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showAlert('success', 'Link berhasil disalin!');
    } catch (error) {
        console.error('Fallback copy failed:', error);
        showAlert('error', 'Gagal menyalin link');
    }
}

function getEquipmentListText() {
    const equipmentItems = [
        @foreach($peminjaman->items as $item)
        '• {{ $item->alat->nama }} ({{ $item->jumlah }} unit)',
        @endforeach
    ];
    return equipmentItems.join('\n');
}

// Alert function
function showAlert(type, message) {
    const alertClass = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'info': 'bg-blue-500',
        'warning': 'bg-yellow-500'
    }[type] || 'bg-gray-500';

    const iconClass = {
        'success': 'fa-check',
        'error': 'fa-exclamation-triangle',
        'info': 'fa-info-circle',
        'warning': 'fa-exclamation-triangle'
    }[type] || 'fa-info';

    const alertElement = document.createElement('div');
    alertElement.className = `fixed top-4 right-4 ${alertClass} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300`;
    alertElement.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${iconClass} mr-2"></i>
            ${message}
        </div>
    `;

    document.body.appendChild(alertElement);

    // Animate in
    setTimeout(() => {
        alertElement.classList.remove('translate-x-full', 'opacity-0');
    }, 100);

    // Animate out and remove
    setTimeout(() => {
        alertElement.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            if (alertElement.parentNode) {
                alertElement.remove();
            }
        }, 300);
    }, 3000);
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing peminjaman functions');

    // Initialize validation if modal exists
    if (document.getElementById('completeModal')) {
        validateAllTotals();
    }

    // Test function availability
    console.log('Functions available:', {
        updateStatus: typeof updateStatus,
        showCompleteModal: typeof showCompleteModal,
        deletePeminjaman: typeof deletePeminjaman
    });
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-opacity-75')) {
        closeCompleteModal();
        closeCancelModal();
        closeCustomMessage();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCompleteModal();
        closeCancelModal();
        closeCustomMessage();
    }
});

// Prevent form submission on invalid totals
document.addEventListener('submit', function(e) {
    if (e.target.id === 'completeForm') {
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn && submitBtn.disabled) {
            e.preventDefault();
            showAlert('warning', 'Mohon periksa kembali total kondisi setiap alat');
            return false;
        }
    }
});

// Export functions to global scope for onclick handlers
window.updateStatus = updateStatus;
window.showCompleteModal = showCompleteModal;
window.closeCompleteModal = closeCompleteModal;
window.showCancelModal = showCancelModal;
window.closeCancelModal = closeCancelModal;
window.deletePeminjaman = deletePeminjaman;
window.sendStatusUpdate = sendStatusUpdate;
window.sendCustomMessage = sendCustomMessage;
window.closeCustomMessage = closeCustomMessage;
window.sendMessage = sendMessage;
window.copyToClipboard = copyToClipboard;
window.updateConditionTotals = updateConditionTotals;
window.validateAllTotals = validateAllTotals;

console.log('All peminjaman functions loaded successfully');
</script>
@endsection
