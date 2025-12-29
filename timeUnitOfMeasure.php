<?php

const SEGMENT = 1;
const ROUND = 2;
const TURN = 3;
const HOUR = 4;
const DAY = 5;
const WEEK = 6;
const MONTH = 7;
const YEAR = 8;
const SECOND = 9;

function getTimeUomDesc($time_uom) {
    if($time_uom == SEGMENT)
        return "segments";
    else if($time_uom == ROUND)
        return "rounds";
    else if($time_uom == TURN)
        return "turns";
    else if($time_uom == HOUR)
        return "hours";
    else if($time_uom == DAY)
        return "days";
    else if($time_uom == WEEK)
        return "weeks";
    else if($time_uom == MONTH)
        return "months";
    else if($time_uom == YEAR)
        return "years";
    else if($time_uom == SECOND)
        return "seconds";
    else
        return "Unknown"; 
}

?>