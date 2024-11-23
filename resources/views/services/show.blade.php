<x-layout>
    <header class="relative text-center h-24 py-3 bg-sky-900 text-white text-4xl font-medium tracking-wide flex items-center justify-center">
        @if($previousService)
            <a href="{{ route('services.show', $previousService) }}" class="absolute left-5">
                <x-carbon-triangle-left-solid class="h-5 fill-sky-200 cursor-pointer hover:scale-125 hover:fill-white active:scale-100 duration-300" />
            </a>
        @endif

        <h1>{{ $service->name }}</h1>

        @if($nextService)
            <a href="{{ route('services.show', $nextService) }}" class="absolute right-5">
                <x-carbon-triangle-right-solid class="h-5 fill-sky-200 cursor-pointer hover:scale-125 hover:fill-white active:scale-100 duration-300" />
            </a>
        @endif
    </header>
    <main class="pt-10 text-lg text-slate-900">
        <p class="mb-7">{{ $service->description }}</p>

        <h1 class="text-sky-700 font-medium text-xl mb-1">Eligibilities</h1>
        <p class="mb-7">{!! nl2br(e($service->eligibility)) !!}</p>

        <h1 class="text-sky-700 font-medium text-xl mb-1">Requirements</h1>
        @if($service->requirements && $service->requirements->isNotEmpty())
            @foreach ($service->requirements as $index => $requirement)
                <div class="flex items-center gap-x-2">
                    <div>{{ ++$index }}</div>
                    <div>{{ $requirement->name }}</div>
                    <div class="text-sm italic font-normal text-slate-800/70">{{ $requirement->details ? "($requirement->details)" : '' }}</div>
                </div>
            @endforeach
        @else
        <div>No requirements specified</div>
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
                    <span class="ms-2 error hidden" for="firstname">● First name is Required</span>
                    <span class="ms-2 error hidden" for="middlename">● Middle name is Required</span>
                    <span class="ms-2 error hidden" for="lastname">● Last name is Required</span>
                    @error('firstname')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                    @error('middlename')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                    @error('lastname')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="flex justify-stretch gap-x-3 w-3/4">
                    <input type="text" autocomplete="off" name="firstname" placeholder="First Name" value="{{ old('firstname') }}" class="w-1/3">
                    <input type="text" autocomplete="off" name="middlename" placeholder="Middle Name" value="{{ old('middlename') }}" class="w-1/3">
                    <input type="text" autocomplete="off" name="lastname" placeholder="Last Name" value="{{ old('lastname') }}" class="w-1/3">
                    <select name="name_extension" class="w-32"> 
                        <option value="">None</option>
                        @foreach ($nameExtensions as $suffix)
                        <option value="{{ $suffix }}" {{ old('name_extension') === $suffix ? 'selected' : '' }}>{{ $suffix }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="block mb-1">
                    <label for="birthdate" class="mb-1">Birthdate</label>
                    <span class="ms-2 error hidden" for="birthdate">● Birthdate is Required</span>
                    <span class="ms-2 error hidden" for="gender">● Gender is Required</span>
                    @error('birthdate')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                    @error('gender')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="flex justify-stretch gap-x-3 w-1/2">
                    <input type="date" name="birthdate" value="{{ old('birthdate') }}" class="w-3/5">
                    <select name="gender" class="w-2/5"> 
                        <option value="">Select Gender</option>.
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>


                <div class="block mb-1">
                    <label for="address" class="mb-1">Address</label>
                    <span class="ms-2 error hidden" for="address">● Address is Required</span>
                    @error('address')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" autocomplete="off" name="address" placeholder="Input full address" value="{{ old('address') }}" class="w-3/4">

                <div class="block mb-1">
                    <label for="contact" class="mb-1">Contact No</label>
                    <span class="ms-2 error hidden" for="contact">● Contact Info is Required</span>  
                    @error('contact')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" autocomplete="off" name="contact" placeholder="Telephone/Cellphone No." value="{{ old('contact') }}" class="w-1/2">

                <div class="block mb-1">
                    <label for="email" class="mb-1">Email</label>
                    <span class="ms-2 error hidden" for="email">● Email Required</span>  
                    @error('email')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="email" name="email" placeholder="Email address" value="{{ old('email') }}" class="w-1/2">

                @if ($service->name === "Burial Assistance")
                    <hr class="border-t my-3" />
                    <div class="block mb-1">
                        <label for="deceased_person" class="mb-1">Deceased Person</label>
                        <span class="ms-2 error hidden" for="deceased_person">● Deceased Person Full Name is Required</span>  
                        @error('deceased_person')
                            <span class="ms-2 text-xs text-red-600 font-medium">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <input type="text" name="deceased_person" placeholder="Full name" value="{{ old('deceased_person') }}" class="w-1/2">
                @endif
                <div class="block mb-1">
                    @if ($service->requirements->count())
                    <h1 class="mb-2">Attachments</h1>
                    @endif

                    @foreach (old('attachments', $service->requirements) as $index => $requirement)
                            <div class="requirement-upload flex flex-col mb-2 w-[40%] bg-slate-400/50 p-1">
                                <label for="attachments[{{ $index }}][file_path]" class="file-label">
                                    {{ old("attachments.{$index}.name") ? old("attachments.{$index}.name") : $requirement->name }}
                                    <span class="ms-2 error hidden">● Attachment Required</span>
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

    // Grab the form fields for validation
    const firstnameInput = form.querySelector('input[name="firstname"]');
    const middlenameInput = form.querySelector('input[name="middlename"]');
    const lastnameInput = form.querySelector('input[name="lastname"]');
    const addressInput = form.querySelector('input[name="address"]');
    const contactInput = form.querySelector('input[name="contact"]');
    const emailInput = form.querySelector('input[name="email"]');
    const birthdateInput = form.querySelector('input[name="birthdate"]');
    const genderSelect = form.querySelector('select[name="gender"]');
    const deceasedPersonInput = form.querySelector('input[name="deceased_person"]');

    // Error spans
    const errors = {
        firstname: form.querySelector('span.error[for="firstname"]'),
        middlename: form.querySelector('span.error[for="middlename"]'),
        lastname: form.querySelector('span.error[for="lastname"]'),
        address: form.querySelector('span.error[for="address"]'),
        contact: form.querySelector('span.error[for="contact"]'),
        email: form.querySelector('span.error[for="email"]'),
        birthdate: form.querySelector('span.error[for="birthdate"]'),
        gender: form.querySelector('span.error[for="gender"]'),
        deceasedPerson: form.querySelector('span.error[for="deceased_person"]')
    };

    // Max date for birthdate input
    birthdateInput.max = new Date().toISOString().split("T")[0];

    // Add real-time validation
    const inputs = [
        { input: firstnameInput, error: errors.firstname },
        { input: middlenameInput, error: errors.middlename },
        { input: lastnameInput, error: errors.lastname },
        { input: addressInput, error: errors.address },
        { input: contactInput, error: errors.contact },
        { input: emailInput, error: errors.email },
        { input: birthdateInput, error: errors.birthdate },
        { input: genderSelect, error: errors.gender },
        { input: deceasedPersonInput, error: errors.deceasedPerson }
    ];

    inputs.forEach(({ input, error }) => {
        if (input) {
            input.addEventListener('input', function () {
                if (this.type === 'date') {
                    // Special validation for birthdate
                    const birthdateValue = new Date(this.value);
                    const minAgeDate = new Date();
                    minAgeDate.setFullYear(minAgeDate.getFullYear() - 18);

                    if (this.value && birthdateValue <= minAgeDate) {
                        error.classList.add('hidden');
                        error.style.display = 'none';
                    }
                } else if (this.type === 'select-one') {
                    if (this.value) {
                        error.classList.add('hidden');
                        error.style.display = 'none';
                    }
                } else {
                    if (this.value.trim()) {
                        error.classList.add('hidden');
                        error.style.display = 'none';
                    }
                }
            });
        }
    });

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

    form.addEventListener('submit', function (event) {
        let isValid = true;
        event.preventDefault();

        inputs.forEach(({ input, error }) => {
            if (input && !input.value.trim()) {
                error.classList.remove('hidden');
                error.style.display = 'inline';
                isValid = false;
            }
        });

        // Special validation for birthdate
        const birthdateValue = new Date(birthdateInput.value);
        const minAgeDate = new Date();
        minAgeDate.setFullYear(minAgeDate.getFullYear() - 18);

        if (!birthdateInput.value.trim()) {
            errors.birthdate.classList.remove('hidden');
            errors.birthdate.style.display = 'inline';
            isValid = false;
        } else if (birthdateValue > minAgeDate) {
            errors.birthdate.textContent = '● Applicant must be at least 18 years old';
            errors.birthdate.classList.remove('hidden');
            errors.birthdate.style.display = 'inline';
            isValid = false;
        } else {
            errors.birthdate.classList.add('hidden');
            errors.birthdate.style.display = 'none';
        }

        // File inputs validation
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

        if (isValid) form.submit();
    });
});

</script>