<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Financial Assistance Document Management System</title>
        <script src="//unpkg.com/alpinejs" defer></script>
        <link rel="shortcut icon" href="{{ asset('images/misor.png') }}">
        @vite('resources/css/app.css')

    </head>
    <body
    class="bg-cover bg-no-repeat bg-center bg-opacity-80 min-h-screen flex flex-col justify-between"
    data-bg-image="{{ asset('images/bg.jpg') }}">
        @include('layouts.navbar')

        <div x-data="{ flash: true }">
            @if (session()->has('success'))
                <div x-show="flash" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <x-carbon-close @click="flash = false" class="w-7 absolute right-3 top-3 cursor-pointer hover:scale-105 active:scale-95" />
                    </span>
                </div>
            @endif

            @if (session()->has('error'))
                <div x-show="flash" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <x-carbon-close @click="flash = false" class="w-7 absolute right-3 top-3 cursor-pointer hover:scale-105 active:scale-95" />
                    </span>
                </div>
            @endif

            @if ($errors->any())
                <div x-show="flash" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <x-carbon-close @click="flash = false" class="w-7 absolute right-3 top-3 cursor-pointer hover:scale-105 active:scale-95" />
                    </span>
                </div>
            @endif
            
        </div>
        
        <main class="grow py-10 px-40 bg-white/50">
            {{ $slot }} 
        </main>
        @include('layouts.footer')
    </body>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const lazyImages = document.querySelectorAll('[data-bg-image]');

            const loadImage = (image) => {
                const imageUrl = image.dataset.bgImage;
                image.style.backgroundImage = `url(${imageUrl})`;
            };

            const options = {
                root: null,
                rootMargin: '200px 0px',
                threshold: 0.5,
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                if (entry.isIntersecting) {
                    loadImage(entry.target);
                    observer.unobserve(entry.target);
                }
                });
            }, options);   


            lazyImages.forEach(image => observer.observe(image));
        });
    </script>
</html>
