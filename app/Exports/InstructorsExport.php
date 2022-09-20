<?php

namespace App\Exports;

use App\Libs\Helpers;
use App\Models\Instructor;
use App\Models\Configuration;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class InstructorsExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithStartRow
{
    use Exportable, RegistersEventListeners;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $input = request()->all();

        $query = Instructor::with(['collegeinfo', 'deptinfo']);

        if(!empty($input['keyword'])) {
            $query->where('last_name', 'like', '%'.$input['keyword'].'%')
                    ->orWhere('first_name', 'like', '%'.$input['keyword'].'%')
                    ->orWhere('middle_name', 'like', '%'.$input['keyword'].'%')
                    ->orWhereHas('user', function (Builder $query) use($input) {
                        $query->where('idno', 'like', '%'.$input['keyword'].'%');
                    });
        }
        if(!empty($request['educational_level'])) {
            $query->where('educational_level_id', $request['educational_level']);
        }
        if(!empty($request['college'])) {
            $query->where('college_id', $request['college']);
        }
        if(!empty($request['department'])) {
            $query->where('department_id', $request['department']);
        }

        return $query->orderBy('last_name')->orderBy('first_name')->get();

    }

    public function map($instructor): array
    {
        return [
            $instructor->id,
            $instructor->user->idno,
            $instructor->name,
            $instructor->collegeinfo->code,
            $instructor->educlevel->level,
            $instructor->deptcode,
            Helpers::getDesignation($instructor->designation),
            ($instructor->user->is_active == 1) ? 'Active' : 'Inactive',
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'ID NUMBER',
            'NAME',
            'COLLEGE',
            'LEVEL',
            'DEPARTMENT',
            'DESIGNATION',
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
        $event->sheet->setCellValue('A3', 'INSTRUCTORS');
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
