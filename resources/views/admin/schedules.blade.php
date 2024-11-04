<x-layout>
    <main class="text-lg text-slate-900">
        <div class="flex justify-between items-center">
            <h1 class="text-lg">List of your Schedules</h1>
            <a href="{{ route('schedules.export') }}" class="btn-secondary flex items-center gap-x-2">
                <span>Export</span>
            </a>
        </div>
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
                <select name="filter" value="{{ request('filter') }}" style="margin-bottom: 0">
                    <option value="" >All Services</option>
                   @foreach ($services as $service)
                    <option value="{{ strtolower($service->name) }}" @selected( request('filter') === strtolower($service->name) )>{{ $service->name }}</option>
                   @endforeach
                </select>
                <select name="sort" style="margin-bottom: 0">
                    <option value="" disabled hidden selected>Sort by</option>
                    <option value="date" @selected( request('sort') === 'date' )>Meeting Date</option>
                    <option value="created_at" @selected( request('sort') === 'created_at' )>Approved Date</option>
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
                    @php
                        $formatDate = new DateTime($schedule->date);
                        $formatTime = new DateTime($schedule->time);
                    @endphp
                    <tr class="bg-white/40 border border-slate-500/50 cursor-pointer hover:bg-white/70 duration-300">
                        <td class="p-3 text-sky-700">{{ strtoupper($schedule->request->tracking_no) }}</td>
                        <td class="p-3">{{ $schedule->request->fullName() }}</td>
                        <td class="p-3">{{ $schedule->request->service->name }}</td>
                        <td class="p-3">{{ $schedule->created_at->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $formatDate->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $formatTime->format('h:m A') }}</td>
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

