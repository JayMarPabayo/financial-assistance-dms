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
                    <div class="col-span-4">{{ $request->name ?? '' }}</div>
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
            $files = json_decode($request->files_path, true)
        @endphp
        <section class="file-section rounded-md p-3 bg-white/50 text-sm mb-5 flex items-center gap-x-3">
            @forelse ($files as $file)
                @php
                    $filename = basename($file);
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                @endphp
                <a
                href="{{ route('file.download', $filename) }}"
                title="{{ $filename }}"
                class="
                    @if($extension === 'pdf') pdf-style @endif
                    @if($extension === 'docx') word-style @endif
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'PNG'])) image-style @endif
                ">
                    @switch($extension)
                        @case('pdf')
                            <x-carbon-document-pdf title="{{ $filename }}" class="w-6"/>
                            @break
                        @case('docx')
                            <x-carbon-document-word-processor title="{{ $filename }}" class="w-6"/>
                            @break
                        @case('jpg')
                        @case('JPG')
                        @case('jpeg')
                        @case('png')
                        @case('PNG')
                        @case('gif')
                            <x-carbon-image-reference title="{{ $filename }}" class="w-6"/>
                            @break
                        @default
                        <x-carbon-document-pdf title="{{ $filename }}" class="w-6"/>
                    @endswitch
                    <span class="text-xs font-medium">
                        {{ substr($filename, 14) }}
                    </span>
                </a>
            @empty
                <span>No files attached</span>
            @endforelse
            @if ($files)
                <button class="ms-auto self-start flex items-center gap-x-2 px-3 py-1 rounded-md bg-sky-950 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg"
                    width="1em"
                    height="1em"
                    viewBox="0 0 24 24">
                        <path fill="currentColor" d="M21 5a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h7.414l2 2H16v2h2V5zm-3 8h-2v2h-2v3h4zm-2-2h-2v2h2zm2-2h-2v2h2zm-2-2h-2v2h2z"/>
                    </svg>
                    <a href="{{ route('files.downloadZip', $request->tracking_no) }}">Downlod as zip</a>
                </button>
            @endif
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
                        <input type="date" name="date" value="{{ old('date') }}" class="w-96 pe-2">
    
                        <div class="block mb-1 mt-2">
                            <label for="time" class="mb-1">Schedule Time</label>
                            @error('time')
                                <span class="ms-2 text-xs text-red-600 font-medium">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <input type="time" name="time" value="{{ old('time') }}" class="w-96 pe-2">
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
                        <textarea name="notes" id="notes" class="w-full" rows="5"></textarea>
                    </section>
                </div>

                
                <section class="flex items-center justify-end text-sm" x-data="{ showConfirm: false }">
                    <button type="button" @click.prevent="showConfirm = true" class="btn-primary">Approve Request</button>
                    <div x-cloak x-show="showConfirm" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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

