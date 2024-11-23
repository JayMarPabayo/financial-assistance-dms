<x-layout>
    <main class="text-lg text-slate-900">
        <header class="flex items-center justify-between mb-5">
            <section class="flex items-center gap-x-2">
                <x-carbon-link class="w-6 fill-sky-800"/>
                <span class="text-sm font-medium text-slate-600">Tracking:</span>
                <h1 class="text-lg tracking-wider">{{ strtoupper($request->tracking_no) }}</h1>
            </section>
            <section class="flex items-center gap-x-2">
                @if ($request->status === "For schedule")
                    <form
                    method="POST"
                    id="cancel-submit-form"
                    action="{{ route('applications.submit.cancel') }}"
                    x-data="{ showConfirm: false }">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tracking" value="{{ $request->tracking_no }}">
                        <button type="button" @click.prevent="showConfirm = true" class="btn-secondary">Cancel Submission</button>
            
                        <div x-cloak x-show="showConfirm" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-lg p-5 min-w-96 text-start">
                                <p class="text-lg text-sky-800 font-medium mb-4">Confirm Cancellation of Submission</p>
                                <hr class="border-t-2 mb-4">
                                <div class="flex justify-end gap-x-4">
                                    <button @click.prevent="showConfirm = false" class="bg-gray-300 px-4 py-2 rounded-md">Cancel</button>
                                    <button @click.prevent="document.getElementById('cancel-submit-form').submit();" class="bg-sky-800 text-white px-4 py-2 rounded-md">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endif
                <a href="{{ route('transactions.index') }}">
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
                <div class="flex items-center gap-x-2 text-xs font-medium py-1 px-2 rounded-md relative"
                    x-data="{ showMenu: false, isChecked: false }"
                    data-status="{{ $request->status }}"
                    :class="isChecked ? 'bg-green-400' : 'bg-slate-400/50'">
                    <input 
                        type="checkbox" 
                        class="cursor-pointer attachment-checkbox"
                        x-show="$el.closest('div').dataset.status === 'For review'"  
                        style="margin-bottom: 0"
                        @change="isChecked = $event.target.checked">
                    <p>{{ substr($filename, 14) }}</p>
                    <button @click="showMenu = !showMenu" class="z-0">
                        <x-carbon-overflow-menu-horizontal class="w-5"/>
                    </button>
                    <div
                        x-show="showMenu" 
                        x-cloak
                        @click.away="showMenu = false"
                        class="absolute top-full right-0 bg-white shadow-md border rounded-md mt-2 w-32 text-left"
                        style="display: none;"
                        x-transition>
                        <a href="{{ route('file.download', ['filename' => $filename]) }}" 
                        class="block px-4 py-2 hover:bg-slate-100">
                            Download
                        </a>
                        <a target="_blank" href="{{ route('file.view', ['filename' => $filename]) }}" 
                        class="block px-4 py-2 hover:bg-slate-100">
                            View
                        </a>
                    </div>
                </div>
            @empty
                <span>No files attached</span>
            @endforelse
        </section>

        <hr class="mb-5 border-sky-800 border-t-2" />
        @if ($request->status !== "For schedule")
            <section class="flex items-center gap-x-2 mb-5">
                <x-carbon-mail-reply class="w-6 fill-sky-800"/>
                <span class="text-sm font-medium text-slate-600">Reject:</span>
            </section>
            <section class="relative file-section rounded-md p-3 bg-white/50 text-sm mb-5 flex items-center gap-x-3" x-data="{ showConfirm: false }">
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
                            <button @click.prevent="showConfirm = true" class="bg-rose-500 w-40 rounded-md truncate text-white py-1 self-end me-44">
                                Reject
                            </button>
                        @else
                            <button @click.prevent="showConfirm = true" class="btn-secondary">
                                Cancel Rejection
                            </button>
                        @endif
                    </form>

                    <form
                    method="POST"
                    id="submit-form"
                    action="{{ route('applications.submit') }}"
                    x-data="{ showConfirm: false }">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tracking" value="{{ $request->tracking_no }}">
                        <button id="submit-button" type="button" @click.prevent="showConfirm = true" class="absolute bg-sky-800 w-40 rounded-md truncate text-white py-1 self-end -ms-44 mt-5">Submit for Schedule</button>
            
                        <div x-cloak x-show="showConfirm" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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
            
 
        
                <div x-cloak x-show="showConfirm" class="z-50 fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('.attachment-checkbox');
            const submitButton = document.getElementById('submit-button');
    
            const updateSubmitButtonState = () => {
                const allChecked = Array.from(checkboxes)?.every(checkbox => checkbox?.checked);
                if (submitButton) {
                    submitButton.disabled = !allChecked;
                }

                submitButton?.classList.toggle('opacity-50', !allChecked);
                submitButton?.classList.toggle('cursor-not-allowed', !allChecked);
            };
    
            checkboxes?.forEach(checkbox => {
                checkbox?.addEventListener('change', updateSubmitButtonState);
            });
    
            updateSubmitButtonState();
        });
    </script>
</x-layout>

