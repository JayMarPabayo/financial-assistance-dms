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
                    @error('name')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" name="name" placeholder="Input full name" value="{{ old('name', $request->name) }}" class="w-1/2">

                <div class="block">
                    <label for="address" class="mb-1">Address</label>
                    @error('address')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" name="address" placeholder="Input full address" value="{{ old('address', $request->address) }}" class="w-3/4">

                <div class="block">
                    <label for="contact" class="mb-1">Contact No.</label>
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

                <div class="block">
                    <label for="files_path" class="mb-1">Add Attachments (docx, pdf, images)</label>
                    @error('files_path.*')
                        <span class="ms-2 text-xs text-red-600 font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <input type="file" name="files_path[]" id="file-input" multiple="multiple" class="w-full">
                <input type="hidden" name="files_to_remove">
                <p id="file-names" class="mt-2 text-sky-700"></p>
                <p class="error hidden" id="file-error">You must upload at least {{ $request->service->numberOfRequirements }} files.</p>
                <button type="submit" class="btn-primary mt-5">Submit</button>
            </form>
        </section>
    </main>
    <script>
        const fileInput = document.getElementById('file-input');
        const fileNamesDisplay = document.getElementById('file-names');
        const filesToRemoveInput = document.querySelector('input[name="files_to_remove"]');

        const fileError = document.getElementById('file-error');
        const applicationForm = document.getElementById('application-form');
        const numberOfRequirements = {{ $request->service->numberOfRequirements }};


        let filesArray = [];

        // Initialize the files array with existing files
        const existingFiles = @json(json_decode($request->files_path) ?? []);

        existingFiles.forEach(file => {
            filesArray.push({
                name: file,
                id: `${file}-${Math.random().toString(36).substring(2, 15)}`,
                existing: true
            });
        });

        renderFileList();

        fileInput.addEventListener('change', function(event) {
            const newFiles = Array.from(event.target.files);
            newFiles.forEach(file => {
                file.id = `${file.name}-${Math.random().toString(36).substring(2, 15)}`;
                filesArray.push(file);
            });

            fileInput.value = '';
            renderFileList();

            const dataTransfer = new DataTransfer();
            filesArray.filter(file => !file.existing).forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        });

        function renderFileList() {
            fileNamesDisplay.innerHTML = '';

            filesArray.forEach(file => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between mb-1 w-2/6';
                fileItem.innerHTML = `
                    <span>${file.name}</span>
                    <button type="button" class="text-red-600 ml-2 remove-file" data-file-id="${file.id}">Remove</button>
                `;

                fileNamesDisplay.appendChild(fileItem);
            });

            document.querySelectorAll('.remove-file').forEach(button => {
                button.addEventListener('click', function() {
                    const fileId = this.getAttribute('data-file-id');
                    removeFile(fileId);
                });
            });
        }

        function removeFile(fileId) {
            const fileToRemove = filesArray.find(file => file.id === fileId);

            // Add the file name to the hidden input if it is an existing file
            if (fileToRemove && fileToRemove.existing) {
                const filesToRemove = filesToRemoveInput.value ? filesToRemoveInput.value.split(',') : [];
                filesToRemove.push(fileToRemove.name);
                filesToRemoveInput.value = filesToRemove.join(',');
            }

            filesArray = filesArray.filter(file => file.id !== fileId);
            renderFileList();

            const dataTransfer = new DataTransfer();
            filesArray.filter(file => !file.existing).forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }

        applicationForm.addEventListener("submit", function(e) {
            e.preventDefault();

            if(filesArray.length < numberOfRequirements) {
                fileError.classList.remove('hidden');
            } else {
                fileError.classList.add('hidden');
                applicationForm.submit();
            }
        })
    </script>
</x-layout>

