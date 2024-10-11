<x-layout>
    <main class="text-lg text-slate-900">
        <div class="flex items-center justify-between mb-5 pe-2 ">
            <h1 class="text-lg me-auto">List of Users</h1>
            <form action="{{ route('users.index') }}" method="GET">
                @php
                    $blockValue = request('block') == 1 ? 0 : 1;
                @endphp
                <input type="hidden" name="block" value="{{ $blockValue }}">
                <button type="submit" class="py-1 px-2 rounded-md flex items-center justify-center gap-x-2 text-sm text-teal-800">
                    <p>{{ $blockValue == 1 ? 'View blocked' : 'Hide blocked' }} users</p>
                    @if ($blockValue)
                        <x-carbon-view-filled class="h-5 fill-sky-800" />
                    @else
                        <x-carbon-view-off-filled class="h-5 fill-sky-800" />
                    @endif
                    
                </button>
            </form>
            <a href="{{ route('users.create') }}" class="btn-secondary">
                Add New User
            </a>
        </div>
        <table class="text-sm w-full shadow-md">
            <thead >
                <tr class="bg-white  border border-sky-700/30 border-b-sky-700 text-xs">
                    <th class="text-start text-sky-900 p-3">Name</th>
                    <th class="text-start text-sky-900 p-3">Contact</th>
                    <th class="text-start text-sky-900 p-3">Username</th>
                    <th class="text-start text-sky-900 p-3">Role</th>
                    <th class="text-start text-sky-900 p-3">Created at</th>
                    <th class="text-center text-sky-900 p-3">Action</th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr class="bg-white/40 border border-slate-500/50 cursor-pointer hover:bg-white/70 duration-300">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->contact }}</td>
                        <td class="p-3">{{ $user->username }}</td>
                        <td class="p-3">{{ $user->role }}</td>
                        <td class="p-3">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="align-middle p-2">
                            @if ($user->block)
                            <form id="{{ $user->id }}" action="{{ route('users.update', $user->id) }}" method="POST" x-data="{ showConfirmation: false }">
                                @csrf
                                @method('PUT')
                                <button type="button"  x-on:click.prevent="showConfirmation = true" class="btn-approve flex justify-center items-center gap-x-2 mx-auto">
                                    <x-carbon-unlocked class="h-3" />
                                    <p class="text-xs">Unblock Account</p>
                                </button>
                                <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300w-0 group-hover:w-full"></span>
                            
                                <div x-cloak x-show="showConfirmation" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="bg-white p-4 rounded-lg shadow w-1/4">
                                        <p class="text-left font-medium text-teal-800 mb-5">Unblock User Confirmation</p>
                                        <div class="flex justify-end gap-x-2 mt-4 text-xs">
                                            <button type="button" class="btn-secondary" x-on:click="showConfirmation = false">Cancel</button>
                                            <button type="submit" class="btn-approve" style="width: 5rem">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @else
                            <form id="{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" x-data="{ showConfirmation: false }">
                                @csrf
                                @method('DELETE')
                                <button type="button"  x-on:click.prevent="showConfirmation = true" class="btn-reject flex items-center gap-x-2 mx-auto">
                                    <x-carbon-locked class="h-3" />
                                    <p>Block Account</p>
                                </button>
                                <span class="absolute bottom-0 left-0 h-[0.15rem] bg-white transition-all duration-300w-0 group-hover:w-full"></span>
                            
                                <div x-cloak x-show="showConfirmation" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="bg-white p-4 rounded-lg shadow">
                                        <p class="text-center font-medium text-teal-800 mb-5">Are you sure you want to block this user?</p>
                                        <div class="flex justify-end mt-4 text-xs">
                                            <button type="button" class="btn-secondary" x-on:click="showConfirmation = false">Cancel</button>
                                            <button type="submit" class="btn-reject">Block</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center py-20">
                            <span class="text-rose-600 font-medium">
                                Empty
                            </span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($users->count())
        <div class="text-xs mt-4">
            {{ $users->links()}}
        </div>
    @endif
    </main>

</x-layout>

