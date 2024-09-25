<x-layout>
    <header class="text-center h-24 py-3 bg-sky-900 text-white text-4xl font-medium tracking-wide flex items-center justify-center">
        <h1>{{ $service->name }}</h1>
    </header>
    <main class="pt-10 text-lg text-slate-900">
        <p class="mb-7">{{ $service->description }}</p>

        <h1 class="text-sky-700 font-medium text-xl mb-1">Eligibilities</h1>
        <p class="mb-7">{!! nl2br(e($service->eligibility)) !!}</p>

        <h1 class="text-sky-700 font-medium text-xl mb-1">Requirements</h1>
        <p class="mb-7">{!! nl2br(e($service->requirements)) !!}</p>
        
        <div class="border-t-2 border-white mt-20">
            <form action="{{ route('applications.post', $service) }}"
            method="POST"
            enctype="multipart/form-data"
            class="px-5 pb-10 pt-5 mt-4 text-base bg-white shadow-md rounded-md">
                @csrf
                <div class="py-3 px-3 text-xl text-center font-semibold text-sky-900 border-b border-sky-900/20 mb-5">
                    APPLICATION FORM
                </div>

                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <div class="block mb-1">
                    <label for="name" class="mb-1">Applicant</label>
                    @error('name')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" name="name" placeholder="Input full name" value="{{ old('name') }}" class="w-1/2">

                <div class="block mb-1">
                    <label for="address" class="mb-1">Address</label>
                    @error('address')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" name="address" placeholder="Input full address" value="{{ old('address') }}" class="w-3/4">

                <div class="block mb-1">
                    <label for="contact" class="mb-1">Contact No.</label>
                    @error('contact')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" name="contact" placeholder="Telephone/Cellphone No." value="{{ old('contact') }}" class="w-1/2">

                <div class="block mb-1">
                    <label for="email" class="mb-1">Email</label>
                    @error('email')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="email" name="email" placeholder="Email address" value="{{ old('email') }}" class="w-1/2">

                <div class="block mb-1">
                    <label for="files_path" class="mb-1">Attachments (docx, pdf, images)</label>
                    @error('files_path.*')
                        <span class="ms-2 text-xs text-red-600 font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <input type="file" name="files_path[]" id="file-input" multiple="multiple" class="w-full">

                <p id="file-names" class="mt-2 text-sky-700"></p>

                <button type="submit" class="btn-primary mt-5">Submit</button>
            </form>
        </div>
    </main>

    <script>
        const fileInput = document.getElementById('file-input');
        const fileNamesDisplay = document.getElementById('file-names');
        let filesArray = [];
    
        fileInput.addEventListener('change', function(event) {
            const newFiles = Array.from(event.target.files);
            newFiles.forEach((file, index) => {
                file.id = `${file.name}-${Math.random().toString(36).substring(2, 15)}`;
                filesArray.push(file);
            });

            fileInput.value = '';

            renderFileList();

            const dataTransfer = new DataTransfer();
            filesArray.forEach(file => dataTransfer.items.add(file));

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
            filesArray = filesArray.filter(file => file.id !== fileId);
            renderFileList();

            const dataTransfer = new DataTransfer();
            filesArray.forEach(file => dataTransfer.items.add(file));

            fileInput.files = dataTransfer.files;
        }
    </script>
</x-layout>

