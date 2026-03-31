<section class="relative min-h-screen flex items-center justify-center overflow-visible w-full h-full">
    <!-- Background Image with Blur -->
    <div class="absolute inset-0 z-0 overflow-hidden w-full max-w-full">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat filter blur-sm scale-110"
             style="background-image: url('{{ asset('images/fisika-1.jpeg') }}');">
        </div>
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black/60"></div>
        <!-- Additional gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/60"></div>
    </div>

    <!-- Content -->
    <div class="relative z-20 mx-6 px-4 sm:px-6 lg:px-8 text-center">
        <!-- Welcome Text -->
        <div class="welcome-text mb-8 opacity-0 transform -translate-y-10">
            <h2 class="text-white text-xl md:text-2xl font-light mb-2">Selamat Datang di</h2>
            <div class="w-24 h-0.5 bg-white mx-auto"></div>
        </div>

        <!-- Main Title -->
        <div class="main-title mb-12">
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold leading-tight mb-6">
                <span class="typewriter-text text-white"></span>
            </h1>
        </div>

        <!-- Subtitle Badge -->
        <div class="subtitle-text mb-16 opacity-0">
            <div class="inline-block">
                <div class="bg-white/20 backdrop-blur-md border border-white/30 rounded-full px-8 py-4">
                    <p class="text-white text-sm md:text-base font-medium">
                        Departemen Fisika - Fakultas Matematika dan Ilmu Pengetahuan Alam - Universitas Syiah Kuala
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="absolute -bottom-20 left-1/2 transform -translate-x-1/2 z-30 w-full max-w-4xl mx-auto px-6">
        <div class="stats-section opacity-0 transform translate-y-20">
            <div class="stats-overlay bg-white border border-gray-200 rounded-3xl p-6 md:p-8 shadow-2xl">
                <div class="grid grid-cols-3 gap-6 md:gap-12">
                   <!-- Stat 1 -->
                    <div class="text-center">
                        <div class="text-3xl md:text-5xl lg:text-6xl font-bold text-blue-600 mb-2 transition-all duration-300 hover:scale-110">
                            {{ $totalKomputer }}
                        </div>
                        <div class="text-gray-700 text-xs md:text-sm lg:text-base font-medium">Unit Komputer</div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="text-center">
                        <div class="text-3xl md:text-5xl lg:text-6xl font-bold text-yellow-500 mb-2 transition-all duration-300 hover:scale-110">
                            {{ $totalKunjunganPerTahun }}
                        </div>
                        <div class="text-gray-700 text-xs md:text-sm lg:text-base font-medium">Kunjungan per Tahun</div>
                    </div>

                    <!-- Stat 3 -->
                    <div class="text-center">
                        <div class="text-3xl md:text-5xl lg:text-6xl font-bold text-blue-600 mb-2 transition-all duration-300 hover:scale-110">
                            {{ $totalStaf }}
                        </div>
                        <div class="text-gray-700 text-xs md:text-sm lg:text-base font-medium">Staf berpengalaman</div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Enhanced blur effect */
.filter.blur-sm {
    filter: blur(8px);
}

/* Glass morphism */
.backdrop-blur-md {
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
}

/* Responsive text scaling */
@media (max-width: 768px) {
    .text-5xl {
        font-size: 2.5rem;
        line-height: 1.1;
    }
}

/* Typewriter cursor */
.typewriter-cursor::after {
    content: '|';
    animation: blink 1s infinite;
    color: #60A5FA;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0; }
}

/* Slide down animation untuk welcome text */
.slide-down {
    opacity: 1 !important;
    transform: translateY(0) !important;
    transition: all 1s ease-out;
}

/* Fade in animation */
.fade-in {
    opacity: 1 !important;
    transition: opacity 1s ease-in-out;
}

/* Stats section animation - slide up from bottom */
.stats-animate {
    opacity: 1 !important;
    transform: translateY(0) !important;
    transition: all 0.8s ease-out;
}

/* Stats section hide - slide down to bottom */
.stats-hide {
    opacity: 0 !important;
    transform: translateY(50px) !important;
    transition: all 0.6s ease-in;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const welcomeText = document.querySelector('.welcome-text');
    const subtitleText = document.querySelector('.subtitle-text');
    const typewriterElement = document.querySelector('.typewriter-text');
    const statsSection = document.querySelector('.stats-section');

    // 1. Animasi ketika website di-load - slide down dari atas
    setTimeout(() => {
        welcomeText.classList.add('slide-down');
    }, 500);

    // 2. Typewriter effect untuk "Laboratorium Fisika Komputasi" (looping dengan warna berbeda)
    function typeWriter(element, speed = 100, isLooping = false) {
        const text1 = "LABORATORIUM ";
        const text2 = "FISIKA KOMPUTASI";

        return new Promise((resolve) => {
            let i = 0;
            element.classList.add('typewriter-cursor');

            function type() {
                const totalLength = text1.length + text2.length;

                if (i < totalLength) {
                    if (i < text1.length) {
                        // Ketik "Laboratorium " dengan warna putih
                        element.innerHTML = `<span class="text-white">${text1.substring(0, i + 1)}</span>`;
                    } else {
                        // Ketik "Fisika Komputasi" dengan warna biru
                        const text2Index = i - text1.length;
                        element.innerHTML = `<span class="text-white">${text1}</span><span class="text-blue-400">${text2.substring(0, text2Index + 1)}</span>`;
                    }
                    i++;
                    setTimeout(type, speed);
                } else {
                    if (isLooping) {
                        // Pause sebentar lalu hapus teks dan mulai lagi
                        setTimeout(() => {
                            deleteText(element, speed);
                        }, 2000);
                    } else {
                        element.classList.remove('typewriter-cursor');
                        resolve();
                    }
                }
            }
            type();
        });
    }

    function deleteText(element, speed) {
        const text1 = "LABORATORIUM ";
        const text2 = "FISIKA KOMPUTASI";
        const totalLength = text1.length + text2.length;
        let i = totalLength;

        function deleteChar() {
            if (i > 0) {
                if (i > text1.length) {
                    // Hapus dari "Fisika Komputasi"
                    const text2Index = i - text1.length;
                    element.innerHTML = `<span class="text-white">${text1}</span><span class="text-blue-400">${text2.substring(0, text2Index - 1)}</span>`;
                } else {
                    // Hapus dari "Laboratorium "
                    element.innerHTML = `<span class="text-white">${text1.substring(0, i - 1)}</span>`;
                }
                i--;
                setTimeout(deleteChar, speed / 2);
            } else {
                element.innerHTML = '';
                // Mulai ketik lagi
                setTimeout(() => {
                    typeWriter(element, speed, true);
                }, 500);
            }
        }
        deleteChar();
    }

    // Mulai typewriter setelah welcome text muncul
    setTimeout(() => {
        // Ketik "Laboratorium Fisika Komputasi" (looping terus)
        typeWriter(typewriterElement, 100, true);

        // Tampilkan subtitle setelah selesai mengetik pertama kali
        setTimeout(() => {
            subtitleText.classList.add('fade-in');
        }, 2500);

    }, 1500);

    // 3. Statistics section muncul dari bawah ketika di-scroll dan hide ketika kembali ke atas
    function isElementInCenter(el) {
        const rect = el.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        const elementCenter = rect.top + (rect.height / 2);
        const windowCenter = windowHeight / 2;

        // Element dianggap di tengah jika centernya berada di area tengah viewport
        return Math.abs(elementCenter - windowCenter) < windowHeight * 0.3;
    }

    function isScrollNearTop() {
        return window.scrollY < 100; // Jika scroll kurang dari 100px dari atas
    }

    function handleScroll() {
        if (isScrollNearTop()) {
            // Kalau scroll kembali ke atas, hide statistics
            if (statsSection.classList.contains('stats-animate')) {
                statsSection.classList.remove('stats-animate');
                statsSection.classList.add('stats-hide');
            }
        } else if (isElementInCenter(statsSection)) {
            // Kalau di tengah, show statistics
            if (!statsSection.classList.contains('stats-animate')) {
                statsSection.classList.remove('stats-hide');
                statsSection.classList.add('stats-animate');
            }
        }
    }

    // Check on scroll
    window.addEventListener('scroll', handleScroll);
});
</script>
