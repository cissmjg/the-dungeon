<?php

function calculateDurationInRounds($character_level, $time_fixed, $time_fixed_uom, $time_per_level, $time_per_level_uom, $spell_duration_level_factor) {
    if($time_fixed_uom == NULL && $time_per_level_uom == NULL) {
        return 0;
    }

    if($time_fixed_uom != NULL && $time_per_level_uom == NULL) {
        return calculationFixedDuration($time_fixed_uom, $time_fixed);
    }

    $final_character_level = adjustLevelBasedOnDurationLevel($character_level, $spell_duration_level_factor);
    if($time_fixed_uom == NULL && $time_per_level_uom != NULL) {
        return calculatePerLevelDuration($final_character_level, $time_per_level, $time_per_level_uom);
    }

    $fixed_duration = calculationFixedDuration($time_fixed_uom, $time_fixed);
    $per_level_duration = calculatePerLevelDuration($final_character_level, $time_per_level, $time_per_level_uom);

    return $fixed_duration + $per_level_duration;
}

function calculationFixedDuration($time_fixed_uom, $time_fixed) {
    if($time_fixed_uom == ROUND) {
        return $time_fixed;
    } else if($time_fixed_uom == TURN) {
        $turn_round_factor = getNormalizedUom(ROUND, $time_fixed_uom);
        return $turn_round_factor * $time_fixed;
    } else {
        return 0;
    }
}

function calculatePerLevelDuration($character_level, $time_per_level, $time_per_level_uom) {
    if($time_per_level_uom == ROUND) {
        return $character_level * $time_per_level;
    } else if($time_per_level_uom == TURN) {
        $turn_round_factor = getNormalizedUom(ROUND, $time_per_level_uom);
        return $turn_round_factor * $time_per_level * $character_level;
    }
}    

function getAdjustedCasterLevel($character_class, $character_level, $spell_type) {
    $spell_level_offset = 0;
    if ($spell_type == $character_class['spell_type_1']) {
        if ($character_class['spell_type_1_offset'] != NULL) {
            $spell_level_offset = $character_class['spell_type_1_offset'];
        }

    } else if ($spell_type == $character_class['spell_type_2']) {
        if ($character_class['spell_type_2_offset'] != NULL) {
            $spell_level_offset = $character_class['spell_type_2_offset'];
        }
    }

    $adjusted_character_level = $character_level - $spell_level_offset;

    return $adjusted_character_level;
}

function getSpellCastingTime($spell_details) {
    $casting_time = $spell_details["spell_casting_time"];
    if($spell_details["spell_casting_time_speed"] != NULL) {
        $casting_time = $spell_details["spell_casting_time_speed"];
    } else if($spell_details["spell_casting_time_in_rounds"] != NULL) {
        $casting_time = $spell_details["spell_casting_time_in_rounds"] . 'r';
    }

    return $casting_time;
}

function getSpellRange($spell_details, $character_level) {
    $spell_range = $spell_details["spell_range"];
    if($spell_details["spell_range_hex_distance"] == NULL && $spell_details["spell_range_distance_per_level"] == NULL && $spell_details["spell_range_level_factor"] == NULL && $spell_details["spell_range_fixed"] == NULL) {
        return $spell_range;
    }

    $range_uom = translateUomToText($spell_details["spell_range_uom"]);
    if($spell_details["spell_range_hex_distance"] != NULL) {
        return $spell_details["spell_range_hex_distance"] . $range_uom;
    }

    $range_level_factor = 1;
    if($spell_details["spell_range_level_factor"] != NULL) {
        $range_level_factor = $spell_details["spell_range_level_factor"];
    }
    
    $final_character_level = intdiv($character_level,$range_level_factor);

    $range_fixed = 0;
    if($spell_details["spell_range_fixed"] != NULL) {
        $range_fixed = $spell_details["spell_range_fixed"];
    }

    $range_distance_per_level = 0;
    if($spell_details["spell_range_distance_per_level"] != NULL) {
        $range_distance_per_level = $spell_details["spell_range_distance_per_level"];
    }

    $final_range = $range_fixed + ($range_distance_per_level * $final_character_level);
    return $final_range . ' ' . $range_uom;
}

function getSpellDuration($spell_details, $character_level) {
    $spell_duration = $spell_details["spell_duration"];
    if($spell_details["spell_duration_time_per_level_uom"] == NULL && $spell_details["spell_duration_time_fixed_uom"] == NULL) {
        return $spell_details["spell_duration"];
    }

    if($spell_details["spell_duration_time_fixed_uom"] != NULL && $spell_details["spell_duration_time_per_level_uom"] == NULL) {
        $fixed_time_duration = $spell_details["spell_duration_time_fixed"];
        $fixed_time_duration_uom = getTimeUomDesc($spell_details["spell_duration_time_fixed_uom"]);
        return $fixed_time_duration . ' ' . $fixed_time_duration_uom;
    }

    $final_character_level = adjustLevelBasedOnDurationLevel($character_level, $spell_details['spell_duration_level_factor']);

    $per_level_time_duration_uom = '';
    $fixed_time_duration_uom = '';
    if($spell_details["spell_duration_time_fixed_uom"] == NULL && $spell_details["spell_duration_time_per_level_uom"] != NULL) {
        $per_level_time_duration_uom = getTimeUomDesc($spell_details["spell_duration_time_per_level_uom"]);
        $per_level_time_duration = $final_character_level * $spell_details["spell_duration_time_per_level"];
        return $per_level_time_duration . ' ' . $per_level_time_duration_uom;
    }

    if($spell_details["spell_duration_time_fixed_uom"] == $spell_details["spell_duration_time_per_level_uom"]) {
        $uom_desc = getTimeUomDesc($spell_details["spell_duration_time_fixed_uom"]);
        $per_level_time_duration = $final_character_level * $spell_details["spell_duration_time_per_level"];
        $fixed_time_duration = $spell_details["spell_duration_time_fixed"];
        $total_duration = $fixed_time_duration + $per_level_time_duration;

        return $total_duration . ' ' . $uom_desc;
    }

    $per_level_time_duration = $final_character_level * $spell_details["spell_duration_time_per_level"];

    $fixed_time_duration = $spell_details["spell_duration_time_fixed"];
    $normalized_uom_factor = getNormalizedUom($per_level_time_duration_uom, $fixed_time_duration_uom);
    $fixed_normalized_duration = $fixed_time_duration * $normalized_uom_factor;

    $total_duration = $fixed_normalized_duration + $per_level_time_duration;
    $total_time_duration_uom = getTimeUomDesc($spell_details["spell_duration_time_per_level_uom"]);

    return $total_duration . $total_time_duration_uom;
}

function adjustLevelBasedOnDurationLevel($character_level, $spell_duration_level_factor) {
    $duration_level_factor = 1;
    if($spell_duration_level_factor != NULL) {
        $duration_level_factor = $spell_duration_level_factor;
    }

    return intdiv($character_level, $duration_level_factor);
}

function translateUomToText($range_uom) {
    $range_unit_of_measure = "";
    if($range_uom == 2) {
        return "'";
    } else if($range_uom == 3) {
        return "yards";
    } else if($range_uom == 4) {
        return "miles";
    }
}

function getNormalizedUom($per_level_time_duration_uom, $fixed_time_duration_uom) {
    // Fixed : turn, Per_Level : round
    if($fixed_time_duration_uom == TURN && $per_level_time_duration_uom == ROUND) {
        return 10;  // 10 rounds in a turn
    }

    // Fixed : hour, Per_Level : turn
    if($fixed_time_duration_uom == HOUR && $per_level_time_duration_uom == TURN) {
        return 6;   // Turn = 10 minutes, six 10 minute intervals in an hour
    }

    // Fixed : week, Per_Level : day
    if($fixed_time_duration_uom == WEEK && $per_level_time_duration_uom == DAY) {
        return 7;   // 7 days in a week
    }

    // Fixed : hour, Per_Level : round
    if($fixed_time_duration_uom == HOUR && $per_level_time_duration_uom == ROUND) {
        return 60;  // 60 minutes (rounds) in an hour
    }

    // Fixed : day, Per_Level : hour
    if($fixed_time_duration_uom == DAY && $per_level_time_duration_uom == HOUR) {
        return 24;  // 24 hours in a day
    }
}
?>