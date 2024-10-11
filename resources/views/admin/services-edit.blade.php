<x-layout>
    <main class="text-sm text-slate-900">
        <div class="mb-3 flex justify-start items-center gap-x-2">
            <x-carbon-web-services-container class="fill-teal-800 h-5" /> 
            <h1 class="font-semibold text-lg text-teal-800/70">NEW SERVICE</h1>
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
            <div class="w-1/2">
                <div class="w-full mb-1">
                    <label for="numberOfRequirements" class="mb-1">No. of Requirements</label>
                    @error('numberOfRequirements')
                        <span class="ms-2 text-xs text-red-600 font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <input type="number" name="numberOfRequirements" placeholder="Input no. of requirements" value="{{ old('numberOfRequirements', $service->numberOfRequirements) }}" class="w-1/2">    
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
        
            <div class="block mb-1">
                <label for="requirements" class="mb-1">Requirements</label>
                @error('requirements')
                    <span class="ms-2 text-xs text-red-600 font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>
            <textarea name="requirements" placeholder="Documents or prerequisites needed to apply for this service." rows="10" class="w-full">{{ old('requirements', $service->requirements) }}</textarea>

            <button type="submit" class="btn-primary mt-5">Submit</button>
        </form>
    </main>
</x-layout>