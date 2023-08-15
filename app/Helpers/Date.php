<?php

namespace App\Helpers;

use Carbon\Carbon;
use DateTime;

class Date
{
    public static function format_long($date, $lang = 'indonesia')
    {
        $carbonDate = Carbon::parse($date);

        $month = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $carbonDate->format('d') . ' ' . $month[$carbonDate->format('n')] . ' ' . $carbonDate->format('Y');
    }

    public static function diff_date($start_date, $end_date)
    {
        # return days
        return round(abs(strtotime($start_date) - strtotime($end_date)) / 86400);
    }

    public static function date_current_week()
    {
        return date('Y-m-d', strtotime('monday this week'));
    }

    public static function compare_date($date1, $date2)
    {
        // Function to compare dates for sorting
        $dateA = DateTime::createFromFormat('d/m/Y H:i:s', $date1);
        $dateB = DateTime::createFromFormat('d/m/Y H:i:s', $date2);
        return $dateA <=> $dateB;
    }

    public static function calculate_age($date)
    {
        $now = Carbon::now();
        $birth_date = Carbon::parse($date);
        return $birth_date->diffInYears($now);
    }
}