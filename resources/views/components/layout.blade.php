<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Financial Assistance Document Management System</title>
        <script src="//unpkg.com/alpinejs" defer></script>
        <link rel="shortcut icon" href="{{ asset('images/tagoloan-logo.png') }}">
        @vite('resources/css/app.css')

    </head>
    <body
    class="bg-cover bg-no-repeat bg-center bg-opacity-80 min-h-screen"
    data-bg-image="{{ asset('images/bg.jpg') }}">
        @include('layouts.navbar')
        {{ $slot }} 
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
