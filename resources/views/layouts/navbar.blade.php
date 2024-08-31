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
            @guest
                <a href="{{ route('home') }}" class="relative group">
                    Home
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('home')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <a href="{{ route('services.index') }}" class="relative group">
                    Services
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('services.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <a href="{{ route('applications.show') }}" class="relative group">
                    Applications
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('requests.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <a href="{{ route('auth.login') }}" class="relative group">
                    Sign in
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('auth.login')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
            @endguest
            @auth
                <a href="{{ route('requests.index') }}" class="relative group">
                    Requests
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('requests.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <a href="#" class="relative group">
                    Transactions
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 w-0 group-hover:w-full"></span>
                </a>
                <a href="#" class="relative group">
                    Profile
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 w-0 group-hover:w-full"></span>
                </a>
                <form action="{{ route('auth.logout') }}" method="POST" class="relative group">
                    @csrf
                    @method('DELETE')
                    <button
                    type="submit">
                        Sign out
                    </button>
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300w-0 group-hover:w-full"></span>
                </form>
            @endauth
        </nav>
    </nav>
</div>