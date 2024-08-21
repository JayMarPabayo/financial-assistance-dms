<x-layout>
    <header class="text-center h-24 py-3 bg-sky-900 text-white text-4xl font-medium tracking-wide flex items-center justify-center">
        <form action="{{ route('requests.show') }}" method="get" class="w-1/2 flex items-center gap-x-3">
            <input type="search"
            name="search"
            placeholder="Input tracking number"
            value="{{ request('search') }}"
            class="search w-full text-sm px-2 py-2 rounded-md border-sky-600 focus:outline-none focus:border-sky-400 focus:outline-sky-400 focus:outline-1 duration-300 text-sky-800"
            >
            <button type="submit"
            class="w-72 text-sm bg-white font-medium text-sky-800 py-2"
            >
                Check
            </button>
        </form>
    </header>
    <main class="w-full min-h-svh px-60 pt-10 bg-white/50 text-lg text-slate-900 pb-5">
        @if(request('search') || request('search') != '')
            @if ($request)
                <table class="text-sm w-full shadow-md">
                    <thead>
                        <tr class="bg-sky-700 border border-sky-700">
                            <th class="text-start text-white py-2 px-3">Tracking No.</th>
                            <th class="text-start text-white py-2 px-3">Applicant</th>
                            <th class="text-start text-white py-2 px-3">Service</th>
                            <th class="text-start text-white py-2 px-3">Submitted at</th>
                            <th class="text-center text-white py-2 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border border-slate-500/50">
                            <td class="py-2 px-3">{{ strtoupper($request->tracking_no) }}</td>
                            <td class="py-2 px-3">{{ $request->name }}</td>
                            <td class="py-2 px-3">{{ $request->service->name }}</td>
                            <td class="py-2 px-3">{{ $request->updated_at->format('d/m/Y') }}</td>
                            <td class="py-2 px-3 text-center">
                                <x-status-badge :status="$request->status" />
                            </td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="text-center py-20">
                    <h2 class="text-2xl font-semibold text-slate-700">Request Not Found</h2>
                    <p class="text-slate-600">Please check the tracking number and try again.</p>
                </div>
            @endif
        @endif
    </main>

</x-layout>

