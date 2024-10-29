<x-layout>
    <header class="text-center h-24 py-3 bg-sky-900 text-white text-4xl font-medium tracking-wide flex items-center justify-center">
        <h1>{{ $service->name }}</h1>
    </header>
    <main class="pt-10 text-lg text-slate-900">
        <p class="mb-7">{{ $service->description }}</p>

        <h1 class="text-sky-700 font-medium text-xl mb-1">Eligibilities</h1>
        <p class="mb-7">{!! nl2br(e($service->eligibility)) !!}</p>

        <h1 class="text-sky-700 font-medium text-xl mb-1">Requirements</h1>
        @if($service->requirements && $service->requirements->isNotEmpty())
            @foreach ($service->requirements as $index => $requirement)
                <div class="flex items-center gap-x-2">
                    <div>{{ ++$index }}.</div>
                    <div>{{ $requirement->name }}</div>
                    <div class="text-sm italic font-normal text-slate-800/70">{{ $requirement->details ? "($requirement->details)" : '' }}</div>
                </div>
            @endforeach
        @else
        <div>No requirements available.</div>
    @endif
        
        <div class="border-t-2 border-white mt-20">
            <form action="{{ route('applications.post', $service) }}"
            method="POST"
            enctype="multipart/form-data"
            class="px-5 pb-10 pt-5 mt-4 text-base bg-white shadow-md rounded-md"
            id="application-form">
                @csrf
                <div class="py-3 px-3 text-xl text-center font-semibold text-sky-900 border-b border-sky-900/20 mb-5">
                    APPLICATION FORM
                </div>

                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <div class="block mb-1">
                    <label for="name" class="mb-1">Applicant</label>
                    <span class="ms-2 error hidden" for="name">Applicant name is Required.</span>
                    @error('name')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" name="name" placeholder="Input full name" value="{{ old('name') }}" class="w-1/2">

                <div class="block mb-1">
                    <label for="address" class="mb-1">Address</label>
                    <span class="ms-2 error hidden" for="address">Address is Required.</span>
                    @error('address')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" name="address" placeholder="Input full address" value="{{ old('address') }}" class="w-3/4">

                <div class="block mb-1">
                    <label for="contact" class="mb-1">Contact No.</label>
                    <span class="ms-2 error hidden" for="contact">Contact Info is Required.</span>  
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
                    <h1 class="mb-2">Attachments</h1>

                    @foreach (old('attachments', $service->requirements) as $index => $requirement)
                            <div class="requirement-upload flex flex-col mb-2 w-[40%] bg-slate-400/50 p-1">
                                <label for="attachments[{{ $index }}][file_path]" class="file-label">
                                    {{ old("attachments.{$index}.name") ? old("attachments.{$index}.name") : $requirement->name }}
                                    <span class="ms-2 error hidden">Attachment Required.</span>
                                </label>
                                <input type="file" name="attachments[{{ $index }}][file_path]" class="text-sm file-input">
                                <input type="hidden" name="attachments[{{ $index }}][requirement_id]" value="{{ old("attachments.{$index}.requirement_id") ? old("attachments.{$index}.requirement_id") : $requirement->id }}">
                                <input type="hidden" name="attachments[{{ $index }}][name]" value="{{ old("attachments.{$index}.name") ? old("attachments.{$index}.name") : $requirement->name }}">
                            </div>
                    @endforeach
                </div>
              
                
                <button type="submit" class="btn-primary mt-5">Submit</button>
            </form>
        </div>
    </main>

    <script>

    </script>
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('application-form');
    const fileInputs = document.querySelectorAll('.file-input');

    // Grab the other form fields for validation
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