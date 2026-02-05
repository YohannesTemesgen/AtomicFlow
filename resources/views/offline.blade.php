<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>Offline - AtomicFlow</title>
    <meta name="theme-color" content="#3B82F6"/>
    <link rel="manifest" href="/manifest.json"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3B82F6",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                },
            },
        };
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-display bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md mx-auto">
        <div class="float-animation mb-8">
            <svg class="w-32 h-32 mx-auto text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a5 5 0 01-1.414-7.072m4.243-4.243L9.879 6.879M3 3l18 18"/>
            </svg>
        </div>
        
        <h1 class="text-3xl font-bold text-white mb-4">You're Offline</h1>
        <p class="text-gray-400 mb-8 text-lg">It looks like you've lost your internet connection. Please check your network and try again.</p>
        
        <div class="space-y-4">
            <button onclick="window.location.reload()" class="w-full bg-primary hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Try Again
            </button>
            
            <div class="bg-gray-800/50 backdrop-blur rounded-xl p-6 border border-gray-700">
                <h3 class="text-white font-semibold mb-3">While you're offline:</h3>
                <ul class="text-gray-400 text-sm space-y-2 text-left">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Previously viewed pages may still be available
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Your changes will sync when you're back online
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Some features require an internet connection
                    </li>
                </ul>
            </div>
        </div>
        
        <p class="text-gray-500 text-sm mt-8">
            <span id="online-status" class="inline-flex items-center gap-1">
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                Currently offline
            </span>
        </p>
    </div>

    <script>
        window.addEventListener('online', () => {
            window.location.reload();
        });
        
        window.addEventListener('offline', () => {
            document.getElementById('online-status').innerHTML = `
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                Currently offline
            `;
        });
    </script>
</body>
</html>
