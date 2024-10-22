<div class="sticky top-0 z-10  w-full h-20 bg-sky-900/90 text-white px-10 flex justify-between items-center">
    <div class="flex items-center gap-x-2">
        <img
        src="{{ asset('images/misor.png') }}"
        alt="Misamis Oriental logo"
        class="w-14">
        <div class="flex flex-col">
            <span class="text-lg font-medium">2nd District of Misamis Oriental</span>
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
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('applications.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <a href="{{ route('login') }}" class="relative group">
                    Sign in
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('login')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
            @endguest
            @auth

                @if (Auth::check() && Auth::user()->role === 'Staff')
                    <a href="{{ route('requests.index') }}" class="relative group">
                        Requests
                        <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('requests.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                    <a href="{{ route('transactions.index') }}" class="relative group">
                        Transactions
                        <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('transactions.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                @else
                    <a href="{{ route('admin.index') }}" class="relative group">
                        Submissions
                        <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('admin.index')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                    <a href="{{ route('schedules.index') }}" class="relative group">
                        Schedules
                        <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('schedules.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                    <a href="{{ route('admin.services') }}" class="relative group">
                        Services
                        <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('admin.services*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                    <a href="{{ route('users.index') }}" class="relative group">
                        Users
                        <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('users.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                @endif
                <a href="{{ route('profile.index') }}" class="relative group">
                    Profile
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300 {{ (request()->routeIs('profile.*')) ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <form action="{{ route('auth.logout') }}" method="POST" class="relative group" x-data="{ showConfirmation: false }">
                    @csrf
                    @method('DELETE')
                    <button type="button" x-on:click.prevent="showConfirmation = true">
                        Sign out
                    </button>
                    <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300w-0 group-hover:w-full"></span>
                
                    <div x-cloak x-show="showConfirmation" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <p class="text-center font-medium text-teal-800 mb-5">Are you sure you want to log out?</p>
                            <div class="flex justify-end mt-4 text-xs gap-x-2">
                                <button type="button" class="btn-secondary" x-on:click="showConfirmation = false">Cancel</button>
                                <button type="submit" class="btn-reject">Log out</button>
                            </div>
                        </div>
                    </div>
                </form>
            @endauth
        </nav>
    </nav>
</div>