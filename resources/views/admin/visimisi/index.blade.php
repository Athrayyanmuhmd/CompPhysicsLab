@extends('layouts.admin')

@section('title', 'Kelola Visi & Misi')

@section('styles')
<style>
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
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

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .mission-item {
        position: relative;
        background: white;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .mission-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-color: #d1d5db;
    }

    .mission-item .mission-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: #eef2ff;
        color: #4f46e5;
        border-radius: 50%;
        font-weight: 600;
        font-size: 0.875rem;
        margin-right: 0.75rem;
    }

    .mission-actions {
        position: absolute;
        top: 1rem;
        right: 1rem;
        display: flex;
        gap: 0.5rem;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .mission-item:hover .mission-actions {
        opacity: 1;
    }

    .modal-overlay {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
    }

    .section-title {
        position: relative;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -0.5rem;
        width: 50px;
        height: 3px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 3px;
    }

    .preview-card {
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        border-radius: 12px;
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .preview-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    }

    .preview-label {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .sortable-ghost {
        opacity: 0.5;
        background: #f3f4f6;
    }

    .sortable-drag {
        opacity: 0.8;
        transform: scale(0.98);
    }

    .mission-option {
        border: 1px solid #e5e7eb;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }

    .mission-option:hover {
        border-color: #3b82f6;
        background-color: #f0f9ff;
    }

    .mission-option.selected {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .mission-radio {
        margin-right: 0.75rem;
    }
</style>
@endsection

@section('content')
<!-- Header Section -->
<div class="mb-8 fade-up">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-semibold text-gray-900 mb-2">Kelola Visi & Misi</h1>
            <p class="text-gray-600">Pengaturan profil, visi, dan misi laboratorium</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Preview -->
    <div class="lg:col-span-1 fade-up" style="animation-delay: 0.1s">
        <div class="preview-card mb-6">
            <span class="preview-label">Preview</span>
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $profil->namaLaboratorium ?? 'Laboratorium Fisika Komputasi' }}</h3>
            <div class="text-sm text-gray-600 mb-4">
                <p>{{ $profil->tentangLaboratorium ?? 'Deskripsi laboratorium belum diatur' }}</p>
            </div>
            <div class="mb-4">
                <h4 class="font-medium text-blue-600 mb-1">Visi</h4>
                <p class="text-gray-700">{{ $profil->visi ?? 'Visi belum diatur' }}</p>
            </div>
            <div>
                <h4 class="font-medium text-blue-600 mb-1">Misi</h4>
                @if($selectedMisi)
                    <div class="flex items-center mt-2">
                        <span class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center text-xs text-blue-700 mr-2">1</span>
                        <span class="text-gray-700">{{ $selectedMisi->pointMisi }}</span>
                    </div>
                @else
                    <p class="text-gray-500">Belum ada misi yang dipilih</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column - Forms -->
    <div class="lg:col-span-2">
        <!-- Profil Laboratorium Form -->
        <div class="card p-6 mb-6 fade-up" style="animation-delay: 0.2s">
            <h2 class="text-xl font-semibold text-gray-900 section-title">Profil Laboratorium</h2>

            <form action="{{ route('admin.visimisi.update-profil') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Laboratorium</label>
                        <input type="text" name="namaLaboratorium" value="{{ $profil->namaLaboratorium ?? '' }}"
                               class="form-input" placeholder="Masukkan nama laboratorium" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tentang Laboratorium</label>
                        <textarea name="tentangLaboratorium" rows="4" class="form-input"
                                  placeholder="Masukkan deskripsi laboratorium" required>{{ $profil->tentangLaboratorium ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Visi</label>
                        <textarea name="visi" rows="3" class="form-input"
                                  placeholder="Masukkan visi laboratorium" required>{{ $profil->visi ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Unit Komputer
                            <span class="text-xs text-gray-500 font-normal ml-1">(ditampilkan di halaman utama)</span>
                        </label>
                        <input type="number" name="jumlah_komputer" min="1" max="9999"
                               value="{{ $profil->jumlah_komputer ?? 40 }}"
                               class="form-input w-32" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Misi</label>

                        @if($misis->count() > 0)
                            <div class="space-y-2">
                                @foreach($misis as $misi)
                                    <label class="mission-option {{ ($profil && $profil->misiId == $misi->id) ? 'selected' : '' }}">
                                        <input type="radio" name="misiId" value="{{ $misi->id }}"
                                               class="mission-radio"
                                               {{ ($profil && $profil->misiId == $misi->id) ? 'checked' : '' }}
                                               required>
                                        <span>{{ $misi->pointMisi }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 bg-yellow-50 text-yellow-700 rounded-lg">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Tidak ada misi yang tersedia. Tambahkan misi terlebih dahulu.
                            </div>
                        @endif
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan Profil
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Misi Form -->
        <div class="card p-6 mb-6 fade-up" style="animation-delay: 0.3s">
            <h2 class="text-xl font-semibold text-gray-900 section-title">Kelola Poin Misi</h2>

            <div class="mb-6">
                <form action="{{ route('admin.visimisi.store-misi') }}" method="POST" class="flex gap-3">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="pointMisi" class="form-input" placeholder="Tambahkan poin misi baru" required>
                    </div>
                    <button type="submit" class="btn btn-primary whitespace-nowrap">
                        <i class="fas fa-plus"></i>
                        Tambah
                    </button>
                </form>
            </div>

            <div id="misiList" class="space-y-3">
                @forelse($misis as $index => $misi)
                    <div class="mission-item flex items-center {{ ($profil && $profil->misiId == $misi->id) ? 'border-blue-300 bg-blue-50' : '' }}">
                        <div class="mission-number">{{ $index + 1 }}</div>
                        <div class="flex-1">{{ $misi->pointMisi }}</div>

                        @if($profil && $profil->misiId == $misi->id)
                            <div class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full mr-2">
                                Aktif
                            </div>
                        @endif

                        <div class="mission-actions">
                            <button onclick="editMisi('{{ $misi->id }}', '{{ $misi->pointMisi }}')" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>

                            @if(!($profil && $profil->misiId == $misi->id))
                                <button onclick="deleteMisi('{{ $misi->id }}', '{{ $misi->pointMisi }}')" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-list-ul text-gray-300 text-4xl mb-3"></i>
                        <p>Belum ada poin misi. Tambahkan poin misi pertama.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Edit Misi Modal -->
<div id="editMisiModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-overlay absolute inset-0" onclick="closeModal('editMisiModal')"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
            <form id="editMisiForm" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Edit Poin Misi</h3>
                    <p class="text-gray-600 text-sm mt-1">Perbarui poin misi laboratorium</p>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Poin Misi</label>
                        <input type="text" name="pointMisi" id="editPointMisi" required class="form-input">
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
                    <button type="button" onclick="closeModal('editMisiModal')" class="btn btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteMisiModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-overlay absolute inset-0" onclick="closeModal('deleteMisiModal')"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus poin misi <strong id="deleteMisiText"></strong>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="closeModal('deleteMisiModal')" class="btn btn-secondary">
                        Batal
                    </button>
                    <form id="deleteMisiForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Edit misi function
function editMisi(id, pointMisi) {
    const editForm = document.getElementById('editMisiForm');
    const editPointMisi = document.getElementById('editPointMisi');

    editForm.action = `/admin/visimisi/misi/${id}`;
    editPointMisi.value = pointMisi;

    openModal('editMisiModal');
}

// Delete misi function
function deleteMisi(id, pointMisi) {
    const deleteForm = document.getElementById('deleteMisiForm');
    const deleteMisiText = document.getElementById('deleteMisiText');

    deleteForm.action = `/admin/visimisi/misi/${id}`;
    deleteMisiText.textContent = `"${pointMisi}"`;

    openModal('deleteMisiModal');
}

// Mission option selection
document.addEventListener('DOMContentLoaded', function() {
    const missionOptions = document.querySelectorAll('.mission-option');

    missionOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');

        option.addEventListener('click', function() {
            radio.checked = true;

            // Update UI for all options
            missionOptions.forEach(opt => {
                if (opt.querySelector('input[type="radio"]').checked) {
                    opt.classList.add('selected');
                } else {
                    opt.classList.remove('selected');
                }
            });
        });
    });

    // Animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe fade-up elements
    document.querySelectorAll('.fade-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                closeModal(modal.id);
            }
        });
    }
});
</script>
@endsection
