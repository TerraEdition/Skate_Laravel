<?php

namespace App\Excel;

use App\Helpers\Excel;
use App\Models\Tournament as ModelsTournament;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Participant
{
    public static function export_excel_participant($tournament_slug, $group_slug)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $tournament = ModelsTournament::get_detail_by_slug($tournament_slug);
        $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
        $participant = TournamentParticipant::get_by_group_slug($group_slug, ($group->status == 2 ? true : false));
        $spreadsheet->getSheet(0)->setTitle('Hasil Lomba');
        # sheet pendaftaran
        $sheet->setCellValueExplicit('A1', strtoupper('Hasil Lomba Turnamen : ' . $tournament->tournament), DataType::TYPE_STRING);
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::bold()));
        $sheet->setCellValueExplicit('A3', strtoupper('RACE ' . $tournament->tournament), DataType::TYPE_STRING);
        $sheet->getStyle('A3')->applyFromArray(array_merge_recursive(Excel::left_top(), Excel::bold()));
        $sheet->mergeCells('A3:C3');
        $sheet->setCellValueExplicit('D3', '1', DataType::TYPE_STRING);
        $sheet->getStyle('D3')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::bold()));
        $sheet->setCellValueExplicit('E3', strtoupper($group->group), DataType::TYPE_STRING);
        $sheet->getStyle('E3')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::bold()));

        $sheet->setCellValueExplicit('A4', "NO", DataType::TYPE_STRING);
        $sheet->getStyle('A4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border(), Excel::bold()));
        $sheet->setCellValueExplicit('B4', "NO BIB", DataType::TYPE_STRING);
        $sheet->getStyle('B4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border(), Excel::bold()));
        $sheet->setCellValueExplicit('C4', "NAMA ATLET", DataType::TYPE_STRING);
        $sheet->getStyle('C4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border(), Excel::bold()));
        $sheet->setCellValueExplicit('D4', "TIM", DataType::TYPE_STRING);
        $sheet->getStyle('D4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border(), Excel::bold()));
        $sheet->setCellValueExplicit('E4', "WAKTU", DataType::TYPE_STRING);
        $sheet->getStyle('E4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border(), Excel::bold()));

        $col = "A";
        $row = 5;
        foreach ($participant as $k => $member) {
            $sheet->setCellValueExplicit($col . $row, $k + 1, DataType::TYPE_STRING);
            $sheet->getStyle($col++ . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
            # NO BIB
            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
            $sheet->setCellValueExplicit($col++ . $row, $member->no_participant, DataType::TYPE_STRING);

            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
            $sheet->setCellValueExplicit($col++ . $row, $member->member, DataType::TYPE_STRING);
            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
            $sheet->setCellValueExplicit($col++ . $row, $member->team, DataType::TYPE_STRING);
            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
            $sheet->setCellValueExplicit($col++ . $row, $member->time, DataType::TYPE_STRING);
            $col = 'A';
            $row++;
        }
        foreach ($sheet->getColumnIterator() as $column) {
            $columnIndex = $column->getColumnIndex();
            $sheet->getColumnDimension($columnIndex)->setAutoSize(true);
        }
        # Save file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $tournament->tournament . '_' . $group->group . '.xlsx"');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $writer->save('php://output');
    }
}
