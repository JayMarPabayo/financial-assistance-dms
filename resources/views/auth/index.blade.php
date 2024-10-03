<x-layout>
    <div class="mx-auto w-3/4">
        <p class="text-justify text-sky-950">
            This platform designed to streamline the application process for various types of assistance, including Burial Assistance, Medical Billing Assistance, and more. It enables the submission of required documents online, with updates provided via SMS. Authorized staff members utilize this system to review and verify applications securely, ensuring that all documents meet the necessary requirements. 
        </p>
        <div class="mt-4 text-base bg-white shadow-md rounded-md"
        >
            <header class="w-full p-2 flex justify-end rounded-t-md bg-sky-700 text-white">
                <h3 class="mr-56">Staff Log In</h3>
            </header>
            <div class="flex">
                <div class="max-w-[40%]">
                    <img src="{{ asset('images/banner.jpg') }}" alt="Banner" class="opacity-80 object-cover object-top w-full h-full rounded-bl-md">
                </div>
                <div class="px-10 py-5 grow">
                    <form action="{{ route('auth.login') }}" method="POST">
                        @csrf
                        @error('username')
                            <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                        <input type="text"
                        name="username"
                        placeholder="Username"
                        value="{{ old('username') }}"
                        class="w-full" >
                        @error('password')
                            <p class="text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                        <input type="password"
                        name="password"
                        autocomplete="off"
                        placeholder="Password"
                        class="w-full" >
                        @error ('credentials')
                            <p class="text-xs text-red-500 font-medium mb-2">{{ $message }}</p>
                        @enderror
                        <div class="flex items-center gap-x-2 w-fit mb-5 px-2 py-1">
                            <input type="checkbox" name="remember" class="cursor-pointer" style="margin: 0;">
                            <label for="remember" class="text-sm">Remember me?</label>
                        </div>
            
                        <button type="submit" class="btn-primary w-full text-lg">
                            Log in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout> 