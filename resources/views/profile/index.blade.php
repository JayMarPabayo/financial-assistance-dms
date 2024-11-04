<x-layout>
    <main class="text-sm text-slate-900">
        <div class="mb-3 flex justify-between items-center w-1/2">
            <h1 class="font-semibold text-lg text-teal-800">USER INFORMATION</h1>
            @if(session('info_status'))
                <span class="success">{{ session('info_status') }}</span>
            @endif
        </div>
        <form action="{{ route('profile.update') }}" method="post">
            @csrf
            @method('PUT')
            <div class="block mb-1">
                <label for="name" class="mb-1">Full name</label>
                @error('name')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="text" autocomplete="off" name="name" placeholder="Input full name" value="{{ old('name', auth()->user()->name) }}" class="w-1/2">

            <div class="block mb-1">
                <label for="username" class="mb-1">Username</label>
                @error('username')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="text" autocomplete="off" name="username" placeholder="Username" value="{{ old('username', auth()->user()->username) }}" class="w-1/2">
        
            <button type="submit" class="btn-primary">Update</button>
        </form>

        <hr class="my-5" />
        <div class="mb-3 flex justify-between items-center w-1/2">
            <h1 class="font-semibold text-lg text-teal-800">PASSWORD</h1>
            @if(session('status'))
                <span class="success">{{ session('status') }}</span>
            @endif
        </div>
        
        <form action="{{ route('profile.password') }}" method="post">
            @csrf
            @method('PUT')

            <div class="block mb-1">
                <label for="current_password" class="mb-1">Current password</label>
                @error('current_password')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="password" name="current_password" placeholder="Input current password" class="w-1/2">

            <div class="block mb-1">
                <label for="password" class="mb-1">New password</label>
                @error('password')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="password" name="password" placeholder="Input new password" class="w-1/2">

            <div class="block mb-1">
                <label for="password_confirmation" class="mb-1">Confirm new password</label>
                @error('password_confirmation')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-1/2">

            <button type="submit" class="btn-primary">Update password</button>
        </form>
    </main>
</x-layout>