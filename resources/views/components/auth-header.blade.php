@props([
    'title',
    'description',
])

<div class="flex flex-col gap-3 text-center mb-10">
    <h1 class="text-3xl font-extrabold font-display text-slate-900 dark:text-white tracking-tight">
        {{ $title }}
    </h1>
    <p class="text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
        {{ $description }}
    </p>
</div>
