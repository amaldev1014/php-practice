<?php
// http://localhost/dates/dates.php

/*
Output: Mon, 30 Nov 2020 - Sun, 06 Dec 2020
        Mon, 07 Dec 2020 - Sun, 13 Dec 2020
        ...
        Mon, 25 Apr 2022 - Sun, 01 May 2022
*/



$start_date = '2020-11-30';
$weeks = 40;

$days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];

$date = [];
for ($i=0; $i < $weeks; $i++) {
    $timestamp_plus6 = strtotime($start_date . ' + 6 days');

    $weekday_start = $days[date("w", strtotime($start_date))];
    $weekday_end = $days[date("w", $timestamp_plus6 )];

    $date[] = "$weekday_start, " . date("d M Y", strtotime($start_date) ) . " - $weekday_end, ".date("d M Y", $timestamp_plus6 );
    $start_date =  date('Y-m-d',  strtotime($start_date . ' + 7 days'));
}

echo implode("<br>", $date); die();