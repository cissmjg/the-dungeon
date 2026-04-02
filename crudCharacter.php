<?php
declare(strict_types=1);
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/webio/pageAction.php';
require_once __DIR__ . '/webio/characterAction.php';
require_once __DIR__ . '/characterActionRoutes.php';
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/helper/HtmlHelper.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once __DIR__ . '/webio/raceId.php';

require_once __DIR__ . '/dbio/constants/characterAttributes.php';
require_once __DIR__ . '/dbio/constants/characterClasses.php';
require_once __DIR__ . '/rules/characterClassSuperStats.php';
require_once __DIR__ . '/rules/characterClassRestrictions.php';

// Alignments
const LAWFUL_GOOD = "Lawful Good";
const LAWFUL_NEUTRAL = "Lawful Neutral";
const LAWFUL_EVIL = "Lawful Evil";
const NEUTRAL_GOOD = "Neutral Good";
const NEUTRAL = "Neutral";
const NEUTRAL_EVIL = "Neutral Evil";
const CHAOTIC_GOOD = "Chaotic Good";
const CHAOTIC_NEUTRAL = "Chaotic Neutral";
const CHAOTIC_EVIL = "Chaotic Evil";

//Social Classes
const UPPER_UPPER_CLASS = "UUC";
const UPPER_MIDDLE_CLASS = "UMC";
const UPPER_LOWER_CLASS = "ULC";
const MIDDLE_UPPER_CLASS = "MUC";
const MIDDLE_MIDDLE_CLASS = "MMC";
const MIDDLE_LOWER_CLASS = "MLC";
const LOWER_UPPER_CLASS = "LUC";
const LOWER_MIDDLE_CLASS = "LMC";
const LOWER_LOWER_CLASS = "LLC";

$input = [];
$errors = [];

$errors[CHARACTER_STRENGTH] = [];
$errors[CHARACTER_INTELLIGENCE] = [];
$errors[CHARACTER_WISDOM] = [];
$errors[CHARACTER_DEXTERITY] = [];
$errors[CHARACTER_CONSTITUTION] = [];
$errors[CHARACTER_CHARISMA] = [];
$errors[CHARACTER_COMELINESS] = [];
$errors[CHARACTER_ARMOR_CLASS] = [];
$errors[CHARACTER_HIT_POINTS] = [];

// Page Action constants
const PAGE_UPDATE = "update";
const PAGE_DELETE = "delete";
const PAGE_VIEW = "view";
const NO_CHARACTER_CLASS_ID = 0;

$classes_that_know_spells = array('Magic-User', 'Illusionist', 'Healer', 'Wu Jen');

getPageAction($errors, $input);
$page_action = $input[PAGE_ACTION];

getPlayerName($errors, $input);

$race_list = [];
if ($page_action == PAGE_UPDATE) {
    $race_list = getRaceList($pdo, $errors);
}

$character_class_list = [];
if ($page_action == PAGE_UPDATE) {
    $character_class_list = getCharacterClassList($pdo, $errors);
}

$character_action = '';
if ($page_action == PAGE_UPDATE) {
    $character_action = CurlHelper::buildUrlDbioDirectory('updateCharacter.php');
} else if ($page_action == PAGE_DELETE) {
    $character_action = CurlHelper::buildCharacterActionRouterUrl();
}

$action_bar = '';
$character_details = null;

getCharacterName($errors, $input);
$character_details = getExistingCharacter($input[PLAYER_NAME], $input[CHARACTER_NAME]);
foreach ($character_details AS $attribute_name => $attribute_value) {
	$input[$attribute_name] = $attribute_value;
}

$action_bar = buildActionBar($page_action, $input[PLAYER_NAME], $input[CHARACTER_NAME]);

$input_class='valid';
$read_only = '';
if ($page_action == PAGE_DELETE) {
	$read_only = ' readonly';
	$input_class = "view_only";
}

$last_update_message = '';
if(isset($_GET['updateTimestamp']) || isset($_POST['updateTimestamp'])) {
	getRequiredIntegerParameter($errors, $input, __FILE__, 'updateTimestamp');
	date_default_timezone_set("America/New_York");
	$last_update_message = $input[CHARACTER_NAME] . ' last updated on ' . date("h:i:sa");
}

$primary_character_class_name = getCharacterClassNameFromClassId($character_class_list, $input[CHARACTER_CLASSES]->characterClass1->class_id);
validateCharacterClass($errors, $character_class_minimums, $character_class_maximums, $input[CHARACTER_CLASSES]->characterClass1->class_id, $primary_character_class_name, $input);

$secondary_class_available = !empty($input[CHARACTER_CLASSES]->characterClass2);
if ($secondary_class_available) {
	$secondary_character_class_name = getCharacterClassNameFromClassId($character_class_list, $input[CHARACTER_CLASSES]->characterClass2->class_id);
	validateCharacterClass($errors, $character_class_minimums, $character_class_maximums, $input[CHARACTER_CLASSES]->characterClass2->class_id, $secondary_character_class_name, $input);
}

$tertiary_class_available = !empty($input[CHARACTER_CLASSES]->characterClass3);
if ($tertiary_class_available) {
	$tertiary_character_class_name = getCharacterClassNameFromClassId($character_class_list, $input[CHARACTER_CLASSES]->characterClass3->class_id);
	validateCharacterClass($errors, $character_class_minimums, $character_class_maximums, $input[CHARACTER_CLASSES]->characterClass3->class_id, $tertiary_character_class_name, $input);
}

$page_title = $input[CHARACTER_NAME];
$site_css_file = 'dnd-default.css';
$page_specific_js = 'crudCharacter.js';
$page_specific_css = '';
$enable_toggle_panels = false;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
<span class="action_bar"><?= $action_bar ?></span>
<div> <!-- Attributes/Classes -->
<span id="statusBar" style="font-weight: bold;"><?= $last_update_message ?></span>
<table>
<form action="<?php echo $character_action ?>" method="POST">
	<input type="hidden" id = "playerName" name="<?= PLAYER_NAME ?>" value="<?= $input[PLAYER_NAME] ?>">
	<tr>
		<td class="character_detail_header" colspan="3"><?php echo $input[CHARACTER_NAME] ?? ''; ?>
			<input type="hidden" id="<?= CHARACTER_NAME ?>" name="<?= CHARACTER_NAME ?>" value="<?php echo $input[CHARACTER_NAME] ?? ''; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" id="raceIdLabel">Race</td>
		<td>
		<?php
			if ($page_action == PAGE_UPDATE) {
				echo '<select class="' . $input_class . '" id="' . CHARACTER_RACE_ID . '" name="' . CHARACTER_RACE_ID . '" ' . $read_only . '>' . PHP_EOL;
				$selectedRace = $input['race'];
				for($i = 0; $i < count($race_list); $i++) {
					$race = $race_list[$i];
					$selected = $race['race_name'] == $selectedRace ? " selected" : '';
					echo '<option value="' . $race[RACE_ID] . '"' . $selected . '>' . $race['race_name'] . '</option>' . PHP_EOL;
				}
				echo '</select>' . PHP_EOL;
			} else {
				echo $input['race'];
				echo '<input type="hidden" id="characterRace" name="characterRace" value="' . $input['race'] . '"' . $read_only . '>' . PHP_EOL;
			}
		?>
		</td>
	</tr>
	<tr style="background-color: lightgray;">
	<td colspan="2" id="genderIdLabel">Gender</td>
		<td>
		<?php
			if ($page_action == PAGE_UPDATE) {
				$male_selected = '';
				$female_selected = '';
				if (!empty($input[CHARACTER_GENDER])) {
					$input[CHARACTER_GENDER] == 'M' ? $male_selected = " selected" : '';
					$input[CHARACTER_GENDER] == 'F' ? $female_selected = " selected" : '';
				}

				echo '<select class="valid" id="' . CHARACTER_GENDER .'" name="' . CHARACTER_GENDER .'">' . PHP_EOL;
				echo '<option value="M"' . $male_selected . '>Male</option>' . PHP_EOL;
				echo '<option value="F"' . $female_selected . '>Female</option>' . PHP_EOL;
				echo '</select>' . PHP_EOL;
			} else {
				$gender = $input[CHARACTER_GENDER] == 'M' ? "Male" : "Female";
				echo $gender . PHP_EOL;
				echo '<input type="hidden" id="' . CHARACTER_GENDER . '" name="' . CHARACTER_GENDER . '" value="' . $input[CHARACTER_GENDER] . '">' . PHP_EOL;
			}
        ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" id="characterStrengthLabel">Strength</td>
		<td>
		<?php 
			if ($page_action == PAGE_UPDATE) {
				$character_strength = $input[CHARACTER_STRENGTH] ?? '';
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_STRENGTH . '" name="' . CHARACTER_STRENGTH . '"  min="3" max="25" value="' . $character_strength . '" required>';
				$super_strength_applies = doesSuperStrengthApply($input, $character_super_stats);
				if ($super_strength_applies) {
					echo '/&nbsp;';
					$character_super_strength = $input[CHARACTER_SUPER_STRENGTH] ?? '';
					echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_SUPER_STRENGTH . '" name="' . CHARACTER_SUPER_STRENGTH . '" min="0" max="100" value="' . $character_super_strength . '"' . $read_only . ' required>';
				} 
			} else {
				$character_strength = $input[CHARACTER_STRENGTH];
				$character_super_strength = $input[CHARACTER_SUPER_STRENGTH] ?? '';
				$super_strength_applies = doesSuperStrengthApply($input, $character_super_stats);
				if ($super_strength_applies && !empty($input[CHARACTER_SUPER_STRENGTH])) {
					$character_strength .= '/' . $input[CHARACTER_SUPER_STRENGTH];
					echo '<input type="hidden"' . '" id="' . CHARACTER_SUPER_STRENGTH . '" name="' . CHARACTER_SUPER_STRENGTH . '" value="' . $character_super_strength . '">';
				}
				echo $character_strength;
			}
		?>
		</td>
		<?php if ($page_action == PAGE_UPDATE): ?>
		<td>
			<?php
				if (!empty($errors[CHARACTER_STRENGTH])) {
					echo buildThumbsDownIcon($errors[CHARACTER_STRENGTH]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
		<?php endif ?>
	</tr>
	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterIntelligenceLabel">Intelligence</td>
		<td>
		<?php 
			if ($page_action == PAGE_UPDATE) {
				$character_intelligence = $input[CHARACTER_INTELLIGENCE] ?? '';
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_INTELLIGENCE . '" name="' . CHARACTER_INTELLIGENCE .'" min="3" max="25" value="' . $character_intelligence .'" required>';
				$super_intelligence_applies = doesSuperIntelligenceApply($input, $character_super_stats);
				if ($super_intelligence_applies) {
					echo '/&nbsp;';
					$character_super_intelligence = $input[CHARACTER_SUPER_INTELLIGENCE] ?? '';
					echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_SUPER_INTELLIGENCE . '" name="' . CHARACTER_SUPER_INTELLIGENCE . '" min="0" max="100" value="' . $character_super_intelligence . '"' . $read_only . ' required>';
				}
			} else {
				$character_intelligence = $input[CHARACTER_INTELLIGENCE];
				$character_super_intelligence = $input[CHARACTER_SUPER_INTELLIGENCE] ?? '';
				$super_intelligence_applies = doesSuperIntelligenceApply($input, $character_super_stats);
				if ($super_intelligence_applies && !empty($input[CHARACTER_SUPER_INTELLIGENCE])) {
					$character_intelligence .= '/' . $input[CHARACTER_SUPER_INTELLIGENCE];
					echo '<input type="hidden"' . '" id="' . CHARACTER_SUPER_INTELLIGENCE . '" name="' . CHARACTER_SUPER_INTELLIGENCE . '" value="' . $character_super_intelligence . '">';
				}
				echo $character_intelligence;
			}
		?>
		</td>
		<?php if ($page_action == PAGE_UPDATE): ?>
			<td>
			<?php
				if (!empty($errors[CHARACTER_INTELLIGENCE])) {
					echo buildThumbsDownIcon($errors[CHARACTER_INTELLIGENCE]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
		<?php endif ?>
	</tr>
	<tr>
		<td colspan="2" id="characterWisdomLabel">Wisdom</td>
		<td>
			<?php
			if ($page_action == PAGE_UPDATE) {
				$character_wisdom = $input[CHARACTER_WISDOM] ?? '';
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_WISDOM .'" name="' . CHARACTER_WISDOM . '" min="3" max="25" value="' . $character_wisdom . '" required>';
				$super_wisdom_applies = doesSuperWisdomApply($input, $character_super_stats);
				if ($super_wisdom_applies) {
					echo '/&nbsp;';
					$character_super_wisdom = $input[CHARACTER_SUPER_WISDOM] ?? '';
					echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_SUPER_WISDOM . '" name="' . CHARACTER_SUPER_WISDOM . '" min="0" max="100" value="' . $character_super_wisdom . '"' . $read_only . ' required>';
				}
			} else {
				$character_wisdom = $input[CHARACTER_WISDOM];
				$character_super_wisdom = $input[CHARACTER_SUPER_WISDOM] ?? '';
				$super_wisdom_applies = doesSuperWisdomApply($input, $character_super_stats);
				if ($super_wisdom_applies && !empty($input[CHARACTER_SUPER_WISDOM])) {
					$character_wisdom .= '/' . $input[CHARACTER_SUPER_WISDOM];
					echo '<input type="hidden"' . '" id="' . CHARACTER_SUPER_WISDOM . '" name="' . CHARACTER_SUPER_WISDOM . '" value="' . $character_super_wisdom . '">';
				}
				echo $character_wisdom;
			}
		?>
		</td>
		<?php if ($page_action == PAGE_UPDATE): ?>
			<td>
			<?php
				if (!empty($errors[CHARACTER_WISDOM])) {
					echo buildThumbsDownIcon($errors[CHARACTER_WISDOM]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
		<?php endif ?>
	</tr>
	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterDexterityLabel">Dexterity</td>
		<td>
		<?php
			if ($page_action == PAGE_UPDATE) {
				$character_dexterity = $input[CHARACTER_DEXTERITY] ?? '';
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_DEXTERITY .'" name="' . CHARACTER_DEXTERITY . '" min="3" max="25" value="' . $character_dexterity . '" required>';
				$super_dexterity_applies = doesSuperDexterityApply($input, $character_super_stats);
				if ($super_dexterity_applies) {
					echo '/&nbsp;';
					$character_super_dexterity = $input[CHARACTER_SUPER_DEXTERITY] ?? '';
					echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_SUPER_DEXTERITY .'" name="' . CHARACTER_SUPER_DEXTERITY . '" min="0" max="100" value="' . $character_super_dexterity . '"' . $read_only . ' required>';
				}
			} else {
				$character_dexterity = $input[CHARACTER_DEXTERITY];
				$character_super_dexterity = $input[CHARACTER_SUPER_DEXTERITY] ?? '';
				$super_dexterity_applies = doesSuperDexterityApply($input, $character_super_stats);
				if ($super_dexterity_applies && !empty($input[CHARACTER_SUPER_DEXTERITY])) {
					$character_dexterity .= '/' . $input[CHARACTER_SUPER_DEXTERITY];
					echo '<input type="hidden"' . '" id="' . CHARACTER_SUPER_DEXTERITY . '" name="' . CHARACTER_SUPER_DEXTERITY . '" value="' . $character_super_dexterity . '">';
				}
				echo $character_dexterity;
			}
		?>
		</td>
		<?php if ($page_action == PAGE_UPDATE): ?>
			<td>
			<?php
				if (!empty($errors[CHARACTER_DEXTERITY])) {
					echo buildThumbsDownIcon($errors[CHARACTER_DEXTERITY]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
		<?php endif ?>
	</tr>
	<tr>
		<td colspan="2" id="characterConstitutionLabel">Constitution</td>
		<td>
		<?php
			if ($page_action == PAGE_UPDATE) {
				$character_constitution = $input[CHARACTER_CONSTITUTION] ?? '';
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_CONSTITUTION . '" name="' . CHARACTER_CONSTITUTION .'" min="3" max="25" value="' . $character_constitution . '" required>';
				$super_constitution_applies = doesSuperConstitutionApply($input, $character_super_stats);
				if ($super_constitution_applies && !empty($input[CHARACTER_SUPER_CONSTITUTION])) {
					echo '/&nbsp;';
					$character_super_constitution = $input[CHARACTER_SUPER_CONSTITUTION] ?? '';
					echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_SUPER_CONSTITUTION .'" name="' . CHARACTER_SUPER_CONSTITUTION . '" min="0" max="100" value="' . $character_super_constitution . '"' . $read_only . ' required>';
				}
			} else {
				$character_constitution = $input[CHARACTER_CONSTITUTION];
				$character_super_constitution = $input[CHARACTER_SUPER_CONSTITUTION] ?? '';
				$super_constitution_applies = doesSuperConstitutionApply($input, $character_super_stats);
				if ($super_constitution_applies && !empty($input[CHARACTER_SUPER_CONSTITUTION])) {
					$character_constitution .= '/' . $input[CHARACTER_SUPER_CONSTITUTION];
					echo '<input type="hidden"' . '" id="' . CHARACTER_SUPER_CONSTITUTION . '" name="' . CHARACTER_SUPER_CONSTITUTION . '" value="' . $character_super_constitution . '">';
				}
				echo $character_constitution;
			}
		?>
		</td>
		<?php if ($page_action == PAGE_UPDATE): ?>
			<td>
			<?php
				if (!empty($errors[CHARACTER_CONSTITUTION])) {
					echo buildThumbsDownIcon($errors[CHARACTER_CONSTITUTION]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
		<?php endif ?>
	</tr>
	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterCharismaLabel">Charisma</td>
		<td>
		<?php
			if ($page_action == PAGE_UPDATE) {
				$character_charisma = $input[CHARACTER_CHARISMA] ?? '';
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_CHARISMA .'" name="' . CHARACTER_CHARISMA .'" min="3" max="25" value="'. $character_charisma .'" required>';
			} else {
				$character_charisma = $input[CHARACTER_CHARISMA];
				echo '<input type="hidden"'  . '" id="' . CHARACTER_CHARISMA .'" name="' . CHARACTER_CHARISMA .'" value="'. $character_charisma .'">';
				echo $character_charisma . '</span>';
			}
		?>
		</td>
		<?php if ($page_action == PAGE_UPDATE): ?>
			<td>
			<?php
				if (!empty($errors[CHARACTER_CHARISMA])) {
					echo buildThumbsDownIcon($errors[CHARACTER_CHARISMA]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
		<?php endif ?>
	</tr>
	<tr>
		<td colspan="2" id="characterComelinessLabel">Comeliness</td>
		<td>
		<?php
			if ($page_action == PAGE_UPDATE) {
				$character_comeliness = $input[CHARACTER_COMELINESS] ?? '';
				echo '<input type="number" style="text-align: center;" class="' . $input_class .'" id="' . CHARACTER_COMELINESS .'" name="' .  CHARACTER_COMELINESS . '" min="3" max="25" value="' . $character_comeliness . '" required>';
			} else {
				$character_comeliness = $input[CHARACTER_COMELINESS];
				echo '<input type="hidden"'  . '" id="' . CHARACTER_COMELINESS .'" name="' . CHARACTER_COMELINESS .'" value="'. $character_comeliness .'">';
				echo '<span>' . $character_comeliness;
			}
		?>
		</td>
		<?php if ($page_action == PAGE_UPDATE): ?>
			<td>
			<?php
				if (!empty($errors[CHARACTER_COMELINESS])) {
					echo buildThumbsDownIcon($errors[CHARACTER_COMELINESS]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
		<?php endif ?>
	</tr>
	<tr style="background-color: lightgray;">
		<td colspan="2" id="armorClassLabel">Armor Class</td>
		<td>
		<?php
			if ($page_action == PAGE_UPDATE) {
				$character_armor_class = $input[CHARACTER_ARMOR_CLASS] ?? '';
				echo '<input type="number" style="text-align: center;" class="' . $input_class .'" id="' . CHARACTER_ARMOR_CLASS .'" name="' . CHARACTER_ARMOR_CLASS .'" min="-10" max="10" value="' . $character_armor_class . '" required>';
			} else {
				$character_armor_class = $input[CHARACTER_ARMOR_CLASS];
				echo '<input type="hidden"'  . '" id="' . CHARACTER_ARMOR_CLASS .'" name="' . CHARACTER_ARMOR_CLASS .'" value="'. $character_armor_class .'">';
				echo $character_armor_class;
			}
		?>
		</td>
		<?php if ($page_action == PAGE_UPDATE): ?>
			<td>
			<?php
				if (!empty($errors[CHARACTER_ARMOR_CLASS])) {
					echo buildThumbsDownIcon($errors[CHARACTER_ARMOR_CLASS]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
		<?php endif ?>
	</tr>
	<tr>
		<td colspan="2" id="hitPointsLabel">Hit Points</td>
		<td>
		<?php
			if ($page_action == PAGE_UPDATE) {
				$character_hit_points =  $input[CHARACTER_HIT_POINTS] ?? '';
				echo '<input type="number" style="text-align: center;" class="' . $input_class .'" id="' . CHARACTER_HIT_POINTS .'" name="' . CHARACTER_HIT_POINTS .'" min="1" max="400" value="' . $character_hit_points . '" required>';
			} else {
				$character_hit_points =  $input[CHARACTER_HIT_POINTS];
				echo '<input type="hidden"'  . '" id="' . CHARACTER_HIT_POINTS .'" name="' . CHARACTER_HIT_POINTS .'" value="'. $character_hit_points .'">';
				echo $character_hit_points;
			}
		?>
		</td>
		<?php if ($page_action == PAGE_UPDATE): ?>
			<td>
			<?php
				if (!empty($errors[CHARACTER_HIT_POINTS])) {
					echo buildThumbsDownIcon($errors[CHARACTER_HIT_POINTS]);
				} else {
					echo buildThumbsUpIcon();
				}
			?>
		</td>
		<?php endif ?>
	</tr>

	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterMovementLabel">Movement</td>
		<td>
		<?php
			$character_movement = $input[CHARACTER_MOVEMENT] ?? '';
			if ($page_action == PAGE_UPDATE) {				
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_MOVEMENT .'" name="' . CHARACTER_MOVEMENT . '" min="3" max="25" value="' . $character_movement . '">' . '&quot;';
			} else {
				echo $character_movement . '&quot;';
			}
		?>
		</td>
	</tr>

	<tr>
		<td colspan="2" id="characterAlignmentLabel">Alignment</td>
		<td>
		<?php
			$character_alignment = $input[CHARACTER_ALIGNMENT] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<select class="valid" id="' . CHARACTER_ALIGNMENT .'" name="' . CHARACTER_ALIGNMENT .'">' . PHP_EOL;
				echo '<optgroup label="Lawful">' . PHP_EOL;
				$selected = $character_alignment == LAWFUL_GOOD ? ' selected' : '';
				echo '<option value="' . LAWFUL_GOOD . '"' . $selected . '>' . LAWFUL_GOOD . '</option>' . PHP_EOL;
				$selected = $character_alignment == LAWFUL_NEUTRAL ? ' selected' : '';
				echo '<option value="' . LAWFUL_NEUTRAL . '"' . $selected . '>' . LAWFUL_NEUTRAL . '</option>' . PHP_EOL;
				$selected = $character_alignment == LAWFUL_EVIL ? ' selected' : '';
				echo '<option value="' . LAWFUL_EVIL . '"' . $selected . '>' . LAWFUL_EVIL . '</option>' . PHP_EOL;
				echo '<optgroup label="Neutral">' . PHP_EOL;
				$selected = $character_alignment == NEUTRAL_GOOD ? ' selected' : '';
				echo '<option value="' . NEUTRAL_GOOD . '"' . $selected . '>' . NEUTRAL_GOOD . '</option>' . PHP_EOL;
				$selected = $character_alignment == NEUTRAL ? ' selected' : '';
				echo '<option value="' . NEUTRAL . '"' . $selected . '>' . NEUTRAL . '</option>' . PHP_EOL;
				$selected = $character_alignment == NEUTRAL_EVIL ? ' selected' : '';
				echo '<option value="' . NEUTRAL_EVIL . '"' . $selected . '>' . NEUTRAL_EVIL . '</option>' . PHP_EOL;
				echo '<optgroup label="Chaotic">' . PHP_EOL;
				$selected = $character_alignment == CHAOTIC_GOOD ? ' selected' : '';
				echo '<option value="' . CHAOTIC_GOOD . '"' . $selected . '>' . CHAOTIC_GOOD . '</option>' . PHP_EOL;
				$selected = $character_alignment == CHAOTIC_NEUTRAL ? ' selected' : '';
				echo '<option value="' . CHAOTIC_NEUTRAL . '"' . $selected . '>' . CHAOTIC_NEUTRAL . '</option>' . PHP_EOL;
				$selected = $character_alignment == CHAOTIC_EVIL ? ' selected' : '';
				echo '<option value="' . CHAOTIC_EVIL . '"' . $selected . '">' . CHAOTIC_EVIL . '</option>' . PHP_EOL;
				echo '</select>' . PHP_EOL;
			} else {
				echo $character_alignment;
			}
		?>
		</td>
	</tr>

	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterReligionLabel">Religion</td>
		<td>
		<?php
			$character_religion = $input[CHARACTER_RELIGION] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="text" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_RELIGION .'" name="' . CHARACTER_RELIGION . '" value="' . $character_religion . '">';
			} else {
				echo $character_religion;
			}
		?>
		</td>
	</tr>

	<tr>
		<td colspan="2" id="characterDeityLabel">Deity</td>
		<td>
		<?php
			$character_deity = $input[CHARACTER_DEITY] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="text" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_DEITY .'" name="' . CHARACTER_DEITY . '" value="' . $character_deity . '">';
			} else {
				echo $character_deity;
			}
		?>
		</td>
	</tr>

	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterHometownLabel">Hometown</td>
		<td>
		<?php
			$character_hometown = $input[CHARACTER_HOMETOWN] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="text" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_HOMETOWN .'" name="' . CHARACTER_HOMETOWN . '" value="' . $character_hometown . '">';
			} else {
				echo $character_hometown;
			}
		?>
		</td>
	</tr>

	<tr>
		<td colspan="2" id="characterHitDieLabel">Hit Die</td>
		<td>
		<?php
			$character_hit_die = $input[CHARACTER_HIT_DIE] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="text" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_HIT_DIE .'" name="' . CHARACTER_HIT_DIE . '" value="' . $character_hit_die . '">';
			} else {
				echo $character_hit_die;
			}
		?>
		</td>
	</tr>

	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterAgeLabel">Age</td>
		<td>
		<?php
			$character_age = $input[CHARACTER_AGE] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_AGE .'" name="' . CHARACTER_AGE . '" min="12" max="3000" value="' . $character_age . '">';
			} else {
				echo $character_age;
			}
		?>
		</td>
	</tr>

	<tr>
		<td colspan="2" id="characterApparentAgeLabel">Apparent Age</td>
		<td>
		<?php
			$character_apparent_age = $input[CHARACTER_APPARENT_AGE] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_APPARENT_AGE .'" name="' . CHARACTER_APPARENT_AGE . '" min="12" max="3000" value="' . $character_apparent_age . '">';
			} else {
				echo $character_apparent_age;
			}
		?>
		</td>
	</tr>

	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterUnnaturalAgeLabel">Unnatural Age</td>
		<td>
		<?php
			$character_unnatural_age = $input[CHARACTER_UNNATURAL_AGE] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="text" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_UNNATURAL_AGE .'" name="' . CHARACTER_UNNATURAL_AGE . '" value="' . $character_unnatural_age . '">';
			} else {
				echo $character_unnatural_age;
			}
		?>
		</td>
	</tr>

	<tr>
		<td colspan="2" id="characterSocialClassLabel">Social Class</td>
		<td>
		<?php
			$character_social_class = $input[CHARACTER_SOCIAL_CLASS] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<select class="valid" id="' . CHARACTER_SOCIAL_CLASS .'" name="' . CHARACTER_SOCIAL_CLASS .'">' . PHP_EOL;
				echo '<optgroup label="Upper">' . PHP_EOL;
				$selected = $character_social_class == UPPER_UPPER_CLASS ? ' selected' : '';
				echo '<option value="' . UPPER_UPPER_CLASS . '"' . $selected . '>' . UPPER_UPPER_CLASS . '</option>' . PHP_EOL;
				$selected = $character_social_class == UPPER_MIDDLE_CLASS ? ' selected' : '';
				echo '<option value="' . UPPER_MIDDLE_CLASS . '"' . $selected . '>' . UPPER_MIDDLE_CLASS . '</option>' . PHP_EOL;
				$selected = $character_social_class == UPPER_LOWER_CLASS ? ' selected' : '';
				echo '<option value="' . UPPER_LOWER_CLASS . '"' . $selected . '>' . UPPER_LOWER_CLASS . '</option>' . PHP_EOL;
				echo '<optgroup label="Middle">' . PHP_EOL;
				$selected = $character_social_class == MIDDLE_UPPER_CLASS ? ' selected' : '';
				echo '<option value="' . MIDDLE_UPPER_CLASS . '"' . $selected . '>' . MIDDLE_UPPER_CLASS . '</option>' . PHP_EOL;
				$selected = $character_social_class == MIDDLE_MIDDLE_CLASS ? ' selected' : '';
				echo '<option value="' . MIDDLE_MIDDLE_CLASS . '"' . $selected . '>' . MIDDLE_MIDDLE_CLASS . '</option>' . PHP_EOL;
				$selected = $character_social_class == MIDDLE_LOWER_CLASS ? ' selected' : '';
				echo '<option value="' . MIDDLE_LOWER_CLASS . '"' . $selected . '>' . MIDDLE_LOWER_CLASS . '</option>' . PHP_EOL;
				echo '<optgroup label="Lower">' . PHP_EOL;
				$selected = $character_social_class == LOWER_UPPER_CLASS ? ' selected' : '';
				echo '<option value="' . LOWER_UPPER_CLASS . '"' . $selected . '>' . LOWER_UPPER_CLASS . '</option>' . PHP_EOL;
				$selected = $character_social_class == LOWER_MIDDLE_CLASS ? ' selected' : '';
				echo '<option value="' . LOWER_MIDDLE_CLASS . '"' . $selected . '>' . LOWER_MIDDLE_CLASS . '</option>' . PHP_EOL;
				$selected = $character_social_class == LOWER_LOWER_CLASS ? ' selected' : '';
				echo '<option value="' . LOWER_LOWER_CLASS . '"' . $selected . '>' . LOWER_LOWER_CLASS . '</option>' . PHP_EOL;
				echo '</select>' . PHP_EOL;

			} else {
				echo $character_social_class;
			}
		?>
		</td>
	</tr>

	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterHeightLabel">Height</td>
		<td>
		<?php
			$character_height = $input[CHARACTER_HEIGHT] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="text" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_HEIGHT .'" name="' . CHARACTER_HEIGHT . '" value="' . $character_height . '">';
			} else {
				echo $character_height;
			}
		?>
		</td>
	</tr>

	<tr>
		<td colspan="2" id="characterWeightLabel">Weight</td>
		<td>
		<?php
			$character_weight = $input[CHARACTER_WEIGHT] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="text" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_WEIGHT .'" name="' . CHARACTER_WEIGHT . '" value="' . $character_weight . '">';
			} else {
				echo $character_weight;
			}
		?>
		</td>
	</tr>

	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterHairLabel">Hair</td>
		<td>
		<?php
			$character_hair = $input[CHARACTER_HAIR] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="text" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_HAIR .'" name="' . CHARACTER_HAIR . '" value="' . $character_hair . '">';
			} else {
				echo $character_hair;
			}
		?>
		</td>
	</tr>

	<tr>
		<td colspan="2" id="characterEyesLabel">Eyes</td>
		<td>
		<?php
			$character_eyes = $input[CHARACTER_EYES] ?? '';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="text" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_EYES .'" name="' . CHARACTER_EYES . '" value="' . $character_eyes . '">';
			} else {
				echo $character_eyes;
			}
		?>
		</td>
	</tr>

	<tr style="background-color: lightgray;">
		<td colspan="2" id="characterSiblingsLabel">Siblings</td>
		<td>
		<?php
			$character_siblings = $input[CHARACTER_SIBLINGS] ?? '0';
			if ($page_action == PAGE_UPDATE) {
				echo '<input type="number" style="text-align: center;" class="' . $input_class . '" id="' . CHARACTER_SIBLINGS .'" name="' . CHARACTER_SIBLINGS . '" min="0" max="99" value="' . $character_siblings . '">';
			} else {
				echo $character_siblings;
			}
		?>
		</td>
	</tr>

	<?php
			echo '<tr style="background-color: lightgray;"><td>Class</td><td style="text-align: center;">Level</td><td>XP</td></tr>' . PHP_EOL;
			echo formatClassesForEdit($input[CHARACTER_CLASSES]->characterClass1, CHARACTER_PRIMARY_CLASS, $read_only, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input_class, $page_action, $classes_that_know_spells);
			if (!empty($input[CHARACTER_CLASSES]->characterClass2)) {
				echo formatClassesForEdit($input[CHARACTER_CLASSES]->characterClass2, CHARACTER_SECONDARY_CLASS, $read_only, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input_class, $page_action, $classes_that_know_spells);
			}

			if (!empty($input[CHARACTER_CLASSES]->characterClass3)) {
				echo formatClassesForEdit($input[CHARACTER_CLASSES]->characterClass3, CHARACTER_TERTIARY_CLASS, $read_only, $input[PLAYER_NAME], $input[CHARACTER_NAME], $input_class, $page_action, $classes_that_know_spells);
			}

			if ($page_action == PAGE_UPDATE) {
				echo '<tr><td colspan="3" style="text-align: center;"><button type="submit"">' . $page_action . '</button></td></tr>';
			} 

			if ($page_action == PAGE_DELETE) {
				$character_description = "'" . $input[CHARACTER_NAME] . "'";
				echo HtmlHelper::buildHiddenTag(CHARACTER_ACTION, CHARACTER_ACTION_DELETE_CHARACTER);
				echo '<tr><td colspan="3" style="text-align: center;"><button onclick="event.preventDefault(); deleteCharacter(this.form, ' . $character_description . ')">' . $page_action . '</button></td></tr>';
			} 
		?>
</table>
</div> <!-- Attributes/Classes -->
</form>
</body>
</html>
<?php

/*				
    const  = 'movement';
    const CHARACTER_ALIGNMENT = 'alignment';
    const CHARACTER_RELIGION = 'religion';
    const CHARACTER_DEITY = 'deity';
    const CHARACTER_HOMETOWN = 'hometown';
    const CHARACTER_HIT_DIE = 'hit_die';
    const CHARACTER_AGE = 'age';
    const CHARACTER_APPARENT_AGE = 'apparent_age';
    const CHARACTER_UNNATURAL_AGE = 'unnatural_age';
    const CHARACTER_SOCIAL_CLASS = 'social_class';
    const CHARACTER_HEIGHT = 'height';
    const CHARACTER_WEIGHT = 'weight';
    const CHARACTER_HAIR = 'hair';
    CONST CHARACTER_EYES = 'eyes';
    CONST CHARACTER_SIBLINGS = 'siblings';
*/

function formatClassesForEdit($character_class, $form_field_id, $read_only, $player_name, $character_name, $input_class, $page_action, $classes_that_know_spells) {
    $form_field_id_for_XP = $form_field_id . 'XP';
    $label_field_id_for_XP = $form_field_id_for_XP . 'Label';

    $form_field_id_for_ClassName = $form_field_id . 'Name';
	$output_html = '<tr>';
	$output_html .= buildClassNameCell($label_field_id_for_XP, $character_class, $classes_that_know_spells, $player_name, $character_name);
	$output_html .= buildClassLevelCell($character_class->class_level, $player_name, $character_name, $character_class->class_name, $page_action);
    $output_html .= '<input type="hidden" id="' . $form_field_id_for_ClassName . '" name="' . $form_field_id_for_ClassName . '" value="' . $character_class->class_name . '">';
	$output_html .= '<td>';
	// $output_html .= '<label class="super_label" id="' . $label_field_id_for_XP . '" for="' . $form_field_id_for_XP . '">Total: </label>';
	if ($page_action == PAGE_UPDATE) {
		$output_html .= '<input style="width: 100%;" type="number"  style="text-align: center;" class="' . $input_class . '" id="' . $form_field_id_for_XP . '" name="' . $form_field_id_for_XP . '" value="' . $character_class->number_of_experience_points . '"' . $read_only . '>';
	} else {
		$output_html .= '<span>' . $character_class->number_of_experience_points . '</span>';
		$output_html .= '<input type="hidden" id="' . $form_field_id_for_XP . '" name="' . $form_field_id_for_XP. '" value="' . $character_class->number_of_experience_points . '">';
	}
	$output_html .= '</td>';
	$output_html .= '</tr>' . PHP_EOL;

    return $output_html;
}

function buildClassLevelCell($class_level, $player_name, $character_name, $character_class_name, $page_action) {
	if ($page_action == PAGE_UPDATE) {
		$output_html = '<td style="text-align: right;">&nbsp;';
		$output_html .= ActionBarHelper::buildPromoteClassIcon($player_name, $character_name, $character_class_name);
		$output_html .= '&nbsp;';
		$output_html .= $class_level;
	} else {
		$output_html = '<td style="text-align: center;">&nbsp;';
		$output_html .= $class_level;
	}

	$output_html .= '</td>' . PHP_EOL;
	return $output_html;
}

function buildClassNameCell($label_field_id_for_XP, $character_class, $classes_that_know_spells, $player_name, $character_name) {
	$output_html = '<td id="' . $label_field_id_for_XP . '" class="valid">';
	$output_html .= $character_class->class_name;
	if ($character_class->spell_classes != null && count($character_class->spell_classes) > 0) {
		$output_html .= buildReadySpellsIcon($character_class, $classes_that_know_spells, $player_name, $character_name);
		$output_html .= buildSpellBookIcon($character_class, $classes_that_know_spells, $player_name, $character_name);
		$output_html .= buildEditExtraSlotIcon($player_name, $character_name);
	}
	
	$output_html .= '</td>';
	return $output_html;
}

function buildReadySpellsIcon($character_class, $classes_that_know_spells, $player_name, $character_name) {
	$output_html = '&nbsp;';
	$spell_icon = '';
	$character_class_name = $character_class->class_name;
	if (getClassID($character_class_name) == GREATER_MAGE) {
		$spell_icon = ActionBarHelper::buildReadyGMSpellsIcon($player_name, $character_name);
	} else {
		$spell_icon = ActionBarHelper::buildReadySpellsIcon($player_name, $character_name);
	}

	$output_html .= $spell_icon;

	return $output_html;
}

function buildEditExtraSlotIcon($player_name, $character_name) {
	$output_html  = '&nbsp;';
	$output_html .= ActionBarHelper::buildEditExtraSlotIcon($player_name, $character_name);
	
	return $output_html;
}

function buildThumbsUpIcon() {
	return '<span class="fa-regular fa-thumbs-up"></span>';
}

function buildThumbsDownIcon($error_message_list) {
	$output_html = '<span class="warning"><ul>';
	foreach($error_message_list AS $error_message) {
		$output_html .= '<li>' . $error_message . '</li>';
	}

	$output_html .= '</ul></span>';

	 return  $output_html;
}

function doesSuperStrengthApply($input, $character_super_stats) {

	$primary_class = $input[CHARACTER_CLASSES]->characterClass1->class_id;
	if ($primary_class == PALADIN || $primary_class == CAVALIER || $primary_class == ELVEN_CAVALIER) {
		return true;
	}

	$character_strength = $input[CHARACTER_STRENGTH];
	$super_stats_for_class = $character_super_stats[$primary_class];
	if (in_array(CHARACTER_STRENGTH, $super_stats_for_class) && $character_strength == 18) {
		return true;
	}

	return false;
}

function doesSuperIntelligenceApply($input, $character_super_stats) {

	$primary_class = $input[CHARACTER_CLASSES]->characterClass1->class_id;
	$character_intelligence = $input[CHARACTER_INTELLIGENCE];
	$super_stats_for_class = $character_super_stats[$primary_class];
	if (in_array(CHARACTER_INTELLIGENCE, $super_stats_for_class) && $character_intelligence == 18) {
		return true;
	}

	return false;
}

function doesSuperWisdomApply($input, $character_super_stats) {

	$primary_class = $input[CHARACTER_CLASSES]->characterClass1->class_id;
	$character_wisdom = $input[CHARACTER_WISDOM];
	$super_stats_for_class = $character_super_stats[$primary_class];
	if (in_array(CHARACTER_WISDOM, $super_stats_for_class) && $character_wisdom == 18) {
		return true;
	}

	return false;
}

function doesSuperDexterityApply($input, $character_super_stats) {

	$primary_class = $input[CHARACTER_CLASSES]->characterClass1->class_id;
	if ($primary_class == PALADIN || $primary_class == CAVALIER || $primary_class == ELVEN_CAVALIER) {
		return true;
	}

	$character_dexterity = $input[CHARACTER_DEXTERITY];
	$super_stats_for_class = $character_super_stats[$primary_class];
	if (in_array(CHARACTER_DEXTERITY, $super_stats_for_class) && $character_dexterity == 18) {
		return true;
	}

	return false;
}

function doesSuperConstitutionApply($input, $character_super_stats) {

	$primary_class = $input[CHARACTER_CLASSES]->characterClass1->class_id;
	$character_constitution = $input[CHARACTER_CONSTITUTION];
	if ($primary_class == PALADIN || $primary_class == CAVALIER || $primary_class == ELVEN_CAVALIER) {
		return true;
	}

	$super_stats_for_class = $character_super_stats[$primary_class];
	if (in_array(CHARACTER_CONSTITUTION, $super_stats_for_class) && $character_constitution == 18) {
		return true;
	}

	return false;
}

function buildSpellBookIcon($character_class, $classes_that_know_spells, $player_name, $character_name) {
	$output_html = '';
	foreach($character_class->spell_classes AS $spell_class) {
		$output_html .= '&nbsp;';
		if ($spell_class != NULL && in_array($spell_class, $classes_that_know_spells)) {
			$output_html .= ActionBarHelper::buildEditSpellBookIcon($player_name, $character_name, $character_class->class_name);
		}
	}

	return $output_html;
}

function getCharacterClassList(\PDO $pdo, &$errors) {
	$sql_exec = "CALL getAllCharacterClasses()";
	
	$statement = $pdo->prepare($sql_exec);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in " . __FILE__ . ".getAllCharacterClasses : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

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

function getExistingCharacter($player_name, $character_name) {
    $params = [];
    $params[PLAYER_NAME] = $player_name;
    $params[CHARACTER_NAME] = $character_name;
    $params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];
    
    $url = CurlHelper::buildUrlDbioDirectory('getPlayerCharacterDetails');
    $raw_results = CurlHelper::performGetRequest($url, $params);

    return json_decode($raw_results);
}

function buildActionBar($page_action, $player_name, $character_name) {

	if ($page_action == PAGE_UPDATE) {
		$output_html = ActionBarHelper::buildUserViewIcon($player_name, $character_name);
		$output_html .= '&nbsp;';
		$output_html .= ActionBarHelper::buildUserDeleteIcon($player_name, $character_name);
		$output_html .= '&nbsp;';
		$output_html .= ActionBarHelper::buildEditWeaponsIcon($player_name, $character_name);
		$output_html .= '&nbsp;';
		$output_html .= ActionBarHelper::buildEditPlayerCharacterWeaponProficienciesIcon($player_name, $character_name);
		$output_html .= '&nbsp;';

		return $output_html . '&nbsp;';
	} else if ($page_action == PAGE_DELETE) {
		$output_html = ActionBarHelper::buildUserViewIcon($player_name, $character_name);
		$output_html .= '&nbsp;';
		$output_html .= ActionBarHelper::buildUserEditIcon($player_name, $character_name);

		return $output_html . '&nbsp;';
	} else if ($page_action == PAGE_VIEW) {
		$output_html = ActionBarHelper::buildUserEditIcon($player_name, $character_name);
		$output_html .= '&nbsp;';
		$output_html .= ActionBarHelper::buildUserDeleteIcon($player_name, $character_name);

		return $output_html . '&nbsp;';
	} else {
		return '';
	}
}

function validateCharacterClass(&$errors, $character_class_minimums, $character_class_maximums, $character_class_id, $character_class_name, $input) {
	$min_strength = getMinimumsForClass($character_class_minimums, $character_class_id, CHARACTER_STRENGTH);
	$max_strength = getMaximumsForClass($character_class_maximums, $character_class_id, CHARACTER_STRENGTH);
	if ($input[CHARACTER_STRENGTH] < $min_strength) {
		$errors[CHARACTER_STRENGTH][] = 'Does not meet minimum for ' . $character_class_name . '(' . $min_strength . ')';
	}

	if (!($character_class_id == CAVALIER || $character_class_id == PALADIN || $character_class_id == ELVEN_CAVALIER)) {
		if ($input[CHARACTER_STRENGTH] > $max_strength) {
			$errors[CHARACTER_STRENGTH][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_strength . ')';
		}
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

	if (!($character_class_id == CAVALIER || $character_class_id == PALADIN || $character_class_id == ELVEN_CAVALIER)) {
		if ($input[CHARACTER_DEXTERITY] > $max_dexterity) {
			$errors[CHARACTER_DEXTERITY][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_dexterity . ')';
		}
	}

	$min_constitution = getMinimumsForClass($character_class_minimums, $character_class_id, CHARACTER_CONSTITUTION);
	$max_constitution = getMaximumsForClass($character_class_maximums, $character_class_id, CHARACTER_CONSTITUTION);
	if ($input[CHARACTER_CONSTITUTION] < $min_constitution) {
		$errors[CHARACTER_CONSTITUTION][] = 'Does not meet minimum for ' . $character_class_name . '(' . $min_constitution . ')';
	}

	if (!($character_class_id == CAVALIER || $character_class_id == PALADIN || $character_class_id == ELVEN_CAVALIER)) {
		if ($input[CHARACTER_CONSTITUTION] > $max_constitution) {
			$errors[CHARACTER_CONSTITUTION][] = 'Exceeds maximum for ' . $character_class_name . '(' . $max_constitution . ')';
		}
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

	if ($input[CHARACTER_ARMOR_CLASS] < -10 || $input[CHARACTER_ARMOR_CLASS] > 10) {
		$errors[CHARACTER_ARMOR_CLASS][] = 'Invalid value for armor class ' . '(' . $input[CHARACTER_ARMOR_CLASS] . ')';
	}

	if ($input[CHARACTER_HIT_POINTS] < 1 || $input[CHARACTER_HIT_POINTS] > 400) {
		$errors[CHARACTER_HIT_POINTS][] = 'Invalid value for hit points ' . '(' . $input[CHARACTER_HIT_POINTS] . ')';
	}
}

function getCharacterClassNameFromClassId($character_class_list, $character_class_id) {
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
