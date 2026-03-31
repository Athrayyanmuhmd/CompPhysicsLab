@extends('layouts.admin')

@section('title', 'Kelola Staff')

@php
use Illuminate\Support\Facades\Storage;
use App\Models\Gambar;
@endphp

@section('head')
<!-- Ensure CSRF token is available -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

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

    .stat-card {
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--accent-color);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.blue { --accent-color: #2563eb; }
    .stat-card.purple { --accent-color: #7c3aed; }
    .stat-card.emerald { --accent-color: #059669; }
    .stat-card.orange { --accent-color: #ea580c; }

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

    .staff-card {
        position: relative;
        overflow: hidden;
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .staff-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        transform: translateY(-4px);
    }

    .staff-avatar {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        background: #f3f4f6;
    }

    .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1;
    }

    .badge-head { background: #fef3c7; color: #d97706; }
    .badge-lecturer { background: #e0e7ff; color: #6366f1; }
    .badge-technician { background: #d1fae5; color: #059669; }
    .badge-other { background: #f3f4f6; color: #6b7280; }

    .modal-overlay {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }

    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
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

    .search-container {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .search-input {
        padding-left: 2.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .debug-info {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .staff-card:hover .debug-info {
        opacity: 1;
    }
</style>
@endsection

@section('content')
<!-- Header Section -->
<div class="mb-8 fade-up">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl font-semibold text-gray-900 mb-2">Kelola Staff</h1>
            <p class="text-gray-600">Manajemen data staff dan pengurus laboratorium</p>
        </div>
        <button onclick="openModal('addModal')" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Tambah Staff
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Staff -->
    <div class="stat-card blue card p-6 fade-up" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Staff</p>
                <p class="text-3xl font-semibold text-gray-900">{{ $stats['total_staff'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500">Semua staff aktif</p>
    </div>

    <!-- Kepala Lab -->
    <div class="stat-card orange card p-6 fade-up" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Kepala Lab</p>
                <p class="text-3xl font-semibold text-gray-900">{{ $stats['kepala_lab'] }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-star text-orange-600 text-xl"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500">Pimpinan laboratorium</p>
    </div>

    <!-- Dosen -->
    <div class="stat-card purple card p-6 fade-up" style="animation-delay: 0.3s">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Dosen</p>
                <p class="text-3xl font-semibold text-gray-900">{{ $stats['dosen'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-graduation-cap text-purple-600 text-xl"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500">Tenaga pengajar</p>
    </div>

    <!-- Laboran -->
    <div class="stat-card emerald card p-6 fade-up" style="animation-delay: 0.4s">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Laboran</p>
                <p class="text-3xl font-semibold text-gray-900">{{ $stats['laboran'] }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-tools text-emerald-600 text-xl"></i>
            </div>
        </div>
        <p class="text-sm text-gray-500">Teknisi laboratorium</p>
    </div>
</div>

<!-- Search and Filter -->
<div class="card p-6 mb-6 fade-up" style="animation-delay: 0.5s">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="search-container flex-1">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Cari nama atau jabatan..."
                   class="form-input search-input">
        </div>
        <select id="categoryFilter" class="form-input md:w-48">
            <option value="">Semua Kategori</option>
            <option value="head-lecturer">Kepala Lab</option>
            <option value="lecturer">Dosen</option>
            <option value="technician">Laboran</option>
            <option value="other">Lainnya</option>
        </select>
    </div>
</div>

<!-- Staff Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="staffGrid">
    @forelse($staff as $index => $person)
    @php
    $staffFoto = Gambar::where('pengurus_id', $person->id)
                ->where('kategori', 'PENGURUS')
                ->latest()
                ->first();
    @endphp
    <div class="staff-card fade-up staff-item"
         style="animation-delay: {{ 0.6 + ($index * 0.1) }}s"
         data-name="{{ strtolower($person->nama) }}"
         data-jabatan="{{ strtolower($person->jabatan) }}"
         data-category="{{ $person->getKategoriJabatan() }}">

        <!-- Debug Info (hanya muncul saat hover) -->
        @if(config('app.debug'))
        <div class="debug-info">
            {{ $staffFoto ? 'Foto: ✓ ('.$staffFoto->url.')' : 'No foto' }}
        </div>
        @endif

        <!-- Card Header with gradient background -->
        <div class="h-24 bg-gradient-to-br from-blue-500 to-purple-600 relative">
            <div class="absolute bg-black opacity-10 w-full h-full"></div>
        </div>

        <!-- Staff Info -->
        <div class="p-6 -mt-10 relative">
            <!-- Avatar -->
            <div class="flex justify-center mb-4">
                @if($staffFoto)
                     <img src="{{ asset('storage/' . $staffFoto->url) }}"
                        alt="{{ $person->nama }}"
                        class="staff-avatar"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        loading="lazy">
                @else
                    <div class="avatar-placeholder">
                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                    </div>
                @endif
            </div>

            <!-- Name and Position -->
            <div class="text-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $person->nama }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $person->jabatan }}</p>

                <!-- Badge -->
                <span class="badge badge-{{ str_replace('-', '', $person->getKategoriJabatan()) }}">
                    <i class="fas fa-{{ $person->getIconBadge() }}"></i>
                    {{ ucwords(str_replace('-', ' ', $person->getKategoriJabatan())) }}
                </span>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 justify-center">
                <button onclick="editStaff('{{ $person->id }}')"
                        class="btn btn-success btn-sm">
                    <i class="fas fa-edit"></i>
                    Edit
                </button>
                <button onclick="deleteStaff('{{ $person->id }}', '{{ $person->nama }}')"
                        class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i>
                    Hapus
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data staff</h3>
            <p class="text-gray-600 mb-4">Mulai dengan menambahkan staff pertama</p>
            <button onclick="openModal('addModal')" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Tambah Staff Pertama
            </button>
        </div>
    </div>
    @endforelse
</div>

<!-- Add Staff Modal -->
<div id="addModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-overlay absolute inset-0" onclick="closeModal('addModal')"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
            <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Tambah Staff Baru</h3>
                    <p class="text-gray-600 text-sm mt-1">Lengkapi informasi staff yang akan ditambahkan</p>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" required class="form-input" placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="jabatan" required class="form-input" placeholder="Masukkan jabatan">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                        <input type="file" name="foto" accept="image/*" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
                    <button type="button" onclick="closeModal('addModal')" class="btn btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Staff Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-overlay absolute inset-0" onclick="closeModal('editModal')"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">Edit Staff</h3>
                    <p class="text-gray-600 text-sm mt-1">Perbarui informasi staff</p>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" id="editNama" required class="form-input">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="jabatan" id="editJabatan" required class="form-input">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                        <div id="currentPhotoContainer" style="display: none;"></div>
                        <input type="file" name="foto" accept="image/*" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-200 flex gap-3 justify-end">
                    <button type="button" onclick="closeModal('editModal')" class="btn btn-secondary">
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
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-overlay absolute inset-0" onclick="closeModal('deleteModal')"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus staff <strong id="deleteStaffName"></strong>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3 justify-center">
                    <button type="button" onclick="closeModal('deleteModal')" class="btn btn-secondary">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
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

    // Reset current photo container if closing edit modal
    if (modalId === 'editModal') {
        const currentPhotoContainer = document.getElementById('currentPhotoContainer');
        if (currentPhotoContainer) {
            currentPhotoContainer.innerHTML = '';
            currentPhotoContainer.style.display = 'none';
        }
    }
}

// Alternative simple edit function without CSRF for GET request
function editStaff(id) {
    console.log('Editing staff with ID:', id);

    // Simple fetch without CSRF (GET requests don't need CSRF)
    fetch(`/admin/staff/${id}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        if (!response.ok) {
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            });
        }

        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);

        // Debug: log foto URL yang diterima
        if (data.foto) {
            console.log('Photo URL received:', data.foto);
        } else {
            console.log('No photo data received');
        }

        // Populate form fields
        const editNama = document.getElementById('editNama');
        const editJabatan = document.getElementById('editJabatan');
        const editForm = document.getElementById('editForm');

        if (editNama) editNama.value = data.nama || '';
        if (editJabatan) editJabatan.value = data.jabatan || '';
        if (editForm) editForm.action = `/admin/staff/${id}`;

        // Show current photo if available
        if (data.foto) {
            const currentPhotoContainer = document.getElementById('currentPhotoContainer');
            if (currentPhotoContainer) {
                currentPhotoContainer.innerHTML = `
                    <div class="mt-2 mb-4">
                        <p class="text-sm text-gray-600 mb-1">Foto saat ini:</p>
                        <img src="${data.foto}"
                             alt="Current Photo"
                             class="h-16 w-16 object-cover rounded-lg border border-gray-200"
                             onerror="console.error('Failed to load image:', this.src); this.style.display='none';"
                             onload="console.log('Image loaded successfully:', this.src);">
                    </div>
                `;
                currentPhotoContainer.style.display = 'block';
                console.log('Photo container updated with image:', data.foto);
            } else {
                console.error('currentPhotoContainer element not found');
            }
        } else {
            // Hide photo container if no photo
            const currentPhotoContainer = document.getElementById('currentPhotoContainer');
            if (currentPhotoContainer) {
                currentPhotoContainer.style.display = 'none';
                currentPhotoContainer.innerHTML = '';
            }
            console.log('No photo to display');
        }

        openModal('editModal');
    })
    .catch(error => {
        console.error('Fetch error:', error);
        console.error('Error details:', {
            message: error.message,
            stack: error.stack
        });
        alert('Terjadi kesalahan saat mengambil data staff: ' + error.message);

        // Debug: try to open modal anyway to see if form elements exist
        console.log('Form elements check:', {
            editNama: document.getElementById('editNama') ? 'Found' : 'Not found',
            editJabatan: document.getElementById('editJabatan') ? 'Found' : 'Not found',
            editForm: document.getElementById('editForm') ? 'Found' : 'Not found',
            currentPhotoContainer: document.getElementById('currentPhotoContainer') ? 'Found' : 'Not found'
        });
    });
}

// Get CSRF token with fallback
function getCSRFToken() {
    // Try to get from meta tag first
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        return metaTag.getAttribute('content');
    }

    // Fallback: try to get from form
    const csrfInput = document.querySelector('input[name="_token"]');
    if (csrfInput) {
        return csrfInput.value;
    }

    // Last fallback: try from Laravel global
    if (window.Laravel && window.Laravel.csrfToken) {
        return window.Laravel.csrfToken;
    }

    console.warn('CSRF token not found');
    return '';
}

// Delete staff function
function deleteStaff(id, nama) {
    document.getElementById('deleteStaffName').textContent = nama;
    document.getElementById('deleteForm').action = `/admin/staff/${id}`;
    openModal('deleteModal');
}

// Search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const staffItems = document.querySelectorAll('.staff-item');

    function filterStaff() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;

        staffItems.forEach(item => {
            const nama = item.dataset.name;
            const jabatan = item.dataset.jabatan;
            const category = item.dataset.category;

            const matchesSearch = nama.includes(searchTerm) || jabatan.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;

            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
                item.classList.add('fade-up');
            } else {
                item.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterStaff);
    categoryFilter.addEventListener('change', filterStaff);

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

// Image loading error handling
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.staff-avatar');
    images.forEach(img => {
        img.addEventListener('error', function() {
            console.log('Image failed to load:', this.src);
            // Fallback sudah dihandle dengan onerror inline
        });
    });
});
</script>
@endsection
