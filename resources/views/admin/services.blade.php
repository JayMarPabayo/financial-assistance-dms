<x-layout>
    <div class="flex items-center justify-between pe-2 mb-5">
        <h1 class="text-lg">List of Services</h1>
        <a href="{{ route('admin.services.create') }}" class="btn-primary text-sm">
            Add New Service
        </a>
    </div>

    <div class="grid grid-cols-2 gap-5">
        @forelse ($services as $service)
            <div class="col-span-1 flex flex-col justify-start bg-stone-100/90 border border-sky-700/80 rounded-md shadow-md py-3 px-4">
                <h1 class="text-sky-900 text-lg font-semibold">{{ $service->name }}</h1>
                <p class="text-slate-700 text-sm mb-3">{{ $service->description }}</p>
                <div class="flex gap-x-2 items-center justify-end mt-auto text-sm">
                    <button title="Edit" class="px-5 py-1 border bg-sky-50 hover:bg-sky-100 border-sky-900/60 rounded-md">
                        <a href="{{ route('admin.services.edit', $service->slug) }}"><x-carbon-edit class="h-5 fill-sky-900" /></a>
                    </button>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="relative group" x-data="{ showConfirmation: false }">
                        @csrf
                        @method('DELETE')
                        <button
                            type="button"
                            title="Remove"
                            class="px-5 py-1 border bg-sky-50 hover:bg-sky-100 border-sky-900/60 rounded-md"
                            x-on:click.prevent="showConfirmation = true">
                                <x-carbon-trash-can class="h-5 fill-sky-900" />
                        </button>
                        <div x-cloak x-show="showConfirmation" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white p-4 rounded-lg shadow">
                                <p class="text-left font-medium text-teal-800 mb-2">Are you sure you want to remove this service?</p>
                                <p class="text-left text-xs mb-5">Deleting this service will also remove all the requests associated with this service.</p>
                                <div class="flex justify-end mt-4 text-xs gap-x-2">
                                    <button type="button" class="btn-secondary" x-on:click="showConfirmation = false">Cancel</button>
                                    <button type="submit" class="btn-reject">Remove</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center">
                No data
            </div>
        @endforelse
    </div>
</x-layout>