<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/classes/ActionBarHelper.php';
require_once 'characterAttributes.php';
require_once 'characterClasses.php';
require_once 'characterSummary.php';
require_once 'characterSummaryRenderer.php';

require_once 'playerName.php';
require_once 'characterName.php';

$input = [];
$log = [];
$errors = [];

getPlayerName($errors, $input);
getCharacterName($errors, $input);

$page_title = $input[CHARACTER_NAME];
$character_details = null;

$character_details = getExistingCharacter($input['playerName'], $input[CHARACTER_NAME]);
foreach ($character_details AS $attribute_name => $attribute_value) {
	$input[$attribute_name] = $attribute_value;
}

$character_summary = new CharacterSummary();
$character_summary->init($pdo, $input['playerName'], $input[CHARACTER_NAME]);

$character_summary_renderer = new CharacterSummaryRenderer($input[CHARACTER_NAME]);
$character_summary_stats = $character_summary_renderer->render($character_summary);

$action_bar = buildActionBar($input['playerName'], $input[CHARACTER_NAME], $character_summary);

$nf = new NumberFormatter('en_US', NumberFormatter::ORDINAL);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
	<link rel="stylesheet" href="dnd-default.css">
	<link rel="stylesheet" href="characterSheet.css">
	<script src="https://kit.fontawesome.com/4295d6f264.js" crossorigin="anonymous"></script>
</head>
<body>
<span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?>
<div class="character_sheet_container">
	<div class="character_sheet_column">
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="4" class="tableHeader"><?= $input[CHARACTER_NAME] ?></td>
			</tr>
			<tr>
				<td class="titleAttributeLiteral">Armor Class</td>
				<td class="titleAttributeValue"><?= $character_summary->getArmorClass(); ?></td>
				<td class="titleAttributeLiteral">Hit Points</td>
				<td class="titleAttributeValue"><?= $character_summary->getHitPoints(); ?></td>
			</tr>
		</table>
		<div>&nbsp;</div>
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="7" class="tableHeader">Combat Summary</td>
			</tr>
			<tr>
				<td class="combatSummaryHeader">Weapon</td>
				<td class="combatSummaryHeader">Spd</td>
				<td class="combatSummaryHeader">Dmg</td>
				<td class="combatSummaryHeader">Range</td>
				<td class="combatSummaryHeader">Hit Adj.</td>
				<td class="combatSummaryHeader">Dam. Adj.</td>
				<td class="combatSummaryHeader">Notes</td>
			</tr>
		</table>
		<div>&nbsp;</div>
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="2" class="tableHeader">Class Abilities</td>
			</tr>
			<tr>
				<td width=75% class="tableHeader">Type</td>
				<td width=25% class="tableHeader">Total</td>
			</tr>
		</table>
		<div>&nbsp;</div>
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="2" class="tableHeader">Saving Throws</td>
			</tr>
			<tr>
				<td width=75% class="tableHeader">Type</td>
				<td width=25% class="tableHeader">Total</td>
			</tr>
		</table>
		<div>&nbsp;</div>
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="2" class="tableHeader">Racial Abilities</td>
			</tr>
			<tr>
				<td width=75% class="tableHeader">Type</td>
				<td width=25% class="tableHeader">Total</td>
			</tr>
		</table>
	</div>
	<div class="character_sheet_column">
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="6" class="tableHeader">Attributes</td>
			</tr>
			<tr>
				<td class="attributeLabel">Str</td>
				<td class="attributeValue"><?= $character_summary->formatStrength() ?></td>
				<td class="attributeLabel">Age</td>
				<td class="attributeValue"><?= $input[CHARACTER_AGE] ?></td>
				<td class="attributeLabel">Sex</td>
				<td class="attributeValue"><?= $input[CHARACTER_GENDER] ?></td>
			</tr>
			<tr>
				<td class="attributeLabel">Int</td>
				<td class="attributeValue"><?= $character_summary->formatIntelligence() ?></td>
				<td class="attributeLabel">Apparent Age</td>
				<td class="attributeValue"><?= $input[CHARACTER_APPARENT_AGE]?></td>
				<td class="attributeLabel">Height</td>
				<td class="attributeValue"><?= $input[CHARACTER_HEIGHT] ?></td>
			</tr>
			<tr>
				<td class="attributeLabel">Wis</td>
				<td class="attributeValue"><?= $character_summary->formatWisdom() ?></td>
				<td class="attributeLabel">Unnatural Age</td>
				<td class="attributeValue"><?= $input[CHARACTER_UNNATURAL_AGE] ?></td>
				<td class="attributeLabel">Weight</td>
				<td class="attributeValue"><?= $input[CHARACTER_WEIGHT] ?></td>
			</tr>
			<tr>
				<td class="attributeLabel">Dex</td>
				<td class="attributeValue"><?= $character_summary->formatDexterity() ?></td>
				<td class="attributeLabel">Social Class</td>
				<td class="attributeValue"><?= $input[CHARACTER_SOCIAL_CLASS] ?></td>
				<td class="attributeLabel">Hair</td>
				<td class="attributeValue"><?= $input[CHARACTER_HAIR] ?></td>
			</tr>
			<tr>
				<td class="attributeLabel">Con</td>
				<td class="attributeValue"><?= $character_summary->getConstitution() ?></td>
				<td class="attributeLabel">&nbsp;</td>
				<td class="attributeValue">&nbsp;</td>
				<td class="attributeLabel">Eyes</td>
				<td class="attributeValue"><?= $input[CHARACTER_EYES] ?></td>
			</tr>
			<tr>
				<td class="attributeLabel">Cha</td>
				<td class="attributeValue"><?= $character_summary->getCharisma() ?></td>
				<td class="attributeLabel">&nbsp;</td>
				<td class="attributeValue">&nbsp;</td>
				<td class="attributeLabel">Siblings</td>
				<td class="attributeValue"><?= $input[CHARACTER_SIBLINGS] ?? "0" ?></td>
			</tr>
			<tr>
				<td class="attributeLabel">Com</td>
				<td class="attributeValue"><?= $character_summary->getComeliness() ?></td>
				<td class="attributeLabel">&nbsp;</td>
				<td class="attributeValue">&nbsp;</td>
				<td class="attributeLabel">&nbsp;</td>
				<td class="attributeValue">&nbsp;</td>
			</tr>
		</table>
		<div>&nbsp;</div>
			<table cellspacing=0 class="tableLayout">
				<tr>
					<td colspan=5 class="tableHeader">Character</td>
				</tr>
				<tr>
					<td class="attributeLabel">Class</td>
					<td class="attributeValue"><?= formatClasses($character_summary) ?></td>
					<td width=15% class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Alignment</td>
					<td class="attributeValue"><?= $input[CHARACTER_ALIGNMENT] ?></td>
				</tr>
				<tr>
					<td class="attributeLabel">Level</td>
					<td class="attributeValue"><?= formatLevels($character_summary, $nf) ?></td>
					<td width=15% class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Religion</td>
					<td class="attributeValue"><?= $input[CHARACTER_RELIGION] ?></td>
				</tr>
				<tr>
					<td class="attributeLabel">Race</td>
					<td class="attributeValue"><?= $input[CHARACTER_RACE] ?></td>
					<td width=15% class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Deity</td>
					<td class="attributeValue"><?= $input[CHARACTER_DEITY] ?></td>
				</tr>
				<tr>
					<td class="attributeLabel">Movement</td>
					<td class="attributeValue"><?= formatMovement($input[CHARACTER_MOVEMENT]) ?></td>
					<td width=15% class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Hometown</td>
					<td class="attributeValue"><?= $input[CHARACTER_HOMETOWN] ?></td>
				</tr>
				<tr>
					<td class="attributeLabel">Hit Points</td>
					<td class="attributeValue"><?= $character_summary->getHitPoints() ?></td>
					<td width=15% class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Hit Die</td>
					<td class="attributeValue"><?= $input[CHARACTER_HIT_DIE] ?></td>
				</tr>
				<tr>
					<td colspan=2 class="attributeLabel">Experience Points</td>
					<td colspan=3 class="attributeLabel"><?= formatExperiencePoints($input[CHARACTER_CLASSES]) ?></td>
				</tr>
			</table>
			<div>&nbsp;</div>
			<table cellspacing="0" class="tableLayout">
				<tr>
					<td colspan="2" class="tableHeader">Weapons and Skills</td>
				</tr>
				<tr>
					<td width=50% class="attributeLabel">&nbsp;</td>
					<td width=50% class="attributeLabel">&nbsp;</td>
				</tr>
			</table>
			<div>&nbsp;</div>
			<table cellspacing=0 class="tableLayout">
				<tr>
					<td colspan=4 class="tableHeader">Valuables</td>
				</tr>
				<tr>
					<td width=25% class="attributeLabel">Copper</td>
					<td width=25% class="attributeValue">&nbsp;</td>
					<td width=25% class="attributeLabel">Platinum</td>
					<td width=25% class="attributeValue">&nbsp;</td>
				</tr>
				<tr>
					<td class="attributeLabel">Silver</td>
					<td class="attributeValue">&nbsp;</td>
					<td class="attributeLabel">Mithral</td>
					<td class="attributeValue">&nbsp;</td>
				</tr>
				<tr>
					<td class="attributeLabel">Gold</td>
					<td class="attributeValue">&nbsp;</td>
					<td class="attributeLabel">Adamantium</td>
					<td class="attributeValue">&nbsp;</td>
				</tr>
				<tr>
					<td class="attributeLabel">Other</td>
					<td colspan=3 class="attributeValue">&nbsp;</td>
				</tr>
			</table>
			<div>&nbsp;</div>
			<table cellspacing=0 class="tableLayout">
				<tr>
					<td width=25% class="tableHeader">Backpack</td>
					<td width=25% class="tableHeader">Left</td>
					<td width=25% class="tableHeader">Center</td>
					<td width=25% class="tableHeader">Right</td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>
<?php

function getExistingCharacter($player_name, $character_name) {
    $params = [];
    $params['playerName'] = $player_name;
    $params[CHARACTER_NAME] = $character_name;
    $params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];
    
    $url = CurlHelper::buildUrl('getPlayerCharacterDetails');
    $raw_results = CurlHelper::performGetRequest($url, $params);

    return json_decode($raw_results);
}

function buildActionBar($player_name, $character_name, $character_summary) {
    $output_html = ActionBarHelper::buildUserEditIcon($player_name, $character_name);
	$output_html .= '&nbsp;';
	if (!empty($character_summary->getSpellClasses())) {
		if (isGreaterMage($character_summary->getCharacterClasses())) {
			$output_html .= ActionBarHelper::buildReadyGMSpellsIcon($player_name, $character_name);
		} else {
			$output_html .= ActionBarHelper::buildReadySpellsIcon($player_name, $character_name);
		}
	}
	
	$output_html .= '&nbsp;';

    return $output_html;
}

function isGreaterMage($character_classes) {
	foreach($character_classes AS $character_class) {
		if (getClassID($character_class['class_name']) == GREATER_MAGE) {
			return true;
		}

		return false;
	}
}

function formatClasses(\CharacterSummary $character_summary) {
	$class_list = '';
	foreach($character_summary->getCharacterClasses() AS $character_class) {
		if (strlen($class_list) > 0) {
			$class_list .= '/';
		}

		$class_list .= $character_class['class_name'];
	}

	return $class_list;
}

function formatLevels(\CharacterSummary $character_summary, $nf) {
	$level_list = '';
	foreach($character_summary->getCharacterClasses() AS $character_class) {
		if (strlen($level_list) > 0) {
			$level_list .= '/';
		}

		$level_list .= $nf->format($character_class['character_level']);
	}

	return $level_list;
}

function  formatMovement($character_movement) {
	if (!empty($character_movement)) {
		return $character_movement . '"';
	}

	return '';
}

function formatExperiencePoints($input_character_classes) {
	$xp_list = '';
	foreach($input_character_classes AS $character_class) {
		if (strlen($xp_list)) {
			$xp_list .= ' / ';
		}
		$xp_list .= number_format($character_class->number_of_experience_points);
	}

	return $xp_list;
}
?>
