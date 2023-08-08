<?php

use Carbon\Carbon;

function formatDate($timeDiff)
{
    $hours = $timeDiff->h;
    $minutes = $timeDiff->i;
    return $hours . "h " . $minutes . "m";
}

function calculateTimeDuration($dep, $arr)
{
    $startDate = Carbon::parse($dep);
    $endDate = Carbon::parse($arr);
    $timeDifference = $startDate->diff($endDate);
    return formatDate($timeDifference);
}


// Economy Class Mapper
$mapping = array(
    'EL' => 'Economy Light',
    'EV' => 'Economy Value',
    'EE' => 'Economy Extra',
);
