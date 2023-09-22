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

class Tournament
{
    public static function export_excel($tournament_slug, $team_slug = false)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $tournament = ModelsTournament::get_detail_by_slug($tournament_slug);
        $group = TournamentGroup::get_all('incoming', $tournament_slug);

        $spreadsheet->getSheet(0)->setTitle('Pendaftaran');
        # sheet pendaftaran
        $sheet->setCellValueExplicit('A1', 'Form Pendaftaran', DataType::TYPE_STRING);
        $sheet->mergeCells('A1:' . Excel::number_to_alphabet(($group->count() - 1) + 4) . '1');
        $sheet->getStyle('A1')->applyFromArray(array_merge_recursive(Excel::center_top()));
        $sheet->setCellValueExplicit('A2', 'Menuju Ke Halaman Informasi', DataType::TYPE_STRING);
        $sheet->getStyle('A2')->applyFromArray(array_merge_recursive(Excel::color('315b98'), Excel::underline()));
        $sheet->mergeCells('A2:' . Excel::number_to_alphabet(($group->count() - 1) + 4) . '2');
        $sheet->setCellValueExplicit('A4', 'Nama', DataType::TYPE_STRING);
        $sheet->getStyle('A4')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->mergeCells('A4:A6');
        $sheet->setCellValueExplicit('B4', 'Tanggal Lahir', DataType::TYPE_STRING);
        $sheet->getStyle('B4')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->mergeCells('B4:B5');
        $sheet->setCellValueExplicit('B6', 'd/m/Y (' . date('d/m/Y') . ')', DataType::TYPE_STRING);
        $sheet->getStyle('B6')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->setCellValueExplicit('C4', 'Jenis Kelamin', DataType::TYPE_STRING);
        $sheet->getStyle('C4')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->mergeCells('C4:C5');
        $sheet->setCellValueExplicit('C6', 'L / P', DataType::TYPE_STRING);
        $sheet->getStyle('C6')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->setCellValueExplicit('D4', 'Group', DataType::TYPE_STRING);
        $sheet->getStyle('D4')->applyFromArray(array_merge_recursive(Excel::center_middle()));
        $sheet->mergeCells('D4:' . Excel::number_to_alphabet(($group->count() - 1) + 4) . '4');
        if ($team_slug) {
            $member = TeamMember::get_all_by_team_slug($team_slug);
            $sheet->setCellValueExplicit('A3', $member[0]->team, DataType::TYPE_STRING);
            $sheet->mergeCells('A3:B3');
            $col = 'A';
            $row = 7;
            foreach ($member as $m) {
                $sheet->setCellValueExplicit($col++ . $row, $m->member, DataType::TYPE_STRING);
                $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('d/m/Y');
                $sheet->setCellValueExplicit($col++ . $row, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($m->birth), DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit($col++ . $row, $m->gender == '1' ? 'L' : 'P', DataType::TYPE_STRING);
                $row++;
                $col = 'A';
            }
        }
        $col = 'D';
        $row = 5;
        foreach ($group as $g) {
            $sheet->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::center_middle()));
            $sheet->setCellValueExplicit($col++ . $row, $g->group, DataType::TYPE_STRING);
        }
        $sheet->setCellValueExplicit('D6', 'isi dengan angka 1 untuk mendaftar', DataType::TYPE_STRING);
        $sheet->mergeCells('D6:' . Excel::number_to_alphabet(($group->count() - 1) + 4) . '6');
        $sheet->getStyle('D6')->applyFromArray(array_merge_recursive(Excel::center_middle()));

        # sheet informasi
        $sheet2 =  $spreadsheet->createSheet();
        $spreadsheet->getSheet(1)->setTitle('Informasi');

        $sheet2->setCellValueExplicit('A1', 'Turnamen', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('B1', ':', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('C1', $tournament->tournament, DataType::TYPE_STRING);
        $sheet2->mergeCells('C1:D1');
        $sheet2->setCellValueExplicit('A2', 'Jadwal', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('B2', ':', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('C2',   Date::format_long($tournament->start_date) . ' s/d ' . Date::format_long($tournament->end_date), DataType::TYPE_STRING);
        $sheet2->mergeCells('C2:D2');
        $sheet2->setCellValueExplicit('A3', 'Lokasi', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('B3', ':', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('C3', $tournament->location, DataType::TYPE_STRING);
        $sheet2->mergeCells('C3:D3');
        $sheet2->setCellValueExplicit('A4', 'Jam', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('B4', ':', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('C4', $tournament->start_time . ' - ' . $tournament->end_time, DataType::TYPE_STRING);
        $sheet2->mergeCells('C4:D4');

        # group tournament
        $sheet2->setCellValueExplicit('A7', 'Group', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('B7', 'Batas Umur', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('C7', 'Kelompok', DataType::TYPE_STRING);
        $sheet2->setCellValueExplicit('D7', 'Maksimal Peserta Per Tim', DataType::TYPE_STRING);

        $row = 8;
        $col = "A";
        foreach ($group as $g) {
            $sheet2->setCellValueExplicit($col++ . $row, $g->group, DataType::TYPE_STRING);
            $sheet2->setCellValueExplicit($col++ . $row, $g->min_age . ' - ' . $g->max_age . ' Tahun', DataType::TYPE_STRING);
            $sheet2->setCellValueExplicit($col++ . $row, Convert::gender($g->gender, false), DataType::TYPE_STRING);
            $sheet2->setCellValueExplicit($col++ . $row, $g->max_per_team . ' Peserta', DataType::TYPE_STRING);
            $row++;
            $col = "A";
        }

        $row += 2;
        $sheet2->setCellValueExplicit($col . $row, 'Lanjutkan Ke Form Pendaftaran', DataType::TYPE_STRING);
        $sheet2->getStyle($col . $row)->applyFromArray(array_merge_recursive(Excel::color('315b98'), Excel::underline(), Excel::center_middle()));
        $sheet2->mergeCells($col . $row . ':' . 'D' . $row);

        $sheet->getCell('A2')->getHyperlink()->setUrl("sheet://'Informasi'!" . $col . $row);
        $sheet2->getCell($col . $row)->getHyperlink()->setUrl("sheet://'Pendaftaran'!A2");

        foreach ($sheet->getColumnIterator() as $column) {
            $columnIndex = $column->getColumnIndex();
            $sheet->getColumnDimension($columnIndex)->setAutoSize(true);
            $sheet2->getColumnDimension($columnIndex)->setAutoSize(true);
        }
        # Save file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Turnamen ' . $tournament->tournament . '.xlsx"');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $writer->save('php://output');
    }
}
