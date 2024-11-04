<x-layout>
    <main class="text-sm text-slate-900">
        <div class="mb-3 flex justify-start items-center gap-x-2 w-1/2">
            <x-carbon-user-filled class="fill-teal-800 h-5" /> 
            <h1 class="font-semibold text-lg text-teal-800/70">NEW USER INFORMATION</h1>
        </div>
        <form action="{{ route('users.store') }}" method="post">
            @csrf
            <div class="block mb-1">
                <label for="name" class="mb-1">Full name</label>
                @error('name')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="text" autocomplete="off" name="name" placeholder="Input full name" value="{{ old('name') }}" class="w-1/2">

            <div class="block mb-1">
                <label for="contact" class="mb-1">Contact</label>
                @error('contact')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="tel" name="contact" placeholder="Contact Information" value="{{ old('contact') }}" class="w-1/2">

            <div class="block mb-1">
                <label for="username" class="mb-1">Username</label>
                @error('username')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="text" autocomplete="off" name="username" placeholder="Username" value="{{ old('username') }}" class="w-1/2">
        
            <div class="block mb-1">
                <label for="password" class="mb-1">Password</label>
                @error('password')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="password" name="password" placeholder="Input password" class="w-1/2">

            <div class="block mb-1">
                <label for="password_confirmation" class="mb-1">Confirm password</label>
                @error('password_confirmation')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-1/2">


            <div class="block mb-1">
                <label for="role" class="mb-1">Role</label>
                @error('role')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <select name="role" id="role" class="w-1/2 block mb-5">
                <option value="">Select a role</option>
                <option value="Administrator" {{ old('role') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
                <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
            </select>

            <button type="submit" class="btn-primary">Submit</button>
        </form>
    </main>
</x-layout>