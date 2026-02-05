<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.theme-script')
    <meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Atomic Flow - Work Happier Together</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Inter:wght@400;500&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#4facfe",
                        "secondary": "#ff9a9e",
                        "accent-blue": "#E0F2FE",
                        "accent-peach": "#FFF1F2",
                        "text-main": "#334155",
                    },
                    fontFamily: {
                        "display": ["Plus Jakarta Sans", "sans-serif"],
                        "sans": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "1rem",
                        "xl": "1.5rem",
                        "2xl": "2rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style type="text/tailwindcss">
        @layer base {
            body { @apply text-text-main; }
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .blob-bg {
            background-image: radial-gradient(circle at 20% 30%, rgba(79, 172, 254, 0.15) 0%, transparent 50%),
                              radial-gradient(circle at 80% 70%, rgba(255, 154, 158, 0.15) 0%, transparent 50%);
        }
        .card-hover { @apply transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary/10; }
    </style>
</head>
<body class="font-sans bg-white dark:bg-[#0f172a] antialiased overflow-x-hidden">
<header class="sticky top-0 z-50 w-full bg-white/70 dark:bg-[#0f172a]/70 backdrop-blur-xl border-b border-accent-blue dark:border-slate-800">
<div class="px-6 md:px-10 py-4 flex items-center justify-between max-w-[1280px] mx-auto">
<div class="flex items-center gap-3">
<div class="size-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/30">
<span class="material-symbols-outlined !text-2xl">bubble_chart</span>
</div>
<h2 class="text-xl font-extrabold tracking-tight font-display text-slate-900 dark:text-white">Atomic <span class="text-primary">Flow</span></h2>
</div>
<nav class="hidden lg:flex flex-1 justify-center gap-10">
<a class="text-sm font-semibold hover:text-primary transition-colors text-slate-600 dark:text-slate-300" href="#">Product</a>
<a class="text-sm font-semibold hover:text-primary transition-colors text-slate-600 dark:text-slate-300" href="#">Templates</a>
<a class="text-sm font-semibold hover:text-primary transition-colors text-slate-600 dark:text-slate-300" href="#">Success Stories</a>
<a class="text-sm font-semibold hover:text-primary transition-colors text-slate-600 dark:text-slate-300" href="#">Pricing</a>
</nav>
<div class="flex items-center gap-6">
            <button onclick="themeManager.toggle()" class="p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 transition-colors focus:outline-hidden" title="Toggle Theme">
                <span class="material-symbols-outlined dark:hidden">dark_mode</span>
                <span class="material-symbols-outlined hidden dark:block">light_mode</span>
            </button>
            <a class="text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-primary" href="{{ route('login') }}">Login</a>
<a href="{{ route('register') }}" class="bg-primary text-white text-sm font-extrabold px-6 py-2.5 rounded-full hover:scale-105 transition-transform shadow-md shadow-primary/20">
                Start for Free
            </a>
</div>
</div>
</header>
<main class="flex flex-col items-center w-full">
<section class="w-full relative overflow-hidden blob-bg pt-12 md:pt-20 lg:pt-28 pb-20">
<div class="max-w-[1280px] mx-auto px-6 md:px-10">
<div class="flex flex-col lg:flex-row items-center gap-16">
<div class="flex flex-col gap-8 flex-1 text-center lg:text-left">
<div class="inline-flex self-center lg:self-start items-center gap-2 bg-accent-peach px-4 py-2 rounded-full border border-secondary/20">
<span class="text-secondary text-sm font-bold">New: AI Auto-Flow ✨</span>
</div>
<h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold font-display leading-[1.1] text-slate-900 dark:text-white">
                        Your Work, Just <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Friendlier.</span>
</h1>
<p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 font-medium leading-relaxed max-w-xl mx-auto lg:mx-0">
                        The playful Kanban board that teams actually love using. Say goodbye to stiff spreadsheets and hello to seamless collaboration.
                    </p>
<div class="flex flex-wrap gap-4 justify-center lg:justify-start">
<a href="{{ route('register') }}" class="bg-primary text-white font-bold text-lg px-8 py-4 rounded-2xl shadow-xl shadow-primary/30 hover:-translate-y-1 transition-all">
                            Create Your Board
                        </a>
<button class="bg-white dark:bg-slate-800 text-slate-700 dark:text-white font-bold text-lg px-8 py-4 rounded-2xl border-2 border-slate-100 dark:border-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2">
<span class="material-symbols-outlined !text-primary">play_circle</span>
                            See it in Action
                        </button>
</div>
</div>
<div class="flex-1 w-full relative group">
<div class="absolute -inset-4 bg-gradient-to-tr from-primary/20 to-secondary/20 rounded-[3rem] blur-2xl group-hover:blur-3xl transition-all"></div>
<div class="relative bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 shadow-2xl border border-slate-100 dark:border-slate-700">
<div class="w-full aspect-[4/3] rounded-2xl overflow-hidden bg-center bg-cover border-4 border-accent-blue" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBX9S8RHi2e69uioFrtEeCjZyDzZwicAtoqCNM6m8aqGE23_kCBxTaLPWIudK4YjSBqiA0C32LeH5biL_2Hfqxh5qnKLb0nSZJa3cgqxFOlUxCyscJjbXYYEuvMbZ23PYUASM5torWzoYnOmNsbsSAX_97t_PDeuQjxX2Pxf9sI-0VtfwFGxzLRzugxlr0tF36mZFhvLtV2Zt0LP76aas-Y5jNO_2HHZk_knU0yE4pdDMglSgNvVDZlaF7gFE-4DskdhC5nEKvwnPg");'>
<div class="w-full h-full bg-slate-900/10 backdrop-blur-[2px] flex items-center justify-center">
<div class="bg-white/90 p-8 rounded-3xl shadow-2xl flex flex-col items-center gap-4 text-center max-w-[80%] border border-primary/20">
<span class="material-symbols-outlined !text-7xl text-primary">groups_3</span>
<h3 class="font-display font-bold text-xl">Teams collaborating seamlessly</h3>
<p class="text-sm text-slate-600">Drag. Drop. Done. All in one beautiful place.</p>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<section class="w-full py-12 border-y border-slate-100 dark:border-slate-800 bg-slate-50/50">
<div class="max-w-[1280px] mx-auto px-6 text-center">
<p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-8">Loved by teams from 5 to 5,000</p>
<div class="flex flex-wrap justify-center items-center gap-12 md:gap-24 opacity-50 grayscale contrast-125">
<div class="flex items-center gap-2 font-black text-2xl"><span class="material-symbols-outlined !text-3xl">filter_vintage</span> FLOWER</div>
<div class="flex items-center gap-2 font-black text-2xl"><span class="material-symbols-outlined !text-3xl">pentagon</span> PENTA</div>
<div class="flex items-center gap-2 font-black text-2xl"><span class="material-symbols-outlined !text-3xl">rocket_launch</span> ORBIT</div>
<div class="flex items-center gap-2 font-black text-2xl"><span class="material-symbols-outlined !text-3xl">eco</span> BIO</div>
</div>
</div>
</section>
<section class="w-full max-w-[1280px] px-6 md:px-10 py-24">
<div class="text-center mb-16 flex flex-col gap-4">
<h2 class="text-4xl md:text-5xl font-extrabold font-display text-slate-900 dark:text-white">Workflow as simple as <span class="text-secondary">magic.</span></h2>
<p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">Getting started is easier than making coffee. Three steps to total team clarity.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
<div class="flex flex-col items-center text-center gap-6 group">
<div class="size-24 rounded-full bg-accent-blue flex items-center justify-center text-primary relative z-10 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined !text-4xl">add_task</span>
<div class="absolute -top-2 -right-2 size-8 bg-white border-2 border-accent-blue rounded-full flex items-center justify-center font-bold text-slate-800 shadow-sm">1</div>
</div>
<div class="flex flex-col gap-2">
<h3 class="text-xl font-bold font-display">Create Your Space</h3>
<p class="text-slate-500">Pick a template or start from scratch. Customize columns to match your team's unique rhythm.</p>
</div>
</div>
<div class="flex flex-col items-center text-center gap-6 group">
<div class="size-24 rounded-full bg-accent-peach flex items-center justify-center text-secondary relative z-10 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined !text-4xl">group_add</span>
<div class="absolute -top-2 -right-2 size-8 bg-white border-2 border-accent-peach rounded-full flex items-center justify-center font-bold text-slate-800 shadow-sm">2</div>
</div>
<div class="flex flex-col gap-2">
<h3 class="text-xl font-bold font-display">Invite the Crew</h3>
<p class="text-slate-500">Add team members with one link. Assign tasks and start chatting right on the cards.</p>
</div>
</div>
<div class="flex flex-col items-center text-center gap-6 group">
<div class="size-24 rounded-full bg-green-100 flex items-center justify-center text-green-500 relative z-10 group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined !text-4xl">celebration</span>
<div class="absolute -top-2 -right-2 size-8 bg-white border-2 border-green-100 rounded-full flex items-center justify-center font-bold text-slate-800 shadow-sm">3</div>
</div>
<div class="flex flex-col gap-2">
<h3 class="text-xl font-bold font-display">Flow to Victory</h3>
<p class="text-slate-500">Watch cards move from "To-Do" to "Done". Celebrate every win with built-in team rewards.</p>
</div>
</div>
</div>
</section>
<section class="w-full bg-accent-blue/30 py-24">
<div class="max-w-[1280px] mx-auto px-6 md:px-10">
<div class="flex flex-col lg:flex-row gap-16 items-center">
<div class="flex-1 flex flex-col gap-6">
<h2 class="text-4xl font-extrabold font-display leading-tight">Everything you need, <br/><span class="text-primary">none of the clutter.</span></h2>
<div class="space-y-4">
<div class="flex gap-4 p-4 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-primary/10">
<span class="material-symbols-outlined text-primary">analytics</span>
<div>
<h4 class="font-bold">Real-time Happiness Analytics</h4>
<p class="text-sm text-slate-500">Measure team sentiment, not just tickets closed.</p>
</div>
</div>
<div class="flex gap-4 p-4 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-secondary/10">
<span class="material-symbols-outlined text-secondary">extension</span>
<div>
<h4 class="font-bold">100+ App Integrations</h4>
<p class="text-sm text-slate-500">Connect with Slack, GitHub, and Figma in a click.</p>
</div>
</div>
</div>
</div>
<div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
<div class="p-8 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 card-hover">
<span class="material-symbols-outlined !text-4xl text-primary mb-4">view_kanban</span>
<h3 class="text-lg font-bold mb-2">Visual Workflow</h3>
<p class="text-slate-500 text-sm">Drag and drop tasks with buttery smooth performance.</p>
</div>
<div class="p-8 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 card-hover">
<span class="material-symbols-outlined !text-4xl text-orange-400 mb-4">speed</span>
<h3 class="text-lg font-bold mb-2">Cycle Time</h3>
<p class="text-slate-500 text-sm">Know exactly how fast your ideas become reality.</p>
</div>
<div class="p-8 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 card-hover">
<span class="material-symbols-outlined !text-4xl text-green-400 mb-4">security</span>
<h3 class="text-lg font-bold mb-2">Enterprise Grade</h3>
<p class="text-slate-500 text-sm">Secure, private, and built for scaling teams.</p>
</div>
<div class="p-8 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-700 card-hover">
<span class="material-symbols-outlined !text-4xl text-secondary mb-4">auto_fix_high</span>
<h3 class="text-lg font-bold mb-2">Smart Rules</h3>
<p class="text-slate-500 text-sm">Automate the boring stuff and stay in the flow.</p>
</div>
</div>
</div>
</div>
</section>
<section class="w-full max-w-[1280px] px-6 md:px-10 py-24">
<div class="text-center mb-16">
<h2 class="text-4xl font-extrabold font-display">Stories from the <span class="text-primary">Flow State</span></h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="bg-accent-peach/50 p-8 rounded-[2rem] border border-secondary/10 flex flex-col gap-6">
<div class="flex items-center gap-2 opacity-60">
<span class="material-symbols-outlined text-2xl">diamond</span>
<span class="font-bold">LUMINA</span>
</div>
<p class="text-lg font-medium text-slate-800 italic">"We tried every tool under the sun. Atomic Flow is the only one that didn't feel like a chore to update."</p>
<div class="flex items-center gap-4 mt-auto">
<div class="size-12 rounded-full overflow-hidden bg-slate-200" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD-9TjzIHonAViFqSCXyF7RkyYlNC8VQNlTa57R0UsmnmK9uHGGuFU_NLdHpU6qV070l_e8XcSNn-rQIwf1F_PmjVb0229hIwPRs8sO_9fGFCOPq03_gnAW_efIybqNda3Byzakd2wM_0zyFR_qvK6hrT699bPiSJW3MFAHZA5-YVJUp-FAsPMvT5GuORihodolQEp0CC_UpFV0yjDlTnDflwOogc32uu-wrFV-YLJLhJ3RdDq-BRDNRlDkS1_v2R5Ukpp35Gy9wMM"); background-size: cover;'></div>
<div>
<div class="font-bold text-sm">Sarah Jenkins</div>
<div class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Product Design</div>
</div>
</div>
</div>
<div class="bg-accent-blue/50 p-8 rounded-[2rem] border border-primary/10 flex flex-col gap-6 scale-105 shadow-xl shadow-primary/5">
<div class="flex items-center gap-2 opacity-60">
<span class="material-symbols-outlined text-2xl">bolt</span>
<span class="font-bold">QUICKIE</span>
</div>
<p class="text-lg font-medium text-slate-800 italic">"Our engineering team is shipping 40% faster. The visual limits help us focus on what actually matters."</p>
<div class="flex items-center gap-4 mt-auto">
<div class="size-12 rounded-full bg-slate-400 flex items-center justify-center text-white">MK</div>
<div>
<div class="font-bold text-sm">Marcus King</div>
<div class="text-xs text-slate-500 uppercase tracking-wider font-semibold">CTO @ TechScale</div>
</div>
</div>
</div>
<div class="bg-green-50/50 p-8 rounded-[2rem] border border-green-200/30 flex flex-col gap-6">
<div class="flex items-center gap-2 opacity-60">
<span class="material-symbols-outlined text-2xl">change_history</span>
<span class="font-bold">CREATIVE</span>
</div>
<p class="text-lg font-medium text-slate-800 italic">"The user experience is just... happy. It sounds silly, but it makes the hard days feel a bit lighter."</p>
<div class="flex items-center gap-4 mt-auto">
<div class="size-12 rounded-full bg-slate-300 flex items-center justify-center text-white font-bold">AL</div>
<div>
<div class="font-bold text-sm">Aisha Lee</div>
<div class="text-xs text-slate-500 uppercase tracking-wider font-semibold">HR Director</div>
</div>
</div>
</div>
</div>
</section>
<section class="w-full max-w-[1280px] px-6 md:px-10 pb-24">
<div class="rounded-[3rem] bg-gradient-to-br from-primary to-[#86daff] p-12 md:p-20 text-center flex flex-col items-center gap-10 shadow-2xl shadow-primary/40 relative overflow-hidden">
<div class="absolute -top-20 -left-20 size-80 bg-white/20 rounded-full blur-[80px]"></div>
<div class="absolute -bottom-20 -right-20 size-80 bg-secondary/30 rounded-full blur-[80px]"></div>
<div class="relative z-10 max-w-2xl">
<h2 class="text-4xl md:text-6xl font-extrabold text-white font-display mb-6">Ready to find your flow?</h2>
<p class="text-white/90 text-xl font-medium">Join 10,000+ teams who prioritize people and progress over process.</p>
</div>
<div class="relative z-10 flex flex-col sm:flex-row gap-6">
<a href="{{ route('register') }}" class="bg-white text-primary hover:bg-slate-50 font-extrabold py-5 px-12 rounded-2xl transition-all shadow-xl hover:-translate-y-1 text-lg">
                    Get Started Free
                </a>
<button class="bg-primary/20 border-2 border-white/50 text-white hover:bg-white/10 font-extrabold py-5 px-12 rounded-2xl transition-all text-lg backdrop-blur-sm">
                    Talk to Sales
                </button>
</div>
<p class="relative z-10 text-white/70 text-sm font-bold flex items-center gap-2">
<span class="material-symbols-outlined !text-sm">verified</span>
                No credit card required • 14-day premium trial
            </p>
</div>
</section>
</main>
<footer class="w-full bg-slate-50 dark:bg-[#0f172a] border-t border-slate-200 dark:border-slate-800 pt-20 pb-12">
<div class="max-w-[1280px] mx-auto px-6 md:px-10">
<div class="grid grid-cols-2 lg:grid-cols-5 gap-12 mb-20">
<div class="col-span-2 flex flex-col gap-6">
<div class="flex items-center gap-3">
<div class="size-8 bg-primary rounded-lg flex items-center justify-center text-white">
<span class="material-symbols-outlined !text-xl">bubble_chart</span>
</div>
<h2 class="text-xl font-extrabold tracking-tight font-display text-slate-900 dark:text-white">Atomic Flow</h2>
</div>
<p class="text-slate-500 dark:text-slate-400 max-w-xs leading-relaxed">Making work visible and teams happier, one card at a time. Designed with love in San Francisco.</p>
<div class="flex gap-4">
<a class="size-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined !text-xl">public</span></a>
<a class="size-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined !text-xl">terminal</span></a>
<a class="size-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-primary transition-colors" href="#"><span class="material-symbols-outlined !text-xl">chat</span></a>
</div>
</div>
<div class="flex flex-col gap-5">
<h4 class="font-bold text-slate-900 dark:text-white">Product</h4>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Templates</a>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Mobile Apps</a>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Integrations</a>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Security</a>
</div>
<div class="flex flex-col gap-5">
<h4 class="font-bold text-slate-900 dark:text-white">Learn</h4>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Guide to Kanban</a>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Best Practices</a>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Community</a>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Blog</a>
</div>
<div class="flex flex-col gap-5">
<h4 class="font-bold text-slate-900 dark:text-white">Company</h4>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">About Us</a>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Careers</a>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Press Kit</a>
<a class="text-sm text-slate-600 dark:text-slate-400 hover:text-primary transition-colors" href="#">Contact</a>
</div>
</div>
<div class="pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-6">
<p class="text-sm text-slate-400">© 2024 Atomic Flow Inc. All rights reserved.</p>
<div class="flex gap-8">
<a class="text-xs font-bold text-slate-400 hover:text-slate-900" href="#">Privacy Policy</a>
<a class="text-xs font-bold text-slate-400 hover:text-slate-900" href="#">Terms of Service</a>
<a class="text-xs font-bold text-slate-400 hover:text-slate-900" href="#">Cookie Policy</a>
</div>
</div>
</div>
</footer>

</body></html>
