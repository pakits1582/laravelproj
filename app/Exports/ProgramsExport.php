<?php

namespace App\Exports;

use App\Models\Configuration;
use App\Models\Program;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProgramsExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithStartRow
{
    use Exportable, RegistersEventListeners;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $input = request()->all();

        $query = Program::with(['level', 'collegeinfo', 'headinfo'])->orderBy('code');

        if(!empty($input['keyword'])){
            $query->where('code', 'like', '%'.$input['keyword'].'%')->orWhere('name', 'like', '%'.$input['keyword'].'%');
        }
        if(!empty($input['educational_level'])) {
            $query->where('educational_level_id', $input['educational_level']);
        }
        if(!empty($input['college'])) {
            $query->where('college_id', $input['college']);
        }
        if(!empty($input['status']) && ($input['status'] == '0' || $input['status'] == '1')) {
            $query->where('active', $input['status']);
        }
        $programs = $query->get();

        return $programs;
    }

    public function map($program): array
    {
        return [
            $program->id,
            $program->code,
            $program->name,
            $program->years,
            $program->level->level,
            $program->collegeinfo->code,
            $program->headName,
            ($program->active === 1) ? 'YES' : 'NO',
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'CODE',
            'NAME',
            'YEARS',
            'LEVEL',
            'COLLEGE',
            'HEAD',
            'ACTIVE',
        ];
    }

    public function startRow(): int
    {
        return 5;
    }

    public static function beforeSheet(BeforeSheet $event)
    {
        $configuration = Configuration::take(1)->first();

        $event->sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true
            ]
        ]);

        // $event->sheet->setFont(array(
        //     'family'     => 'Calibri',
        //     'size'       => '15',
        //     'bold'       => true
        // ));

        $event->sheet->getDelegate()->getStyle('A2:H2')->getFont()->setSize(8);

        $event->sheet->getDelegate()->mergeCells('A1:H1');
        $event->sheet->setCellValue('A1', $configuration->name);
        $event->sheet->getDelegate()->getStyle('A1')
                                    ->getAlignment()
                                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        $event->sheet->getDelegate()->mergeCells('A2:H2');
        $event->sheet->setCellValue('A2', $configuration->address);
        $event->sheet->getDelegate()->getStyle('A2')
                                    ->getAlignment()
                                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $event->sheet->getDelegate()->mergeCells('A3:H3');
        $event->sheet->setCellValue('A3', 'Programs');
        $event->sheet->getDelegate()->getStyle('A3')
                                    ->getAlignment()
                                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle('A3:H3')
                                    ->getFont()
                                    ->setBold(true);
        
        $event->sheet->getDelegate()->mergeCells('A4:H4');
        $event->sheet->setCellValue('A4', '');
    }

    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->getStyle('A5:H5')->applyFromArray([
            'font' => [
                'bold' => true
            ]
        ]);

        $event->sheet->getDelegate()->getStyle('A5:H5')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }
}
