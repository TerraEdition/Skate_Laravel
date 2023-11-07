<?php

namespace App\Excel;

use App\Helpers\Convert;
use App\Helpers\Date;
use App\Helpers\Excel;
use App\Models\TeamMember;
use App\Models\Tournament as ModelsTournament;
use App\Models\TournamentGroup;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Dashboard
{
    public static function export_excel_by_pass()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $group = TournamentGroup::get_all_tournament_closest();
        $spreadsheet->getSheet(0)->setTitle('Pendaftaran');
        # sheet pendaftaran
        $sheet->setCellValueExplicit('A1', 'Form Pendaftaran', DataType::TYPE_STRING);
        $sheet->mergeCells('A1:' . Excel::number_to_alphabet(($group->count() - 1) + 4) . '1');
        $sheet->getStyle('A1')->applyFromArray(array_merge_recursive(Excel::center_top()));
        $sheet->setCellValueExplicit('A4', 'Nama', DataType::TYPE_STRING);
        $sheet->getStyle('A4')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->mergeCells('A4:A6');
        $sheet->setCellValueExplicit('B4', 'Nama Team', DataType::TYPE_STRING);
        $sheet->getStyle('B4')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->mergeCells('B4:B6');
        $sheet->setCellValueExplicit('C4', 'No BIB', DataType::TYPE_STRING);
        $sheet->getStyle('C4')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->mergeCells('C4:C6');
        $sheet->setCellValueExplicit('D4', 'Group', DataType::TYPE_STRING);
        $sheet->getStyle('D4')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->mergeCells('D4:' . Excel::number_to_alphabet(($group->count() - 1) + 4) . '4');
        $col = "D";
        $row = 6;
        foreach ($group as $g) {
            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_middle()));
            $sheet->setCellValueExplicit($col++ . $row, $g->group, DataType::TYPE_STRING);
        }
        $sheet->setCellValueExplicit('D5', 'isi dengan v untuk mendaftar', DataType::TYPE_STRING);
        $sheet->mergeCells('D5:' . Excel::number_to_alphabet(($group->count() - 1) + 4) . '5');
        $sheet->getStyle('D5')->applyFromArray(array_merge_recursive(Excel::center_middle()));

        foreach ($sheet->getColumnIterator() as $column) {
            $columnIndex = $column->getColumnIndex();
            $sheet->getColumnDimension($columnIndex)->setAutoSize(true);
        }
        # Save file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Turnamen.xlsx"');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $writer->save('php://output');
    }
}
