<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* Custom CSS for decorative shapes and responsive adjustments */
            .decorative-shape-1 { /* Üst-sağ: Büyük turuncu circle (200px) */
                background: #ffb366; /* accent-orange */
                width: 200px;
                height: 200px;
                border-radius: 50%;
                position: absolute;
                top: 10%;
                right: 15%;
                opacity: 0.8;
                filter: blur(20px);
            }
            .decorative-shape-2 { /* Üst-orta: Mavi-mor büyük circle (yarım görünür, 250px) */
                background: linear-gradient(135deg, #6b73ff 0%, #764ba2 100%); /* accent-blue-purple to primary-gradient-end */
                width: 250px;
                height: 250px;
                border-radius: 50%;
                position: absolute;
                top: -50px; /* Yarım görünür */
                left: 40%;
                opacity: 0.7;
                filter: blur(25px);
            }
            .decorative-shape-3 { /* Orta-sol: Pembe curved shape (150x100px) */
                background: #ff7b7b; /* accent-coral */
                width: 150px;
                height: 100px;
                border-radius: 50px / 25px; /* Oval şekil */
                position: absolute;
                top: 50%;
                left: 10%;
                transform: translateY(-50%) rotate(-20deg);
                opacity: 0.6;
                filter: blur(15px);
            }
            .decorative-shape-4 { /* Alt-orta: Turquoise half-circle (180px) */
                background: #4ecdc4; /* accent-turquoise */
                width: 180px;
                height: 90px; /* Yarım daire */
                border-bottom-left-radius: 180px;
                border-bottom-right-radius: 180px;
                position: absolute;
                bottom: 10%;
                left: 30%;
                opacity: 0.7;
                filter: blur(20px);
            }
            .decorative-shape-5 { /* Sağ-alt: Mor triangle (120px) */
                background: linear-gradient(135deg, #764ba2 0%, #c3a6ff 100%); /* primary-gradient-end to accent-lavender */
                width: 0;
                height: 0;
                border-left: 60px solid transparent;
                border-right: 60px solid transparent;
                border-bottom: 100px solid currentColor; /* Renk Tailwind tarafından yönetilecek */
                position: absolute;
                bottom: 20%;
                right: 10%;
                opacity: 0.5;
                transform: rotate(45deg);
                filter: blur(10px);
            }
            .decorative-shape-6 { /* Sol-üst kısmi: Açık mor shape (köşede kesik) */
                background: #c3a6ff; /* accent-lavender */
                width: 100px;
                height: 100px;
                border-top-left-radius: 50px;
                position: absolute;
                top: 0;
                left: 0;
                opacity: 0.4;
                filter: blur(10px);
            }

            @media (max-width: 768px) {
                .split-screen {
                    flex-direction: column;
                }
                .left-panel, .right-panel {
                    width: 100%;
                    padding: 40px 20px;
                }
                .decorative-shapes-container {
                    display: none; /* Mobile'da dekoratif şekilleri gizle */
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-neutral-white min-h-screen flex split-screen">
        <!-- Left Panel -->
        <div class="w-2/5 p-20 flex flex-col justify-center bg-neutral-white left-panel">
            {{ $slot }}
        </div>

        <!-- Right Panel -->
        <div class="w-3/5 relative overflow-hidden flex items-center justify-center bg-neutral-light-gray right-panel">
            <!-- Decorative Shapes -->
            <div class="decorative-shapes-container">
                <div class="decorative-shape-1"></div>
                <div class="decorative-shape-2"></div>
                <div class="decorative-shape-3"></div>
                <div class="decorative-shape-4"></div>
                <div class="decorative-shape-5" style="border-bottom-color: #764ba2;"></div> <!-- primary-gradient-end -->
                <div class="decorative-shape-6"></div>
            </div>

            <h2 class="text-neutral-dark-gray text-6xl font-extrabold leading-tight font-sans z-10 text-center">
                Changing the way <br> the world writes
            </h2>
        </div>
    </body>
</html>
