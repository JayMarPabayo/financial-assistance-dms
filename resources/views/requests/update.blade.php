<x-layout>
    <main class="w-full min-h-svh px-40 bg-white/50 text-lg text-slate-900 py-5">
        <header class="flex items-center justify-between mb-5">
            <section class="flex items-center gap-x-2">
                <x-carbon-link class="w-6 fill-sky-800"/>
                <span class="text-sm font-medium text-slate-600">Tracking:</span>
                <h1 class="text-lg tracking-wider">{{ strtoupper($request->tracking_no) }}</h1>
            </section>
            <section>
                <a href="{{ route('requests.index') }}">
                    <button title="Return" class="btn-secondary">
                            <x-carbon-launch title="Return" class="w-4"/>
                    </button>
                </a>
            </section>
        </header>

        <section class="rounded-md p-3 bg-white/70 text-sm mb-5">
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
        <section class="file-section rounded-md p-3 bg-white/70 text-sm mb-5 flex items-center gap-x-3">
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
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) image-style @endif
                ">
                    @switch($extension)
                        @case('pdf')
                            <x-carbon-document-pdf title="{{ $filename }}" class="w-6"/>
                            @break
                        @case('docx')
                            <x-carbon-document-word-processor title="{{ $filename }}" class="w-6"/>
                            @break
                        @case('jpg')
                        @case('jpeg')
                        @case('png')
                        @case('gif')
                            <x-carbon-image-reference title="{{ $filename }}" class="w-6"/>
                            @break
                        @default
                        <x-carbon-document-pdf title="{{ $filename }}" class="w-6"/>
                    @endswitch
                    <span class="text-xs font-medium">
                        {{ $filename }}
                    </span>
                </a>
            @empty
                <span>No files attached</span>
            @endforelse
        </section>

    </main>

</x-layout>

