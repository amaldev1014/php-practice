<?php
// http://localhost/dates/date_functions.php

/**
 * A function which returns an ARRAY or STRING contains week_start date and week_end date depends on the number of weeks specified
 * 
 * @param Int $weeks Required number of weeks
 * @param String $start_date  date at  which program starts
 * @param Bool $return_str  specifies whether the return value is string or array
 * @return Array/String dates
 */
function weekly_dates_fnc($weeks, $start_date, $return_str=false)
{

    $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

    $dates = [];
    for ($i=0; $i < $weeks; $i++) {
        $timestamp_plus6 = strtotime($start_date . ' + 6 days');

        $weekday_start = $days[date("w", strtotime($start_date))]; // week day start at each iterations

        $weekday_end = $days[date("w", $timestamp_plus6 )]; // weekday end at each iterations

        // adding respective week start date and week end date into the array at each iterations
        $dates[] = "$weekday_start, " . date("d M Y", strtotime($start_date) ) . " - $weekday_end, ".date("d M Y", $timestamp_plus6 ); 

        $start_date =  date('Y-m-d',  strtotime($start_date . ' + 7 days')); // loop goes to the next week
    }

    return $return_str ? implode( "\n", $dates) : $dates;
}

$dates = weekly_dates_fnc(40, '2020-11-30', 1);
echo '<pre style="background:#002; color:#fff;">'; print_r($dates); echo '</pre>';