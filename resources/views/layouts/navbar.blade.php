<div class="sticky top-0 z-10  w-full h-20 bg-sky-900/90 text-white px-10 flex justify-between items-center">
    <div class="flex items-center gap-x-2">
        <img
        src="{{ asset('images/tagoloan-logo.png') }}"
        alt="Tagoloan logo"
        class="w-14">
        <div class="flex flex-col">
            <span class="text-lg font-medium">Municipality of Tagoloan</span>
            <span class="text-sm text-opacity-70">Provincial Extension Office</span>
        </div>
    </div>
    <nav class="flex gap-x-4">
        <nav class="flex gap-x-4">
            <a href="{{ route('home') }}" class="relative group">
                Home
                <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('home')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('services.index') }}" class="relative group">
                Services
                <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('services.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('requests.show') }}" class="relative group">
                Applications
                <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('requests.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
            <a href="{{ route('auth.index') }}" class="relative group">
                Sign in
                <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('auth.index')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
            </a>
        </nav>
    </nav>
</div>