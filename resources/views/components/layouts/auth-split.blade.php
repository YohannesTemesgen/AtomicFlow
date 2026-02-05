<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @include('partials.head')
        <style>
            /* Layout specific overrides */
            body {
                overflow: hidden; /* Match register.html */
            }
        </style>
    </head>
    <body class="font-sans antialiased h-screen overflow-hidden bg-white text-text-main">
        <div class="flex h-full w-full">
            <!-- Left Side: Gradient Mesh & Content -->
            <div class="hidden lg:flex lg:w-1/2 gradient-mesh p-16 flex-col justify-between relative overflow-hidden">
                <div class="absolute top-1/4 -right-12 w-64 h-64 bg-primary/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-1/4 -left-12 w-48 h-48 bg-secondary/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-12">
                        <div class="size-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined !text-2xl">folder_managed</span>
                        </div>
                        <h2 class="text-2xl font-extrabold tracking-tight font-display text-white">Pro-<span class="text-primary">File</span></h2>
                    </div>
                    <h1 class="text-5xl font-extrabold font-display leading-[1.1] text-white max-w-md">
                        Automate Your <br/> <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Design Pipeline.</span>
                    </h1>
                    <p class="mt-6 text-slate-400 text-lg max-w-sm">
                        Join 500+ agencies automating their web-based asset delivery with secure, multi-tenant Laravel power.
                    </p>
                </div>

                <div class="relative z-10">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-8 rounded-[2.5rem] max-w-md shadow-2xl">
                        <div class="flex items-center gap-1 text-yellow-400 mb-4">
                            <span class="material-symbols-outlined !text-sm">star</span>
                            <span class="material-symbols-outlined !text-sm">star</span>
                            <span class="material-symbols-outlined !text-sm">star</span>
                            <span class="material-symbols-outlined !text-sm">star</span>
                            <span class="material-symbols-outlined !text-sm">star</span>
                        </div>
                        <p class="text-white text-lg font-medium leading-relaxed mb-6">
                            "Pro-File is the missing link in our design-to-dev workflow. The auto-extraction and in-browser previews save us hours every week."
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="size-12 rounded-full border-2 border-primary overflow-hidden">
                                <img alt="User" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-9TjzIHonAViFqSCXyF7RkyYlNC8VQNlTa57R0UsmnmK9uHGGuFU_NLdHpU6qV070l_e8XcSNn-rQIwf1F_PmjVb0229hIwPRs8sO_9fGFCOPq03_gnAW_efIybqNda3Byzakd2wM_0zyFR_qvK6hrT699bPiSJW3MFAHZA5-YVJUp-FAsPMvT5GuORihodolQEp0CC_UpFV0yjDlTnDflwOogc32uu-wrFV-YLJLhJ3RdDq-BRDNRlDkS1_v2R5Ukpp35Gy9wMM"/>
                            </div>
                            <div>
                                <div class="font-bold text-white">Sarah Chen</div>
                                <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Creative Lead @ Vivid</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 flex items-center gap-8 opacity-40 grayscale contrast-200">
                        <div class="text-white font-black text-xl">VIVID</div>
                        <div class="text-white font-black text-xl">SPARK</div>
                        <div class="text-white font-black text-xl">ORBITAL</div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form Content -->
            <div class="w-full lg:w-1/2 bg-white flex items-center justify-center p-8 overflow-y-auto">
                <div class="w-full max-w-[440px] flex flex-col gap-8 py-12">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @fluxScripts
    </body>
</html>