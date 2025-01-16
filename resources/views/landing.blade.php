<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMKN 1 Punggelan - Sistem Informasi Akademik</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero-pattern {
            background: #111827;  /* Mengubah ke background hitam */
        }
        .hero-pattern::before {
            display: none;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
        }
        .nav-link {
            position: relative;
            padding: 0.5rem 0;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #f59e0b; /* amber-500 */
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .drop-shadow-lg {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .max-w-md {
            max-width: 28rem;
        }
        .backdrop-blur-sm {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
        .hero-image {
            max-width: 80%;
            margin: 0 auto;
        }
        .btn-primary {
            @apply bg-white text-gray-800 hover:bg-gray-100;
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <!-- Header/Navigation -->
    <header class="bg-black/90 backdrop-blur-sm shadow-lg fixed w-full top-0 z-50">
        <nav class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <!-- Logo dan Nama Sekolah -->
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Punggelan" class="h-12 w-auto">
                    <div class="text-white">
                        <h1 class="text-xl font-bold tracking-tight">SMKN 1 Punggelan</h1>
                        <p class="text-xs opacity-90">Sistem Informasi Akademik</p>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <!-- Navigation Links dengan Background Semi-Transparan -->
                    <div class="flex items-center space-x-1 bg-white/5 backdrop-blur-sm px-6 py-2 rounded-full">
                        <a href="#beranda" class="nav-link text-white font-medium hover:text-amber-400 transition-all duration-300 px-4 py-2 rounded-full hover:bg-white/10">
                            Beranda
                        </a>
                        <a href="#jurusan" class="nav-link text-white font-medium hover:text-amber-400 transition-all duration-300 px-4 py-2 rounded-full hover:bg-white/10">
                            Jurusan
                        </a>
                        <a href="#prestasi" class="nav-link text-white font-medium hover:text-amber-400 transition-all duration-300 px-4 py-2 rounded-full hover:bg-white/10">
                            Prestasi
                        </a>
                        <a href="#kontak" class="nav-link text-white font-medium hover:text-amber-400 transition-all duration-300 px-4 py-2 rounded-full hover:bg-white/10">
                            Kontak
                        </a>
                    </div>
                    
                    <!-- Login Button dengan efek hover yang lebih menarik -->
                    <a href="{{ route('filament.admin.auth.login') }}" 
                       class="bg-amber-500 text-white px-6 py-2 rounded-full font-medium 
                              hover:bg-amber-400 transition-all duration-300 
                              flex items-center space-x-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                </div>

                <!-- Mobile Menu Button dengan animasi -->
                <button class="md:hidden text-white focus:outline-none p-2 hover:bg-white/10 rounded-lg transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </nav>

        <!-- Mobile Navigation Menu dengan animasi dan desain yang lebih baik -->
        <div class="md:hidden hidden">
            <div class="bg-black/95 px-4 py-3 space-y-1">
                <a href="#beranda" class="block py-2 px-4 text-white hover:bg-white/10 rounded-lg transition-all duration-300 flex items-center space-x-2">
                    <i class="fas fa-home w-5"></i>
                    <span>Beranda</span>
                </a>
                <a href="#jurusan" class="block py-2 px-4 text-white hover:bg-white/10 rounded-lg transition-all duration-300 flex items-center space-x-2">
                    <i class="fas fa-graduation-cap w-5"></i>
                    <span>Jurusan</span>
                </a>
                <a href="#prestasi" class="block py-2 px-4 text-white hover:bg-white/10 rounded-lg transition-all duration-300 flex items-center space-x-2">
                    <i class="fas fa-trophy w-5"></i>
                    <span>Prestasi</span>
                </a>
                <a href="#kontak" class="block py-2 px-4 text-white hover:bg-white/10 rounded-lg transition-all duration-300 flex items-center space-x-2">
                    <i class="fas fa-envelope w-5"></i>
                    <span>Kontak</span>
                </a>
                <div class="pt-2 mt-2 border-t border-white/10">
                    <a href="{{ route('filament.admin.auth.login') }}" 
                       class="block py-2 px-4 text-white hover:bg-amber-500 rounded-lg transition-all duration-300 flex items-center space-x-2">
                        <i class="fas fa-sign-in-alt w-5"></i>
                        <span>Login</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="beranda" class="hero-pattern min-h-screen flex items-center pt-28 pb-16">
        <div class="container mx-auto px-4 relative">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-white space-y-8" data-aos="fade-right">
                    <div class="space-y-4">
                        <h2 class="text-4xl font-bold leading-tight">
                            Selamat Datang di<br>SMKN 1 Punggelan
                        </h2>
                        <p class="text-lg text-gray-300 leading-relaxed">
                            Membentuk generasi unggul dengan pendidikan berkualitas dan karakter yang kuat untuk masa depan yang lebih baik
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('filament.admin.auth.login') }}" 
                           class="bg-amber-500 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-amber-400 transition-all duration-300 shadow-lg hover:shadow-xl flex items-center space-x-2">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                        <a href="#jurusan" 
                           class="bg-white/10 backdrop-blur-sm text-white border border-white/20 hover:border-amber-400 px-8 py-4 rounded-full text-lg font-semibold hover:bg-amber-500 transition-all duration-300 flex items-center space-x-2">
                            <i class="fas fa-arrow-right"></i>
                            <span>  Next  </span>
                        </a>
                    </div>
                </div>
                <div class="relative" data-aos="fade-left">
                    <img src="{{ asset('images/hero-image.png') }}" 
                         alt="Hero Image" 
                         class="rounded-2xl shadow-2xl w-full hero-image">
                    <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-lg shadow-lg hidden md:block">
                        <div class="flex items-center space-x-2">
                            <div class="bg-green-500 h-3 w-3 rounded-full animate-pulse"></div>
                            <span class="text-gray-600 font-medium"> # SOlID</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Keahlian -->
    <section id="jurusan" class="py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Program Keahlian</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Kami menyediakan berbagai program keahlian yang dirancang untuk mempersiapkan siswa menghadapi dunia kerja modern
                </p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                $jurusan = [
                    [
                        'singkatan' => 'TKRO',
                        'nama' => 'Teknik Kendaraan Ringan Otomotif',
                        'icon' => 'fa-car',
                        'desc' => 'Keahlian dalam perawatan dan perbaikan kendaraan ringan',
                        'delay' => '0'
                    ],
                    [
                        'singkatan' => 'TBO',
                        'nama' => 'Teknik Bodi Otomotif',
                        'icon' => 'fa-tools',
                        'desc' => 'Spesialisasi dalam perbaikan dan finishing bodi kendaraan',
                        'delay' => '100'
                    ],
                    [
                        'singkatan' => 'AKL',
                        'nama' => 'Akuntansi Keuangan dan Lembaga',
                        'icon' => 'fa-calculator',
                        'desc' => 'Pengelolaan keuangan dan akuntansi profesional',
                        'delay' => '200'
                    ],
                    [
                        'singkatan' => 'SIJA',
                        'nama' => 'Sistem Informatika Jaringan dan Aplikasi',
                        'icon' => 'fa-network-wired',
                        'desc' => 'Pengembangan aplikasi dan manajemen jaringan',
                        'delay' => '300'
                    ]
                ];
                @endphp

                @foreach($jurusan as $jur)
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 card-hover border border-gray-100"
                     data-aos="fade-up" 
                     data-aos-delay="{{ $jur['delay'] }}">
                    <div class="text-amber-500 text-4xl mb-6">
                        <i class="fas {{ $jur['icon'] }}"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $jur['singkatan'] }}</h3>
                    <h4 class="text-sm text-amber-500 font-medium mb-4">{{ $jur['nama'] }}</h4>
                    <p class="text-gray-600 leading-relaxed">{{ $jur['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Prestasi -->
    <section id="prestasi" class="py-24 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Prestasi Sekolah</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Pencapaian terbaru siswa kami dalam berbagai kompetisi
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @php
                $prestasi = [
                    [
                        'icon' => 'fa-shield-alt',
                        'title' => 'Juara 3 Cyber Security',
                        'desc' => 'Juara 3 Teknologi Keamanan Siber (Cyber Security) LKS Tingkat Provinsi 2024',
                        'delay' => '0'
                    ],
                    [
                        'icon' => 'fa-code',
                        'title' => 'Juara 1 Web Technologies',
                        'desc' => 'Juara 1 Teknik Desain Laman (Web Technologies) LKS Tingkat Kabupaten Banjarnegara 2024',
                        'delay' => '100'
                    ],
                    [
                        'icon' => 'fa-robot',
                        'title' => 'Juara 3 AI & Design',
                        'desc' => 'Juara 3 Artificial Intelligence dan Teknologi Desain Grafis LKS Tingkat Kabupaten Banjarnegara 2024',
                        'delay' => '200'
                    ]
                ];
                @endphp

                @foreach($prestasi as $pres)
                <div class="bg-white p-8 rounded-2xl shadow-lg card-hover"
                     data-aos="fade-up"
                     data-aos-delay="{{ $pres['delay'] }}">
                    <div class="text-amber-500 text-4xl mb-6">
                        <i class="fas {{ $pres['icon'] }}"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">{{ $pres['title'] }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $pres['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Hubungi Kami</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    Jangan ragu untuk menghubungi kami jika memiliki pertanyaan
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @php
                $contacts = [
                    [
                        'icon' => 'fa-map-marker-alt',
                        'title' => 'Alamat',
                        'info' => 'Jl. Raya Pasar Manis, Loji,<br>Punggelan, Banjarnegara',
                        'delay' => '0'
                    ],
                    [
                        'icon' => 'fa-phone',
                        'title' => 'Telepon',
                        'info' => '08112517414',
                        'delay' => '100'
                    ],
                    [
                        'icon' => 'fa-envelope',
                        'title' => 'Email',
                        'info' => 'smkn1_pgl@yahoo.com',
                        'delay' => '200'
                    ]
                ];
                @endphp

                @foreach($contacts as $contact)
                <div class="text-center" data-aos="fade-up" data-aos-delay="{{ $contact['delay'] }}">
                    <div class="bg-amber-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas {{ $contact['icon'] }} text-amber-500 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">{{ $contact['title'] }}</h3>
                    <p class="text-gray-600">{!! $contact['info'] !!}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-12 mb-12">
                <div>
                    <div class="flex items-center space-x-4 mb-6">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto">
                        <div>
                            <h4 class="text-lg font-semibold">SMKN 1 Punggelan</h4>
                            <p class="text-gray-400 text-sm">Sistem Informasi Akademik</p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed">
                        SMKN 1 Punggelan adalah sekolah kejuruan unggulan yang berkomitmen menghasilkan lulusan berkualitas dan siap kerja.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6">Link Cepat</h4>
                    <ul class="space-y-3">
                        <li><a href="#beranda" class="text-gray-400 hover:text-white transition-all duration-300">Beranda</a></li>
                        <li><a href="#jurusan" class="text-gray-400 hover:text-white transition-all duration-300">Program Keahlian</a></li>
                        <li><a href="#prestasi" class="text-gray-400 hover:text-white transition-all duration-300">Prestasi</a></li>
                        <li><a href="#kontak" class="text-gray-400 hover:text-white transition-all duration-300">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-6">Media Sosial</h4>
                    <div class="flex space-x-4">
                        <a href="https://www.instagram.com/smkn1punggelan_official/" 
                           class="bg-gray-700 w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all duration-300"
                           target="_blank">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" 
                           class="bg-gray-700 w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all duration-300">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" 
                           class="bg-gray-700 w-12 h-12 rounded-full flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all duration-300">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-8 text-center">
                <p class="text-gray-400">Copyright © {{ date('Y') }} SMKN 1 Punggelan. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Mobile menu toggle
        document.querySelector('button').addEventListener('click', function() {
            document.querySelector('.md\\:hidden.hidden').classList.toggle('hidden');
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                const headerOffset = 80;
                const elementPosition = target.offsetTop;
                const offsetPosition = elementPosition - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Close mobile menu if open
                if (!document.querySelector('.md\\:hidden.hidden').classList.contains('hidden')) {
                    document.querySelector('.md\\:hidden.hidden').classList.add('hidden');
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('bg-opacity-95', 'backdrop-blur-sm');
            } else {
                header.classList.remove('bg-opacity-95', 'backdrop-blur-sm');
            }
        });
    </script>
</body>
</html> 