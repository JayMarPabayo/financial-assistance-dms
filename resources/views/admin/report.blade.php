<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Financial Assistance Document Management System</title>
        <link rel="shortcut icon" href="{{ asset('images/misor.png') }}">
        @vite('resources/css/app.css')
        @vite('resources/css/print.css')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    </head>
    <body
    class="bg-slate-100 min-h-screen" style="padding-block: 5rem; padding-inline: 10rem;">
        <main id="printable-area" class="bg-white rounded-md shadow-md mb-5" style="padding-block: 2rem; padding-inline: 1rem;">
            <header class=" flex justify-between mb-10">
                <div class="flex items-center gap-x-2">
                    <img
                    src="{{ asset('images/misor.png') }}"
                    alt="Misamis Oriental logo"
                    class="w-14">
                    <div class="flex flex-col">
                        <span class="text-lg font-medium">2nd District of Misamis Oriental</span>
                        <span class="text-sm text-opacity-70">Provincial Extension Office</span>
                        <span class="text-sm text-opacity-70">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
                <div>
                    <div class="text-lg font-medium">{{ auth()->user()->name }}</div>
                    <div class="text-xs font-medium text-white bg-sky-950 rounded-md py-1 px-2 w-fit">{{ auth()->user()->role }}</div>
                </div>
            </header>
    
            <h1 class="mb-2 text-base font-medium text-sky-800">Schedules</h1>
            <table class="text-sm w-full shadow-md">
                <thead >
                    <tr class="bg-slate-100 border border-sky-700/30 border-b-sky-700 text-xs">
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
        </main>
        <button onclick="printMainContent()" class="btn-primary flex justify-center items-center gap-x-2 hover:scale-105 active:scale-100 duration-300">
            <x-carbon-generate-pdf class="fill-white w-5"/>
            <span>Export</span>
        </button>
    </body>
    <script>
        function printMainContent() {
            window.print();
        }

        async function convertToPdf(employeename) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ unit: 'in', format: 'legal' });
            const canvas = await html2canvas(document.getElementById('printable-area'));
            const imgData = canvas.toDataURL('image/png');
            
            const pdfWidth = doc.internal.pageSize.getWidth();
            const pdfHeight = doc.internal.pageSize.getHeight();
            const imgProps = doc.getImageProperties(imgData);
            const imgHeight = (imgProps.height * pdfWidth) / imgProps.width;

            const paddingBottom = 0;
            const availableHeight = pdfHeight - paddingBottom;
            const finalHeight = imgHeight > availableHeight ? availableHeight : imgHeight;

            doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, finalHeight);
            doc.save(`HRIS-${employeename}.pdf`);
        }
    </script>
</html>
