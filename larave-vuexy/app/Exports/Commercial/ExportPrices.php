<?php

namespace App\Exports\Commercial;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPrices implements FromCollection, WithColumnWidths, WithCustomStartCell, WithEvents, WithHeadings
{
    protected $headers;
    protected $data;
    protected $sequence;
    protected $lastLetter;
    protected $numbers = [];
    protected $lettersNumber;
    public function __construct($headers, $data)
    {
        $this->headers = $headers;
        $this->data = $data;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $collection = [];
        foreach ($this->data as $value) {
            $item = [];
            foreach ($this->headers as $key => $header) {
                if (is_numeric($value->{$header['key']}) && $header['title'] != 'CODIGO') {
                    $item[$header['key']] = number_format($value->{$header['key']}, 2, '.', '');
                    $this->numbers[] = $key;

                } else {
                    $item[$header['title']] = trim($value->{$header['key']});
                }
            }
            $collection[] = $item;
        }
        return collect($collection);
    }

    public function headings(): array
    {
        $rows = [];
        foreach ($this->headers as $header) {
            $rows[] = $header['title'];
        }
        return $rows;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function columnWidths(): array
    {
        $this->generateSequence(count($this->headers));
        return $this->sequence;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // ***** STYLES
                $text_center = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ];

                $background = [
                    'fillType' => 'solid',
                    'rotation' => 0,
                    'color' => ['rgb' => '1b8df4']
                ];

                $border = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ];

                $bold = [
                    'font' => [
                        'bold' => true,
                    ]
                ];

                $color_white = [
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                ];

                $letter_end = $this->lastLetter;
                // ******* TITLE ********
                $event->sheet->mergeCells('A2:' . $letter_end . '2'); // merge cells
                $event->sheet->setCellValue('A2', 'LISTA DE PRECIOS');
                // ****** STYLES ******
                for ($key = 1; $key <= count($this->data); $key++) {
                    $number = (int) $key + 4;
                    $event->sheet->getStyle('A' . $number . ':' . $letter_end . $number)->applyFromArray(array_merge($text_center));
                }
                $event->sheet->getStyle('A2:B2')->applyFromArray(array_merge($text_center, $bold));
                $event->sheet->getStyle('A4:' . $letter_end . '4')->getFill()->applyFromArray($background);
                $event->sheet->getStyle('A4:' . $letter_end . '4')->applyFromArray(array_merge($text_center, $bold, $color_white));
                $event->sheet->getStyle('A4:' . $letter_end . '4')->applyFromArray(array_merge($border));
                // ******* FORMAT NUMBER ******
                foreach ($this->lettersNumber as $letter) {
                    $event->sheet->getStyle($letter . ':' . $letter)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_00);
                }
            }
        ];
    }

    public function generateSequence($number, $part = 0)
    {
        $alphabet = range('A', 'Z');
        $current_sum = 1;

        foreach ($alphabet as $key => $letter) {
            if ($current_sum <= $number) {
                if (in_array($current_sum, [2, 3])) { // column B and C
                    if ($part === 0) {
                        $this->sequence[$letter] = 60; // letter and width of column
                    } else {
                        $this->sequence[$alphabet[$part - 1] . $letter] = 20; // letter and width of column
                    }
                } else {
                    if ($part === 0) {
                        $this->sequence[$letter] = 20; // letter and width of column
                    } else {
                        $this->sequence[$alphabet[$part - 1] . $letter] = 20; // letter and width of column
                    }
                }
                if ($part === 0) {
                    $this->lastLetter = $letter;
                    if (in_array($key, $this->numbers)) {
                        $this->lettersNumber[] = $letter; // letters for format number
                    }
                } else {
                    $this->lastLetter = $alphabet[$part - 1] . $letter;
                    $this->lettersNumber[] = $alphabet[$part - 1] . $letter; // letters for format number
                }
                $current_sum++;
            } else {
                break;
            }
        }

        if (count($alphabet) < $number) {
            $this->generateSequence($number - count($alphabet), $part + 1);
        }

        return;
    }
}
