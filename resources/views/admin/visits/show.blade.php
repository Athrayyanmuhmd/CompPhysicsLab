@extends('layouts.admin')

@section('title', 'Detail Kunjungan - ' . $kunjungan->namaPengunjung)

@section('content')
<!-- Header with Gradient -->
<div class="detail-card rounded-2xl p-8 mb-8 relative z-10">
    <div class="relative z-20">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-white/80 mb-6">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition">
                <i class="fas fa-home mr-1"></i> Dashboard
            </a>
            <i class="fas fa-chevron-right mx-2"></i>
            <a href="{{ route('admin.visits.index') }}" class="hover:text-white transition">
                Kelola Kunjungan
            </a>
            <i class="fas fa-chevron-right mx-2"></i>
            <span class="text-white font-medium">Detail Kunjungan</span>
        </nav>

        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center mb-3">
                    <h1 class="text-4xl font-bold text-white mr-4">{{ $kunjungan->namaPengunjung }}</h1>
                    <span class="status-badge px-4 py-2 rounded-full text-sm font-semibold {{ $kunjungan->statusBadgeColor }}">
                        {{ $kunjungan->statusText }}
                    </span>
                </div>
                <p class="text-white/90 text-lg mb-2">{{ $kunjungan->institution }}</p>
                <p class="text-white/80">{{ $kunjungan->tujuan }}</p>
                <div class="flex items-center text-white/80 mt-3">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span class="font-medium">{{ $kunjungan->formattedDateTime }}</span>
                    <span class="mx-3">•</span>
                    <i class="fas fa-users mr-2"></i>
                    <span>{{ $kunjungan->jumlahPengunjung }} peserta</span>
                </div>
                <div class="flex items-center text-white/80 mt-2">
                    <i class="fas fa-clock mr-2"></i>
                    <span>ID: {{ substr($kunjungan->id, 0, 8) }}</span>
                    <span class="mx-3">•</span>
                    <i class="fas fa-calendar-plus mr-2"></i>
                    <span>Dibuat: {{ $kunjungan->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Contact Information -->
        <div class="info-card rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-address-card text-blue-600 mr-3"></i>
                Informasi Kontak
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-user text-blue-600 w-5 mr-3"></i>
                        <div>
                            <div class="text-sm text-gray-500">Nama Lengkap</div>
                            <div class="font-medium text-gray-900">{{ $kunjungan->namaPengunjung }}</div>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-envelope text-blue-600 w-5 mr-3"></i>
                        <div>
                            <div class="text-sm text-gray-500">Email</div>
                            <div class="font-medium text-gray-900">
                                <a href="mailto:{{ $kunjungan->email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $kunjungan->email }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-phone text-blue-600 w-5 mr-3"></i>
                        <div>
                            <div class="text-sm text-gray-500">Nomor Telepon</div>
                            <div class="font-medium text-gray-900">
                                <a href="tel:{{ $kunjungan->phone }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $kunjungan->phone }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-building text-blue-600 w-5 mr-3"></i>
                        <div>
                            <div class="text-sm text-gray-500">Institusi</div>
                            <div class="font-medium text-gray-900">{{ $kunjungan->institution }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visit Details -->
        <div class="info-card rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-calendar-check text-blue-600 mr-3"></i>
                Detail Kunjungan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                        <i class="fas fa-calendar-day text-blue-600 w-5 mr-3"></i>
                        <div>
                            <div class="text-sm text-blue-700">Tanggal Kunjungan</div>
                            <div class="font-bold text-blue-900">{{ $kunjungan->formattedDate }}</div>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 rounded-lg">
                        <i class="fas fa-users text-green-600 w-5 mr-3"></i>
                        <div>
                            <div class="text-sm text-green-700">Jumlah Peserta</div>
                            <div class="font-bold text-green-900">{{ $kunjungan->jumlahPengunjung }} orang</div>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-purple-50 rounded-lg">
                        <i class="fas fa-clock text-purple-600 w-5 mr-3"></i>
                        <div>
                            <div class="text-sm text-purple-700">Waktu Kunjungan</div>
                            <div class="font-bold text-purple-900">{{ $kunjungan->formattedTime }}</div>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-orange-50 rounded-lg">
                        <i class="fas fa-flag text-orange-600 w-5 mr-3"></i>
                        <div>
                            <div class="text-sm text-orange-700">Status</div>
                            <div class="font-bold text-orange-900" id="currentStatus">{{ $kunjungan->statusText }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Section -->
        @if($kunjungan->dokumen_surat)
        <div class="info-card rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-file-alt text-blue-600 mr-3"></i>
                Dokumen Pengajuan
            </h3>
            <div class="bg-blue-50 rounded-xl p-4 flex items-center justify-between">
                <div class="flex items-center">
                    @php
                        $extension = pathinfo($kunjungan->dokumen_surat, PATHINFO_EXTENSION);
                        $iconClass = match(strtolower($extension)) {
                            'pdf' => 'fas fa-file-pdf text-red-600',
                            'doc', 'docx' => 'fas fa-file-word text-blue-600',
                            'jpg', 'jpeg', 'png' => 'fas fa-file-image text-green-600',
                            default => 'fas fa-file text-gray-600'
                        };
                    @endphp
                    <i class="{{ $iconClass }} text-2xl mr-3"></i>
                    <div>
                        <p class="font-medium text-gray-900">{{ basename($kunjungan->dokumen_surat) }}</p>
                        <p class="text-sm text-gray-500">
                            Diupload: {{ $kunjungan->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <!-- Preview Button (for images and PDFs) -->
                    @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'pdf']))
                    <button onclick="previewDocument('{{ $kunjungan->document_url }}')"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        Preview
                    </button>
                    @endif

                    <!-- Download Button -->
                    <a href="{{ route('admin.visits.download-document', $kunjungan) }}"
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Download
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Purpose and Notes -->
        <div class="info-card rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-bullseye text-blue-600 mr-3"></i>
                Tujuan dan Catatan
            </h3>
            <div class="space-y-4">
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="text-sm font-medium text-blue-700 mb-2">Tujuan Kunjungan</div>
                    <div class="text-blue-900">{{ $kunjungan->tujuan }}</div>
                </div>
                @if($kunjungan->catatan_tambahan)
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</div>
                    <div class="text-gray-900 whitespace-pre-line">{{ $kunjungan->catatan_tambahan }}</div>
                </div>
                @else
                <div class="p-4 bg-gray-50 rounded-lg text-center">
                    <i class="fas fa-sticky-note text-gray-400 text-2xl mb-2"></i>
                    <div class="text-gray-500">Tidak ada catatan tambahan</div>
                </div>
                @endif
            </div>
        </div>

        <!-- WhatsApp Communication Section -->
        <div class="info-card rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fab fa-whatsapp text-green-600 mr-3"></i>
                Komunikasi WhatsApp
            </h3>

            <div class="space-y-4">
                <!-- Send Update to Visitor -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Kirim Update ke Pengunjung</h4>
                    <p class="text-sm text-gray-600 mb-3">Kirim update status kunjungan langsung ke WhatsApp pengunjung</p>

                    <div class="flex flex-wrap gap-2">
                        @if($kunjungan->status === 'PENDING')
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

                        <button onclick="sendCustomMessage()"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                            <i class="fab fa-whatsapp mr-2"></i>
                            Pesan Custom
                        </button>
                    </div>
                </div>

                <!-- Tracking Link -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Link Tracking</h4>
                    <p class="text-sm text-gray-600 mb-2">Link untuk pengunjung memantau status pengajuan:</p>
                    <div class="flex items-center space-x-2">
                        <input type="text"
                               value="{{ $kunjungan->tracking_url }}"
                               readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm">
                        <button onclick="copyToClipboard('{{ $kunjungan->tracking_url }}')"
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
        <!-- Quick Actions -->
        <div class="info-card rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <div class="flex items-center mb-3">
                    <label class="text-sm font-medium text-gray-700 mr-3">Ubah Status:</label>
                </div>
                <select id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ $kunjungan->status == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <button onclick="updateStatus()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Update Status
                </button>

                <hr class="my-4">

                <a href="{{ route('admin.visits.edit', $kunjungan) }}"
                   class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Kunjungan
                </a>

                <button onclick="deleteVisit()"
                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center justify-center">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Kunjungan
                </button>
            </div>
        </div>

        <!-- Visit Timeline -->
        <div class="info-card rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
            <div class="space-y-3">
                <div class="timeline-item flex items-center p-3 bg-blue-50 rounded-lg">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                    <div>
                        <div class="text-sm font-medium text-blue-900">Kunjungan Dibuat</div>
                        <div class="text-xs text-blue-700">{{ $kunjungan->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                @if($kunjungan->updated_at != $kunjungan->created_at)
                <div class="timeline-item flex items-center p-3 bg-yellow-50 rounded-lg">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                    <div>
                        <div class="text-sm font-medium text-yellow-900">Terakhir Diperbarui</div>
                        <div class="text-xs text-yellow-700">{{ $kunjungan->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif

                <div class="timeline-item flex items-center p-3 bg-green-50 rounded-lg">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <div>
                        <div class="text-sm font-medium text-green-900">Jadwal Kunjungan</div>
                        <div class="text-xs text-green-700">{{ $kunjungan->formattedDateTime }}</div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-medium">Preview Dokumen</h3>
            <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="previewContent" class="p-4">
            <!-- Content will be loaded here -->
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
            <textarea id="customMessageText"
                      rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg resize-none"
                      placeholder="Tulis pesan untuk pengunjung..."></textarea>
            <div class="flex justify-end space-x-2 mt-4">
                <button onclick="closeCustomMessage()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus Kunjungan</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus kunjungan <strong>{{ $kunjungan->namaPengunjung }}</strong>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 transition">
                    Hapus
                </button>
                <button id="cancelDelete" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .detail-card {
        background: linear-gradient(135deg, #748be6 0%, #5c57f5 30%, #2575bb 100%);
        position: relative;
        overflow: hidden;
    }

    .detail-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.1"><circle cx="30" cy="30" r="2"/></g></svg>');
        opacity: 0.3;
    }

    .info-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .status-badge {
        position: relative;
        overflow: hidden;
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
    }

    .status-badge:hover::before {
        left: 100%;
    }

    .timeline-item {
        transition: all 0.2s ease;
    }

    .timeline-item:hover {
        transform: translateX(4px);
    }

    .action-button {
        transition: all 0.2s ease;
    }

    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }
</style>
@endsection

@section('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateStatus() {
        const newStatus = document.getElementById('statusSelect').value;
        const currentStatus = '{{ $kunjungan->status }}';

        if (newStatus === currentStatus) {
            showAlert('info', 'Status tidak berubah.');
            return;
        }

        $.ajax({
            url: '{{ route("admin.visits.update-status", $kunjungan) }}',
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            data: JSON.stringify({
                status: newStatus
            }),
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);

                    // Update status badge
                    $('.status-badge').removeClass().addClass('status-badge px-4 py-2 rounded-full text-sm font-semibold ' + response.badgeColor);
                    $('.status-badge').text(response.newStatus);

                    // Update status in sidebar
                    $('#currentStatus').text(response.newStatus);

                    // Reload page after a short delay to reflect all changes
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                showAlert('error', 'Gagal mengubah status. Silakan coba lagi.');
                // Revert select
                document.getElementById('statusSelect').value = currentStatus;
            }
        });
    }

    // Preview document function
    function previewDocument(url) {
        const modal = document.getElementById('previewModal');
        const content = document.getElementById('previewContent');

        if (url.toLowerCase().includes('.pdf')) {
            content.innerHTML = `<iframe src="${url}" width="100%" height="600px" frameborder="0"></iframe>`;
        } else {
            content.innerHTML = `<img src="${url}" alt="Document Preview" class="max-w-full h-auto">`;
        }

        modal.classList.remove('hidden');
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
    }

    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showAlert('success', 'Link berhasil disalin!');
        });
    }

    // WhatsApp functions
    function sendStatusUpdate(type) {
        const visitorPhone = '{{ $kunjungan->phone }}';
        const visitorName = '{{ $kunjungan->namaPengunjung }}';
        const trackingUrl = '{{ $kunjungan->tracking_url }}';
        const visitDate = '{{ $kunjungan->formattedDate }}';
        const visitTime = '{{ $kunjungan->formattedTime }}';

        let message = '';

        if (type === 'approved') {
            message = `Halo ${visitorName},\n\n✅ *KUNJUNGAN DISETUJUI*\n\nKunjungan Anda ke Laboratorium Fisika Komputasi telah disetujui!\n\n📅 Tanggal: ${visitDate}\n⏰ Waktu: ${visitTime}\n\n📋 Silakan datang sesuai jadwal dan bawa:\n• Kartu identitas\n• Surat pengajuan asli\n• Mengikuti protokol keselamatan lab\n\n🔗 Pantau status: ${trackingUrl}\n\nTerima kasih!`;
        } else if (type === 'rejected') {
            message = `Halo ${visitorName},\n\n❌ *KUNJUNGAN TIDAK DAPAT DIPROSES*\n\nMohon maaf, pengajuan kunjungan Anda tidak dapat kami proses saat ini.\n\nSilakan hubungi admin untuk informasi lebih lanjut atau ajukan ulang dengan jadwal yang berbeda.\n\n🔗 Status pengajuan: ${trackingUrl}\n\nTerima kasih atas pengertiannya.`;
        }

        const whatsappUrl = `https://wa.me/${visitorPhone.replace(/^0/, '62')}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    }

    function sendCustomMessage() {
        document.getElementById('customMessageModal').classList.remove('hidden');
    }

    function closeCustomMessage() {
        document.getElementById('customMessageModal').classList.add('hidden');
        document.getElementById('customMessageText').value = '';
    }

    function sendMessage() {
        const message = document.getElementById('customMessageText').value;
        if (!message.trim()) {
            alert('Silakan tulis pesan terlebih dahulu');
            return;
        }

        const visitorPhone = '{{ $kunjungan->phone }}';
        const visitorName = '{{ $kunjungan->namaPengunjung }}';
        const trackingUrl = '{{ $kunjungan->tracking_url }}';

        const fullMessage = `Halo ${visitorName},\n\n${message}\n\n🔗 Pantau status pengajuan: ${trackingUrl}\n\nSalam,\nAdmin Laboratorium Fisika Komputasi`;

        const whatsappUrl = `https://wa.me/${visitorPhone.replace(/^0/, '62')}?text=${encodeURIComponent(fullMessage)}`;
        window.open(whatsappUrl, '_blank');

        closeCustomMessage();
    }

    function deleteVisit() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    document.getElementById('confirmDelete').onclick = function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.visits.destroy", $kunjungan) }}';
        form.innerHTML = `
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    };

    document.getElementById('cancelDelete').onclick = function() {
        document.getElementById('deleteModal').classList.add('hidden');
    };

    // Close modal when clicking outside
    document.getElementById('deleteModal').onclick = function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    };

    // Keyboard handler for modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('previewModal').classList.add('hidden');
            document.getElementById('customMessageModal').classList.add('hidden');
        }
    });

    // Alert function
    function showAlert(type, message) {
        const alertClass = {
            'success': 'bg-green-500',
            'error': 'bg-red-500',
            'info': 'bg-blue-500'
        }[type] || 'bg-gray-500';

        const iconClass = {
            'success': 'fa-check',
            'error': 'fa-exclamation-triangle',
            'info': 'fa-info-circle'
        }[type] || 'fa-info';

        const alert = $(`
            <div class="fixed top-4 right-4 ${alertClass} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full opacity-0 transition-all duration-300">
                <div class="flex items-center">
                    <i class="fas ${iconClass} mr-2"></i>
                    ${message}
                </div>
            </div>
        `);

        $('body').append(alert);

        setTimeout(() => {
            alert.removeClass('translate-x-full opacity-0');
        }, 100);

        setTimeout(() => {
            alert.addClass('translate-x-full opacity-0');
            setTimeout(() => alert.remove(), 300);
        }, 3000);
    }
</script>
@endsection
