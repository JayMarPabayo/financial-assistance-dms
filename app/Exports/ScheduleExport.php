<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/** @disregard [OPTIONAL CODE] [OPTIONAL DESCRIPTION] */
class ScheduleExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithCustomStartCell,
    WithEvents
{
    protected $searchKey;

    public function __construct($searchKey)
    {
        $this->searchKey = $searchKey;
    }

    public function query()
    {
        return Schedule::when($this->searchKey, fn($query) => $query->search($this->searchKey));
    }

    public function headings(): array
    {
        return [
            'Tracking No.',
            'Date Approved',
            'Municipality',
            'Financial Service',
            'Applicant',
            'Contact',
            'Email',
            'Reviewed By',
            'Approved By',
        ];
    }

    public function map($schedule): array
    {
        return [
            strtoupper($schedule->request->tracking_no),
            $schedule->created_at->format('d/m/Y'),
            $schedule->request->municipality,
            $schedule->request->service->name,
            $schedule->request->fullName(),
            $schedule->request->contact,
            $schedule->request->email,
            $schedule->request->user->name,
            $schedule->user->name,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Bold headers
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function startCell(): string
    {
        return 'A5'; // Data will start at row 5
    }

    public function registerEvents(): array
    {
        return [
            /** @disregard [OPTIONAL CODE] [OPTIONAL DESCRIPTION] */
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Add titles
                $sheet->getDelegate()->mergeCells('A1:I1');
                $sheet->getDelegate()->mergeCells('A2:I2');
                $sheet->getDelegate()->mergeCells('A3:I3');

                $sheet->setCellValue('A1', '2nd District of Misamis Oriental');
                $sheet->setCellValue('A2', 'Provincial Extension Office');
                $sheet->setCellValue('A3', 'Financial Assistance Requests Archive');

                /** @disregard [OPTIONAL CODE] [OPTIONAL DESCRIPTION] */
                $sheet->getStyle('A1:A3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);


                $sheet->getStyle('A5:I' . $sheet->getHighestRow())->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                ]);
            },
        ];
    }
}
