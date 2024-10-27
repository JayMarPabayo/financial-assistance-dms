<x-layout>
    <main class="text-lg text-slate-900">
        <header class="flex items-center justify-between mb-5">
            <section class="flex items-center gap-x-2">
                <x-carbon-link class="w-6 fill-sky-800"/>
                <span class="text-sm font-medium text-slate-600">Tracking:</span>
                <h1 class="text-lg tracking-wider">{{ strtoupper($request->tracking_no) }}</h1>
            </section>
            <section class="flex items-center gap-x-2">
                @if ($request->status === "For review")
                    <form
                    method="POST"
                    id="submit-form"
                    action="{{ route('applications.submit') }}"
                    x-data="{ showConfirm: false }">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tracking" value="{{ $request->tracking_no }}">
                        <button type="button" @click.prevent="showConfirm = true" class="btn-primary">Submit for Approval</button>
            
                        <div x-cloak x-show="showConfirm" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-lg p-5 min-w-96 text-start">
                                <p class="text-lg text-sky-800 font-medium mb-4">Confirm Submission</p>
                                <hr class="border-t-2 mb-4">
                                <div class="flex justify-end gap-x-4">
                                <button @click.prevent="showConfirm = false" class="bg-gray-300 px-4 py-2 rounded-md">Cancel</button>
                                <button @click.prevent="document.getElementById('submit-form').submit();" class="bg-sky-800 text-white px-4 py-2 rounded-md">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
                @if ($request->status === "For approval")
                    <form
                    method="POST"
                    id="cancel-submit-form"
                    action="{{ route('transactions.submit.cancel') }}"
                    x-data="{ showConfirm: false }">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tracking" value="{{ $request->tracking_no }}">
                        <button type="button" @click.prevent="showConfirm = true" class="btn-secondary">Cancel Submission</button>
            
                        <div x-cloak x-show="showConfirm" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-lg p-5 min-w-96 text-start">
                                <p class="text-lg text-sky-800 font-medium mb-4">Confirm Cancellation of Submission</p>
                                <hr class="border-t-2 mb-4">
                                <div class="flex justify-end gap-x-4">
                                    <button @click.prevent="showConfirm = false" class="bg-gray-300 px-4 py-2 rounded-md">Cancel</button>
                                    <button @click.prevent="document.getElementById('cancel-submit-form').submit();" class="bg-sky-800 text-white px-4 py-2 rounded-md">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
                <a href="{{ url()->previous() }}">
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
            $attachments = $request->attachments ?? [];
        @endphp
        <section class="file-section rounded-md p-3 bg-white/50 text-sm mb-5 flex items-center gap-x-3">
            @forelse ($attachments as $attachment)
                @php
                    $filename = basename($attachment->file_path);
                @endphp
                <a
                href="{{ route('file.download', $filename) }}"
                title="{{ $filename }}"
                class="flex items-center gap-x-2">
                    <button class="text-xs font-medium py-1 px-2 rounded-md bg-slate-400/50">
                        {{ substr($filename, 14) }}
                    </button>
                </a>
            @empty
                <span>No files attached</span>
            @endforelse
            @if ($attachments)
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

        <hr class="mb-5 border-sky-800 border-t-2" />
        @if ($request->status !== "For approval")
            <section class="flex items-center gap-x-2 mb-5">
                <x-carbon-mail-reply class="w-6 fill-sky-800"/>
                <span class="text-sm font-medium text-slate-600">Reject:</span>
            </section>
            <section class="file-section rounded-md p-3 bg-white/50 text-sm mb-5 flex items-center gap-x-3" x-data="{ showConfirm: false }">
                @if ($request->status === 'For review')
                    <form method="POST" id="reject-form" action="{{ route('applications.reject') }}" class="w-full flex flex-col">
                        @csrf
                        @method('PUT')
                        <textarea name="message" class="p-2 w-full"></textarea>
                        @error('message')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        <input type="hidden" name="tracking" value="{{ $request->tracking_no }}">
                        @if ($request->status === "For review")
                            <button @click.prevent="showConfirm = true" class="bg-rose-500 w-40 rounded-md truncate text-white py-1 self-end">
                                Reject
                            </button>
                        @else
                            <button @click.prevent="showConfirm = true" class="btn-secondary">
                                Cancel Rejection
                            </button>
                        @endif
                    </form>
                @else
                    <form method="POST" id="cancel-reject-form" action="{{ route('applications.cancel.reject') }}" class="w-full flex flex-col">
                        @csrf
                        @method('PUT')
                        <textarea name="message" class="p-2 w-full" readonly>{{ $request->message }}</textarea>
                        @error('message')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        <input type="hidden" name="tracking" value="{{ $request->tracking_no }}">
                        @if ($request->status === "For review")
                            <button @click.prevent="showConfirm = true" class="bg-rose-500 w-40 rounded-md truncate text-white py-1 self-end">
                                Reject
                            </button>
                        @else
                            <button @click.prevent="showConfirm = true" class="bg-amber-600 w-40 rounded-md truncate text-white py-1 self-end">
                                Cancel Rejection
                            </button>
                        @endif
                    </form>
                @endif
            
 
        
                <div x-cloak x-show="showConfirm" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg p-5 min-w-96 text-start">
                        @if ($request->status === 'For review')
                            <p class="text-lg text-sky-800 font-medium mb-4">Confirm Rejection</p>
                        @else
                            <p class="text-lg text-sky-800 font-medium mb-4">Confirm Cancellation</p>
                        @endif
                        
                        <hr class="border-t-2 mb-4">
                        <div class="flex justify-end gap-x-4">
                            <button @click="showConfirm = false" class="bg-gray-300 px-4 py-2 rounded-md">Cancel</button>
                            @if ($request->status === 'For review')
                                <button @click="document.getElementById('reject-form').submit();" class="bg-rose-500 text-white px-4 py-2 rounded-md">Confirm</button>
                            @else
                                <button @click="document.getElementById('cancel-reject-form').submit();" class="bg-rose-500 text-white px-4 py-2 rounded-md">Confirm</button>
                            @endif
                            
                        </div>
                    </div>
                </div>
            </section>
        @endif

    </main>

</x-layout>

