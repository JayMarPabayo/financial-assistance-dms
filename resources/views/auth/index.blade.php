<x-layout>
    <main class="mx-60 mt-5 h-[34.2em]">
        <div class="mx-auto mt-5">
            <p class="text-justify text-sky-950">
                This platform designed to streamline the application process for various types of assistance, including Burial Assistance, Medical Billing Assistance, and more. It enables the submission of required documents online, with updates provided via SMS. Authorized staff members utilize this system to review and verify applications securely, ensuring that all documents meet the necessary requirements. 
            </p>
            <div class="mt-4 text-base bg-white shadow-md rounded-md"
            >
                @csrf
                <header class="w-full rounded-t-md bg-sky-700 p-2 text-white">
                    <h3>Please Log In</h3>
                </header>
                <div class="flex">
                    <div class="max-w-[40%]">
                        <img src="{{ asset('images/banner.jpg') }}" alt="Banner" class="opacity-80 object-cover object-top w-full h-full rounded-bl-md">
                    </div>
                    <div class="px-10 py-5 grow">
                        <form action="#">
                            @csrf
                            <input type="text" name="username" placeholder="Username" class="w-full">
                            <input type="password" name="password" autocomplete="off" placeholder="Password" class="w-full">
                            
                            <div class="flex items-center gap-x-2 w-fit mb-5 bg-slate-400 px-2 py-1 rounded-md">
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
    </main>
</x-layout> 