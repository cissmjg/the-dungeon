<?php
declare(strict_types=1);
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once 'pageAction.php';
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'hiddenTag.php';

require_once __DIR__ . '/webio/characterName.php';
require_once 'characterAtributes.php';
require_once 'adjustCharacterRacialAttributes.php';
require_once 'getCharacterCreationAttributes.php';
require_once 'validateRacialAttributes.php';

const PAGE_ACTION_VALIDATE = "validate";
const PAGE_ACTION_EDIT = "edit";

$input = [];
$errors = [];
$errors[CHARACTER_STRENGTH] = [];
$errors[CHARACTER_INTELLIGENCE] = [];
$errors[CHARACTER_WISDOM] = [];
$errors[CHARACTER_DEXTERITY] = [];
$errors[CHARACTER_CONSTITUTION] = [];
$errors[CHARACTER_CHARISMA] = [];

getPlayerName($errors, $input);
getPageAction($errors, $input);

$page_action = $input[PAGE_ACTION];

$data_entered = !empty($_POST[PLAYER_NAME]);
if ($data_entered) {
	getCharacterAttributes($errors, $input, __FILE__);
	adjustCharacterAttributes($errors, $input, __FILE__);
	validateRacialAttributes($errors, $input, $attributes_min_max);
}

$race_list = getRaceList($pdo, $errors);

$page_title = 'New Character';

$input_class='valid';
if ($input[PAGE_ACTION] == PAGE_ACTION_EDIT) {
	$input_class = "valid";
}

$read_only = '';
$disabled = '';
if ($input[PAGE_ACTION] != PAGE_ACTION_EDIT && $data_entered && noErrorsPresent($errors)) {
	$read_only = ' readonly';
	$disabled = ' disabled';
	$input_class = "view_only";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="Cache-Control" content="no-store">
    <title><?= $page_title ?></title>
	<link rel="stylesheet" href="dnd-default.css">
	<script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
	<meta name="Cache-Control" content="no-store">
</head>
<body>
    <div style="border: solid 1px; border-color: blue; border-radius: 10px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; width: auto; display: table;">
    <table style="margin-top: 5px;">
    <form id="characterCreation1" action="characterCreation1.php" method="post">
	<input type="hidden" id="playerName" name="playerName" value="<?= $input[PLAYER_NAME] ?>">
	<tr>
		<td colspan="4">
			<div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Character Creation Stage 1</div>
		</td>
	</tr>
	<tr>
		<td id="characterNameLabel">Character name</td>
		<td colspan="2"><input type="text" class="<?= $input_class ?>" id="<?= CHARACTER_NAME ?>" name="<?= CHARACTER_NAME ?>" value="<?php echo $input[CHARACTER_NAME] ?? ''; ?>"<?= $read_only ?>  required></td>
		<td>
			<?php
				if ($data_entered && empty($input[CHARACTER_NAME])) {
					echo buildThumbsDownIcon('Please enter a name');
				}

				if ($data_entered && !empty($input[CHARACTER_NAME])) {
					echo  buildThumbsUpIcon();
				}

				if(!$data_entered) {
					echo '&nbsp;';
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="raceIdLabel">Race</td>
		<td colspan="2">
			<select class="<?= $input_class ?>" id="<?= CHARACTER_RACE_ID ?>" name="<?= CHARACTER_RACE_ID ?>"<?= $disabled ?>>
				<?php
					$selectedRace = $input[CHARACTER_RACE_ID] ?? '';
					foreach($race_list AS $race) {
						$selected = $race['race_id'] == $selectedRace ? " selected" : '';
						echo '<option value="' . $race['race_id'] . '"' . $selected . '>' . $race['race_name'] . '</option>' . PHP_EOL;
					}
				?>
	        </select>
		</td>
	</tr>
	<tr>
		<td id="genderIdLabel">Gender</td>
		<td>
			<?php
				$male_selected = '';
				$female_selected = '';
				if (!empty($input[CHARACTER_GENDER])) {
					$input[CHARACTER_GENDER] == 'M' ? $male_selected = " selected" : '';
					$input[CHARACTER_GENDER] == 'F' ? $female_selected = " selected" : '';
				}
			?>
			<select class="valid" id="<?= CHARACTER_GENDER ?>" name="<?= CHARACTER_GENDER ?>"<?= $disabled ?>>
				<option value="M"<?= $male_selected?>>Male</option>
				<option value="F"<?= $female_selected?>>Female</option>
        	</select>
		</td>
		<td style="text-align: center;">Adjusted<br/>Value</td>
	</tr>
	<tr>
		<td id="characterStrengthLabel">Strength</td>
		<td>
			<input type="number" style="text-align: center;" class="<?= $input_class ?>" id="<?= CHARACTER_STRENGTH_RAW ?>" name="<?= CHARACTER_STRENGTH_RAW ?>" min="3" max="18" value="<?php echo $input[CHARACTER_STRENGTH_RAW] ?? ''; ?>"<?= $read_only ?> required>
			<?= buildHiddenTag(CHARACTER_STRENGTH, $input[CHARACTER_STRENGTH] ?? '') . PHP_EOL; ?>
		</td>
		<td style="text-align: center;"><span class="view_only"><?= $input[CHARACTER_STRENGTH] ?? '' ?></span></td>
		<td>
			<?php
				if ($data_entered && count($errors[CHARACTER_STRENGTH]) > 0) {
					echo buildThumbsDownIcon($errors[CHARACTER_STRENGTH][0]);
				}
				
				if ($data_entered && count($errors[CHARACTER_STRENGTH]) == 0) {
					echo buildThumbsUpIcon();
				}

				if (!$data_entered) {
					echo '&nbsp;';
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterIntelligenceLabel">Intelligence</td>
		<td>
			<input type="number" style="text-align: center;" class="<?= $input_class ?>" id="<?= CHARACTER_INTELLIGENCE_RAW ?>" name="<?= CHARACTER_INTELLIGENCE_RAW ?>" min="3" max="18" value="<?php echo $input[CHARACTER_INTELLIGENCE_RAW] ?? ''; ?>"<?= $read_only ?> required>
			<?= buildHiddenTag(CHARACTER_INTELLIGENCE, $input[CHARACTER_INTELLIGENCE] ?? '') . PHP_EOL; ?>
		</td>
		<td style="text-align: center;"><span class="view_only"><?= $input[CHARACTER_INTELLIGENCE] ?? ''; ?></span></td>
		<td>
			<?php
				if ($data_entered && count($errors[CHARACTER_INTELLIGENCE]) > 0) {
					echo buildThumbsDownIcon($errors[CHARACTER_INTELLIGENCE][0]);
				}
				
				if ($data_entered && count($errors[CHARACTER_INTELLIGENCE]) == 0) {
					echo buildThumbsUpIcon();
				}

				if (!$data_entered) {
					echo '&nbsp;';
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterWisdomLabel">Wisdom</td>
		<td>
			<input type="number" style="text-align: center;" class="<?= $input_class ?>" id="<?= CHARACTER_WISDOM_RAW ?>" name="<?= CHARACTER_WISDOM_RAW ?>" min="3" max="18" value="<?php echo $input[CHARACTER_WISDOM_RAW] ?? ''; ?>"<?= $read_only ?> required>
			<?= buildHiddenTag(CHARACTER_WISDOM, $input[CHARACTER_WISDOM] ?? '') . PHP_EOL; ?>
		</td>
		<td style="text-align: center;"><span class="view_only"><?= $input[CHARACTER_WISDOM] ?? ''; ?></span></td>
		<td>
			<?php
				if ($data_entered && count($errors[CHARACTER_WISDOM]) > 0) {
					echo buildThumbsDownIcon($errors[CHARACTER_WISDOM][0]);
				}
				
				if ($data_entered && count($errors[CHARACTER_WISDOM]) == 0) {
					echo buildThumbsUpIcon();
				}

				if (!$data_entered) {
					echo '&nbsp;';
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterDexterityLabel">Dexterity</td>
		<td>
			<input type="number" style="text-align: center;" class="<?= $input_class ?>" id="<?= CHARACTER_DEXTERITY_RAW ?>" name="<?= CHARACTER_DEXTERITY_RAW ?>" min="3" max="18" value="<?php echo $input[CHARACTER_DEXTERITY_RAW] ?? ''; ?>"<?= $read_only ?> required>
			<?= buildHiddenTag(CHARACTER_DEXTERITY, $input[CHARACTER_DEXTERITY] ?? '') . PHP_EOL; ?>
		</td>
		<td style="text-align: center;"><span class="view_only"><?= $input[CHARACTER_DEXTERITY] ?? ''; ?></span></td>
		<td>
			<?php
				if ($data_entered && count($errors[CHARACTER_DEXTERITY]) > 0) {
					echo buildThumbsDownIcon($errors[CHARACTER_DEXTERITY][0]);
				}
				
				if ($data_entered && count($errors[CHARACTER_DEXTERITY]) == 0) {
					echo buildThumbsUpIcon();
				}

				if (!$data_entered) {
					echo '&nbsp;';
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterConstitutionLabel">Constitution</td>
		<td>
			<input type="number" style="text-align: center;" class="<?= $input_class ?>" id="<?= CHARACTER_CONSTITUTION_RAW ?>" name="<?= CHARACTER_CONSTITUTION_RAW ?>" min="3" max="18" value="<?php echo $input[CHARACTER_CONSTITUTION_RAW] ?? ''; ?>"<?= $read_only ?> required>
			<?= buildHiddenTag(CHARACTER_CONSTITUTION, $input[CHARACTER_CONSTITUTION] ?? '') . PHP_EOL; ?>
		</td>
		<td style="text-align: center;"><span class="view_only"><?= $input[CHARACTER_CONSTITUTION] ?? ''; ?></span></td>
		<td>
			<?php
				if ($data_entered && count($errors[CHARACTER_CONSTITUTION]) > 0) {
					echo buildThumbsDownIcon($errors[CHARACTER_CONSTITUTION][0]);
				}
				
				if ($data_entered && count($errors[CHARACTER_CONSTITUTION]) == 0) {
					echo buildThumbsUpIcon();
				}

				if (!$data_entered) {
					echo '&nbsp;';
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterCharismaLabel">Charisma</td>
		<td>
			<input type="number" style="text-align: center;" class="<?= $input_class ?>" id="<?= CHARACTER_CHARISMA_RAW ?>" name="<?= CHARACTER_CHARISMA_RAW ?>" min="3" max="18" value="<?php echo $input[CHARACTER_CHARISMA_RAW] ?? ''; ?>"<?= $read_only ?> required>
			<?= buildHiddenTag(CHARACTER_CHARISMA, $input[CHARACTER_CHARISMA] ?? '') . PHP_EOL; ?>
		</td>
		<td style="text-align: center;"><span class="view_only"><?= $input[CHARACTER_CHARISMA] ?? ''; ?></span></td>
		<td>
			<?php
				if ($data_entered && count($errors[CHARACTER_CHARISMA]) > 0) {
					echo buildThumbsDownIcon($errors[CHARACTER_CHARISMA][0]);
				}
				
				if ($data_entered && count($errors[CHARACTER_CHARISMA]) == 0) {
					echo buildThumbsUpIcon();
				}

				if (!$data_entered) {
					echo '&nbsp;';
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterComelinessLabel">Comeliness</td>
		<td>
			<input type="number" style="text-align: center;" class="<?= $input_class ?>" id="<?= CHARACTER_COMELINESS_RAW ?>" name="<?= CHARACTER_COMELINESS_RAW ?>" min="3" max="18" value="<?php echo $input[CHARACTER_COMELINESS_RAW] ?? ''; ?>"<?= $read_only ?> required>
			<?= buildHiddenTag(CHARACTER_COMELINESS, $input[CHARACTER_COMELINESS] ?? '') . PHP_EOL; ?>
		</td>
		<td style="text-align: center;"><span class="view_only"><?= $input[CHARACTER_COMELINESS] ?? ''; ?></span></td>
	</tr>
</table>
</div>
<?php
	if ($input[PAGE_ACTION] == PAGE_ACTION_VALIDATE && $data_entered && noErrorsPresent($errors)) {
		$button_bar = '<div style="border: solid 1px; border-color: blue; border-radius: 10px; margin-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; width: 405px; display: table;">' . PHP_EOL;
		$button_bar .= '<button style="float:left; margin-top: 5px;" type="submit" name="pageAction" value="' . PAGE_ACTION_EDIT . '" formaction="characterCreation1.php">Edit</button>' . PHP_EOL;
		$button_bar .= '<button style="float:right; margin-top: 5px;" type="submit" formaction="characterCreation2.php">Select Class(es)</button>' . PHP_EOL;
		$button_bar .= '</div>' . PHP_EOL;
		echo $button_bar;
		echo buildHiddenTag(CHARACTER_RACE_ID, $input[CHARACTER_RACE_ID]) . PHP_EOL;
		echo buildHiddenTag(CHARACTER_GENDER, $input[CHARACTER_GENDER]) . PHP_EOL;
	} else {
		echo '<div style="border: solid 1px; border-color: blue; border-radius: 10px; margin-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; width: 405px; display: table;"><button style="margin-top: 5px;" type="submit">Validate</button></div>';
		echo buildHiddenTag(PAGE_ACTION, PAGE_ACTION_VALIDATE);
	} 
?>
</form>
</body>
</html>
<?php
function getRaceList(\PDO $pdo, &$errors) {
	$sql_exec = "CALL getAllRaces()";
	
	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in " . __FILE__ . ".getAllRaces : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function buildThumbsUpIcon() {
	return '<span class="fa-regular fa-thumbs-up"></span>';
}

function buildThumbsDownIcon($error_message) {
	return '<span class="error"><span class="fa-regular fa-thumbs-down"></span>&nbsp;' . $error_message . '</span>';
}

function noErrorsPresent($errors) {
	return count($errors[CHARACTER_STRENGTH]) == 0 && count($errors[CHARACTER_INTELLIGENCE]) == 0 && count($errors[CHARACTER_WISDOM]) == 0 && count($errors[CHARACTER_DEXTERITY]) == 0 && count($errors[CHARACTER_CONSTITUTION]) == 0 && count($errors[CHARACTER_CHARISMA]) == 0;
}

?>

