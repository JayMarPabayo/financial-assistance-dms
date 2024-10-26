<x-layout>
    <main class="text-sm text-slate-900">
        <div class="mb-3 flex justify-start items-center gap-x-2">
            <x-carbon-web-services-container class="fill-teal-800 h-5" /> 
            <h1 class="font-semibold text-lg text-teal-800/70">UPDATE SERVICE</h1>
            <a href="{{ route('admin.services') }}" class="ms-auto">
                <button title="Return" class="btn-secondary">
                  <x-carbon-launch title="Return" class="w-4" />
                </button>
              </a>
        </div>
        <form action="{{ route('services.update', $service) }}" method="post">
            @csrf
            @method('PUT')
            <section class="flex items-center w-full gap-x-4">
                <div class="w-1/2">
                    <div class="w-full mb-1">
                        <label for="name" class="mb-1">Service Name</label>
                        @error('name')
                            <span class="ms-2 text-xs text-red-600 font-medium">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <input type="text" name="name" placeholder="Input service name" value="{{ old('name', $service->name) }}" class="w-full">
                </div>
            </section>
            <div class="block mb-1">
                <label for="description" class="mb-1">Description</label>
                @error('description')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <textarea name="description" placeholder="Description" rows="5" class="w-full">{{ old('description', $service->description) }}</textarea>

            <div class="block mb-1">
                <label for="eligibility" class="mb-1">Eligibility</label>
                @error('eligibility')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <textarea name="eligibility" placeholder="Criteria or qualifications required to avail of this service." rows="8" class="w-full">{{ old('eligibility', $service->eligibility) }}</textarea>
        
            <div class="flex items-center gap-x-2 mb-1">
                <h1>Requirements</h1>
                <button type="button" id="add-requirement" title="Return" class="bg-slate-100 px-2 rounded-md text-lg text-center text-slate-700 font-medium hover:bg-slate-100/50">
                   +
                </button>
                @error('requirements')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <section id="requirements-section" class="flex flex-col gap-y-2">
                @foreach (old('requirements', $service->requirements) as $index => $requirement)
                    <div class="requirement-input flex items-center gap-x-2">
                        <p>{{ $index + 1 }}.</p>
                        <select
                        name="requirements[{{ $index }}][type]"
                        style="margin-bottom: 0;" >
                            @foreach ($requirementTypes as $type)
                                <option value="{{ $type }}" {{ old("requirements.{$index}.type", $requirement->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
            
                        <input
                            type="text"
                            name="requirements[{{ $index }}][name]"
                            placeholder="Name (Valid ID, Barangay Clearance, etc.)" 
                            value="{{ old("requirements.{$index}.name", $requirement->name) }}"
                            class="search w-80 shadow-inner ps-2 py-2 rounded-md block border focus:outline-none focus:border-sky-400 focus:outline-sky-400 focus:outline-1 duration-300 text-sky-800 {{ $errors->has("requirements.{$index}.name") ? 'border-red-400' : 'border-sky-600' }}"
                            style="margin-bottom: 0;" >

                        <input
                            type="text"
                            name="requirements[{{ $index }}][details]"
                            placeholder="Details (Optional)" 
                            value="{{ old("requirements.{$index}.details", $requirement->details) }}" 
                            class="flex-1"
                            style="margin-bottom: 0;" >
                        
                        <button type="button" class="remove-requirement bg-slate-100 px-2 rounded-md text-lg text-center text-slate-700 font-medium hover:bg-slate-100/50">X</button>
                    </div>
                @endforeach
            </section>
            <button type="submit" class="btn-primary mt-5">Submit</button>
        </form>
    </main>
</x-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addButton = document.getElementById('add-requirement');
        const requirementsSection = document.getElementById('requirements-section');
        let requirementIndex = {{ count(old('requirements', $service->requirements)) }}; // Get the current number of requirements
    
        addButton.addEventListener('click', function () {
            // Create a new div for the requirement input
            const newRequirementDiv = document.createElement('div');
            newRequirementDiv.className = 'requirement-input flex items-center gap-x-2';
    
            // Create new elements for requirement type, name, and details
            newRequirementDiv.innerHTML = `
                <p>${requirementIndex + 1}.</p>
    
                <select name="requirements[${requirementIndex}][type]" class="border" style="margin-bottom: 0;">
                    @foreach ($requirementTypes as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
    
                <input
                type="text"
                name="requirements[${requirementIndex}][name]"
                placeholder="Name (Valid ID, Barangay Clearance, etc.)"
                class="w-80 border"
                style="margin-bottom: 0;">
    
                <input
                type="text"
                name="requirements[${requirementIndex}][details]"
                placeholder="Details (Optional)"
                class="flex-1 border"
                style="margin-bottom: 0;">
                <button type="button" class="remove-requirement bg-slate-100 px-2 rounded-md text-lg text-center text-slate-700 font-medium hover:bg-slate-100/50">X</button>
            `;
    
            requirementsSection.appendChild(newRequirementDiv);
            newRequirementDiv.querySelector('.remove-requirement').addEventListener('click', function () {
                newRequirementDiv.remove();
            });
            requirementIndex++;
        });
    
            document.querySelectorAll('.remove-requirement').forEach(button => {
            button.addEventListener('click', function () {
                button.parentElement.remove();
            });
        });
    });
    </script>
    