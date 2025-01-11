<x-filament-panels::page.simple>
    <div class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-purple-950 to-gray-900">
        <!-- Animated background -->
        <div class="absolute inset-0 overflow-hidden">
            <div id="stars"></div>
            <div id="stars2"></div>
            <div id="stars3"></div>
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/50 to-transparent"></div>

        <!-- Content -->
        <div class="relative w-full max-w-[24rem] p-4">
            <!-- Card -->
            <div class="w-full rounded-xl bg-black/40 backdrop-blur-xl border border-white/10 overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-6 text-center bg-black/20 space-y-3">
                    <h2 class="text-base font-medium text-white/80">SMKN 1 Punggelan</h2>
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-purple-700 to-purple-900 ring-2 ring-purple-500/20">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 object-contain">
                    </div>
                    <div class="space-y-1">
                        <h1 class="text-xl font-bold text-white">SISTEM INFORMASI</h1>
                        <p class="text-sm text-white/70">SMKN 1 Punggelan</p>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-6 space-y-6 bg-black/40">
                    {{ $this->form }}

                    <x-filament::button
                        type="submit"
                        form="mountedActionForm"
                        class="w-full bg-gradient-to-r from-purple-700 to-purple-900 hover:from-purple-800 hover:to-purple-950 text-white font-medium py-2.5 border border-purple-500/20"
                    >
                        Masuk
                    </x-filament::button>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center space-y-2">
                <p class="text-sm text-white/50">© 2024 SMKN 1 Punggelan. All rights reserved.</p>
                <p class="text-xs text-white/30">Developed with ❤️ by <a href="https://github.com/idiarso" class="hover:text-purple-400 transition-colors">@idiarso</a></p>
            </div>
        </div>
    </div>

    <style>
        /* Reset default input styles */
        .fi-input {
            all: unset;
            display: block !important;
            width: 100% !important;
            padding: 0.75rem 1rem !important;
            border-radius: 0.5rem !important;
            background-color: rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            transition: all 0.2s ease-in-out !important;
        }

        .fi-input:focus {
            border-color: rgba(147, 51, 234, 0.5) !important;
            box-shadow: 0 0 0 1px rgba(147, 51, 234, 0.1) !important;
            background-color: rgba(0, 0, 0, 0.3) !important;
        }

        .fi-input::placeholder {
            color: rgba(255, 255, 255, 0.3) !important;
        }

        /* Label styles */
        .fi-fo-field-wrp label {
            color: rgba(255, 255, 255, 0.7) !important;
            font-size: 0.875rem !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }

        /* Checkbox styles */
        .fi-checkbox {
            border-color: rgba(255, 255, 255, 0.2) !important;
            background-color: rgba(0, 0, 0, 0.2) !important;
        }

        .fi-checkbox:checked {
            background-color: rgb(147, 51, 234) !important;
            border-color: rgb(147, 51, 234) !important;
        }

        .fi-checkbox-label {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        /* Animated background */
        #stars {
            width: 1px;
            height: 1px;
            background: transparent;
            box-shadow: 831px 945px #FFF , 1795px 352px #FFF , 1064px 1843px #FFF , 605px 1938px #FFF , 1960px 1069px #FFF , 1728px 405px #FFF , 1325px 1929px #FFF , 1242px 1244px #FFF , 1181px 1708px #FFF , 55px 1921px #FFF , 1561px 1859px #FFF , 1911px 1202px #FFF , 1865px 1438px #FFF , 553px 1907px #FFF , 1920px 279px #FFF , 1093px 1607px #FFF , 1687px 1654px #FFF , 1311px 1570px #FFF , 1615px 1651px #FFF , 1329px 1640px #FFF;
            animation: animStar 50s linear infinite;
        }

        #stars2 {
            width: 2px;
            height: 2px;
            background: transparent;
            box-shadow: 1558px 985px #FFF , 1076px 1252px #FFF , 1440px 1426px #FFF , 242px 1897px #FFF , 1199px 1897px #FFF , 1427px 1282px #FFF , 1747px 1060px #FFF , 1890px 1453px #FFF , 1313px 1636px #FFF , 1591px 1414px #FFF , 1001px 1570px #FFF , 908px 1810px #FFF , 1654px 1764px #FFF , 1587px 1860px #FFF , 353px 1352px #FFF , 1576px 1370px #FFF , 1244px 1872px #FFF , 1259px 1911px #FFF , 1475px 1937px #FFF , 1950px 1080px #FFF;
            animation: animStar 100s linear infinite;
        }

        #stars3 {
            width: 3px;
            height: 3px;
            background: transparent;
            box-shadow: 1558px 985px #FFF , 1076px 1252px #FFF , 1440px 1426px #FFF , 242px 1897px #FFF , 1199px 1897px #FFF , 1427px 1282px #FFF , 1747px 1060px #FFF , 1890px 1453px #FFF , 1313px 1636px #FFF , 1591px 1414px #FFF , 1001px 1570px #FFF , 908px 1810px #FFF , 1654px 1764px #FFF , 1587px 1860px #FFF , 353px 1352px #FFF , 1576px 1370px #FFF , 1244px 1872px #FFF , 1259px 1911px #FFF , 1475px 1937px #FFF , 1950px 1080px #FFF;
            animation: animStar 150s linear infinite;
        }

        @keyframes animStar {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-2000px);
            }
        }

        #stars:after {
            content: " ";
            position: absolute;
            top: 2000px;
            width: 1px;
            height: 1px;
            background: transparent;
            box-shadow: 831px 945px #FFF , 1795px 352px #FFF , 1064px 1843px #FFF , 605px 1938px #FFF , 1960px 1069px #FFF , 1728px 405px #FFF , 1325px 1929px #FFF , 1242px 1244px #FFF , 1181px 1708px #FFF , 55px 1921px #FFF , 1561px 1859px #FFF , 1911px 1202px #FFF , 1865px 1438px #FFF , 553px 1907px #FFF , 1920px 279px #FFF , 1093px 1607px #FFF , 1687px 1654px #FFF , 1311px 1570px #FFF , 1615px 1651px #FFF , 1329px 1640px #FFF;
        }

        #stars2:after {
            content: " ";
            position: absolute;
            top: 2000px;
            width: 2px;
            height: 2px;
            background: transparent;
            box-shadow: 1558px 985px #FFF , 1076px 1252px #FFF , 1440px 1426px #FFF , 242px 1897px #FFF , 1199px 1897px #FFF , 1427px 1282px #FFF , 1747px 1060px #FFF , 1890px 1453px #FFF , 1313px 1636px #FFF , 1591px 1414px #FFF , 1001px 1570px #FFF , 908px 1810px #FFF , 1654px 1764px #FFF , 1587px 1860px #FFF , 353px 1352px #FFF , 1576px 1370px #FFF , 1244px 1872px #FFF , 1259px 1911px #FFF , 1475px 1937px #FFF , 1950px 1080px #FFF;
        }

        #stars3:after {
            content: " ";
            position: absolute;
            top: 2000px;
            width: 3px;
            height: 3px;
            background: transparent;
            box-shadow: 1558px 985px #FFF , 1076px 1252px #FFF , 1440px 1426px #FFF , 242px 1897px #FFF , 1199px 1897px #FFF , 1427px 1282px #FFF , 1747px 1060px #FFF , 1890px 1453px #FFF , 1313px 1636px #FFF , 1591px 1414px #FFF , 1001px 1570px #FFF , 908px 1810px #FFF , 1654px 1764px #FFF , 1587px 1860px #FFF , 353px 1352px #FFF , 1576px 1370px #FFF , 1244px 1872px #FFF , 1259px 1911px #FFF , 1475px 1937px #FFF , 1950px 1080px #FFF;
        }
    </style>
</x-filament-panels::page.simple> 