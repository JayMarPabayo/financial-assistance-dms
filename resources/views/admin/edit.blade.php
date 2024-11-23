<x-layout>
    <main class="text-lg text-slate-900">
        <header class="flex items-center justify-between mb-5">
            <section class="flex items-center gap-x-2">
                <x-carbon-link class="w-6 fill-sky-800"/>
                <span class="text-sm font-medium text-slate-600">Tracking:</span>
                <h1 class="text-lg tracking-wider">{{ strtoupper($request->tracking_no) }}</h1>
            </section>
            <section class="flex items-center gap-x-2">
                
                <a href="{{ route('admin.index') }}">
                  <button title="Return" class="btn-secondary">
                    <x-carbon-launch title="Return" class="w-4" />
                  </button>
                </a>
            </section>
        </header>

        <section class="rounded-md p-3 bg-white/50 text-sm mb-5">
            <div class="grid grid-cols-2 gap-y-3">
                <div class="flex items-center gap-x-3">
                    <x-carbon-user-avatar-filled-alt class="w-5 fill-slate-500" />
                    <div class="col-span-4">{{ $request->fullName() ?? '' }}</div>
                </div>
                <div class="flex items-center gap-x-3">
                    <x-carbon-web-services-container class="w-5 fill-slate-500" />
                    <div class="col-span-4">{{ $request->service->name ?? '' }}</div>
                </div>
                <div class="flex items-center gap-x-3">
                    <x-carbon-calendar class="w-5 fill-slate-500" />
                    <div class="col-span-4">{{ $request->created_at->format('d F Y  |  h:i A') ?? '' }}</div>
                </div>
                <div class="flex items-center gap-x-3">
                    <x-carbon-location-filled class="w-5 fill-slate-500" />
                    <div class="col-span-4">{{ $request->address ?? '' }}</div>
                </div>
                <div class="flex items-center gap-x-3">
                    <x-carbon-phone-filled class="w-5 fill-slate-500" />
                    <div class="col-span-4">{{ $request->contact ?? '' }}</div>
                </div>
                <div class="flex items-center gap-x-3">
                    <x-carbon-email class="w-5 fill-slate-500" />
                    <div class="col-span-4">{{ $request->email ?? '' }}</div>
                </div>
            </div>
        </section>
        
        <section class="flex items-center gap-x-2 mb-5">
            <x-carbon-document-multiple-02 class="w-6 fill-sky-800"/>
            <span class="text-sm font-medium text-slate-600">Attachments:</span>
        </section>
        @php
            $attachments = $request->attachments ?? [];
        @endphp
        <section class="file-section rounded-md p-3 bg-white/50 text-sm mb-5 flex items-center gap-x-3">
            @forelse ($attachments as $attachment)
                @php
                    $filename = basename($attachment->file_path);
                @endphp
                <div class="flex items-center gap-x-2 text-xs font-medium py-1 px-2 rounded-md bg-slate-400/50 relative"  x-data="{ showMenu: false }">
                    <p>{{ substr($filename, 14) }}</p>
                    <button @click="showMenu = !showMenu" class="relative">
                        <x-carbon-overflow-menu-horizontal class="w-5"/>
                    </button>
                    <div 
                    x-show="showMenu" 
                    @click.away="showMenu = false" 
                    class="absolute top-full right-0 bg-white shadow-md border rounded-md mt-2 w-32 text-left"
                    x-cloak
                >
                        <a @click.away="showMenu = false" href="{{ route('file.download', ['filename' => $filename]) }}" 
                        class="block px-4 py-2 hover:bg-slate-100">
                            Download
                        </a>
                        <a @click.away="showMenu = false" target="_blank" href="{{ route('file.view', ['filename' => $filename]) }}" 
                        class="block px-4 py-2 hover:bg-slate-100">
                            View
                        </a>
                    </div>
                </div>
            @empty
                <span>No files attached</span>
            @endforelse
        </section>

        <section class="rounded-md p-3 bg-emerald-700/80 text-sm mb-5 text-white">
            <h1 class="mb-2 text-xs font-medium">Submitted by:</h1>
            <div class="flex items-center gap-x-3 mb-1">
                <x-carbon-user-avatar-filled-alt class="w-5 fill-white" />
                <div class="col-span-4">{{ $request->user->name ?? '' }}</div>
            </div>
            <div class="flex items-center gap-x-3">
                <x-carbon-phone-filled class="w-5 fill-white" />
                <div class="col-span-4">{{ $request->user->contact ?? '' }}</div>
            </div>
        </section>

        <section class="flex items-center gap-x-2 mb-5">
            <x-carbon-event-schedule class="w-6 fill-sky-800"/>
            <span class="text-sm font-medium text-slate-600">Meeting Details:</span>
        </section>

        <section class="rounded-md p-3 bg-white/50 text-sm mb-5">
            <form action="{{ route('schedules.store') }}" method="post" id="submit-form">
                @csrf
                <div class="flex items-start justify-between gap-x-4">
                    <section>
                        <input type="hidden" name="request_id" value="{{ $request->id }}">
                        <div class="block mb-1 mt-2">
                            <label for="date" class="mb-1">Schedule Date</label>
                            @error('date')
                                <span class="ms-2 text-xs text-red-600 font-medium">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <input type="date" name="date" value="{{ old('date') }}" class="w-96 pe-2"  min="{{ now()->toDateString() }}">
    
                        <div class="block mb-1 mt-2">
                            <label for="time" class="mb-1">Schedule Time</label>
                            @error('time')
                                <span class="ms-2 text-xs text-red-600 font-medium">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <select name="time" value="{{ old('time') }}" class="w-96 pe-2">
                            <option value="8:00 AM - 12:00 PM" @selected( old('time') === "8:00 AM - 12:00 PM")>8:00 AM - 12:00 PM</option>
                            <option value="1:00 PM - 5:00 PM" @selected( old('time') === "1:00 PM - 5:00 PM")>1:00 PM - 5:00 PM</option>
                        </select>
                    </section>
                    
                    <section class="flex-grow">
                        <div class="block mb-1 mt-2">
                            <label for="notes" class="mb-1">Notes</label>
                            @error('notes')
                                <span class="ms-2 text-xs text-red-600 font-medium">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <textarea name="notes" id="notes" class="w-full" rows="5" placeholder="Optional"></textarea>
                    </section>
                </div>

                
                <section class="flex items-center justify-end text-sm" x-data="{ showConfirm: false }">
                    <button type="button" @click.prevent="showConfirm = true" class="btn-primary">Approve Request</button>
                    <div x-cloak x-show="showConfirm" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded-lg p-5 min-w-96 text-start">
                            <p class="text-lg text-sky-800 font-medium mb-4">Confirm Approval</p>
                            <hr class="border-t-2 mb-4">
                            <div class="flex justify-end gap-x-4 text-sm">
                                <button @click.prevent="showConfirm = false" class="bg-gray-300 px-4 py-2 rounded-md">Cancel</button>
                                <button @click.prevent="document.getElementById('submit-form').submit();" class="bg-sky-800 text-white px-4 py-2 rounded-md">Submit</button>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </section>

    </main>

</x-layout>

