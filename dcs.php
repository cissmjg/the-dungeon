<?php

require_once __DIR__ . '/env.php';
require_once __DIR__ . '/validateCredentials.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';

validateSessionCredentials($pdo);

require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/ActionBarHelper.php';
require_once __DIR__ . '/helper/HtmlHelper.php';

require_once __DIR__ . '/dbio/constants/characterAttributes.php';
require_once __DIR__ . '/dbio/constants/characterClasses.php';
require_once __DIR__ . '/classes/attributeMetadata.php';
require_once __DIR__ . '/classes/characterDetails.php';
require_once __DIR__ . '/classes/characterSummaryRenderer.php';
require_once __DIR__ . '/classes/playerCharacterSkillSet.php';
require_once __DIR__ . '/classes/playerCharacterWeaponSet.php';
require_once __DIR__ . '/classes/playerCharacterMeleeWeaponRenderer.php';
require_once __DIR__ . '/classes/playerCharacterMeleeElvenCavalierWeaponRenderer.php';
require_once __DIR__ . '/classes/rollModifier/meleeToHitRmCollectionCalculator.php';
require_once __DIR__ . '/classes/rollModifier/meleeDamageRmCollectionCalculator.php';
require_once __DIR__ . '/classes/rollModifier/meleeElvenCavalierToHitRmCollectionCalculator.php';
require_once __DIR__ . '/classes/rollModifier/meleeElvenCavalierDamageRmCollectionCalculator.php';
require_once __DIR__ . '/classes/rollModifier/rmUIContainer.php';
require_once __DIR__ . '/rules/attacksPerRound.php';
require_once __DIR__ . '/fa/faChevronIcon.php';

require_once __DIR__ . '/dbio/constants/weaponType.php';
require_once __DIR__ . '/dbio/constants/characterClasses.php';
require_once __DIR__ . '/dbio/constants/cavalierCombatMode.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';

$input = [];
$log = [];
$errors = [];

getPlayerName($errors, $input);
getCharacterName($errors, $input);

$player_name = $input[PLAYER_NAME];
$character_name = $input[CHARACTER_NAME];

$character_details = new CharacterDetails();
$character_details->init($pdo, $player_name, $character_name, $errors);

$primary_class = $character_details->getPrimaryClass();

$character_summary_renderer = new CharacterSummaryRenderer($character_name);
$character_summary_stats = $character_summary_renderer->renderCharacterDetails($character_details);

$player_character_skill_set = new PlayerCharacterSkillSet();
$player_character_skill_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $errors);

$player_character_weapon_set = New PlayerCharacterWeaponSet();
$player_character_weapon_set->init($pdo, $input[PLAYER_NAME], $input[CHARACTER_NAME], $player_character_skill_set, $errors);

$attribute_metadata = new AttributeMetadata($character_details);

$action_bar = buildActionBar($player_name, $character_name, $character_details);

$nf = new NumberFormatter('en_US', NumberFormatter::ORDINAL);

$page_title = $character_name;
$site_css_file = 'dnd-default.css';
$page_specific_js = 'dcs.js';
$page_specific_css = 'dcs.css';
$enable_toggle_panels = true;

$html_header = HtmlHelper::formatHtmlHeader($page_title, $site_css_file, $page_specific_js, $page_specific_css, $enable_toggle_panels);
echo $html_header;

?>
<body>
<span class="character_summary"><?= $character_summary_stats ?></span><span class="action_bar"><?= $action_bar ?></span>
<table cellspacing="0" class="tableLayout">
	<tr>
		<td colspan="12" class="tableHeader"><?= $character_name ?></td>
	</tr>
	<tr>
		<td class="titleAttributeLiteral">Armor Class</td>
		<td class="titleAttributeValue"><?= $character_details->getArmorClass() ?></td>
		<td class="titleAttributeLiteral">Hit Points</td>
		<td class="titleAttributeValue"><?= $character_details->getHitPoints() ?></td>
		<td class="titleAttributeLiteral">Movement</td>
		<td class="titleAttributeValue"><?= formatMovement($character_details->getMovement()); ?></td>
		<td class="titleAttributeLiteral">Class</td>
		<td class="titleAttributeValue"><?= formatClasses($character_details) ?></td>
		<td class="titleAttributeLiteral">Level</td>
		<td class="titleAttributeValue"><?= formatLevels($character_details, $nf) ?></td>
		<td class="titleAttributeLiteral">Level</td>
		<td class="titleAttributeValue"><?= $character_details->getRace() ?></td>
	</tr>
</table>
<div>&nbsp;</div>
<span style="font-weight: bold;">Combat Summary</span>
<?php
$index = 1;
if ($primary_class->getClassId() == ELVEN_CAVALIER) {
	echo '<div class="togglePanel">' . PHP_EOL;
	echo '<a href="#">' . PHP_EOL;
	echo '    <span class="fa fa-plus"></span> Mounted' . PHP_EOL;
	echo '</a>' . PHP_EOL;
	echo '<div class="togglePanelContent">' . PHP_EOL;
	echo '  <div class="rmWeaponContainer">' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Weapon</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Spd</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Att</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Dmg</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Range</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Bonus</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Notes</div>' . PHP_EOL;
	echo '  </div>' . PHP_EOL;

	foreach($player_character_weapon_set->getAll() AS $player_character_weapon) {
		$player_character_melee_weapon_renderer = new PlayerCharacterMeleeElvenCavalierWeaponRenderer($player_character_weapon, $player_character_skill_set, $character_details, $attribute_metadata);
		$background_style = $index % 2 == 0 ? 'rmWeaponContainerAltBackground' : '';
		$player_character_melee_weapon_renderer->setWeaponContainerBackgroundStyle($background_style);
		$player_character_melee_weapon_renderer->setCombatMode(COMBAT_MODE_MOUNTED);
		echo $player_character_melee_weapon_renderer->render();

		$index++;
	}

	echo '</div>' . PHP_EOL;
	echo '<div>&nbsp;</div>' . PHP_EOL;
	echo '<div class="togglePanel">' . PHP_EOL;
	echo '<a href="#">' . PHP_EOL;
	echo '    <span class="fa fa-plus"></span> Unmounted' . PHP_EOL;
	echo '</a>' . PHP_EOL;
	echo '<div class="togglePanelContent"\>' . PHP_EOL;
	echo '  <div class="rmWeaponContainer">' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Weapon</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Spd</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Att</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Dmg</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Range</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Bonus</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Notes</div>' . PHP_EOL;
	echo '  </div>' . PHP_EOL;
	foreach($player_character_weapon_set->getAll() AS $player_character_weapon) {
		$player_character_melee_weapon_renderer = new PlayerCharacterMeleeElvenCavalierWeaponRenderer($player_character_weapon, $player_character_skill_set, $character_details, $attribute_metadata);
		$background_style = $index % 2 == 0 ? 'rmWeaponContainerAltBackground' : '';
		$player_character_melee_weapon_renderer->setWeaponContainerBackgroundStyle($background_style);
		$player_character_melee_weapon_renderer->setCombatMode(COMBAT_MODE_UNMOUNTED);
		echo $player_character_melee_weapon_renderer->render();

		$index++;
	}
	echo '</div>' . PHP_EOL;
} else {
	// If the character has Two Weapon Fighting, Format the display area
	// $index++;
	echo '  <div class="rmWeaponContainer">' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Weapon</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Spd</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Att</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Dmg</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Range</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Bonus</div>' . PHP_EOL;
	echo '    <div class="rmWeaponHeaderItem">Notes</div>' . PHP_EOL;
	echo '  </div>' . PHP_EOL;
	foreach($player_character_weapon_set->getAll() AS $player_character_weapon) {
		$player_character_melee_weapon_renderer = new PlayerCharacterMeleeWeaponRenderer($player_character_weapon, $player_character_skill_set, $character_details, $attribute_metadata);
		$background_style = $index % 2 == 0 ? 'rmWeaponContainerAltBackground' : '';
		$player_character_melee_weapon_renderer->setWeaponContainerBackgroundStyle($background_style);
		echo $player_character_melee_weapon_renderer->render();
		$index++;
	}
}
?>
<div class="characterSheetContainer">
	<div class="characterSheetColumn">
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="2" class="tableHeader">Class Abilities</td>
			</tr>
			<tr>
				<td width="75%" class="tableHeader">Type</td>
				<td width="25%" class="tableHeader">Total</td>
			</tr>
		</table>
		<div>&nbsp;</div>
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="2" class="tableHeader">Saving Throws</td>
			</tr>
			<tr>
				<td width="75%" class="tableHeader">Type</td>
				<td width="25%" class="tableHeader">Total</td>
			</tr>
		</table>
		<div>&nbsp;</div>
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="2" class="tableHeader">Racial Abilities</td>
			</tr>
			<tr>
				<td width="75%" class="tableHeader">Type</td>
				<td width="25%" class="tableHeader">Total</td>
			</tr>
		</table>
	</div>
	<div class="characterSheetColumn">
		<table cellspacing="0" class="tableLayout">
			<tr>
				<td colspan="6" class="tableHeader">Attributes</td>
			</tr>
			<tr>
				<td class="attributeLabel"><a href="#" onclick="attributeDetailPanelClick('strength-metadata', 'strength-metadata-icon', DEFAULT_CLOSED_ICON_CLASS, DEFAULT_OPEN_ICON_CLASS);"><span id="strength-metadata-icon" class="fa-solid fa-chevron-down attributeDetailPanelIcon"></span></a>Str</td>
				<td class="attributeValue"><?= $character_details->formatStrength() ?></td>
				<td class="attributeLabel">Age</td>
				<td class="attributeValue"><?= $character_details->getAge() ?></td>
				<td class="attributeLabel">Sex</td>
				<td class="attributeValue"><?= $character_details->getGender() ?></td>
			</tr>
			<tr id="strength-metadata" hidden>
				<td colspan="6"><?= $attribute_metadata->getStrengthMetadata() ?></td>
			</tr>
			<tr>
				<td class="attributeLabel"><a href="#" onclick="attributeDetailPanelClick('intelligence-metadata', 'intelligence-metadata-icon', DEFAULT_CLOSED_ICON_CLASS, DEFAULT_OPEN_ICON_CLASS);"><span id="intelligence-metadata-icon" class="fa-solid fa-chevron-down attributeDetailPanelIcon"></span></a>Int</td>
				<td class="attributeValue"><?= $character_details->formatIntelligence() ?></td>
				<td class="attributeLabel">Apparent Age</td>
				<td class="attributeValue"><?= $character_details->getApparentAge()?></td>
				<td class="attributeLabel">Height</td>
				<td class="attributeValue"><?= $character_details->getHeight() ?></td>
			</tr>
			<tr id="intelligence-metadata" hidden>
				<td colspan="6"><?= $attribute_metadata->getIntelligenceMetadata() ?></td>
			</tr>
			<tr>
				<td class="attributeLabel"><a href="#" onclick="attributeDetailPanelClick('wisdom-metadata', 'wisdom-metadata-icon', DEFAULT_CLOSED_ICON_CLASS, DEFAULT_OPEN_ICON_CLASS);"><span id="wisdom-metadata-icon" class="fa-solid fa-chevron-down attributeDetailPanelIcon"></span></a>Wis</td>
				<td class="attributeValue"><?= $character_details->formatWisdom() ?></td>
				<td class="attributeLabel">Unnatural Age</td>
				<td class="attributeValue"><?= $character_details->getUnnaturalAge() ?></td>
				<td class="attributeLabel">Weight</td>
				<td class="attributeValue"><?= $character_details->getWeight() ?></td>
			</tr>
			<tr id="wisdom-metadata" hidden>
				<td colspan="6"><?= $attribute_metadata->getWisdomMetadata() ?></td>
			</tr>
			<tr>
				<td class="attributeLabel"><a href="#" onclick="attributeDetailPanelClick('dexterity-metadata', 'dexterity-metadata-icon', DEFAULT_CLOSED_ICON_CLASS, DEFAULT_OPEN_ICON_CLASS);"><span id="dexterity-metadata-icon" class="fa-solid fa-chevron-down attributeDetailPanelIcon"></span></a>Dex</td>
				<td class="attributeValue"><?= $character_details->formatDexterity() ?></td>
				<td class="attributeLabel">Social Class</td>
				<td class="attributeValue"><?= $character_details->getSocialClass() ?></td>
				<td class="attributeLabel">Hair</td>
				<td class="attributeValue"><?= $character_details->getHair() ?></td>
			</tr>
			<tr id="dexterity-metadata" hidden>
				<td colspan="6"><?= $attribute_metadata->getDexterityMetadata() ?></td>
			</tr>
			<tr>
				<td class="attributeLabel"><a href="#" onclick="attributeDetailPanelClick('constitution-metadata', 'constitution-metadata-icon', DEFAULT_CLOSED_ICON_CLASS, DEFAULT_OPEN_ICON_CLASS);"><span id="constitution-metadata-icon" class="fa-solid fa-chevron-down attributeDetailPanelIcon"></span></a>Con</td>
				<td class="attributeValue"><?= $character_details->getCharacterConstitution() ?></td>
				<td class="attributeLabel">&nbsp;</td>
				<td class="attributeValue">&nbsp;</td>
				<td class="attributeLabel">Eyes</td>
				<td class="attributeValue"><?= $character_details->getEyes() ?></td>
			</tr>
			<tr id="constitution-metadata" hidden>
				<td colspan="6"><?= $attribute_metadata->getConstitutionMetadata() ?></td>
			</tr>
			<tr>
				<td class="attributeLabel"><a href="#" onclick="attributeDetailPanelClick('charisma-metadata', 'charisma-metadata-icon', DEFAULT_CLOSED_ICON_CLASS, DEFAULT_OPEN_ICON_CLASS);"><span id="charisma-metadata-icon" class="fa-solid fa-chevron-down attributeDetailPanelIcon"></span></a>Cha</td>
				<td class="attributeValue"><?= $character_details->getCharacterCharisma() ?></td>
				<td class="attributeLabel">&nbsp;</td>
				<td class="attributeValue">&nbsp;</td>
				<td class="attributeLabel">Siblings</td>
				<td class="attributeValue"><?= $character_details->getSiblings() ?? "0" ?></td>
			</tr>
			<tr id="charisma-metadata" hidden>
				<td colspan="6"><?= $attribute_metadata->getCharismaMetadata() ?></td>
			</tr>
			<tr>
				<td class="attributeLabel"><span style="padding-left: 28px;">Com</span></td>
				<td class="attributeValue"><?= $character_details->getAdjustedCharacterComeliness() ?></td>
				<td class="attributeLabel">&nbsp;</td>
				<td class="attributeValue">&nbsp;</td>
				<td class="attributeLabel">&nbsp;</td>
				<td class="attributeValue">&nbsp;</td>
			</tr>
		</table>
		<div>&nbsp;</div>
			<table cellspacing="0" class="tableLayout">
				<tr>
					<td colspan="5" class="tableHeader">Character</td>
				</tr>
				<tr>
					<td class="attributeLabel">Class</td>
					<td class="attributeValue"><?= formatClasses($character_details) ?></td>
					<td width="15%" class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Alignment</td>
					<td class="attributeValue"><?= $character_details->getAlignment() ?></td>
				</tr>
				<tr>
					<td class="attributeLabel">Level</td>
					<td class="attributeValue"><?= formatLevels($character_details, $nf) ?></td>
					<td width="15%" class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Religion</td>
					<td class="attributeValue"><?= $character_details->getReligion() ?></td>
				</tr>
				<tr>
					<td class="attributeLabel">Race</td>
					<td class="attributeValue"><?= $character_details->getRace() ?></td>
					<td width="15%" class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Deity</td>
					<td class="attributeValue"><?= $character_details->getDeity() ?></td>
				</tr>
				<tr>
					<td class="attributeLabel">Movement</td>
					<td class="attributeValue"><?= formatMovement(character_movement: $character_details->getMovement()) ?></td>
					<td width="15%" class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Hometown</td>
					<td class="attributeValue"><?= $character_details->getHometown() ?></td>
				</tr>
				<tr>
					<td class="attributeLabel">Hit Points</td>
					<td class="attributeValue"><?= $character_details->getHitPoints() ?></td>
					<td width="15%" class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Hit Die</td>
					<td class="attributeValue"><?= $character_details->getHitDie() ?></td>
				</tr>
				<tr>
					<td class="attributeLabel">Experience Points</td>
					<td class="attributeValue"><?= formatExperiencePoints($character_details) ?></td>
					<td width="15%" class="attributeValue"> &nbsp; </td>
					<td class="attributeLabel">Non-Proficiency Penalty</td>
					<td class="attributeValue"><?= $character_details->getNonProficienyPenalty() ?></td>
				</tr>
			</table>
			<div>&nbsp;</div>
			<table cellspacing="0" class="tableLayout">
				<tr>
					<td colspan="2" class="tableHeader">Weapons and Skills</td>
				</tr>
				<tr>
					<td width="50%" class="attributeLabel">&nbsp;</td>
					<td width="50%" class="attributeLabel">&nbsp;</td>
				</tr>
			</table>
			<div>&nbsp;</div>
			<table cellspacing="0" class="tableLayout">
				<tr>
					<td colspan="4" class="tableHeader">Valuables</td>
				</tr>
				<tr>
					<td width="25%" class="attributeLabel">Copper</td>
					<td width="25%" class="attributeValue">&nbsp;</td>
					<td width="25%" class="attributeLabel">Platinum</td>
					<td width="25%" class="attributeValue">&nbsp;</td>
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
					<td colspan="3" class="attributeValue">&nbsp;</td>
				</tr>
			</table>
			<div>&nbsp;</div>
			<table cellspacing="0" class="tableLayout">
				<tr>
					<td width="25%" class="tableHeader">Backpack</td>
					<td width="25%" class="tableHeader">Left</td>
					<td width="25%" class="tableHeader">Center</td>
					<td width="25%" class="tableHeader">Right</td>
				</tr>
			</table>
		</div>
	</div>
</div>
</body>
</html>
<?php

function buildActionBar($player_name, $character_name, \CharacterDetails $character_details) {
    $output_html = ActionBarHelper::buildUserEditIcon($player_name, $character_name);
	$output_html .= '&nbsp;';

	if ($character_details->isSpellcaster()) {
		$isGreaterMage = $character_details->containsClassId(GREATER_MAGE);
		if ($isGreaterMage) {
			$output_html .= ActionBarHelper::buildReadyGMSpellsIcon($player_name, $character_name);
		} else {
			$output_html .= ActionBarHelper::buildReadySpellsIcon($player_name, $character_name);
		}
	}
	
	$output_html .= '&nbsp;';

    return $output_html;
}

function formatClasses(\CharacterDetails $character_details) {
	$class_list = '';
	foreach($character_details->getCharacterClasses() AS $character_class) {
		if (strlen($class_list) > 0) {
			$class_list .= '/';
		}

		$class_list .= $character_class->getClassName();
	}

	return $class_list;
}

function formatLevels(\CharacterDetails $character_details, $nf) {
	$level_list = '';
	foreach($character_details->getCharacterClasses() AS $character_class) {
		if (strlen($level_list) > 0) {
			$level_list .= '/';
		}

		$level_list .= $nf->format($character_class->getClassLevel());
	}

	return $level_list;
}

function  formatMovement($character_movement) {
	if (!empty($character_movement)) {
		return $character_movement . '"';
	}

	return '';
}

function formatExperiencePoints(\CharacterDetails $character_details) {
	$xp_list = '';
	foreach($character_details->getCharacterClasses() AS $character_class) {
		if (strlen($xp_list)) {
			$xp_list .= ' / ';
		}
		$xp_list .= number_format($character_class->getNumberOfExperiencePoints());
	}

	return $xp_list;
}

?>

