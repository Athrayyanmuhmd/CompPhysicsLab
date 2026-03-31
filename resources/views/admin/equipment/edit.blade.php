{{-- resources/views/admin/equipment/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Alat - ' . $equipment->nama)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg text-white p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-edit text-blue-600 text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold">Edit Alat</h1>
                <p class="text-blue-100">Perbarui informasi alat laboratorium</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route('admin.equipment.update', $equipment) }}" method="POST" enctype="multipart/form-data" id="equipment-form">
            @csrf
            @method('PUT')

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column - Basic Information -->
                    <div class="space-y-6">
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Dasar</h3>
                        </div>

                        <!-- Equipment Name -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Alat <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   value="{{ old('nama', $equipment->nama) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                                   placeholder="Masukkan nama alat"
                                   required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Equipment Code -->
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Alat <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="kode"
                                   name="kode"
                                   value="{{ old('kode', $equipment->kode) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kode') border-red-500 @enderror"
                                   placeholder="Contoh: OSC-001"
                                   required>
                            @error('kode')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="nama_kategori"
                                    name="nama_kategori"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_kategori') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->nama_kategori }}"
                                            {{ old('nama_kategori', $equipment->nama_kategori) == $kategori->nama_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga (Opsional)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                                <input type="number"
                                       id="harga"
                                       name="harga"
                                       value="{{ old('harga', $equipment->harga) }}"
                                       min="0"
                                       step="0.01"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('harga') border-red-500 @enderror"
                                       placeholder="0">
                            </div>
                            @error('harga')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi"
                                      name="deskripsi"
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                      placeholder="Masukkan deskripsi detail alat..."
                                      required>{{ old('deskripsi', $equipment->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column - Quantities & Image -->
                    <div class="space-y-6">
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Inventaris & Media</h3>
                        </div>

                        <!-- Current Status -->
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-800 mb-3">Status Saat Ini</h4>
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="bg-white p-3 rounded">
                                    <div class="text-lg font-bold text-green-600">{{ $equipment->jumlah_tersedia }}</div>
                                    <div class="text-xs text-gray-600">Tersedia</div>
                                </div>
                                <div class="bg-white p-3 rounded">
                                    <div class="text-lg font-bold text-yellow-600">{{ $equipment->jumlah_dipinjam }}</div>
                                    <div class="text-xs text-gray-600">Dipinjam</div>
                                </div>
                                <div class="bg-white p-3 rounded">
                                    <div class="text-lg font-bold text-red-600">{{ $equipment->jumlah_rusak }}</div>
                                    <div class="text-xs text-gray-600">Rusak</div>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-800 mb-4">Update Informasi Stok</h4>

                            <!-- Total Stock -->
                            <div class="mb-4">
                                <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                                    Total Stok <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="stok"
                                       name="stok"
                                       value="{{ old('stok', $equipment->stok) }}"
                                       min="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stok') border-red-500 @enderror"
                                       placeholder="0"
                                       required>
                                @error('stok')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Available Quantity -->
                            <div class="mb-4">
                                <label for="jumlah_tersedia" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Tersedia <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="jumlah_tersedia"
                                       name="jumlah_tersedia"
                                       value="{{ old('jumlah_tersedia', $equipment->jumlah_tersedia) }}"
                                       min="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah_tersedia') border-red-500 @enderror"
                                       placeholder="0"
                                       required>
                                @error('jumlah_tersedia')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Borrowed Quantity -->
                            <div class="mb-4">
                                <label for="jumlah_dipinjam" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Dipinjam <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="jumlah_dipinjam"
                                       name="jumlah_dipinjam"
                                       value="{{ old('jumlah_dipinjam', $equipment->jumlah_dipinjam) }}"
                                       min="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah_dipinjam') border-red-500 @enderror"
                                       placeholder="0"
                                       required>
                                @error('jumlah_dipinjam')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Damaged Quantity -->
                            <div class="mb-4">
                                <label for="jumlah_rusak" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Rusak <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       id="jumlah_rusak"
                                       name="jumlah_rusak"
                                       value="{{ old('jumlah_rusak', $equipment->jumlah_rusak) }}"
                                       min="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah_rusak') border-red-500 @enderror"
                                       placeholder="0"
                                       required>
                                @error('jumlah_rusak')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Stock Validation Alert -->
                            <div id="stock-alert" class="hidden p-3 bg-red-100 border border-red-400 text-red-700 text-sm rounded">
                                Total jumlah (tersedia + dipinjam + rusak) tidak boleh melebihi stok total.
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Alat
                            </label>

                            <!-- Current Image -->
                            @if($equipment->image_url)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-2">Gambar saat ini:</p>
                                    <img src="{{ $equipment->image_asset }}"
                                         alt="{{ $equipment->nama }}"
                                         class="max-w-full h-32 object-cover rounded border"
                                         onerror="this.src='{{ asset('images/equipment/default.jpg') }}'">
                                </div>
                            @endif

                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                                <input type="file"
                                       id="image"
                                       name="image"
                                       accept="image/*"
                                       class="hidden"
                                       onchange="previewImage(this)">

                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Preview" class="max-w-full h-48 object-cover rounded mx-auto mb-4">
                                    <button type="button" onclick="removeImage()" class="text-red-600 hover:text-red-700 text-sm">
                                        <i class="fas fa-trash mr-1"></i>Hapus Gambar Baru
                                    </button>
                                </div>

                                <div id="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 mb-2">Klik untuk upload gambar baru atau drag & drop</p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                                    <button type="button" onclick="document.getElementById('image').click()"
                                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Pilih Gambar
                                    </button>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <a href="{{ route('admin.equipment.show', $equipment) }}"
                   class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>

                <div class="flex space-x-3">
                    <button type="reset"
                            class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Update Alat
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('upload-placeholder').classList.remove('hidden');
}

// Stock validation
function validateStock() {
    const stok = parseInt(document.getElementById('stok').value) || 0;
    const tersedia = parseInt(document.getElementById('jumlah_tersedia').value) || 0;
    const dipinjam = parseInt(document.getElementById('jumlah_dipinjam').value) || 0;
    const rusak = parseInt(document.getElementById('jumlah_rusak').value) || 0;

    const total = tersedia + dipinjam + rusak;
    const alert = document.getElementById('stock-alert');

    if (total > stok) {
        alert.classList.remove('hidden');
        return false;
    } else {
        alert.classList.add('hidden');
        return true;
    }
}

// Add event listeners for stock validation
document.getElementById('stok').addEventListener('input', validateStock);
document.getElementById('jumlah_tersedia').addEventListener('input', validateStock);
document.getElementById('jumlah_dipinjam').addEventListener('input', validateStock);
document.getElementById('jumlah_rusak').addEventListener('input', validateStock);

// Form submission validation
document.getElementById('equipment-form').addEventListener('submit', function(e) {
    if (!validateStock()) {
        e.preventDefault();
        alert('Total jumlah (tersedia + dipinjam + rusak) tidak boleh melebihi stok total.');
    }
});

// Initial validation on page load
document.addEventListener('DOMContentLoaded', function() {
    validateStock();
});
</script>
@endsection
