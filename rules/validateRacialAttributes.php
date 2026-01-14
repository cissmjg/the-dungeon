<?php
require_once __DIR__ . '/../dbio/constants/characterAttributes.php';
require_once __DIR__ . '/minmaxRacialAttributes.php';

function validateRacialAttributes(&$errors, $input, $attributes_min_max) {
    $race_id = $input[CHARACTER_RACE_ID];
    $gender = $input[CHARACTER_GENDER];

    $min_strength = -1;
    $max_strength = -1;
    $min_intelligence = -1;
    $max_intelligence = -1;
    $min_wisdom = -1;
    $max_wisdom = -1;
    $min_constitution = -1;
    $max_constitution = -1;
    $min_charisma = -1;
    $max_charisma = -1;

    if ($gender == MALE) {
        if (!empty($input[CHARACTER_STRENGTH])) {
            $strength = $input[CHARACTER_STRENGTH];
            $min_strength = getMinForMale($attributes_min_max, CHARACTER_STRENGTH, $race_id);
            if ($strength < $min_strength) {
                $errors[CHARACTER_STRENGTH][] = "Character does not meet racial minimum for Strength (" .  $min_strength . ")";
            }

            $max_strength = getMaxForMale($attributes_min_max, CHARACTER_STRENGTH, $race_id);
            if ($strength > $max_strength) {
                $errors[CHARACTER_STRENGTH][] = "Character exceeds racial maximum for Strength (" .  $max_strength . ")";
            }
        }

        if (!empty($input[CHARACTER_INTELLIGENCE])) {
            $intelligence = $input[CHARACTER_INTELLIGENCE];
            $min_intelligence = getMinForMale($attributes_min_max, CHARACTER_INTELLIGENCE, $race_id);
            if ($intelligence < $min_intelligence) {
                $errors[CHARACTER_INTELLIGENCE][] = "Character does not meet racial minimum for Intelligence (" .  $min_intelligence . ")";
            }

            $max_intelligence = getMaxForMale($attributes_min_max, CHARACTER_INTELLIGENCE, $race_id);
            if ($intelligence > $max_intelligence) {
                $errors[CHARACTER_INTELLIGENCE][] = "Character exceeds racial maximum for Intelligence (" .  $max_intelligence . ")";
            }
        }

        if (!empty($input[CHARACTER_WISDOM])) {
            $wisdom = $input[CHARACTER_WISDOM];
            $min_wisdom = getMinForMale($attributes_min_max, CHARACTER_WISDOM, $race_id);
            if ($wisdom < $min_wisdom) {
                $errors[CHARACTER_WISDOM][] = "Character does not meet racial minimum for Wisdom (" .  $min_wisdom . ")";
            }

            $max_wisdom = getMaxForMale($attributes_min_max, CHARACTER_WISDOM, $race_id);
            if ($wisdom > $max_wisdom) {
                $errors[CHARACTER_WISDOM][] = "Character exceeds racial maximum for Wisdom (" .  $max_wisdom . ")";
            }
        }

        if (!empty($input[CHARACTER_DEXTERITY])) {
            $dexterity = $input[CHARACTER_DEXTERITY];
            $min_dexterity = getMinForMale($attributes_min_max, CHARACTER_DEXTERITY, $race_id);
            if ($dexterity < $min_dexterity) {
                $errors[CHARACTER_DEXTERITY][] = "Character does not meet racial minimum for Dexterity (" .  $min_dexterity . ")";
            }

            $max_dexterity = getMaxForMale($attributes_min_max, CHARACTER_DEXTERITY, $race_id);
            if ($dexterity > $max_dexterity) {
                $errors[CHARACTER_DEXTERITY][] = "Character exceeds racial maximum for Dexterity (" .  $max_dexterity . ")";
            }
        }

        if(!empty($input[CHARACTER_CONSTITUTION])) {
            $constitution = $input[CHARACTER_CONSTITUTION];
            $min_constitution = getMinForMale($attributes_min_max, CHARACTER_CONSTITUTION, $race_id);
            if ($constitution < $min_constitution) {
                $errors[CHARACTER_CONSTITUTION][] = "Character does not meet racial minimum for Constitution (" .  $min_constitution . ")";
            }

            $max_constitution = getMaxForMale($attributes_min_max, CHARACTER_CONSTITUTION, $race_id);
            if ($constitution > $max_constitution) {
                $errors[CHARACTER_CONSTITUTION][] = "Character exceeds racial maximum for Constitution (" .  $max_constitution . ")";
            }
        }

        if (!empty($input[CHARACTER_CHARISMA])) {
            $charisma = $input[CHARACTER_CHARISMA];
            $min_charisma = getMinForMale($attributes_min_max, CHARACTER_CHARISMA, $race_id);
            if ($charisma < $min_charisma) {
                $errors[CHARACTER_CHARISMA][] = "Character does not meet racial minimum for Charisma (" .  $min_charisma . ")";
            }

            $max_charisma = getMaxForMale($attributes_min_max, CHARACTER_CHARISMA, $race_id);
            if ($charisma > $max_charisma) {
                $errors[CHARACTER_CHARISMA][] = "Character exceeds racial maximum for Charisma (" .  $max_charisma . ")";
            }
        }
    } else {
        if (!empty($input[CHARACTER_STRENGTH])) {
            $strength = $input[CHARACTER_STRENGTH];
            $min_strength = getMinForFemale($attributes_min_max, CHARACTER_STRENGTH, $race_id);
            if ($strength < $min_strength) {
                $errors[CHARACTER_STRENGTH][] = "Character does not meet racial minimum for Strength (" .  $min_strength . ")";
            }

            $max_strength = getMaxForFemale($attributes_min_max, CHARACTER_STRENGTH, $race_id);
            if ($strength > $max_strength) {
                $errors[CHARACTER_STRENGTH][] = "Character exceeds racial maximum for Strength (" .  $max_strength . ")";
            }
        }

        if (!empty($input[CHARACTER_INTELLIGENCE])) {
            $intelligence = $input[CHARACTER_INTELLIGENCE];
            $min_intelligence = getMinForFemale($attributes_min_max, CHARACTER_INTELLIGENCE, $race_id);
            if ($intelligence < $min_intelligence) {
                $errors[CHARACTER_INTELLIGENCE][] = "Character does not meet racial minimum for Intelligence (" .  $min_intelligence . ")";
            }

            $max_intelligence = getMaxForFemale($attributes_min_max, CHARACTER_INTELLIGENCE, $race_id);
            if ($intelligence > $max_intelligence) {
                $errors[CHARACTER_INTELLIGENCE][] = "Character exceeds racial maximum for Intelligence (" .  $max_intelligence . ")";
            }
        }

        if (!empty($input[CHARACTER_WISDOM])) {
            $wisdom = $input[CHARACTER_WISDOM];
            $min_wisdom = getMinForFemale($attributes_min_max, CHARACTER_WISDOM, $race_id);
            if ($wisdom < $min_wisdom) {
                $errors[CHARACTER_WISDOM][] = "Character does not meet racial minimum for Wisdom (" .  $min_wisdom . ")";
            }

            $max_wisdom = getMaxForFemale($attributes_min_max, CHARACTER_WISDOM, $race_id);
            if ($wisdom > $max_wisdom) {
                $errors[CHARACTER_WISDOM][] = "Character exceeds racial maximum for Wisdom (" .  $max_wisdom . ")";
            }
        }

        if (!empty($input[CHARACTER_DEXTERITY])) {
            $dexterity = $input[CHARACTER_DEXTERITY];
            $min_dexterity = getMinForFemale($attributes_min_max, CHARACTER_DEXTERITY, $race_id);
            if ($dexterity < $min_dexterity) {
                $errors[CHARACTER_DEXTERITY][] = "Character does not meet racial minimum for Dexterity (" .  $min_dexterity . ")";
            }

            $max_dexterity = getMaxForFemale($attributes_min_max, CHARACTER_DEXTERITY, $race_id);
            if ($dexterity > $max_dexterity) {
                $errors[CHARACTER_DEXTERITY][] = "Character exceeds racial maximum for Dexterity (" .  $max_dexterity . ")";
            }
        }

        if(!empty($input[CHARACTER_CONSTITUTION])) {
            $constitution = $input[CHARACTER_CONSTITUTION];
            $min_constitution = getMinForFemale($attributes_min_max, CHARACTER_CONSTITUTION, $race_id);
            if ($constitution < $min_constitution) {
                $errors[CHARACTER_CONSTITUTION][] = "Character does not meet racial minimum for Constitution (" .  $min_constitution . ")";
            }

            $max_constitution = getMaxForFemale($attributes_min_max, CHARACTER_CONSTITUTION, $race_id);
            if ($constitution > $max_constitution) {
                $errors[CHARACTER_CONSTITUTION][] = "Character exceeds racial maximum for Constitution (" .  $max_constitution . ")";
            }
        }
        if (!empty($input[CHARACTER_CHARISMA])) {
            $charisma = $input[CHARACTER_CHARISMA];
            $min_charisma = getMinForFemale($attributes_min_max, CHARACTER_CHARISMA, $race_id);
            if ($charisma < $min_charisma) {
                $errors[CHARACTER_CHARISMA][] = "Character does not meet racial minimum for Charisma (" .  $min_charisma . ")";
            }

            $max_charisma = getMaxForFemale($attributes_min_max, CHARACTER_CHARISMA, $race_id);
            if ($charisma > $max_charisma) {
                $errors[CHARACTER_CHARISMA][] = "Character exceeds racial maximum for Charisma (" .  $max_charisma . ")";
            }
        }
    }
}

?>