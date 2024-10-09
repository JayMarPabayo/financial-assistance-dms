<x-layout>
    <main class="text-lg text-slate-900">
        <h1 class="text-lg mb-5">List of your Transactions</h1>
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
                        <td class="p-3">{{ $schedule->request->name }}</td>
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

