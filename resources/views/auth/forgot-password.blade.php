<x-layout>
    <div class="w-1/2 rounded-md bg-white shadow-md p-5 mx-auto">
        <h1 class="text-sky-900 text-lg">Reset Password</h1>
        <hr class="border-t border-sky-700/70" style="margin-block: 1rem">
        @if (session('status'))
            <div class="error">{{ session('status') }}</div>
        @endif
    
        <form action="{{ route('password.email') }}" method="post">
            @csrf

            <div class="mb-4">
                <label for="email">Enter email address</label>
                <input type="text" name="email" value="{{ old('email') }}"
                    class="w-full">

                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary">Submit</button>
        </form>
    </div>
</x-layout>