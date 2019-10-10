<?php

use Carbon\Carbon;

require "vendor/autoload.php";

$time = "19. 10. 10.(목) 오전 9:00~3:00";

$dateString = mb_strcut($time, 0, mb_strpos($time, '('));

$beforeOrAfterNoon = mb_strpos($time, '오전') ? 'before' : 'after';

$timeString = substr($time, strpos($time, '오')+7);

$timeStringArr = explode('~', $timeString);
$timeStringArr = array_map(function($item) {
    return trim($item);
}, $timeStringArr);
//var_dump($timeStringArr);
$startTimeString = "{$dateString} {$timeStringArr[0]}:00";
$endTimeString = "{$dateString} {$timeStringArr[1]}:00";

//var_dump($startTimeString);
//var_dump($endTimeString);
$startCarbon = Carbon::createFromFormat('y. n. j. G:i:s', $startTimeString);
$endCarbon = Carbon::createFromFormat('y. n. j. G:i:s', $endTimeString);
//
if($beforeOrAfterNoon == 'after') {
    $startCarbon->addHours(12);
    $endCarbon->addHours(12);
}

if($startCarbon->greaterThan($endCarbon)) {
    $endCarbon->addHours(12);
}

var_dump($startCarbon);
var_dump($endCarbon);
