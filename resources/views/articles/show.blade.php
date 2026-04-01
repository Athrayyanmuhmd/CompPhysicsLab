{{-- resources/views/articles/show.blade.php --}}
@extends('layouts.app')
@section('title', $article['title'] . ' - Laboratorium Fisika Komputasi')
@section('content')

<!-- Hero Section -->
<section class="relative pt-32 pb-16 bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle at 1px 1px, #2563eb 1px, transparent 0); background-size: 20px 20px;"></div>
    </div>

    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('articles.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Artikel</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-700 font-medium">{{ $article['title'] }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Article Header -->
        <div class="text-center mb-8">
            <!-- Article Badge -->
            <div class="mb-6">
                <div class="inline-flex items-center bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium">
                    <i class="fas fa-newspaper mr-2"></i>
                    Artikel Laboratorium
                </div>
            </div>

            <!-- Title -->
            <h1 class="font-poppins text-3xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                {{ $article['title'] }}
            </h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap justify-center items-center gap-6 text-gray-600 mb-8">
                <div class="flex items-center">
                    <i class="fas fa-user mr-2 text-blue-600"></i>
                    <span>{{ $article['author'] }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-2 text-blue-600"></i>
                    <span>{{ \Carbon\Carbon::parse($article['date'])->format('d M Y') }}</span>
                </div>
            </div>


        </div>
    </div>
</section>

<!-- Article Content -->
<section class="py-16 bg-white">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        <!-- Featured Image -->
        <div class="mb-12">
            <img src="{{ $article['image'] }}"
                 alt="{{ $article['title'] }}"
                 class="w-full h-80 object-cover rounded-3xl shadow-lg"
                 onerror="this.src='{{ asset('images/article/default.jpg') }}'">
        </div>

        <!-- Article Content -->
        <div class="prose prose-lg max-w-none">
            <div class="text-gray-700 leading-relaxed text-lg">
                {!! $article['content'] !!}
            </div>
        </div>

    </div>
</section>



<style>
/* Typography */
.font-poppins {
    font-family: 'Poppins', sans-serif;
}

/* Prose styling for article content */
.prose {
    color: #374151;
    max-width: none;
}

.prose h2 {
    color: #1f2937;
    font-weight: 700;
    font-size: 1.875rem;
    margin-top: 2rem;
    margin-bottom: 1rem;
    line-height: 1.3;
}

.prose h3 {
    color: #1f2937;
    font-weight: 600;
    font-size: 1.5rem;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.prose p {
    margin-bottom: 1.25rem;
    line-height: 1.7;
}

.prose strong {
    color: #1f2937;
    font-weight: 600;
}

.prose ul, .prose ol {
    margin-bottom: 1.25rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }

    body {
        font-size: 12pt;
        line-height: 1.4;
    }

    h1, h2, h3 {
        page-break-after: avoid;
    }
}

/* Share buttons hover effects */
.w-10.h-10 {
    transition: all 0.2s ease;
}

.w-10.h-10:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Image loading effect */
img {
    transition: opacity 0.3s ease;
}

img[src=""], img:not([src]) {
    opacity: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Image lazy loading effect
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
    });

    // Share functionality
    const shareButtons = document.querySelectorAll('[href="#"]');
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            // Get article info
            const title = document.querySelector('h1').textContent;
            const url = window.location.href;

            // Determine share platform based on icon
            const icon = this.querySelector('i');

            if (icon.classList.contains('fa-facebook-f')) {
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
            } else if (icon.classList.contains('fa-twitter')) {
                window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, '_blank');
            } else if (icon.classList.contains('fa-linkedin-in')) {
                window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, '_blank');
            } else if (icon.classList.contains('fa-whatsapp')) {
                window.open(`https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`, '_blank');
            }
        });
    });

    // Save article functionality (you can customize this)
    const saveButton = document.querySelector('.fa-bookmark').parentElement;
    if (saveButton) {
        saveButton.addEventListener('click', function() {
            // Toggle saved state
            const icon = this.querySelector('i');
            const text = this.querySelector('span') || this.childNodes[2];

            if (icon.classList.contains('fas')) {
                icon.classList.remove('fas');
                icon.classList.add('far');
                if (text) text.textContent = ' Simpan';
                this.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                this.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');

                // Show notification
                showNotification('Artikel dihapus dari simpanan');
            } else {
                icon.classList.remove('far');
                icon.classList.add('fas');
                if (text) text.textContent = ' Tersimpan';
                this.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                this.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');

                // Show notification
                showNotification('Artikel disimpan');
            }
        });
    }

    // Simple notification function
    function showNotification(message) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.textContent = message;

        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Hide notification after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
});
</script>

@endsection
