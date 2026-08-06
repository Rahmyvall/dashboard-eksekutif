<?php

declare (strict_types = 1);

namespace App\Exports;

use App\Models\PerformanceIndicator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerformanceIndicatorsExport implements
FromCollection,
WithHeadings,
WithMapping,
ShouldAutoSize,
WithColumnFormatting,
WithStyles
{
    public function __construct(
        private readonly Collection $performanceIndicators
    ) {
    }

    public function collection(): Collection
    {
        return $this->performanceIndicators;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Kode',
            'Nama Indikator',
            'Deskripsi',
            'Satuan',
            'Bobot (%)',
            'Arah Target',
            'Status',
            'Dibuat',
            'Diperbarui',
        ];
    }

    public function map($performanceIndicator): array
    {
        static $number = 0;
        $number++;

        $directionOptions = PerformanceIndicator::targetDirectionOptions();
        $statusOptions    = PerformanceIndicator::statusOptions();

        return [
            $number,
            $performanceIndicator->code,
            $performanceIndicator->name,
            $performanceIndicator->description ?? '-',
            $performanceIndicator->unit,
            (float) $performanceIndicator->weight,
            $directionOptions[$performanceIndicator->target_direction] ?? $performanceIndicator->target_direction,
            $statusOptions[$performanceIndicator->status] ?? $performanceIndicator->status,
            optional($performanceIndicator->created_at)->format('d/m/Y H:i'),
            optional($performanceIndicator->updated_at)->format('d/m/Y H:i'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => '0.00',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = max(1, $this->performanceIndicators->count() + 1);

        $sheet->freezePane('A2');
        $sheet->setAutoFilter("A1:J{$lastRow}");
        $sheet->getStyle("A1:J{$lastRow}")
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);

        $sheet->getStyle('A1:J1')->applyFromArray([
            'font'      => [
                'bold'  => true,
                'color' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
            'fill'      => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FF4F46E5',
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(26);
        $sheet->getColumnDimension('C')->setWidth(32);
        $sheet->getColumnDimension('D')->setWidth(48);
        $sheet->getColumnDimension('G')->setWidth(30);

        return [];
    }
}
