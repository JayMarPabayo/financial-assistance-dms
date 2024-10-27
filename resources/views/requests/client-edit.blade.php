<x-layout>
    <main class="text-lg text-slate-900">
        <header class="flex items-center justify-between mb-5">
            <section class="flex items-center gap-x-2">
                <x-carbon-link class="w-6 fill-sky-800"/>
                <span class="text-sm font-medium text-slate-600">Tracking:</span>
                <h1 class="text-lg tracking-wider">{{ strtoupper($request->tracking_no) }}</h1>
            </section>
            <section class="flex items-center gap-x-2">
                <a href="{{ url()->previous() }}">
                  <button title="Return" class="btn-secondary">
                    <x-carbon-launch title="Return" class="w-4" />
                  </button>
                </a>
              </section>
        </header>

        <section class="rounded-md p-3 bg-white/50 text-sm mb-5">
            <form action="{{ route('applications.update', $request->tracking_no) }}"
            method="POST"
            enctype="multipart/form-data"
            class="px-5 pb-10 pt-5 mt-4 text-base bg-white shadow-md rounded-md"
            id="application-form">
                @method('PUT')
                @csrf
                <div class="py-2 px-3 bg-sky-900 text-white text-center mb-5">
                    <span>
                        Edit Request
                    </span>
                </div>
                <input type="hidden" name="tracking_no" value="{{ $request->tracking_no }}">
                <div class="block">
                    <label for="name" class="mb-1">Applicant</label>
                    <span class="ms-2 error hidden" for="name">Applicant name is Required.</span>
                    @error('name')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" name="name" placeholder="Input full name" value="{{ old('name', $request->name) }}" class="w-1/2">

                <div class="block">
                    <label for="address" class="mb-1">Address</label>
                    <span class="ms-2 error hidden" for="address">Address is Required.</span>
                    @error('address')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" name="address" placeholder="Input full address" value="{{ old('address', $request->address) }}" class="w-3/4">

                <div class="block">
                    <label for="contact" class="mb-1">Contact No.</label>
                    <span class="ms-2 error hidden" for="contact">Contact Info is Required.</span> 
                    @error('contact')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                
                <input type="text" name="contact" placeholder="Telephone/Cellphone No." value="{{ old('contact', $request->contact) }}" class="w-1/2">

                <div class="block">
                    <label for="email" class="mb-1">Email</label>
                    @error('email')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="email" name="email" placeholder="Email address" value="{{ old('email', $request->email) }}" class="w-1/2">

                <div class="block mb-1">
                    <h1 class="mb-2">Attachments</h1>

                    @php
                        $requirementFiles = [];

                        if (isset($request->attachments)) {
                            foreach ($request->attachments as $attachment) {
                                $reqId = $attachment->requirement_id;
                                if (!isset($requirementFiles[$reqId])) {
                                    $requirementFiles[$reqId] = [];
                                }
                                $requirementFiles[$reqId][] = $attachment->file_path;
                            }
                        }
                    @endphp

                    @foreach (old('attachments', $request->service->requirements) as $index => $requirement)
                        @php
                            // Check if $requirement is an array or an object
                            $requirementId = is_array($requirement) ? $requirement['requirement_id'] : $requirement->id;
                            $requirementName = is_array($requirement) ? $requirement['name'] : $requirement->name;
                        @endphp

                        <div class="requirement-upload flex flex-col mb-2 w-[40%] bg-slate-400/50 p-1">
                            <label for="attachments[{{ $index }}][file_path]" class="file-label">
                                {{ $requirementName }}
                                <span class="ms-2 error hidden">Attachment Required.</span>
                            </label>
                            
                            @if(isset($requirementFiles[$requirementId]) && !empty($requirementFiles[$requirementId]))
                                <div class="mb-2">
                                    <span class="text-gray-600">Current File: </span>
                                    @foreach($requirementFiles[$requirementId] as $filePath)
                                        <a href="{{ route('file.download', basename($filePath)) }}" class="text-blue-600 underline">{{ substr(basename($filePath), 14) }}</a>
                                    @endforeach
                                </div>
                            @endif

                            <input type="file" name="attachments[{{ $index }}][file_path]" class="text-sm file-input" id="file-input-{{ $requirementId }}">
                            <input type="hidden" name="attachments[{{ $index }}][requirement_id]" value="{{ $requirementId }}">
                            <input type="hidden" name="attachments[{{ $index }}][name]" value="{{ $requirementName }}">
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="btn-primary mt-5">Submit</button>
            </form>
        </section>
    </main>

</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('application-form');
        const fileInputs = document.querySelectorAll('.file-input');
        const nameInput = form.querySelector('input[name="name"]');
        const nameError = form.querySelector('span.error[for="name"]');
        const addressInput = form.querySelector('input[name="address"]');
        const addressError = form.querySelector('span.error[for="address"]');
        const contactInput = form.querySelector('input[name="contact"]');
        const contactError = form.querySelector('span.error[for="contact"]');

        form.addEventListener('submit', function (event) {
            let isValid = true;

            // Validate Name
            if (!nameInput.value.trim()) {
                nameError.classList.remove('hidden');
                nameError.style.display = 'inline';
                isValid = false;
            } else {
                nameError.classList.add('hidden');
                nameError.style.display = 'none';
            }

            // Validate Address
            if (!addressInput.value.trim()) {
                addressError.classList.remove('hidden');
                addressError.style.display = 'inline';
                isValid = false;
            } else {
                addressError.classList.add('hidden');
                addressError.style.display = 'none';
            }

            // Validate Contact
            if (!contactInput.value.trim()) {
                contactError.classList.remove('hidden');
                contactError.style.display = 'inline';
                isValid = false;
            } else {
                contactError.classList.add('hidden');
                contactError.style.display = 'none';
            }

            // Validate File Inputs
            fileInputs.forEach(fileInput => {
                const container = fileInput.closest('.requirement-upload');
                const error = container.querySelector('.error');

                if (!fileInput.files || fileInput.files.length === 0) {
                    error.classList.remove('hidden');
                    error.style.display = 'inline';
                    isValid = false;
                } else {
                    error.classList.add('hidden');
                    error.style.display = 'none';
                }
            });

            // Prevent form submission if any validation fails
            if (!isValid) {
                event.preventDefault();
            }
        });

            // Update file input change handler to manage visual styles
    fileInputs.forEach(fileInput => {
        fileInput.addEventListener('change', function () {
            const container = this.closest('.requirement-upload');
            const label = container.querySelector('.file-label');
            const error = container.querySelector('.error');

            if (this.files && this.files.length > 0) {
                container.style.backgroundColor = '#10B981';
                container.style.color = '#ffffff';
                if (label) {
                    label.style.color = '#ffffff';
                }
                if (error) {
                    error.classList.add('hidden');
                    error.style.display = 'none';
                }
            } else {
                container.style.backgroundColor = 'rgba(156, 163, 175, 0.5)';
                container.style.color = '';
                if (label) {
                    label.style.color = '';
                }
            }
        });
    });

    });

</script>