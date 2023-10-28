<?php

namespace App\Excel;

use App\Helpers\Convert;
use App\Helpers\Date;
use App\Helpers\Excel;
use App\Models\TeamMember;
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
        $participant = TournamentParticipant::get_by_group_slug($group_slug);
        $spreadsheet->getSheet(0)->setTitle('Pendaftaran');
        # sheet pendaftaran
        $sheet->setCellValueExplicit('A1', strtoupper('Hasil Lomba Turnamen : ' . $tournament->tournament), DataType::TYPE_STRING);
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->applyFromArray(array_merge_recursive(Excel::center_top()));
        $sheet->setCellValueExplicit('A3', strtoupper('RACE ' . $tournament->tournament), DataType::TYPE_STRING);
        $sheet->getStyle('A3')->applyFromArray(array_merge_recursive(Excel::center_top()));
        $sheet->setCellValueExplicit('B3', '1', DataType::TYPE_STRING);
        $sheet->getStyle('B3')->applyFromArray(array_merge_recursive(Excel::center_top()));
        $sheet->setCellValueExplicit('C3', strtoupper($group->group), DataType::TYPE_STRING);
        $sheet->mergeCells('C3:F3');

        $sheet->setCellValueExplicit('A4', "NO", DataType::TYPE_STRING);
        $sheet->getStyle('A4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
        $sheet->setCellValueExplicit('B4', "NO BIB", DataType::TYPE_STRING);
        $sheet->getStyle('B4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
        $sheet->setCellValueExplicit('C4', "NAMA ATLET", DataType::TYPE_STRING);
        $sheet->getStyle('C4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
        $sheet->setCellValueExplicit('D4', "TIM", DataType::TYPE_STRING);
        $sheet->getStyle('D4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
        $sheet->setCellValueExplicit('E4', "WAKTU", DataType::TYPE_STRING);
        $sheet->getStyle('E4')->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));

        $col = "A";
        $row = 5;
        foreach ($participant as $k => $member) {
            $sheet->setCellValueExplicit($col . $row, $k + 1, DataType::TYPE_STRING);
            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
            # NO BIB
            $sheet->getStyle($col++ . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));

            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
            $sheet->setCellValueExplicit($col++ . $row, $member->member, DataType::TYPE_STRING);
            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
            $sheet->setCellValueExplicit($col++ . $row, $member->team, DataType::TYPE_STRING);
            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
            $sheet->setCellValueExplicit($col++ . $row, $member->time, DataType::TYPE_STRING);
            # POSITION
            $sheet->getStyle($col++ . $row)->applyFromArray(array_merge_recursive(Excel::center_top(), Excel::border()));
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
