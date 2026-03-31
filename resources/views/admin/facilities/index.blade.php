@extends('layouts.admin')

@section('title', 'Kelola Fasilitas')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Kelola Fasilitas Laboratorium</h1>
                <p class="text-blue-100">Kelola informasi dan gambar fasilitas laboratorium dengan mudah</p>
            </div>
            <div class="text-right">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                    <i class="fas fa-building text-3xl mb-2"></i>
                    <div class="text-sm text-blue-100">Total Gambar</div>
                        <div class="text-2xl font-bold">
                            @php
                                $imageCount = 0;
                                if ($facility && $facility->images) {
                                    if (is_array($facility->images)) {
                                        $imageCount = count($facility->images);
                                    } elseif (is_string($facility->images)) {
                                        $decoded = json_decode($facility->images, true);
                                        $imageCount = is_array($decoded) ? count($decoded) : 0;
                                    }
                                }
                            @endphp
                            {{ $imageCount }}
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Informasi Fasilitas</h3>
            <a href="{{ route('facilities') }}" target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-external-link-alt mr-2"></i>Lihat Halaman
            </a>
        </div>

        <form action="{{ route('admin.facilities.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Halaman</label>
                        <input type="text" name="title" value="{{ $facility->title ?? 'Fasilitas Laboratorium Fisika Komputasi' }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Singkat</label>
                        <textarea name="description" required rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Deskripsi singkat tentang fasilitas laboratorium...">{{ $facility->description ?? 'Laboratorium Fisika Komputasi dilengkapi dengan berbagai fasilitas modern untuk mendukung kegiatan praktikum dan pembelajaran mahasiswa.' }}</textarea>
                    </div>
                </div>

                <!-- Facility Points -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Point-Point Fasilitas</label>
                    <div id="facility-points-container">
                        @php
                            // Handle facility_points yang mungkin string atau array
                            $facilityPoints = [];
                            if ($facility && $facility->facility_points) {
                                if (is_array($facility->facility_points)) {
                                    $facilityPoints = $facility->facility_points;
                                } elseif (is_string($facility->facility_points)) {
                                    $decoded = json_decode($facility->facility_points, true);
                                    $facilityPoints = is_array($decoded) ? $decoded : [];
                                }
                            }

                            // Jika tidak ada points, gunakan default
                            if (empty($facilityPoints)) {
                                $facilityPoints = [
                                    'Ruang laboratorium yang luas dan nyaman',
                                    'Peralatan praktikum lengkap dan modern',
                                    'Kapasitas hingga 40 mahasiswa'
                                ];
                            }
                        @endphp

                        @foreach($facilityPoints as $index => $point)
                        <div class="flex items-center space-x-3 mb-3 facility-point-row">
                            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
                                {{ $index + 1 }}
                            </div>
                            <input type="text" name="facility_points[]" value="{{ $point }}" required
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan poin fasilitas...">
                            <button type="button" onclick="removeFacilityPoint(this)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-3 rounded-lg transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addFacilityPoint()"
                            class="mt-3 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Poin Fasilitas
                    </button>
                </div>

                <!-- Current Images -->
                @php
                    // Handle images yang mungkin string atau array
                    $facilityImages = [];
                    if ($facility && $facility->images) {
                        if (is_array($facility->images)) {
                            $facilityImages = $facility->images;
                        } elseif (is_string($facility->images)) {
                            $decoded = json_decode($facility->images, true);
                            $facilityImages = is_array($decoded) ? $decoded : [];
                        }
                    }
                @endphp

                @if(count($facilityImages) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Gambar Saat Ini</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($facilityImages as $index => $image)
                        <div class="relative group">
                            <img src="{{ Storage::url($image) }}" alt="Fasilitas {{ $index + 1 }}"
                                 class="w-full h-32 object-cover rounded-lg border border-gray-300">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                <label class="flex items-center text-white cursor-pointer">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image }}" class="mr-2">
                                    <span class="text-sm">Hapus</span>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Centang gambar yang ingin dihapus, lalu klik Simpan
                    </p>
                </div>
                @endif

                <!-- Upload New Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">
                        {{ count($facilityImages) > 0 ? 'Tambah Gambar Baru' : 'Upload Gambar Fasilitas' }}
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <input type="file" name="images[]" multiple accept="image/*"
                               class="hidden" id="imageUpload" onchange="previewImages(this)">
                        <label for="imageUpload" class="cursor-pointer">
                            <div class="space-y-3">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                <div>
                                    <p class="text-lg font-medium text-gray-700">Klik untuk upload gambar</p>
                                    <p class="text-sm text-gray-500">Atau drag & drop gambar ke sini</p>
                                    <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, GIF. Maksimal 2MB per file</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div id="image-previews" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end pt-8 border-t border-gray-200 space-x-4">
                <button type="button" onclick="resetForm()"
                        class="px-6 py-3 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg font-medium transition-colors">
                    Reset Form
                </button>
                <button type="submit"
                        class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Add new facility point
function addFacilityPoint() {
    const container = document.getElementById('facility-points-container');
    const pointNumber = container.children.length + 1;

    const div = document.createElement('div');
    div.className = 'flex items-center space-x-3 mb-3 facility-point-row';
    div.innerHTML = `
        <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-semibold">
            ${pointNumber}
        </div>
        <input type="text" name="facility_points[]" required
               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
               placeholder="Masukkan poin fasilitas...">
        <button type="button" onclick="removeFacilityPoint(this)"
                class="bg-red-500 hover:bg-red-600 text-white px-3 py-3 rounded-lg transition-colors">
            <i class="fas fa-trash"></i>
        </button>
    `;

    container.appendChild(div);
    updatePointNumbers();
}

// Remove facility point
function removeFacilityPoint(button) {
    const container = document.getElementById('facility-points-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
        updatePointNumbers();
    } else {
        alert('Minimal harus ada 1 poin fasilitas');
    }
}

// Update point numbers
function updatePointNumbers() {
    const rows = document.querySelectorAll('.facility-point-row');
    rows.forEach((row, index) => {
        const numberDiv = row.querySelector('.w-8.h-8');
        numberDiv.textContent = index + 1;
    });
}

// Preview uploaded images
function previewImages(input) {
    const previewContainer = document.getElementById('image-previews');
    previewContainer.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview"
                         class="w-full h-32 object-cover rounded-lg border border-gray-300">
                    <div class="absolute top-2 right-2 bg-green-500 text-white p-1 rounded-full text-xs">
                        <i class="fas fa-plus"></i>
                    </div>
                `;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
}

// Reset form
function resetForm() {
    if (confirm('Yakin ingin mereset form? Semua perubahan akan hilang.')) {
        location.reload();
    }
}

// Drag and drop functionality
const uploadArea = document.querySelector('label[for="imageUpload"]');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    uploadArea.parentElement.classList.add('border-blue-500', 'bg-blue-50');
}

function unhighlight(e) {
    uploadArea.parentElement.classList.remove('border-blue-500', 'bg-blue-50');
}

uploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    document.getElementById('imageUpload').files = files;
    previewImages(document.getElementById('imageUpload'));
}

// Auto-save draft (optional)
let saveTimeout;
document.querySelectorAll('input, textarea').forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            // You can implement auto-save to localStorage here
            console.log('Auto-saving draft...');
        }, 2000);
    });
});
</script>
@endsection
