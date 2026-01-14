<?php
declare(strict_types=1);
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/pageAction.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'hiddenTag.php';

require_once __DIR__ . '/fa/faEditIcon.php';

require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/raceId.php';
require_once __DIR__ . '/dbio/constants/characterAttributes.php';
require_once __DIR__ . '/dbio/constants/characterRaces.php';
require_once __DIR__ . '/rules/adjustCharacterRacialAttributes.php';
require_once 'getCharacterCreationAttributes.php';
require_once __DIR__ . '/rules/characterClassCombinations.php';
require_once 'characterClassRestrictions.php';
require_once 'validateRacialAttributes.php';

const PAGE_ACTION_VALIDATE = "validate";
const PAGE_ACTION_EDIT = "edit";
const NO_CLASS_SELECTED = "None";
const NO_CHARACTER_CLASS_ID = 0;
const FINALIZE_BUTTON_ID = "finalizeChacterActionID";

$input = [];
$errors = [];

$errors[CHARACTER_STRENGTH] = [];
$errors[CHARACTER_INTELLIGENCE] = [];
$errors[CHARACTER_WISDOM] = [];
$errors[CHARACTER_DEXTERITY] = [];
$errors[CHARACTER_CONSTITUTION] = [];
$errors[CHARACTER_CHARISMA] = [];
$errors[CHARACTER_COMELINESS] = [];
$errors[CHARACTER_PRIMARY_CLASS] = [];

getPlayerName($errors, $input);
getCharacterAttributes($errors, $input, __FILE__);

$race_list = getRaceList($pdo, $errors);
$character_class_list = getCharacterClassList($pdo, $errors);

$primary_classes = getCharacterPrimaryClassesForRace($class_combinations, $input[CHARACTER_RACE_ID]);
$secondary_classes = [];
$tertiary_classes = [];

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

if ($primary_class_available == false) {
	$errors[CHARACTER_PRIMARY_CLASS][] = 'Please enter a primary class';
	$secondary_class_available = false;
	$tertiary_class_available = false;
} else {
	if ($secondary_class_available == true) {
		if ($tertiary_class_available == false) {
			$primary_character_class_name = getCharacterClassNameFromCharacterSummary($character_class_list, $input[CHARACTER_PRIMARY_CLASS]);
			$secondary_character_class_name = getCharacterClassNameFromCharacterSummary($character_class_list, $input[CHARACTER_SECONDARY_CLASS]);
			$tertiary_classes = getCharacterTertiaryClassesForRace($class_combinations, $input[CHARACTER_RACE_ID], $primary_character_class_name, $secondary_character_class_name);
		}
	} else {
		$primary_character_class_name = getCharacterClassNameFromCharacterSummary($character_class_list, $input[CHARACTER_PRIMARY_CLASS]);
		$secondary_classes = getCharacterSecondaryClassesForRace($class_combinations, $input[CHARACTER_RACE_ID], $primary_character_class_name);
		$tertiary_class_available = false;
	}
}

if ($primary_class_available) {
	$primary_character_class_name = getCharacterClassNameFromCharacterSummary($character_class_list, $input[CHARACTER_PRIMARY_CLASS]);
	validateCharacterClass($errors, $character_class_minimums, $character_class_maximums, $input[CHARACTER_PRIMARY_CLASS], $primary_character_class_name, $input);
}

if ($secondary_class_available) {
	$secondary_character_class_name = getCharacterClassNameFromCharacterSummary($character_class_list, $input[CHARACTER_SECONDARY_CLASS]);
	validateCharacterClass($errors, $character_class_minimums, $character_class_maximums, $input[CHARACTER_SECONDARY_CLASS], $secondary_character_class_name, $input);
}

if ($tertiary_class_available) {
	$tertiary_character_class_name = getCharacterClassNameFromCharacterSummary($character_class_list, $input[CHARACTER_TERTIARY_CLASS]);
	validateCharacterClass($errors, $character_class_minimums, $character_class_maximums, $input[CHARACTER_TERTIARY_CLASS], $tertiary_character_class_name, $input);
}

$page_title = 'New Character';

$errors_exist = errorsExist($errors);

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
	<script type="text/javascript">
		function submitTheForm(form_id, characterClassId) {
			let theForm = document.getElementById(form_id);

			theForm.elements[characterClassId].value = "None";

			if (theForm == null) {
				alert("Cannot find form with ID [" + form_id + "]");
			} else {
				theForm.submit();
			}
		}

		function disableFinalize(finalizeButton) {
			finalizeButton.disabled = 'true';
			finalizeButton.style.opacity = 0.5;
			finalizeButton.style.cursor = 'not-allowed';
		}
	</script>
</head>
<body>
    <div style="border: solid 1px; border-color: blue; border-radius: 10px; padding-bottom: 5px; padding-left: 5px; padding-right: 5px; width: auto; display: table;">
    <table style="margin-top: 5px;">
    <form id="characterCreation2" action="<?= CurlHelper::buildUrl('characterCreation2.php') ?>" method="POST">
	<input type="hidden" id="playerName" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
	<tr>
		<td colspan="4">
			<div style="background-color: Aquamarine; text-align:center; border-radius: 10px;">Character Creation Stage 2</div>
		</td>
	</tr>
	<tr>
		<td id="characterNameLabel">Character name</td>
		<td colspan="2"><input type="text" class="view_only" id="<?= CHARACTER_NAME ?>" name="<?= CHARACTER_NAME ?>" value="<?= $input[CHARACTER_NAME] ?>" required></td>
	</tr>
	<tr>
		<td id="raceIdLabel">Race</td>
		<td colspan="2">
            <?php
                $selectedRace = $input[CHARACTER_RACE_ID];
                $race_display_name = '';
                foreach($race_list AS $race) {
                    if ($race[RACE_ID] == $selectedRace) {
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
		<td>
			<?php
				if (!empty($errors[CHARACTER_STRENGTH])) {
					echo buildThumbsDownIcon($errors[CHARACTER_STRENGTH]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterIntelligenceLabel">Intelligence</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_INTELLIGENCE_RAW ?>" name="<?= CHARACTER_INTELLIGENCE_RAW ?>" value="<?php echo $input[CHARACTER_INTELLIGENCE_RAW] ?>">
            <input type="text" class="view_only" id="<?= CHARACTER_INTELLIGENCE ?>" name="<?= CHARACTER_INTELLIGENCE ?>" size="2" value="<?= $input[CHARACTER_INTELLIGENCE] ?>" readonly>
        </td>
		<td>
			<?php
				if (!empty($errors[CHARACTER_INTELLIGENCE])) {
					echo buildThumbsDownIcon($errors[CHARACTER_INTELLIGENCE]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterWisdomLabel">Wisdom</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_WISDOM_RAW ?>" name="<?= CHARACTER_WISDOM_RAW ?>" value="<?php echo $input[CHARACTER_WISDOM_RAW] ?>">
            <input type="text" class="view_only" id="<?= CHARACTER_WISDOM ?>" name="<?= CHARACTER_WISDOM ?>" size="2" value="<?= $input[CHARACTER_WISDOM] ?>" readonly>
        </td>
		<td>
			<?php
				if (!empty($errors[CHARACTER_WISDOM])) {
					echo buildThumbsDownIcon($errors[CHARACTER_WISDOM]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterDexterityLabel">Dexterity</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_DEXTERITY_RAW ?>" name="<?= CHARACTER_DEXTERITY_RAW ?>" value="<?php echo $input[CHARACTER_DEXTERITY_RAW] ?>" readonly>
            <input type="text" class="view_only" id="<?= CHARACTER_DEXTERITY ?>" name="<?= CHARACTER_DEXTERITY ?>" size="2" value="<?= $input[CHARACTER_DEXTERITY] ?>" readonly>
        </td>
		<td>
			<?php
				if (!empty($errors[CHARACTER_DEXTERITY])) {
					echo buildThumbsDownIcon($errors[CHARACTER_DEXTERITY]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterConstitutionLabel">Constitution</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_CONSTITUTION_RAW ?>" name="<?= CHARACTER_CONSTITUTION_RAW ?>" value="<?= $input[CHARACTER_CONSTITUTION_RAW] ?>" readonly>
            <input type="text" class="view_only" id="<?= CHARACTER_CONSTITUTION ?>" name="<?= CHARACTER_CONSTITUTION ?>" size="2" value="<?= $input[CHARACTER_CONSTITUTION] ?>" readonly>
        </td>
		<td>
			<?php
				if (!empty($errors[CHARACTER_CONSTITUTION])) {
					echo buildThumbsDownIcon($errors[CHARACTER_CONSTITUTION]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterCharismaLabel">Charisma</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_CHARISMA_RAW ?>" name="<?= CHARACTER_CHARISMA_RAW ?>" value="<?= $input[CHARACTER_CHARISMA_RAW] ?>" readonly>
            <input type="text" class="view_only" id="<?= CHARACTER_CHARISMA ?>" name="<?= CHARACTER_CHARISMA ?>" size="2" value="<?= $input[CHARACTER_CHARISMA] ?>" readonly>
        </td>
		<td>
			<?php
				if (!empty($errors[CHARACTER_CHARISMA])) {
					echo buildThumbsDownIcon($errors[CHARACTER_CHARISMA]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
	</tr>
	<tr>
		<td id="characterComelinessLabel">Comeliness</td>
		<td>
            <input type="hidden" id="<?= CHARACTER_COMELINESS_RAW ?>" name="<?= CHARACTER_COMELINESS_RAW ?>" value="<?= $input[CHARACTER_COMELINESS_RAW] ?>" readonly>
            <input type="text" class="view_only" id="<?= CHARACTER_COMELINESS ?>" name="<?= CHARACTER_COMELINESS ?>" size="2" value="<?= $input[CHARACTER_COMELINESS] ?>">
        </td>
		<td>
			<?php
				if (!empty($errors[CHARACTER_COMELINESS])) {
					echo buildThumbsDownIcon($errors[CHARACTER_COMELINESS]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
	</tr>
	<tr>
		<td>Primary Class</td>
	    <td>
			<?php
				if ($primary_class_available) {
					$primary_class_name =  getCharacterClassNameFromCharacterSummary($character_class_list, $input[CHARACTER_PRIMARY_CLASS]);
					echo '<input style="float: left;" class="view_only" type="text" value="' . $primary_class_name . '" readonly>' . PHP_EOL;
					echo buildHiddenTag(CHARACTER_PRIMARY_CLASS, $input[CHARACTER_PRIMARY_CLASS]) . PHP_EOL;
					$change_primary_class = new FaEditIcon();
					$change_primary_class->addStyle('float: right;');
					$change_primary_class->setOnClickJsFunction('submitTheForm');
					$change_primary_class->addOnclickJsParameter('characterCreation2');
					$change_primary_class->addOnclickJsParameter(CHARACTER_PRIMARY_CLASS);
					echo $change_primary_class->build();
				} else {
					echo '<select onchange="disableFinalize(' . FINALIZE_BUTTON_ID . ');" style="font-size: 1em;" id="' . CHARACTER_PRIMARY_CLASS . '" name="' . CHARACTER_PRIMARY_CLASS . '">' . PHP_EOL;
					$selected_primary_class = $input[CHARACTER_PRIMARY_CLASS] ?? ''; 
					echo '<option value='. NO_CLASS_SELECTED .'>None</option>';
					foreach($primary_classes AS $primary_class) {
						$primary_character_class_id = getCharacterClassId($character_class_list, $primary_class);
						$selected_option = $selected_primary_class == $primary_character_class_id ? " selected" : '';
						echo '<option value="' . $primary_character_class_id . '"' . $selected_option . '>' . $primary_class . '</option>' . PHP_EOL;
					}
					echo '</select>' . PHP_EOL;
				}
			?>			
		</td>
		<td>
			<?php
				if (!empty($errors[CHARACTER_PRIMARY_CLASS])) {
					echo buildThumbsDownIcon($errors[CHARACTER_PRIMARY_CLASS]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
	</tr>
	<?php
	if (count($secondary_classes) > 0 || $secondary_class_available) {
		echo '<tr>' . PHP_EOL;
		echo '<td>2<sup>nd</sup> class</td>' . PHP_EOL;
		echo '<td>';
		if ($secondary_class_available) {
			$secondary_class_name = getCharacterClassNameFromCharacterSummary($character_class_list, $input[CHARACTER_SECONDARY_CLASS]);
			echo '<input style="float: left;" class="view_only" type="text" value="' . $secondary_class_name . '" readonly>' . PHP_EOL;
			echo buildHiddenTag(CHARACTER_SECONDARY_CLASS, $input[CHARACTER_SECONDARY_CLASS]) . PHP_EOL;
			$change_secondary_class = new FaEditIcon();
			$change_secondary_class->addStyle('float: right;');
			$change_secondary_class->setOnClickJsFunction('submitTheForm');
			$change_secondary_class->addOnclickJsParameter('characterCreation2');
			$change_secondary_class->addOnclickJsParameter(CHARACTER_SECONDARY_CLASS);
			echo $change_secondary_class->build();
		} else {
			echo '<select onchange="disableFinalize(' . FINALIZE_BUTTON_ID . ');" style="font-size: 1em;" id="' . CHARACTER_SECONDARY_CLASS . '" name="' . CHARACTER_SECONDARY_CLASS . '">' . PHP_EOL;
			echo '<option value=' . NO_CLASS_SELECTED .'>None</option>' . PHP_EOL;
			$selected_secondary_class = $input[CHARACTER_SECONDARY_CLASS] ?? '';
			foreach($secondary_classes AS $secondary_class) {
				$secondary_character_class_id = getCharacterClassId($character_class_list, $secondary_class);
				$selected_option =  $selected_secondary_class == $secondary_character_class_id ? " selected" : '';
				echo '<option value="' . $secondary_character_class_id . '"' . $selected_option . '>' . $secondary_class . '</option>' . PHP_EOL;
			}
			echo '</select>' . PHP_EOL;
		}
		echo '</td>' . PHP_EOL;
		echo '</tr>' . PHP_EOL;
	}

	if ((is_array($tertiary_classes) && count($tertiary_classes) > 0) || $tertiary_class_available) {
		echo '<tr>' . PHP_EOL;
		echo '<td>3<sup>rd</sup> class</td>' . PHP_EOL;
		echo '<td>';
		if ($tertiary_class_available) {
			$tertiary_class_name = getCharacterClassNameFromCharacterSummary($character_class_list, $input[CHARACTER_TERTIARY_CLASS]);
			echo '<input style="float: left;" class="view_only" type="text" value="' . $tertiary_class_name . '" readonly>' . PHP_EOL;
			echo buildHiddenTag(CHARACTER_TERTIARY_CLASS, $input[CHARACTER_TERTIARY_CLASS]) . PHP_EOL;
			$change_tertiary_class = new FaEditIcon();
			$change_tertiary_class->addStyle('float: right;');
			$change_tertiary_class->setOnClickJsFunction('submitTheForm');
			$change_tertiary_class->addOnclickJsParameter('characterCreation2');
			$change_tertiary_class->addOnclickJsParameter(CHARACTER_TERTIARY_CLASS);
			echo $change_tertiary_class->build();
		} else {
			echo '<select onchange="disableFinalize(' . FINALIZE_BUTTON_ID . ');" style="font-size: 1em;" id="' . CHARACTER_TERTIARY_CLASS . '" name="' . CHARACTER_TERTIARY_CLASS . '">' . PHP_EOL;
			echo '<option value=' . NO_CLASS_SELECTED . '>None</option>' . PHP_EOL;
			$selected_tertiary_class = $input[CHARACTER_TERTIARY_CLASS] ?? '';
			foreach($tertiary_classes AS $tertiary_class) {
				$tertiary_character_class_id = getCharacterClassId($character_class_list, $tertiary_class);
				$selected_option = $selected_tertiary_class == $tertiary_character_class_id ? " selected" : '';
				echo '<option value="' . $tertiary_character_class_id . '"' . $selected_option . '>' . $tertiary_class . '</option>' . PHP_EOL;
			}
			echo '</select>' . PHP_EOL;
		}
		echo '</td>' . PHP_EOL;
		echo '</tr>' . PHP_EOL;
	}
	?>
</table>
</div>
<?php
	echo buildHiddenTag(PAGE_ACTION, PAGE_ACTION_VALIDATE);
	$button_bar = '<div style="margin-top: 5px; padding-bottom: 5px; padding-left: 5px; width: 405px;" class="character_create_action_bar_container">' . PHP_EOL;
	$button_bar .= '<div class="character_create_action_bar_item_one"><button style="margin-top: 5px;" type="submit" formaction="characterCreation1.php">Attributes</button></div>' . PHP_EOL;
	$button_bar .= '<div style="text-align: center;"  class="character_create_action_bar_item_two"><button style="margin-top: 5px;" type="submit" formaction="characterCreation2.php">Validate</button></div>' . PHP_EOL;
	$disabled = $errors_exist ? " disabled" : '';
	$finalize_button_style = "float: right; margin-top: 5px;";
	if ($errors_exist) {
		$finalize_button_style .= ' opacity: 0.5; cursor: not-allowed';
	}
	$button_bar .= '<div class="character_create_action_bar_item_three"><button id="' . FINALIZE_BUTTON_ID . '" style="' . $finalize_button_style . '" type="submit" formaction="characterCreation3.php"' . $disabled . '>Finalize</button></div>' . PHP_EOL;
	$button_bar .= '</div>' . PHP_EOL;
	echo $button_bar;
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

function getCharacterClassNameFromCharacterSummary($character_class_list, $character_class_id) {
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

function validateCharacterClass(&$errors, $character_class_minimums, $character_class_maximums, $character_class_id, $character_class_name, $input) {
	$min_strength = getMinimumsForClass($character_class_minimums, $character_class_id, CHARACTER_STRENGTH);
	$max_strength = getMaximumsForClass($character_class_maximums, $character_class_id, CHARACTER_STRENGTH);
	if ($input[CHARACTER_STRENGTH] < $min_strength) {
		$errors[CHARACTER_STRENGTH][] = 'Does not meet minimum for ' . $character_class_name . '(' . $min_strength . ')';
	}

	if ($input[CHARACTER_STRENGTH] > $max_strength) {
		$errors[CHARACTER_STRENGTH][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_strength . ')';
	}

	$min_intelligence = getMinimumsForClass($character_class_minimums, $character_class_id, CHARACTER_INTELLIGENCE);
	$max_intelligence = getMaximumsForClass($character_class_maximums, $character_class_id, CHARACTER_INTELLIGENCE);
	if ($input[CHARACTER_INTELLIGENCE] < $min_intelligence) {
		$errors[CHARACTER_INTELLIGENCE][] = 'Does not meet minimum for ' . $character_class_name . '(' . $min_intelligence . ')';
	}

	if ($input[CHARACTER_INTELLIGENCE] > $max_intelligence) {
		$errors[CHARACTER_INTELLIGENCE][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_intelligence . ')';
	}

	$min_wisdom = getMinimumsForClass($character_class_minimums, $character_class_id, CHARACTER_WISDOM);
	$max_wisdom = getMaximumsForClass($character_class_maximums, $character_class_id, CHARACTER_WISDOM);
	if ($input[CHARACTER_WISDOM] < $min_wisdom) {
		$errors[CHARACTER_WISDOM][] = 'Does not meet minimum for ' . $character_class_name . '(' . $min_wisdom . ')';
	}

	if ($input[CHARACTER_WISDOM] > $max_wisdom) {
		$errors[CHARACTER_WISDOM][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_wisdom . ')';
	}

	$min_dexterity = getMinimumsForClass($character_class_minimums, $character_class_id, CHARACTER_DEXTERITY);
	$max_dexterity = getMaximumsForClass($character_class_maximums, $character_class_id, CHARACTER_DEXTERITY);
	if ($input[CHARACTER_DEXTERITY] < $min_dexterity) {
		$errors[CHARACTER_DEXTERITY][] = 'Does not meet minimum for ' . $character_class_name . '(' . $min_dexterity . ')';
	}

	if ($input[CHARACTER_DEXTERITY] > $max_dexterity) {
		$errors[CHARACTER_DEXTERITY][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_dexterity . ')';
	}

	$min_constitution = getMinimumsForClass($character_class_minimums, $character_class_id, CHARACTER_CONSTITUTION);
	$max_constitution = getMaximumsForClass($character_class_maximums, $character_class_id, CHARACTER_CONSTITUTION);
	if ($input[CHARACTER_CONSTITUTION] < $min_constitution) {
		$errors[CHARACTER_CONSTITUTION][] = 'Does not meet minimum for ' . $character_class_name . '(' . $min_constitution . ')';
	}

	if ($input[CHARACTER_CONSTITUTION] > $max_constitution) {
		$errors[CHARACTER_CONSTITUTION][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_constitution . ')';
	}

	$min_charisma = getMinimumsForClass($character_class_minimums, $character_class_id, CHARACTER_CHARISMA);
	$max_charisma = getMaximumsForClass($character_class_maximums, $character_class_id, CHARACTER_CHARISMA);
	if ($input[CHARACTER_CHARISMA] < $min_charisma) {
		$errors[CHARACTER_CHARISMA][] = 'Does not meet minimum for ' . $character_class_name . '(' . $min_charisma . ')';
	}

	if ($input[CHARACTER_CHARISMA] > $max_charisma) {
		$errors[CHARACTER_CHARISMA][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_charisma . ')';
	}

	$min_comeliness = getMinimumsForClass($character_class_minimums, $character_class_id, CHARACTER_COMELINESS);
	$max_comeliness = getMaximumsForClass($character_class_maximums, $character_class_id, CHARACTER_COMELINESS);
	if ($input[CHARACTER_COMELINESS] < $min_comeliness) {
		$errors[CHARACTER_COMELINESS][] = 'Does not meet minimum for ' . $character_class_name . '(' . $min_comeliness . ')';
	}

	if ($input[CHARACTER_COMELINESS] > $max_comeliness) {
		$errors[CHARACTER_COMELINESS][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_comeliness . ')';
	}
}

function buildThumbsUpIcon() {
	return '<span class="fa-regular fa-thumbs-up"></span>';
}

function buildThumbsDownIcon($error_message_list) {
	$output_html = '<span class="error"><ul>';
	foreach($error_message_list AS $error_message) {
		$output_html .= '<li>' . $error_message . '</li>';
	}

	$output_html .= '</ul></span>';

	 return  $output_html;
}

function getCharacterPrimaryClassesForRace($class_combinations, $race_id) {
	$primary_classes = [];
	$all_classes_for_race = $class_combinations[$race_id];
	foreach($all_classes_for_race AS $class_for_race) {
		if (!is_array($class_for_race)) {
			$primary_classes[] = $class_for_race;
		}
	}
	return $primary_classes;
}

function getCharacterSecondaryClassesForRace($class_combinations, $race_id, $primary_class) {
	$secondary_classes = [];
	if (!empty($class_combinations[$race_id][$primary_class])) {
		$all_secondary_classes = $class_combinations[$race_id][$primary_class];
		foreach($all_secondary_classes AS $secondary_class) {
			if (!is_array($secondary_class)) {
				$secondary_classes[] = $secondary_class;
			}
		}
	}

	return $secondary_classes;
}

function getCharacterTertiaryClassesForRace($class_combinations, $race_id, $primary_class, $secondary_class) {
	if (isset($class_combinations[$race_id][$primary_class][$secondary_class])) {
		return $class_combinations[$race_id][$primary_class][$secondary_class];
	}

	return null;
}

function errorsExist($errors) {
	return count($errors[CHARACTER_STRENGTH]) > 0 || count($errors[CHARACTER_INTELLIGENCE]) > 0 || count($errors[CHARACTER_WISDOM]) > 0 || count($errors[CHARACTER_DEXTERITY]) > 0 || count($errors[CHARACTER_CONSTITUTION]) > 0 || count($errors[CHARACTER_CHARISMA]) > 0 || count($errors[CHARACTER_COMELINESS]) > 0 || count($errors[CHARACTER_PRIMARY_CLASS]) > 0;
}
?>

