<x-layout>
    <main class="text-lg text-slate-900">
        <h1 class="text-lg">List of your Transactions</h1>
        <form action="{{ route('schedules.index') }}" method="get" class="text-xs flex justify-between items-center w-full h-20">
            <label for="search" class="flex items-center gap-x-2 bg-white rounded-md p-2 w-[30rem]">
                <x-carbon-search style="width: 1rem;" />
                <input type="search"
                name="search"
                placeholder="Search requests"
                value="{{ request('search') }}"
                class="search outline-none focus:outline-none w-full pe-2"
                >
            </label>
            <section class="flex items-center gap-x-3">
                <select name="filter" value="{{ request('filter') }}">
                    <option value="" hidden selected>Filter by</option>
                    <option value="">All Services</option>
                   @foreach ($services as $service)
                   <option value="{{ strtolower($service->name) }}" @selected( request('filter') === $service->name )>{{ $service->name }}</option>
                   @endforeach
                </select>
                <select name="sort">
                    <option value="" disabled hidden selected>Sort by</option>
                    <option value="name" @selected( request('sort') === 'name' )>Name</option>
                    <option value="created_at" @selected( request('sort') === 'created_at' )>Date</option>
                </select>
                <button type="submit" class="btn-secondary">
                    Apply
                </button>
            </section>
            
        </form>

        <table class="text-sm w-full shadow-md">
            <thead >
                <tr class="bg-white  border border-sky-700/30 border-b-sky-700 text-xs">
                    <th class="text-start text-sky-900 p-3">Tracking No.</th>
                    <th class="text-start text-sky-900 p-3">Applicant</th>
                    <th class="text-start text-sky-900 p-3">Financial Service</th>
                    <th class="text-start text-sky-900 p-3">Date Approved</th>
                    <th class="text-start text-sky-900 p-3">Meeting Date</th>
                    <th class="text-start text-sky-900 p-3">Meeting Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $index => $schedule)
                    <tr class="bg-white/40 border border-slate-500/50 cursor-pointer hover:bg-white/70 duration-300">
                        <td class="p-3 text-sky-700">{{ strtoupper($schedule->tracking_no) }}</td>
                        <td class="p-3">{{ $schedule->request->name }}</td>
                        <td class="p-3">{{ $schedule->request->service->name }}</td>
                        <td class="p-3">{{ $schedule->created_at->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $schedule->date->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $schedule->time->format('g:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%" class="text-center py-20">
                            <span class="text-rose-600 font-medium">
                                Empty
                            </span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($schedules->count())
        <div class="text-xs mt-4">
            {{ $schedules->links()}}
        </div>
    @endif
    </main>

</x-layout>

