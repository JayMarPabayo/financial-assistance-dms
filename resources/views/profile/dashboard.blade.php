<x-layout>
    <main class="text-sm text-slate-900">
        <div class="grid grid-cols-3 gap-5 mb-5">
            <div class="bg-emerald-300/70 rounded-sm shadow-sm p-2 col-span-1 flex justify-between items-center">
                <div>
                    <h1 class="font-semibold text-lg text-emerald-900">
                        {{ $forReviewsCount }}
                    </h1>
                    <p class="font-semibold text-slate-600/70">For review</p>
                </div>
                <x-carbon-certificate-check class="fill-emerald-900 h-10" />
            </div>
            <div class="bg-red-300/70 rounded-sm shadow-sm p-2 col-span-1 flex justify-between items-center">
                <div>
                    <h1 class="font-semibold text-lg text-red-900">
                        {{ $rejectedCount }}
                    </h1>
                    <p class="font-semibold text-slate-600/70">Rejected</p>
                </div>
                <x-carbon-data-backup class="fill-red-900 h-10" />
            </div>
            <div class="bg-amber-300/70 rounded-sm shadow-sm p-2 col-span-1 flex justify-between items-center">
                <div>
                    <h1 class="font-semibold text-lg text-amber-900">
                        {{ $forSchedulesCount }}
                    </h1>
                    <p class="font-semibold text-slate-600/70">For schedule</p>
                </div>
                <x-carbon-event-schedule class="fill-amber-900 h-10" />
            </div>
        </div>
        <div class="grid grid-cols-5 gap-5">
            @foreach ($services as $service)
                <div class="bg-white/80 rounded-md shadow-sm p-2 col-span-1">
                    <h1 class="font-medium">
                        <div class="mb-2 text-slate-600 text-base"> {{ $service->name }}</div>
                        <div class="flex items-center gap-x-2 text-emerald-900 mb-1">
                            <div class="w-5 h-5 rounded-full bg-emerald-300/70 text-center">
                                <span>
                                    {{ $service->forReview }}
                                </span>
                            </div>
                            <p class="text-xs text-emerald-900/70">Pending Requests</p>
                        </div>
                    </h1>
                </div>
            @endforeach
        </div>
    </main>
</x-layout>