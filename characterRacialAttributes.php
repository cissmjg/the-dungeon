<?php

$errors = [];
$input = [];

$pdo = require_once __DIR__ . '/dbio/DBConnection.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/dbio/constants/characterRaces.php';
require_once __DIR__ . '/dbio/constants/characterAttributes.php';
require_once 'minmaxRacialAttributes.php';
require_once __DIR__ . '/webio/raceId.php';

$url_racial_attributes = CurlHelper::buildUrl('characterRacialAttributes');

if (isset($_POST[RACE_ID])) {
    getRaceId($errors, $input);
}

$all_races = getAllRaces($pdo, $errors);
$select_race_id_tag = buildAllRacesTag($all_races, $input);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="dnd-default.css">
    <title>Racial Attributes Limits</title>
</head>
<body>
    <table>
        <tr>
            <td>Race</td><td colspan="2"><form action="<?= $url_racial_attributes ?>" method="POST"><?= $select_race_id_tag ?><button type="submit">Go</button></form></td>
        </tr>
        <?php
            if (!empty($input[RACE_ID])) {
                echo '<tr><td>Strength</td><td style="text-align: center;">M</td><td style="text-align: center;">F</td></tr>' . PHP_EOL;
                $min_strength_male = getMinForMale($attributes_min_max, CHARACTER_STRENGTH, $input[RACE_ID]);
                $min_strength_female = getMinForFemale($attributes_min_max, CHARACTER_STRENGTH, $input[RACE_ID]);
                echo '<tr><td>Min</td><td style="text-align: right;">' . $min_strength_male . '</td><td style="text-align: right;">' . $min_strength_female . '</td></tr>' . PHP_EOL;
                $max_strength_male = getMaxForMale($attributes_min_max, CHARACTER_STRENGTH, $input[RACE_ID]);
                $max_strength_female = getMaxForFemale($attributes_min_max, CHARACTER_STRENGTH, $input[RACE_ID]);
                echo '<tr><td>Max</td><td style="text-align: right;">' . $max_strength_male . '</td><td style="text-align: right;">' . $max_strength_female . '</td></tr>' . PHP_EOL;
                echo '<tr><td colspan="3" style="height: 4px;"> </td></tr>' . PHP_EOL;

                echo '<tr><td>Intelligence</td><td style="text-align: center;">M</td><td style="text-align: center;">F</td></tr>' . PHP_EOL;
                $min_intelligence_male = getMinForMale($attributes_min_max, CHARACTER_INTELLIGENCE, $input[RACE_ID]);
                $min_intelligence_female = getMinForFemale($attributes_min_max, CHARACTER_INTELLIGENCE, $input[RACE_ID]);
                echo '<tr><td>Min</td><td style="text-align: right;">' . $min_intelligence_male . '</td><td style="text-align: right;">' . $min_intelligence_female . '</td></tr>' . PHP_EOL;
                $max_intelligence_male = getMaxForMale($attributes_min_max, CHARACTER_INTELLIGENCE, $input[RACE_ID]);
                $max_intelligence_female = getMaxForFemale($attributes_min_max, CHARACTER_INTELLIGENCE, $input[RACE_ID]);
                echo '<tr><td>Max</td><td style="text-align: right;">' . $max_intelligence_male . '</td><td style="text-align: right;">' . $max_intelligence_female . '</td></tr>' . PHP_EOL;
                echo '<tr><td colspan="3" style="height: 4px;"> </td></tr>' . PHP_EOL;

                echo '<tr><td>Wisdom</td><td style="text-align: center;">M</td><td style="text-align: center;">F</td></tr>' . PHP_EOL;
                $min_wisdom_male = getMinForMale($attributes_min_max, CHARACTER_WISDOM, $input[RACE_ID]);
                $min_wisdom_female = getMinForFemale($attributes_min_max, CHARACTER_WISDOM, $input[RACE_ID]);
                echo '<tr><td>Min</td><td style="text-align: right;">' . $min_wisdom_male . '</td><td style="text-align: right;">' . $min_wisdom_female . '</td></tr>' . PHP_EOL;
                $max_wisdom_male = getMaxForMale($attributes_min_max, CHARACTER_WISDOM, $input[RACE_ID]);
                $max_wisdom_female = getMaxForFemale($attributes_min_max, CHARACTER_WISDOM, $input[RACE_ID]);
                echo '<tr><td>Max</td><td style="text-align: right;">' . $max_wisdom_male . '</td><td style="text-align: right;">' . $max_wisdom_female . '</td></tr>' . PHP_EOL;
                echo '<tr><td colspan="3" style="height: 4px;"> </td></tr>' . PHP_EOL;

                echo '<tr><td>Dexterity</td><td style="text-align: center;">M</td><td style="text-align: center;">F</td></tr>' . PHP_EOL;
                $min_dexterity_male = getMinForMale($attributes_min_max, CHARACTER_DEXTERITY, $input[RACE_ID]);
                $min_dexterity_female = getMinForFemale($attributes_min_max, CHARACTER_DEXTERITY, $input[RACE_ID]);
                echo '<tr><td>Min</td><td style="text-align: right;">' . $min_dexterity_male . '</td><td style="text-align: right;">' . $min_dexterity_female . '</td></tr>' . PHP_EOL;
                $max_dexterity_male = getMaxForMale($attributes_min_max, CHARACTER_DEXTERITY, $input[RACE_ID]);
                $max_dexterity_female = getMaxForFemale($attributes_min_max, CHARACTER_DEXTERITY, $input[RACE_ID]);
                echo '<tr><td>Max</td><td style="text-align: right;">' . $max_dexterity_male . '</td><td style="text-align: right;">' . $max_dexterity_female . '</td></tr>' . PHP_EOL;
                echo '<tr><td colspan="3" style="height: 4px;"> </td></tr>' . PHP_EOL;

                echo '<tr><td>Constitution</td><td style="text-align: center;">M</td><td style="text-align: center;">F</td></tr>' . PHP_EOL;
                $min_constitution_male = getMinForMale($attributes_min_max, CHARACTER_CONSTITUTION, $input[RACE_ID]);
                $min_constitution_female = getMinForFemale($attributes_min_max, CHARACTER_CONSTITUTION, $input[RACE_ID]);
                echo '<tr><td>Min</td><td style="text-align: right;">' . $min_constitution_male . '</td><td style="text-align: right;">' . $min_constitution_female . '</td></tr>' . PHP_EOL;
                $max_constitution_male = getMaxForMale($attributes_min_max, CHARACTER_CONSTITUTION, $input[RACE_ID]);
                $max_constitution_female = getMaxForFemale($attributes_min_max, CHARACTER_CONSTITUTION, $input[RACE_ID]);
                echo '<tr><td>Max</td><td style="text-align: right;">' . $max_constitution_male . '</td><td style="text-align: right;">' . $max_constitution_female . '</td></tr>' . PHP_EOL;
                echo '<tr><td colspan="3" style="height: 4px;"> </td></tr>' . PHP_EOL;

                echo '<tr><td>Charisma</td><td style="text-align: center;">M</td><td style="text-align: center;">F</td></tr>' . PHP_EOL;
                $min_charisma_male = getMinForMale($attributes_min_max, CHARACTER_CHARISMA, $input[RACE_ID]);
                $min_charisma_female = getMinForFemale($attributes_min_max, CHARACTER_CHARISMA, $input[RACE_ID]);
                echo '<tr><td>Min</td><td style="text-align: right;">' . $min_charisma_male . '</td><td style="text-align: right;">' . $min_charisma_female . '</td></tr>' . PHP_EOL;
                $max_charisma_male = getMaxForMale($attributes_min_max, CHARACTER_CHARISMA, $input[RACE_ID]);
                $max_charisma_female = getMaxForFemale($attributes_min_max, CHARACTER_CHARISMA, $input[RACE_ID]);
                echo '<tr><td>Max</td><td style="text-align: right;">' . $max_charisma_male . '</td><td style="text-align: right;">' . $max_charisma_female . '</td></tr>' . PHP_EOL;
                echo '<tr><td colspan="3" style="height: 4px;"> </td></tr>' . PHP_EOL;
            }
        ?>
    </table>
</body>
</html>

<?php

function getAllRaces(\PDO $pdo, &$errors) {
	$sql_exec = "CALL getAllRaces()";
	
	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in " . __FILE__ . ".getAllRaces : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function buildAllRacesTag($all_races, $input) {
    $output_html = '<select name="race_id">' . PHP_EOL;
    $input_race_id = $input[RACE_ID] ?? '';
    foreach($all_races AS $race) {
        $selected = $input_race_id == $race[RACE_ID] ? " selected" : '';
        $output_html .= '<option value="' . $race[RACE_ID] . '"' . $selected . '>' . $race['race_name'] . '</option>' . PHP_EOL;
    }
    $output_html .= '</select>' . PHP_EOL;

    return $output_html;
}

?>