<?php

$errors = [];
$input = [];

$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once 'characterClassName.php';

const MAX_CHARACTER_LEVEL_FOR_ATTAINING_SPELLS = 30;

$spell_casting_classes = getAllSpellCastingClasses($pdo, $errors);

$character_class_name = '';
$spell_count_for_classes = null;
if (isset($_POST['characterClassName']))
{
    getCharacterClassName($errors, $input);
    $character_class_name = $input['characterClassName'];
    $url = CurlHelper::buildUrl('getCharacterClassSpellCount');
    $raw_results = CurlHelper::performGetRequest($url, $input);
    
    $spell_count_for_classes = json_decode($raw_results);
}


$spell_count_by_level = [];
$spell_class_types = [];
$spell_class_max_level = [];

if ($spell_count_for_classes != null) {
    // Get all spell classes for character class ordered by level ascending
    foreach($spell_count_for_classes AS $spell_count_for_class) {
        if (!array_key_exists($spell_count_for_class->character_level, $spell_count_by_level)) {
            $spell_count_by_level[$spell_count_for_class->character_level] = array();
        }

        $spell_count_by_level[$spell_count_for_class->character_level][$spell_count_for_class->spell_type_name] = $spell_count_for_class;

        if(!array_key_exists($spell_count_for_class->spell_type_name, $spell_class_types)) {
            $spell_class_types[$spell_count_for_class->spell_type_name] = $spell_count_for_class->spell_type_name;
        }

        if (!array_key_exists($spell_count_for_class->spell_type_name, $spell_class_max_level)) {
            $spell_class_max_level[$spell_count_for_class->spell_type_name] = -1;
        }

        $max_level_for_current_row = getMaxLevelForCurrentRow($spell_count_for_class);
        if ($max_level_for_current_row > $spell_class_max_level[$spell_count_for_class->spell_type_name]) {
            $spell_class_max_level[$spell_count_for_class->spell_type_name] = $max_level_for_current_row;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="dnd-default.css">
    <title>Character Class Spell Count</title>
</head>
<body>
    <?php
    if ($spell_count_for_classes != null) {
        echo '<h3>Spell allocation count by level and spell type for ' . $character_class_name . '</h3>' . PHP_EOL;
        echo '<table>' . PHP_EOL;
        // Header row
        echo '<tr><td>Level</td>';
        foreach($spell_class_types AS $spell_class_type) {
            $max_spell_level = $spell_class_max_level[$spell_class_type];
            echo '<td colspan="'. $max_spell_level . '">' . $spell_class_type . '</td>';
        }
        echo '</tr>' . PHP_EOL;

        // Row with Spell Level Headers
        echo '<tr><td>&nbsp;</td>';
        foreach($spell_class_types AS $spell_class_type) {
            for($i = 1; $i <= $spell_class_max_level[$spell_class_type]; $i++) {
                echo '<td>&nbsp;' . $i . '&nbsp;</td>';
            }
        }
        echo '</tr>' . PHP_EOL;

        for ($i = 1; $i < MAX_CHARACTER_LEVEL_FOR_ATTAINING_SPELLS; $i++) {
            if (array_key_exists($i, $spell_count_by_level)) {
                echo '<tr>';
                echo '<td>' . $i . '</td>';
                foreach($spell_class_types AS $spell_class_type) {
                    $max_spell_level = $spell_class_max_level[$spell_class_type];
                    if (is_null($spell_count_by_level[$i][$spell_class_type])) {
                        $row = buildBlankRow($max_spell_level);
                        echo $row;
                    } else {
                        $row = buildRow($spell_count_by_level[$i][$spell_class_type], $max_spell_level);
                        echo $row;
                    }
                }
                echo '</tr>' . PHP_EOL;
            }
        }
        echo '</table>' . PHP_EOL;
    }

    $form_action_url = CurlHelper::buildUrl('characterClassSpellCount');
    
    ?>

    <div style="padding-top: 25px;">Get spell counts for<br/>
    <form id="spell-count" name="spell-count" action="<?= $form_action_url ?>" method="post">
    <select id="characterClassName" name="characterClassName">
    <?php
        foreach($spell_casting_classes AS $spell_casting_class) {
            $selected_text = '';
            if ($spell_casting_class['character_class_name'] == $input['characterClassName']) {
                $selected_text = " selected";
            }
            echo '<option value="' . $spell_casting_class['character_class_name'] . '"' . $selected_text . '>' . $spell_casting_class['character_class_name'] . '</option>' . PHP_EOL;
        }
    ?>
    </select>
    <button type="submit">Go</button>
    </form>
    </div>

    <?php
        function getMaxLevelForCurrentRow($spell_count_for_class) {
            if ($spell_count_for_class->number_level_9 > 0) {
                return 9;
            }

            if ($spell_count_for_class->number_level_8 > 0) {
                return 8;
            }

            if ($spell_count_for_class->number_level_7 > 0) {
                return 7;
            }

            if ($spell_count_for_class->number_level_6 > 0) {
                return 6;
            }

            if ($spell_count_for_class->number_level_5 > 0) {
                return 5;
            }

            if ($spell_count_for_class->number_level_4 > 0) {
                return 4;
            }

            if ($spell_count_for_class->number_level_3 > 0) {
                return 3;
            }

            if ($spell_count_for_class->number_level_2 > 0) {
                return 2;
            }
             
            if ($spell_count_for_class->number_level_1 > 0) {
                return 1;
            }

            return 0;
        }

        function buildRow($spell_count_row, $max_spell_level) {
            $row = '';
            if ($spell_count_row->number_level_1 > 0) {
                $row .= '<td>&nbsp;' . $spell_count_row->number_level_1 . '&nbsp;</td>';
            } else {
                $row .= '<td>&nbsp;-&nbsp;</td>';
            }

            if ($max_spell_level < 2) {
                return $row;
            }

            if ($spell_count_row->number_level_2 > 0) {
                $row .= '<td>&nbsp;' . $spell_count_row->number_level_2 . '&nbsp;</td>';
            } else {
                $row .= '<td>&nbsp;-&nbsp;</td>';
            }

            if ($max_spell_level < 3) {
                return $row;
            }

            if ($spell_count_row->number_level_3 > 0) {
                $row .= '<td>&nbsp;' . $spell_count_row->number_level_3 . '&nbsp;</td>';
            } else {
                $row .= '<td>&nbsp;-&nbsp;</td>';
            }

            if ($max_spell_level < 4) {
                return $row;
            }

            if ($spell_count_row->number_level_4 > 0) {
                $row .= '<td>&nbsp;' . $spell_count_row->number_level_4 . '&nbsp;</td>';
            } else {
                $row .= '<td>&nbsp;-&nbsp;</td>';
            }

            if ($max_spell_level < 5) {
                return $row;
            }

            if ($spell_count_row->number_level_5 > 0) {
                $row .= '<td>&nbsp;' . $spell_count_row->number_level_5 . '&nbsp;</td>';
            } else {
                $row .= '<td>&nbsp;-&nbsp;</td>';
            }

            if ($max_spell_level < 6) {
                return $row;
            }

            if ($spell_count_row->number_level_6 > 0) {
                $row .= '<td>&nbsp;' . $spell_count_row->number_level_6 . '&nbsp;</td>';
            } else {
                $row .= '<td>&nbsp;-&nbsp;</td>';
            }

            if ($max_spell_level < 7) {
                return $row;
            }

            if ($spell_count_row->number_level_7 > 0) {
                $row .= '<td>&nbsp;' . $spell_count_row->number_level_7 . '&nbsp;</td>';
            } else {
                $row .= '<td>&nbsp;-&nbsp;</td>';
            }

            if ($max_spell_level < 8) {
                return $row;
            }

            if ($spell_count_row->number_level_8 > 0) {
                $row .= '<td>&nbsp;' . $spell_count_row->number_level_8 . '&nbsp;</td>';
            } else {
                $row .= '<td>&nbsp;-&nbsp;</td>';
            }

            if ($max_spell_level < 9) {
                return $row;
            }

            if ($spell_count_row->number_level_9 > 0) {
                $row .= '<td>&nbsp;' . $spell_count_row->number_level_9 . '&nbsp;</td>';
            } else {
                $row .= '<td>&nbsp;-&nbsp;</td>';
            }

            return $row;
        }

        function buildBlankRow($colCount) {
            $blank_row = '';
            for ($i = 0; $i < $colCount; $i++) {
                $blank_row .= '<td>&nbsp;</td>';
            }

            return $blank_row;
        }

        function getAllSpellCastingClasses(\PDO $pdo, $errors) {
            $sql_exec = "CALL getAllSpellCastingClasses()";
            
            $statement = $pdo->prepare($sql_exec);
            try {
                $statement->execute();
            } catch(Exception $e) {
                $errors[] = "Exception in getAllSpellCastingClasses : " . $e->getMessage();
            }
        
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    ?>
</body>
</html>