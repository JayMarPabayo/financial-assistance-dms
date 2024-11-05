<x-layout>
    <header class="text-center h-24 py-3 bg-sky-900 text-white text-4xl font-medium tracking-wide flex items-center justify-center">
        <form action="{{ route('applications.show') }}" method="get" class="w-1/2 flex items-center gap-x-3">
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
    <main class="pt-10 text-lg text-slate-900">
        @if(request('search') || request('search') != '')
            @if ($request)
                <table class="text-sm w-full shadow-md">
                    <thead>
                        <tr class="bg-sky-700 border border-sky-700">
                            <th class="text-start text-white py-2 px-3">Tracking No.</th>
                            <th class="text-start text-white py-2 px-3">Applicant</th>
                            <th class="text-start text-white py-2 px-3">Service</th>
                            <th class="text-start text-white py-2 px-3">Submitted at</th>
                            @if ($request->status === "Approved")
                                <th class="text-start text-white py-2 px-3">Approved at</th>
                            @endif
                            <th class="text-center text-white py-2 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border border-slate-500/50 cursor-pointer hover:bg-white/90 duration-300">
                            <td class="py-2 px-3">{{ strtoupper($request->tracking_no) }}</td>
                            <td class="py-2 px-3">{{ $request->fullName()}}</td>
                            <td class="py-2 px-3">{{ $request->service->name }}</td>
                            <td class="py-2 px-3">{{ $request->created_at->format('d/m/Y') }}</td>
                            @if ($request->status === "Approved")
                            <td class="py-2 px-3">{{ $request->updated_at->format('d/m/Y') }}</td>
                            @endif
                            <td class="py-2 px-3 text-center">
                                <x-status-badge :status="$request->status" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                @if (session('prompt'))
                    <div class="flex items-center justify-center gap-x-3 text-emerald-900 text-sm font-semibold mt-5 p-3 rounded-md bg-yellow-300/60 border border-slate-700">
                        <svg class="fill-emerald-900" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                        width="1.5rem" height="1.5rem" viewBox="0 0 478.125 478.125"
                        xml:space="preserve">
                        <g>
                            <g>
                                <g>
                                    <circle cx="239.904" cy="314.721" r="35.878"/>
                                    <path d="M256.657,127.525h-31.9c-10.557,0-19.125,8.645-19.125,19.125v101.975c0,10.48,8.645,19.125,19.125,19.125h31.9
                                        c10.48,0,19.125-8.645,19.125-19.125V146.65C275.782,136.17,267.138,127.525,256.657,127.525z"/>
                                    <path d="M239.062,0C106.947,0,0,106.947,0,239.062s106.947,239.062,239.062,239.062c132.115,0,239.062-106.947,239.062-239.062
                                        S371.178,0,239.062,0z M239.292,409.734c-94.171,0-170.595-76.348-170.595-170.596c0-94.248,76.347-170.595,170.595-170.595
                                        s170.595,76.347,170.595,170.595C409.887,333.387,333.464,409.734,239.292,409.734z"/>
                                </g>
                            </g>
                        </g>
                        </svg>
                        <p>Please take note of your tracking code. You'll need this code to trace the progress of your request.</p>
                    </div>
                @endif

                @if ($request->status === "Rejected")
                    <div class="text-xs flex justify-between items-center font-semibold mt-5 p-3 rounded-md bg-red-500/30 border border-red-700">
                        <div>
                            {!! nl2br(e($request->message)) !!}
                        </div>
                        <a
                            href="{{ route('applications.edit', $request->tracking_no) }}"
                            class="text-sky-700 font-semibold hover:text-sky-900 hover:underline transition duration-200 ease-in-out px-2 py-1 rounded-md bg-sky-100 hover:bg-sky-200">
                            Edit here ->>
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-20">
                    <h2 class="text-2xl font-semibold text-slate-700">Request Not Found</h2>
                    <p class="text-slate-600">Please check the tracking number and try again.</p>
                </div>
            @endif
        @endif
    </main>

</x-layout>

