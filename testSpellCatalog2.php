<?php
declare(strict_types=1);
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);
$errors=[];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Spell Page</title>
	<link rel="stylesheet" href="dnd-default.css">
    <script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
    <meta name="Cache-Control" content="no-store">
</head>
<body>
<?php
$spell_details = [];
$spell_types = [1, 2, 3, 4, 5, 9, 10];
$spells_per_level = [];
if(!empty($_POST["spellTypeId"])) {
    if(!empty($_POST["spellLevel"]))
    {
        $spells_per_level = getSpellsForTypeAndLevel($pdo, $errors, $_POST["spellTypeId"], $_POST["spellLevel"]);
    }
}

if(count($errors) > 0) {
    echo '<div>';
    echo $errors;
    echo "</div>";
}
?>
    <form action="testSpellCatalog2.php" method="POST">
        <label for="spellTypeId">Spell Type</label>
        <select name="spellTypeId">
            <?php
                foreach($spell_types AS $spell_type) {
                    $spell_type_selected = "";
                    if(!empty($_POST["spellTypeId"]) && $spell_type == $_POST["spellTypeId"]) {
                        $spell_type_selected = " selected";
                    }
                    else {
                        $spell_type_selected = "";
                    }
                    echo '<option value="' . $spell_type . '"' . $spell_type_selected . '>' . getClassName($spell_type) . '</option>' . PHP_EOL;
                }
            ?>
        </select>
        <label for="spellLevel">Spell Level</label>
        <select name="spellLevel">
            <?php
                for($i = 1; $i < 10; $i++) {
                    $spell_level_selected = "";
                    if(!empty($_POST["spellLevel"]) && $_POST["spellLevel"] == $i) {
                        $spell_level_selected = " selected";
                    }
                    else {
                        $spell_level_selected = "";
                    }
                    echo '<option value="' . $i . '"' . $spell_level_selected . '>' . $i . '</option>' . PHP_EOL;
                }
            ?>
        </select>
        <label for="characterLevel">Character Level</label>
        <select name="characterLevel">
        <?php
            for($i = 1; $i < 21; $i++) {
                $character_level_selected = "";
                if(!empty($_POST["characterLevel"]) && $i == $_POST["characterLevel"]) {
                    $character_level_selected = " selected";
                }
                else {
                    $character_level_selected = "";
                }
                echo '<option value="' . $i . '"' . $character_level_selected . '>' . $i . '</option>' . PHP_EOL;
            } 
        ?>
        </select>
        <input type="submit" value="Go">
        <br>
        <br>
        <?php
            if(!empty($spells_per_level)) {
                echo '<select name="targetSpellId">' . PHP_EOL;
                $spell_selected = "";
                foreach($spells_per_level AS $spell) {
                    if(!empty($_POST["targetSpellId"]) && $_POST["targetSpellId"] == $spell["id"]) {
                        $spell_selected = " selected";
                    }
                    else{
                        $spell_selected = "";
                    }

                    echo '<option value="' . $spell["id"] . '"' . $spell_selected . '>' . $spell["name"] . '</option>' . PHP_EOL;
                }
                echo '</select>' . PHP_EOL;
            }

            if(!empty($_POST["targetSpellId"])) {
                $spell_details = getSpellDetails($pdo, $errors, $_POST["targetSpellId"]);

                echo '<div>' . PHP_EOL;
                if (count($errors) > 0) {
                    echo '<pre>' . PHP_EOL;
                    echo print_r($errors, true);
                    echo '</pre>' . PHP_EOL;
                }
                else {
                    echo '<p>Original spell text</p>' . PHP_EOL;
                    echo '<ul>' . PHP_EOL;
                    echo '<li>' . 'Casting Time: ' . $spell_details["casting_time"] . '</li>' . PHP_EOL;
                    echo '<li>' . 'Spell Range: ' . $spell_details["spell_range"] . '</li>' . PHP_EOL;
                    echo '<li>' . 'Spell Duration: ' . $spell_details["duration"] . '</li>' . PHP_EOL;
                    echo '<li>' . 'Area of Effect: ' . $spell_details["area_of_effect"] . '</li>' . PHP_EOL;
                    echo '</ul>' . PHP_EOL;
                }
                echo '</div>' . PHP_EOL;


                $spell_link = $spell_details["link"];
                $spell_name = $spell_details["name"];
                $casting_time = getSpellCastingTime($spell_details);
                $spell_range = getSpellRange($spell_details, (int)$_POST["characterLevel"]);
                $area_of_effect = $spell_details["area_of_effect"];
                $spell_duration = getSpellDuration($spell_details, (int)$_POST["characterLevel"]);

                echo '<table><tr><th>Name</th><th>T</th><th>Rng</th><th>Dur</th><th>AoE</th></tr>' . PHP_EOL;
                echo '<tr>' . PHP_EOL;
                echo '<td><a href="' . $spell_link . '" target="_blank">' . $spell_name . '</a></td><td>' . $casting_time . '</td><td>' . $spell_range . '</td><td>' . $spell_duration . '</td><td>' .  $area_of_effect . '</td>' . PHP_EOL;
                echo '</tr></table>' . PHP_EOL;
            }
        ?>
    </form>
    <div>
        <h4>Explanation</h4>
        <p>T : This is the CASTING TIME for a spell. If this field is JUST a number, this represents the spell's SPEED. If the casting time is 1 round or greater, the casting time will have a trailing 'r'.</p>
        <p>Rng : This is the spell's RANGE. If this field is JUST a number, this represent the distance in HEXES. Other numbers will have designations of ' (feet), "yards" or "miles".</p>
        <p>Dur : This is the spell's DURATION. If the duration is a number, there will always be a time designation. For example: "segments", "rounds", "days" etc.</p>
        <p>Please note that casting time, spell duration and spell range use the characters LEVEL when calculating their respective values.</p>
    </div>
<?php

function getSpellsForTypeAndLevel(\PDO $pdo, &$errors, $spell_type_id, $spell_level) {
	$sql_exec = "CALL testGetSpellsByTypeAndLevel(:spellTypeId, :spellLevel)";
	
	$statement = $pdo->prepare($sql_exec);
	try {
        $statement->bindParam(':spellTypeId', $spell_type_id, PDO::PARAM_INT);
        $statement->bindParam(':spellLevel', $spell_level, PDO::PARAM_INT);
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in " . __FILE__ . ".testGetSpellsByTypeAndLevel : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getSpellDetails(\PDO $pdo, &$errors, $spell_id) {
	$sql_exec = "CALL testGetSpellById(:spellId)";
	
	$statement = $pdo->prepare($sql_exec);
	try {
        $statement->bindParam(':spellId', $spell_id, PDO::PARAM_INT);
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in " . __FILE__ . ".testGetSpellById : " . $e->getMessage();
	}

	return $statement->fetch(PDO::FETCH_ASSOC);
}

function getClassName($class_id) {
    if($class_id == 1) {
        return "Cleric";
    }

    if ($class_id == 2) {
        return "Druid";
    }

    if ($class_id == 3) {
        return "Healer";
    }

    if ($class_id == 4) {
        return "Magic-User";
    }

    if ($class_id == 5) {
        return "Illusionist";
    }

    if ($class_id == 9) {
        return "Shukenja";
    }

    if ($class_id == 10) {
        return "Wu Jen";
    }
}

function getSpellCastingTime($spell_details) {
    
    $casting_time = $spell_details["casting_time"];
    if($spell_details["casting_time_speed"] != NULL) {
        $casting_time = $spell_details["casting_time_speed"];
    } else if($spell_details["casting_time_in_rounds"] != NULL) {
        $casting_time = $spell_details["casting_time_in_rounds"] . 'r';
    }

    return $casting_time;
}

function getSpellRange($spell_details, $character_level) {
    $spell_range = $spell_details["spell_range"];
    if($spell_details["range_hex_distance"] == NULL && $spell_details["range_distance_per_level"] == NULL && $spell_details["range_level_factor"] == NULL && $spell_details["range_fixed"] == NULL) {
        return $spell_range;
    }

    $range_uom = translateUomToText($spell_details["range_uom"]);
    if($spell_details["range_hex_distance"] != NULL) {
        return $spell_details["range_hex_distance"] . $range_uom;
    }

    $range_level_factor = 1;
    if($spell_details["range_level_factor"] != NULL) {
        $range_level_factor = $spell_details["range_level_factor"];
    }
    
    $final_character_level = intdiv($character_level,$range_level_factor);

    $range_fixed = 0;
    if($spell_details["range_fixed"] != NULL) {
        $range_fixed = $spell_details["range_fixed"];
    }

    $range_distance_per_level = 0;
    if($spell_details["range_distance_per_level"] != NULL) {
        $range_distance_per_level = $spell_details["range_distance_per_level"];
    }

    $final_range = $range_fixed + ($range_distance_per_level * $final_character_level);
    return $final_range . ' ' . $range_uom;
}

function getSpellDuration($spell_details, $character_level) {
    $spell_duration = $spell_details["duration"];
    if($spell_details["duration_time_per_level_uom"] == NULL && $spell_details["duration_time_fixed_uom"] == NULL) {
        return $spell_details["duration"];
    }

    if($spell_details["duration_time_fixed_uom"] != NULL && $spell_details["duration_time_per_level_uom"] == NULL) {
        $fixed_time_duration = $spell_details["duration_time_fixed"];
        $fixed_time_duration_uom = get_time_uom_desc($spell_details["duration_time_fixed_uom"]);
        return $fixed_time_duration . ' ' . $fixed_time_duration_uom;
    }

    $duration_level_factor = 1;
    if($spell_details["duration_level_factor"] != NULL) {
        $duration_level_factor = $spell_details["duration_level_factor"];
    }

    $final_character_level = intdiv($character_level,$duration_level_factor);

    if($spell_details["duration_time_fixed_uom"] == NULL && $spell_details["duration_time_per_level_uom"] != NULL) {
        $per_level_time_duration_uom = get_time_uom_desc($spell_details["duration_time_per_level_uom"]);
        $per_level_time_duration = $final_character_level * $spell_details["duration_time_per_level"];
        return $per_level_time_duration . ' ' . $per_level_time_duration_uom;
    }

    if($spell_details["duration_time_fixed_uom"] == $spell_details["duration_time_per_level_uom"]) {
        $uom_desc = get_time_uom_desc($spell_details["duration_time_fixed_uom"]);
        $per_level_time_duration = $final_character_level * $spell_details["duration_time_per_level"];
        $fixed_time_duration = $spell_details["duration_time_fixed"];
        $total_duration = $fixed_time_duration + $per_level_time_duration;

        return $total_duration . ' ' . $uom_desc;
    }

    $per_level_time_duration = $final_character_level * $spell_details["duration_time_per_level"];

    $fixed_time_duration = $spell_details["duration_time_fixed"];
    $normalized_uom_factor = getNormalizedUom($per_level_time_duration_uom, $fixed_time_duration_uom);
    $fixed_normalized_duration = $fixed_time_duration * $normalized_uom_factor;

    $total_duration = $fixed_normalized_duration + $per_level_time_duration;
    $total_time_duration_uom = get_time_uom_desc($spell_details["duration_time_per_level_uom"]);

    return $total_duration . $total_time_duration_uom;
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

function get_time_uom_desc($time_uom) {
    if($time_uom == 1)
        return "segments";
    else if($time_uom == 2)
        return "rounds";
    else if($time_uom == 3)
        return "turns";
    else if($time_uom == 4)
        return "hours";
    else if($time_uom == 5)
        return "days";
    else if($time_uom == 6)
        return "weeks";
    else if($time_uom == 7)
        return "months";
    else if($time_uom == 8)
        return "years";
    else if($time_uom == 9)
        return "seconds";
    else
        return "Unknown"; 
}

function getNormalizedUom($per_level_time_duration_uom, $fixed_time_duration_uom) {
    // Fixed : turn, Per_Level : round
    if($fixed_time_duration_uom == 3 && $per_level_time_duration_uom == 2) {
        return 10;  // 10 rounds in a turn
    }

    // Fixed : hour, Per_Level : turn
    if($fixed_time_duration_uom == 4 && $per_level_time_duration_uom == 3) {
        return 6;   // Turn = 10 minutes, six 10 minute intervals in an hour
    }

    // Fixed : week, Per_Level : day
    if($fixed_time_duration_uom == 6 && $per_level_time_duration_uom == 5) {
        return 7;   // 7 days in a week
    }

    // Fixed : hour, Per_Level : round
    if($fixed_time_duration_uom == 4 && $per_level_time_duration_uom == 2) {
        return 60;  // 60 minutes (rounds) in an hour
    }

    // Fixed : day, Per_Level : hour
    if($fixed_time_duration_uom == 5 && $per_level_time_duration_uom == 4) {
        return 24;  // 24 hours in a day
    }
}
?>
</body>
</html>
