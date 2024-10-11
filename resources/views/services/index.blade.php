<x-layout>
    <div class="grid grid-cols-2 gap-5">
        @forelse ($services as $service)
            <div class="col-span-1 flex flex-col justify-start bg-stone-100/90 border border-sky-700/80 rounded-md shadow-md py-3 px-4">
                <h1 class="text-sky-900 text-lg font-semibold">{{ $service->name }}</h1>
                <p class="text-slate-700 text-sm mb-3">{{ $service->description }}</p>
                <div class="flex justify-end mt-auto">
                    <a href="{{ route('services.show', $service ) }}" class="btn-primary">Apply</a>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center">
                No data
            </div>
        @endforelse
    </div>
</x-layout>