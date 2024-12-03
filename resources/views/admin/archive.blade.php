<x-layout>
    <main class="text-lg text-slate-900">
        <div class="flex justify-between items-center">
            <h1 class="text-lg">List of your Schedules</h1>
            <a href="{{ route('admin.archive.export', ['search' => request('search')]) }}" class="btn-archive flex items-center gap-x-2">
                <x-carbon-archive class="h-4" />
                <div>Archive</div>
            </a>
        </div>
        <form action="{{ route('archive') }}" method="get" class="text-xs flex justify-between items-center w-full h-20">
            
            <section class="flex items-center gap-x-3">
                <label for="search" class="flex items-center gap-x-2 bg-white rounded-md p-2 w-[25rem]">
                    <x-carbon-search style="width: 1rem;" />
                    <input type="search"
                    name="search"
                    placeholder="Search requests"
                    value="{{ request('search') }}"
                    class="search outline-none focus:outline-none w-full pe-2"
                    >
                </label>
                <select name="municipality" value="{{ request('municipality') }}" class="no-style p-2 rounded-md">
                    <option value="" >All Municipalities</option>
                   @foreach ($municipalities as $municipality)
                    <option value="{{ strtolower($municipality) }}" @selected( request('municipality') === strtolower($municipality) )>{{ $municipality }}</option>
                   @endforeach
                </select>
                <button type="submit" class="btn-secondary">
                    Search
                </button>
            </section>    
            
            <section 
                x-data="{
                    week: '{{ request('week') }}',
                    month: '{{ request('month') }}',
                    year: '{{ request('year') }}',
                    clearOthers(except) {
                        if (except !== 'week') this.week = '';
                        if (except !== 'month') this.month = '';
                        if (except !== 'year') this.year = '';
                    }
                }"
                class="flex items-center gap-x-3"
                >
                <input 
                    type="week" 
                    name="week" 
                    x-model="week" 
                    @input="clearOthers('week')" 
                    class="no-style p-2 rounded-md" 
                />
                <input 
                    type="month" 
                    name="month" 
                    x-model="month" 
                    @input="clearOthers('month')" 
                    class="no-style p-2 rounded-md" 
                />
                <select 
                    name="year" 
                    x-model="year" 
                    @change="clearOthers('year')" 
                    class="no-style p-2 rounded-md w-20"
                >
                    <option value="">Year</option>
                    @for ($year = now()->year; $year >= 2000; $year--)
                        <option value="{{ $year }}">
                            {{ $year }}
                        </option>
                    @endfor
                </select>
                <button type="submit" class="btn-secondary">
                    Filter
                </button>
            </section>
        
        </form>
        <table class="text-sm w-full shadow-md">
            <thead >
                <tr class="bg-white  border border-sky-700/30 border-b-sky-700 text-xs">
                    <th class="text-start text-sky-900 p-3">Tracking No.</th>
                    <th class="text-start text-sky-900 p-3">Date Approved</th>
                    <th class="text-start text-sky-900 p-3">Municipality</th>
                    <th class="text-start text-sky-900 p-3">Financial Service</th>
                    <th class="text-start text-sky-900 p-3">Applicant</th>
                    <th class="text-start text-sky-900 p-3">Contact</th>
                    <th class="text-start text-sky-900 p-3">Email</th>
                    <th class="text-start text-sky-900 p-3">Reviewed by</th>
                    <th class="text-start text-sky-900 p-3">Approved by</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $index => $schedule)
                    @php
                        $formatDate = new DateTime($schedule->date);
                    @endphp
                    <tr class="bg-white/40 border border-slate-500/50 cursor-pointer hover:bg-white/70 duration-300">
                        <td class="p-3 text-sky-700">{{ strtoupper($schedule->request->tracking_no) }}</td>
                        <td class="p-3">{{ $schedule->created_at->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $schedule->request->municipality }}</td>
                        <td class="p-3">{{ $schedule->request->service->name }}</td>
                        <td class="p-3">{{ $schedule->request->fullName() }}</td>
                        <td class="p-3">{{ $schedule->request->contact }}</td>
                        <td class="p-3">{{ $schedule->request->email }}</td>
                        <td class="p-3">{{ $schedule->request->user->name }}</td>
                        <td class="p-3">{{ $schedule->user->name }}</td>
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

