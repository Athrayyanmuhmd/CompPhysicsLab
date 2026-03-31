{{-- resources/views/admin/articles/show.blade.php --}}

@extends('layouts.admin')

@section('title', 'Detail Artikel')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.articles.index') }}"
               class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Artikel</h1>
                <p class="text-gray-600 mt-1">{{ $article->nama_acara }}</p>
            </div>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.articles.edit', $article) }}"
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('articles.show', $article->id) }}"
               target="_blank"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-external-link-alt mr-2"></i>Lihat di Website
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Article Content -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">{{ $article->nama_acara }}</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($article->deskripsi)) !!}
                </div>
            </div>

            <!-- Images -->
            @if($article->gambar->count() > 0)
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Gambar Artikel ({{ $article->gambar->count() }})</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($article->gambar as $gambar)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $gambar->url) }}"
                                     alt="Gambar artikel"
                                     class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition"
                                     onclick="openImageModal('{{ asset('storage/' . $gambar->url) }}')">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                                    <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-all text-xl"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-500">Tidak ada gambar</p>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Article Info -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Artikel</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Penulis</label>
                        <p class="text-gray-900">{{ $article->penulis }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tanggal Acara</label>
                        <p class="text-gray-900">{{ $article->tanggal_acara->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Dibuat</label>
                        <p class="text-gray-900">{{ $article->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Terakhir Diupdate</label>
                        <p class="text-gray-900">{{ $article->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Jumlah Gambar</label>
                        <p class="text-gray-900">{{ $article->gambar->count() }} gambar</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.articles.edit', $article) }}"
                       class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition text-center block">
                        <i class="fas fa-edit mr-2"></i>Edit Artikel
                    </a>
                    <button onclick="deleteArticle('{{ $article->id }}')"
                            class="w-full bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash mr-2"></i>Hapus Artikel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="relative max-w-4xl max-h-screen m-4">
        <button onclick="closeImageModal()"
                class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Gambar artikel" class="max-w-full max-h-full rounded-lg">
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Hapus Artikel</h3>
            <p class="text-gray-500 mb-6">Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-end space-x-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function deleteArticle(id) {
    document.getElementById('deleteForm').action = `/admin/articles/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal with escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
        closeDeleteModal();
    }
});
</script>
@endsection
