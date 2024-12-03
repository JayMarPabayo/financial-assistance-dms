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
                {{-- <input type="text" name="name" placeholder="Input full name" value="{{ old('name', $request->name) }}" class="w-1/2"> --}}
                <div class="flex justify-stretch gap-x-3 w-3/4">
                    <input type="text" autocomplete="off" name="firstname" placeholder="First Name" value="{{ old('firstname', $request->firstname) }}" class="w-1/3">
                    <input type="text" autocomplete="off" name="middlename" placeholder="Middle Name" value="{{ old('middlename', $request->middlename) }}" class="w-1/3">
                    <input type="text" autocomplete="off" name="lastname" placeholder="Last Name" value="{{ old('lastname', $request->lastname) }}" class="w-1/3">
                    <select name="name_extension" class="w-32"> 
                        <option value="">None</option>
                        @foreach ($nameExtensions as $suffix)
                        <option value="{{ $suffix }}" {{ old('name_extension', $request->name_extension) === $suffix ? 'selected' : '' }}>{{ $suffix }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="block mb-1">
                    <label for="birthdate" class="mb-1">Birthdate</label>
                    <span class="ms-2 error hidden" for="birthdate">Birthdate is Required</span>
                    <span class="ms-2 error hidden" for="gender">Gender is Required</span>
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
                    <input type="date" name="birthdate" value="{{ old('birthdate', $request->birthdate) }}" class="w-3/5">
                    <select name="gender" class="w-2/5"> 
                        <option value="" hidden>Select Gender</option>
                        <option value="Male" {{ old('gender', $request->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $request->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="block">
                    <label for="address" class="mb-1">Full Address</label>
                    <span class="ms-2 error hidden" for="address">Address is Required.</span>
                    @error('address')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="text" autocomplete="off" name="address" placeholder="Block / Street / Barangay / Municipality / Province"" value="{{ old('address', $request->address) }}" class="w-3/4">

                <div class="block mb-1">
                    <label for="municipality" class="mb-1">Municipality</label>
                    <span class="ms-2 error hidden" for="municipality">● Municipality is Required</span>
                    @error('municipality')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="flex justify-stretch gap-x-3 w-1/2">
                    <select name="municipality" class="w-3/5">
                        <option value="" hidden>Select Municipality</option>.
                        @foreach ($municipalities as $municipality)
                        <option value="{{ $municipality }}" {{ old('municipality', $request->municipality) === $municipality ? 'selected' : '' }}>{{ $municipality }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="block">
                    <label for="contact" class="mb-1">Contact No.</label>
                    <span class="ms-2 error hidden" for="contact">Contact Info is Required.</span> 
                    @error('contact')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                
                <input type="text" autocomplete="off" name="contact" placeholder="Telephone/Cellphone No." value="{{ old('contact', $request->contact) }}" class="w-1/2">

                <div class="block">
                    <label for="email" class="mb-1">Email</label>
                    @error('email')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="email" name="email" placeholder="Email address" value="{{ old('email', $request->email) }}" class="w-1/2">

                @if ($request->service->name === "Burial Assistance")
                    <hr class="border-t my-3" />
                    <div class="block mb-1">
                        <label for="deceased_person" class="mb-1">Deceased Person</label>
                        <span class="ms-2 error hidden" for="deceased_person">Deceased Person Full Name is Required</span>  
                        @error('deceased_person')
                            <span class="ms-2 text-xs text-red-600 font-medium">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <input type="text" name="deceased_person" placeholder="Full name" value="{{ old('deceased_person', $request->deceased_person) }}" class="w-1/2">
                @endif

                <div class="block mb-1">
                    <h1 class="mb-2">Attachments <span class="text-sm text-slate-500 ms-2">(pdf, jpeg, jpg, png & gif)</span></h1>

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

                            <input
                            type="file"
                            name="attachments[{{ $index }}][file_path]"
                            class="text-sm file-input"
                            id="file-input-{{ $requirementId }}"
                            accept="image/png, image/jpeg, image/jpg, image/gif, application/pdf" />
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

        const firstameInput = form.querySelector('input[name="firstname"]');
        const lastnameInput = form.querySelector('input[name="lastname"]');
        const nameError = form.querySelector('span.error[for="name"]');
        const addressInput = form.querySelector('input[name="address"]');
        const addressError = form.querySelector('span.error[for="address"]');
        const contactInput = form.querySelector('input[name="contact"]');
        const contactError = form.querySelector('span.error[for="contact"]');
        const emailInput = form.querySelector('input[name="email"]');
        const emailError = form.querySelector('span.error[for="email"]');
        const birthdateInput = form.querySelector('input[name="birthdate"]');
        const birthdateError = form.querySelector('span.error[for="birthdate"]');
        const genderSelect = form.querySelector('select[name="gender"]');
        const genderError = form.querySelector('span.error[for="gender"]');
        const municipalitySelect = form.querySelector('select[name="municipality"]');
        const municipalityError = form.querySelector('span.error[for="municipality"]');

        const deceasedPersonInput = form.querySelector('input[name="deceased_person"]');
        const deceasedPersonError = form.querySelector('span.error[for="deceased_person"]');

        birthdateInput.max = new Date().toISOString().split("T")[0];

        form.addEventListener('submit', function (event) {
            let isValid = true;

            event.preventDefault();

             if (!firstameInput.value.trim()) {
                nameError.classList.remove('hidden');
                nameError.style.display = 'inline';
                isValid = false;
            } else {
                nameError.classList.add('hidden');
                nameError.style.display = 'none';
            }

            if (!lastnameInput.value.trim()) {
                nameError.classList.remove('hidden');
                nameError.style.display = 'inline';
                isValid = false;
            } else {
                nameError.classList.add('hidden');
                nameError.style.display = 'none';
            }

            if (!municipalitySelect.value || municipalitySelect.value === "") {
                municipalityError.textContent = 'Municipality is Required';
                municipalityError.classList.remove('hidden');
                municipalityError.style.display = 'inline';
                isValid = false;
            } else {
                municipalityError.classList.add('hidden');
                municipalityError.style.display = 'none';
            }


            if (!addressInput.value.trim()) {
                addressError.classList.remove('hidden');
                addressError.style.display = 'inline';
                isValid = false;
            } else {
                addressError.classList.add('hidden');
                addressError.style.display = 'none';
            }

            if (!contactInput.value.trim()) {
                contactError.classList.remove('hidden');
                contactError.style.display = 'inline';
                isValid = false;
            } else {
                contactError.classList.add('hidden');
                contactError.style.display = 'none';
            }

            const birthdateValue = new Date(birthdateInput.value);
            const minAgeDate = new Date();
            minAgeDate.setFullYear(minAgeDate.getFullYear() - 18);

            if (!birthdateInput.value.trim()) {
                birthdateError.textContent = 'Birthdate is Required';
                birthdateError.classList.remove('hidden');
                birthdateError.style.display = 'inline';
                isValid = false;
            } else if (birthdateValue > minAgeDate) {
                birthdateError.textContent = 'Applicant must be at least 18 years old';
                birthdateError.classList.remove('hidden');
                birthdateError.style.display = 'inline';
                isValid = false;
            } else {
                birthdateError.classList.add('hidden');
                birthdateError.style.display = 'none';
            }

            if (!genderSelect.value || genderSelect.value === "") {
                genderError.textContent = 'Gender is Required';
                genderError.classList.remove('hidden');
                genderError.style.display = 'inline';
                isValid = false;
            } else {
                genderError.classList.add('hidden');
                genderError.style.display = 'none';
            }

            if(deceasedPersonInput) {
                if (!deceasedPersonInput.value.trim()) {
                    deceasedPersonError.classList.remove('hidden');
                    deceasedPersonError.style.display = 'inline';
                    isValid = false;
                } else {
                    deceasedPersonError.classList.add('hidden');
                    deceasedPersonError.style.display = 'none';
                }
            }
            

            fileInputs.forEach(fileInput => {
                const container = fileInput.closest('.requirement-upload');
                const error = container.querySelector('.error');

                const currentFiles = container.querySelectorAll('.text-blue-600');
                const fileAlreadyUploaded = currentFiles.length > 0;
                
                if ((!fileInput.files || fileInput.files.length === 0) && !fileAlreadyUploaded) {
                    error.classList.remove('hidden');
                    error.style.display = 'inline';
                    isValid = false;
                } else {
                    error.classList.add('hidden');
                    error.style.display = 'none';
                }
            });

            if(isValid) form.submit();
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
    });

</script>