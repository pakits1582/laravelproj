<?php

namespace App\Exports;

use App\Models\Configuration;
use App\Models\Subject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SubjectsExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithStartRow
{
    use Exportable, RegistersEventListeners;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $input = request()->all();

        $query = Subject::with(['collegeinfo', 'educlevel'])->orderBy('code');

        if(!empty($input['keyword'])){
            $query->where('code', 'like', '%'.$input['keyword'].'%')->orWhere('name', 'like', '%'.$input['keyword'].'%');
        }
        if(!empty($input['educational_level'])) {
            $query->where('educational_level_id', $input['educational_level']);
        }
        if(!empty($input['college'])) {
            $query->where('college_id', $input['college']);
        }
        if(!empty($input['type'])) {
            if($input['type'] === 'professional'){
                $query->where('professional', 1);
            }
            if($input['type'] === 'laboratory'){
                $query->where('laboratory', 1);
            }
        }
        $subjects = $query->get();

        return $subjects;
    }

    public function map($subject): array
    {
        return [
            ++$this->row,
            $subject->code,
            $subject->name,
            $subject->units,
            $subject->tfunits,
            $subject->loadunits,
            $subject->lecunits,
            $subject->labunits,
            $subject->hours,
            $subject->educlevel->code,
            $subject->collegeinfo->code,
            ($subject->professional == 1) ? 'YES' : 'NO',
            ($subject->laboratory == 1) ? 'YES' : 'NO',
            
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'CODE',
            'NAME',
            'UNITS',
            'LOAD UNITS',
            'TF UNITS',
            'LEC UNITS',
            'LAB UNITS',
            'HOURS',
            'LEVEL',
            'COLLEGE',
            'PROF',
            'LAB',
        ];
    }

    public function startRow(): int
    {
        return 5;
    }

    public static function beforeSheet(BeforeSheet $event)
    {
        $configuration = Configuration::take(1)->first();

        $event->sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true
            ]
        ]);

        // $event->sheet->setFont(array(
        //     'family'     => 'Calibri',
        //     'size'       => '15',
        //     'bold'       => true
        // ));

        $event->sheet->getDelegate()->getStyle('A2:M2')->getFont()->setSize(8);

        $event->sheet->getDelegate()->mergeCells('A1:M1');
        $event->sheet->setCellValue('A1', $configuration->name);
        $event->sheet->getDelegate()->getStyle('A1')
                                    ->getAlignment()
                                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        $event->sheet->getDelegate()->mergeCells('A2:M2');
        $event->sheet->setCellValue('A2', $configuration->address);
        $event->sheet->getDelegate()->getStyle('A2')
                                    ->getAlignment()
                                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $event->sheet->getDelegate()->mergeCells('A3:M3');
        $event->sheet->setCellValue('A3', 'SUBJECTS');
        $event->sheet->getDelegate()->getStyle('A3')
                                    ->getAlignment()
                                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle('A3:M3')
                                    ->getFont()
                                    ->setBold(true);
        
        $event->sheet->getDelegate()->mergeCells('A4:M4');
        $event->sheet->setCellValue('A4', '');
    }

    public static function afterSheet(AfterSheet $event)
    {
        $event->sheet->getStyle('A5:M5')->applyFromArray([
            'font' => [
                'bold' => true
            ]
        ]);

        $event->sheet->getDelegate()->getStyle('A5:M5')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }
}
