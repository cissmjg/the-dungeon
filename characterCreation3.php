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

require_once 'faEditIcon.php';

require_once __DIR__ . '/webio/characterName.php';
require_once 'characterAtributes.php';
require_once 'characterRaces.php';
require_once 'adjustCharacterRacialAttributes.php';
require_once 'getCharacterCreationAttributes.php';
require_once 'characterClassCombinations.php';
require_once 'characterClassRestrictions.php';
require_once 'validateRacialAttributes.php';

const PAGE_ACTION_VALIDATE = "validate";
const PAGE_ACTION_EDIT = "edit";
const NO_CLASS_SELECTED = "None";
const NO_CHARACTER_CLASS_ID = 0;
const FINALIZE_BUTTON_ID = "finalizeChacterActionID";

$input = [];

getPlayerName($errors, $input);
getCharacterAttributes($errors, $input, __FILE__);

$race_list = getRaceList($pdo, $errors);
$character_class_list = getCharacterClassList($pdo, $errors);

$primary_class_available = false;
if (!empty($_POST[CHARACTER_PRIMARY_CLASS])) {
 	$input[CHARACTER_PRIMARY_CLASS] = $_POST[CHARACTER_PRIMARY_CLASS];
	if ($input[CHARACTER_PRIMARY_CLASS] != NO_CLASS_SELECTED) {
		$primary_class_available = true;
	}
} 

$secondary_class_available = false;
if (!empty($_POST[CHARACTER_SECONDARY_CLASS])) {
	$input[CHARACTER_SECONDARY_CLASS] = $_POST[CHARACTER_SECONDARY_CLASS];
	if ($input[CHARACTER_SECONDARY_CLASS] != NO_CLASS_SELECTED) {
		$secondary_class_available = true;
	}
}
 
$tertiary_class_available = false;
if (!empty($_POST[CHARACTER_TERTIARY_CLASS])) {
	$input[CHARACTER_TERTIARY_CLASS] = $_POST[CHARACTER_TERTIARY_CLASS];
	if ($input[CHARACTER_TERTIARY_CLASS] != NO_CLASS_SELECTED) {
		$tertiary_class_available = true;
	}
}

$primary_character_class_id = NO_CHARACTER_CLASS_ID;
$secondary_character_class_id = NO_CHARACTER_CLASS_ID;
$tertiary_character_class_id = NO_CHARACTER_CLASS_ID;

if ($primary_class_available) {
	$primary_character_class_id = getCharacterClassId($character_class_list, $input[CHARACTER_PRIMARY_CLASS]);
}

if ($secondary_class_available) {
	$secondary_character_class_id = getCharacterClassId($character_class_list, $input[CHARACTER_SECONDARY_CLASS]);
}

if ($tertiary_class_available) {
	$tertiary_character_class_id = getCharacterClassId($character_class_list, $input[CHARACTER_TERTIARY_CLASS]);
}

$page_title = 'New Character';

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
    <form id="characterCreation3" action="insertCharacter.php" method="post">
	<input type="hidden" id="playerName" name="playerName" value="<?= $input[PLAYER_NAME] ?>">
	<tr>
		<td colspan="4">
			<div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Character Creation Stage 3</div>
		</td>
	</tr>
	<tr>
		<td id="characterNameLabel">Character name</td>
		<td colspan="2"><input type="text" class="view_only" id="<?= CHARACTER_NAME ?>" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>" readonly></td>
	</tr>
	<tr>
		<td id="raceIdLabel">Race</td>
		<td colspan="2">
            <?php
                $selectedRace = $input[CHARACTER_RACE_ID];
                $race_display_name = '';
                foreach($race_list AS $race) {
                    if ($race['race_id'] == $selectedRace) {
                        $race_display_name = $race['race_name'];
                        break;
                    }
                }
            ?>
			<span class="view_only"><?= $race_display_name ?></span>
			<input type="hidden" id="<?= CHARACTER_RACE_ID ?>" name="<?= CHARACTER_RACE_ID ?>" value="<?= $input[CHARACTER_RACE_ID] ?>">
		</td>
	</tr>
	<tr>
		<td id="genderIdLabel">Gender</td>
		<td>
            <span class="view_only">
                <?php 
                    if($input[CHARACTER_GENDER] == 'M') {
                        echo "MALE";
                    } else {
                        echo "FEMALE";
                    }
                 ?>
            </span>
			<input type="hidden" id="<?= CHARACTER_GENDER ?>" name="<?= CHARACTER_GENDER ?>" value="<?= $input[CHARACTER_GENDER] ?>">
		</td>
	</tr>
	<tr>
		<td id="characterStrengthLabel">Strength</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_STRENGTH_RAW ?>" name="<?= CHARACTER_STRENGTH_RAW ?>" value="<?php echo $input[CHARACTER_STRENGTH_RAW] ?>">
            <input type="text" class="view_only" id="<?= CHARACTER_STRENGTH ?>" name="<?= CHARACTER_STRENGTH ?>" size="2" value="<?= $input[CHARACTER_STRENGTH] ?>" readonly>
        </td>
	</tr>
	<tr>
		<td id="characterIntelligenceLabel">Intelligence</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_INTELLIGENCE_RAW ?>" name="<?= CHARACTER_INTELLIGENCE_RAW ?>" value="<?php echo $input[CHARACTER_INTELLIGENCE_RAW] ?>">
            <input type="text" class="view_only" id="<?= CHARACTER_INTELLIGENCE ?>" name="<?= CHARACTER_INTELLIGENCE ?>" size="2" value="<?= $input[CHARACTER_INTELLIGENCE] ?>" readonly>
        </td>
	</tr>
	<tr>
		<td id="characterWisdomLabel">Wisdom</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_WISDOM_RAW ?>" name="<?= CHARACTER_WISDOM_RAW ?>" value="<?php echo $input[CHARACTER_WISDOM_RAW] ?>">
            <input type="text" class="view_only" id="<?= CHARACTER_WISDOM ?>" name="<?= CHARACTER_WISDOM ?>" size="2" value="<?= $input[CHARACTER_WISDOM] ?>" readonly>
        </td>
	</tr>
	<tr>
		<td id="characterDexterityLabel">Dexterity</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_DEXTERITY_RAW ?>" name="<?= CHARACTER_DEXTERITY_RAW ?>" value="<?php echo $input[CHARACTER_DEXTERITY_RAW] ?>" readonly>
            <input type="text" class="view_only" id="<?= CHARACTER_DEXTERITY ?>" name="<?= CHARACTER_DEXTERITY ?>" size="2" value="<?= $input[CHARACTER_DEXTERITY] ?>" readonly>
        </td>
	</tr>
	<tr>
		<td id="characterConstitutionLabel">Constitution</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_CONSTITUTION_RAW ?>" name="<?= CHARACTER_CONSTITUTION_RAW ?>" value="<?= $input[CHARACTER_CONSTITUTION_RAW] ?>" readonly>
            <input type="text" class="view_only" id="<?= CHARACTER_CONSTITUTION ?>" name="<?= CHARACTER_CONSTITUTION ?>" size="2" value="<?= $input[CHARACTER_CONSTITUTION] ?>" readonly>
        </td>
	</tr>
	<tr>
		<td id="characterCharismaLabel">Charisma</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_CHARISMA_RAW ?>" name="<?= CHARACTER_CHARISMA_RAW ?>" value="<?= $input[CHARACTER_CHARISMA_RAW] ?>" readonly>
            <input type="text" class="view_only" id="<?= CHARACTER_CHARISMA ?>" name="<?= CHARACTER_CHARISMA ?>" size="2" value="<?= $input[CHARACTER_CHARISMA] ?>" readonly>
        </td>
	</tr>
	<tr>
		<td id="characterComelinessLabel">Comeliness</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_COMELINESS_RAW ?>" name="<?= CHARACTER_COMELINESS_RAW ?>" value="<?= $input[CHARACTER_COMELINESS_RAW] ?>" readonly>
            <input type="text" class="view_only" id="<?= CHARACTER_COMELINESS ?>" name="<?= CHARACTER_COMELINESS ?>" size="2" value="<?= $input[CHARACTER_COMELINESS] ?>">
        </td>
	</tr>
	<tr>
		<td>Primary Class</td>
	    <td>
			<?php
				if ($primary_class_available) {
					$primary_class_name = getCharacterClassName($character_class_list, $input[CHARACTER_PRIMARY_CLASS]);
					echo '<input style="float: left;" class="view_only" type="text" value="' . $primary_class_name . '" readonly>' . PHP_EOL;
					echo buildHiddenTag(CHARACTER_PRIMARY_CLASS,$input[CHARACTER_PRIMARY_CLASS]) . PHP_EOL;
				}
			?>			
		</td>
	</tr>
	<?php
	if ($secondary_class_available) {
		echo '<tr>' . PHP_EOL;
		echo '<td>2<sup>nd class</td>' . PHP_EOL;
		echo '<td>';
		if ($secondary_class_available) {
			$secondary_class_name = getCharacterClassName($character_class_list, $input[CHARACTER_SECONDARY_CLASS]);
			echo '<input style="float: left;" class="view_only" type="text" value="' . $secondary_class_name . '" readonly>' . PHP_EOL;
			echo buildHiddenTag(CHARACTER_SECONDARY_CLASS, $input[CHARACTER_SECONDARY_CLASS]) . PHP_EOL;
		}
		echo '</td>' . PHP_EOL;
		echo '</tr>' . PHP_EOL;
	}

	if ($tertiary_class_available) {
		echo '<tr>' . PHP_EOL;
		echo '<td>3<sup>rd class</td>' . PHP_EOL;
		echo '<td>';
		if ($tertiary_class_available == true) {
			$tertiary_class_name = getCharacterClassName($character_class_list, $input[CHARACTER_TERTIARY_CLASS]);
			echo '<input style="float: left;" class="view_only" type="text" value="' . $tertiary_class_name . '" readonly>' . PHP_EOL;
			echo buildHiddenTag(CHARACTER_TERTIARY_CLASS, $input[CHARACTER_TERTIARY_CLASS]) . PHP_EOL;
		}
        
        echo '</td>' . PHP_EOL;
        echo '</tr>' . PHP_EOL;
}
	?>
</table>
</div>
<?php
	echo buildHiddenTag('pageAction', PAGE_ACTION_VALIDATE);
	$button_bar = '<div style="margin-top: 5px; padding-bottom: 5px; padding-left: 5px; width: 405px;" class="character_create_action_bar_container">' . PHP_EOL;
	$button_bar .= '<button style="float:right; margin-top: 5px;" type="submit" formaction="characterCreation2.php">Select Class(es)</button>' . PHP_EOL;
	$button_bar .= '<div style="text-align: center;"  class="character_create_action_bar_item_two">&nbsp;</div>' . PHP_EOL;
	$button_bar .= '<div class="character_create_action_bar_item_three"><button id="' . FINALIZE_BUTTON_ID . '" type="submit">Create Character</button></div>' . PHP_EOL;
	$button_bar .= '</div>' . PHP_EOL;
	echo $button_bar;

	echo buildHiddenTag(CHARACTER_ARMOR_CLASS, 10);
	echo buildHiddenTag(CHARACTER_HIT_POINTS, 0);
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

function getCharacterClassList(\PDO $pdo, $errors) {
	$sql_exec = "CALL getAllCharacterClasses()";
	
	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in " . __FILE__ . ".getCharacterClassList : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getCharacterClassName($character_class_list, $character_class_id) {
	foreach($character_class_list AS $character_class) {
		if ($character_class['character_class_id'] == $character_class_id) {
			return $character_class['character_class_name'];
		}
	}

	return NO_CHARACTER_CLASS_ID;
}

function getCharacterClassId($character_class_list, $character_class_name) {
	foreach($character_class_list AS $character_class) {
		if ($character_class['character_class_name'] == $character_class_name) {
			return $character_class['character_class_id'];
		}
	}

	return NO_CHARACTER_CLASS_ID;
}
?>

