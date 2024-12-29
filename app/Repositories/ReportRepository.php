<?php

namespace App\Repositories;

use App\Models\Reservation;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportRepository
{
    public function createExport($request)
    {
        $reservations = Reservation::with(['client_info', 'table_info', 'location_info'])
            ->where('business_id', $request->business_id);

        if ($request->has('cafe') && !empty($request->cafe)) {
            $reservations->whereHas('location_info', function ($query) use ($request) {
                $query->where('id', $request->cafe);
            });
        }

        // if ($request->has('status') && !empty($request->status)) {
        //     $reservations->where('status', $request->status);
        // }
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $reservations->where('status', $request->status);
        }

        if ($request->has('from') && !empty($request->from)) {
            $reservations->whereDate('request_date', '>=', $request->from);
        }
        if ($request->has('to') && !empty($request->to)) {
            $reservations->whereDate('request_date', '<=', $request->to);
        }

        $reservations = $reservations->get();

        return new class($reservations) implements FromCollection, WithHeadings, WithStyles
        {
            protected $reservations;

            public function __construct($reservations)
            {
                $this->reservations = $reservations;
            }

            public function collection()
            {
                return $this->reservations->map(function ($reservation) {
                    $status = '';
                    $paid_status = $reservation->paid_status == 1 ? 'Paid' : 'Not Paid';
                    switch ($reservation->status) {
                        case 0:
                            $status = 'Pending';
                            break;
                        case 1:
                            $status = 'Rejected';
                            break;
                        case 2:
                            $status = 'Confirmed';
                            break;
                        case 3:
                            $status = 'Cancelled';
                            break;
                        case 4:
                            $status = 'Completed';
                            break;
                    }

                    return [
                        'client_name' => $reservation->client_info ? $reservation->client_info->name : '',
                        'client_contact' => $reservation->client_info ? $reservation->client_info->contact : '',
                        'location_name' => $reservation->location_info ? $reservation->location_info->location_name : '',
                        'table_name' => $reservation->table_info ? $reservation->table_info->name : '',
                        'request_date' => $reservation->request_date,
                        'no_of_people' => $reservation->no_of_people,
                        'status' => $status,
                        'paid_status' => $paid_status,
                    ];
                });
            }

            public function headings(): array
            {
                return [
                    'Client Name',
                    'Client Contact',
                    'Location Name',
                    'Table Name',
                    'Request Date',
                    'No of People',
                    'Status',
                    'Paid Status',
                ];
            }

            public function styles(Worksheet $sheet)
            {
                $styleArray = [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '00000000'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $sheet->getStyle('A1:H1')->applyFromArray($styleArray);

                $sheet->getRowDimension(1)->setRowHeight(25);

                $sheet->insertNewRowBefore(2, 1);
                $sheet->getRowDimension(2)->setRowHeight(15);

                $sheet->getStyle('A2:H2')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFFFF'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $dataRange = 'A3:H' . $sheet->getHighestRow();
                $sheet->getStyle($dataRange)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $columns = range('A', 'H');
                foreach ($columns as $column) {
                    $sheet->getColumnDimension($column)->setWidth(20);
                }

                return [];
            }
        };
    }
}
