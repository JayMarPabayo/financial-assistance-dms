<x-layout>
    <main class="text-lg text-slate-900">
        <h1 class="text-lg">For Schedule Submissions</h1>
        <form action="{{ route('admin.index') }}" method="get" class="text-xs flex justify-between items-center w-full h-20">
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
                    <option value="lastname" @selected( request('sort') === 'lastname' )>Name</option>
                    <option value="created_at" @selected( request('sort') === 'created_at' )>Submission</option>
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
                    <th class="text-start text-sky-900 p-3">Email</th>
                    <th class="text-start text-sky-900 p-3">Contact</th>
                    <th class="text-start text-sky-900 p-3">Date Submitted</th>
                    <th class="text-start text-sky-900 p-3">Time Submitted</th>
                    <th class="text-center text-sky-900 p-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $index => $request)
                    <tr onclick="window.location.href = '{{ route('admin.edit', $request->tracking_no) }}'" class="bg-white/40 border border-slate-500/50 cursor-pointer hover:bg-white/70 duration-300">
                        <td class="p-3 text-sky-700">{{ strtoupper($request->tracking_no) }}</td>
                        <td class="p-3">{{ $request->fullName() }}</td>
                        <td class="p-3">{{ $request->service->name }}</td>
                        <td class="p-3">{{ $request->email ?? 'N/A' }}</td>
                        <td class="p-3">{{ $request->contact ?? 'N/A' }}</td>
                        <td class="p-3">{{ $request->created_at->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $request->created_at->format('g:i A') }}</td>
                        <td class="p-3 text-center">
                            <x-status-badge :status="$request->status" />
                        </td>
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
        @if ($requests->count())
        <div class="text-xs mt-4">
            {{ $requests->links()}}
        </div>
    @endif
    </main>

</x-layout>
