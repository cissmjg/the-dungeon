<?php

$errors = [];
$input = [];
$log = [];

require_once __DIR__ . '/env.php';
$pdo = require_once __DIR__ . '/dbio/DBConnection.php';
require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/helper/RestHeaderHelper.php';
require_once __DIR__ . '/helper/WebParameterHelper.php';
require_once 'textInput.php';

require_once __DIR__ . '/webio/playerName.php';
require_once __DIR__ . '/webio/characterName.php';
require_once 'characterClassName.php';
require_once 'characterLevel.php';
require_once 'guid.php';
require_once 'pageAction.php';
require_once 'spellCatalogId.php';
require_once 'spellSlotId.php';
require_once 'spellLevel.php';
require_once 'spellPoolId.php';
require_once 'spellPoolSlotId.php';
require_once 'spellDuration.php';
require_once 'spellCastingTime.php';
require_once __DIR__ . '/webio/requiredParameter.php';
require_once __DIR__ . '/webio/optionalParameter.php';
require_once 'cantripSpellSlot.php';
require_once 'playerCharacterClassId.php';
require_once 'playerCharacterWeaponId.php';
require_once 'spellSlotLevel.php';
require_once 'spellTypeId.php';
require_once 'hoursOfSleep.php';

require_once __DIR__ . '/webio/weaponProficiencyId.php';
require_once __DIR__ . '/webio/weaponDescription.php';
require_once __DIR__ . '/webio/weaponLocation.php';
require_once __DIR__ . '/webio/isProficient.php';
require_once __DIR__ . '/webio/isReady.php';
require_once __DIR__ . '/webio/isPreferred.php';
require_once __DIR__ . '/webio/craftStatus.php';
require_once 'strengthBonusAvailable.php';
require_once 'playerNote1.php';
require_once 'playerNote2.php';
require_once 'playerNote3.php';
require_once 'mastercraftHitDescription.php';
require_once 'mastercraftDamageDescription.php';
require_once 'meleeWeaponType.php';
require_once 'meleeWeaponSpeed.php';
require_once 'meleeWeaponDamage.php';
require_once 'meleeAttacksPerRound.php';
require_once 'meleeNumberOfHands.php';
require_once 'meleeAdditionalText.php';
require_once 'meleeHitBonus.php';
require_once 'meleeDamageBonus.php';
require_once 'meleeSpec1HitBonus.php';
require_once 'meleeSpec1DamageBonus.php';
require_once 'meleeSpec1Description.php';
require_once 'meleeSpec2HitBonus.php';
require_once 'meleeSpec2DamageBonus.php';
require_once 'meleeSpec2Description.php';
require_once 'meleeSpec3HitBonus.php';
require_once 'meleeSpec3DamageBonus.php';
require_once 'meleeSpec3Description.php';
require_once 'missileWeaponType.php';
require_once 'missileWeaponSpeed.php';
require_once 'missileWeaponDamage.php';
require_once 'missileAttacksPerRound.php';
require_once 'missileAdditionalText.php';
require_once 'missileSpec1HitBonus.php';
require_once 'missileSpec1DamageBonus.php';
require_once 'missileSpec1Description.php';
require_once 'missileSpec2HitBonus.php';
require_once 'missileSpec2DamageBonus.php';
require_once 'missileSpec2Description.php';
require_once 'missileSpec3HitBonus.php';
require_once 'missileSpec3DamageBonus.php';
require_once 'missileSpec3Description.php';
require_once 'missileShortRange.php';
require_once 'missileMediumRange.php';
require_once 'missileLongRange.php';
require_once 'missileHitBonus.php';
require_once 'missileDamageBonus.php';

getRequiredStringParameter($errors, $input, __FILE__, 'characterAction');
$character_action = $input['characterAction'];

switch($character_action) {
	case "login":
		// Get player name
		getPlayerName($errors, $input);

		//Get the password the user entered
		getRequiredStringParameter($errors, $input, __FILE__, 'password');
		
		$url = CurlHelper::buildUrl('getPlayerPassword');
		$params = buildGetPasswordParams($input);
		$raw_result = CurlHelper::performGetRequest($url, $params);
		$player_creds = json_decode($raw_result);
		$pwd_hash = $player_creds[0]->password;
		if (!password_verify($input['password'], $pwd_hash)) {
			$location_header = buildLoginRedirect();
			header($location_header);
			break;
			exit;
		}
		initiateSession($pdo, $input[PLAYER_NAME], $errors);
		$player_permissions = getPlayerPermissions($pdo, $input[PLAYER_NAME], $errors);
		$redirect_url = '';
		if(!empty($player_permissions['is_dm']) && $player_permissions['is_dm'] == TRUE) {
			$redirect_url = buildDmDashboardRedirect($input);
		} else {
			$redirect_url = buildCharacterListRedirect($input);
		}
		
		header($redirect_url);
		break;
		exit;
	case "promote":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get character class name	
		getCharacterClassName($errors, $input);
		
		$url = CurlHelper::buildUrl('promoteClass');
		$params =  buildPromoteClassParams($input);
		$raw_result = CurlHelper::performGetRequest($url, $params);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditCharacterRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}
		break;
		
	case "characterList":
		// Get player name
		getPlayerName($errors, $input);

		$location_header = buildCharacterListRedirect($input);
		header($location_header);
		exit;
		break;
	case "createNew":
		// Get player name
		getPlayerName($errors, $input);

		$location_header = buildCreateCharacterRedirect($input);
		header($location_header);
		exit;
		break;
	case "deleteCharacter":
		// Get player name
		getPlayerName($errors, $input);

		// Get Character Name
		getCharacterName($errors, $input);

		$url_delete_character = CurlHelper::buildUrl('deleteCharacter');
		$params_delete_character = buildDeleteCharacterParams($input);
		$raw_result = CurlHelper::performGetRequest($url_delete_character, $params_delete_character);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildCharacterListRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}
		break;
	case "viewCharacter":
		// Get player name
		getPlayerName($errors, $input);

		// Get Character Name
		getCharacterName($errors, $input);

		$location_header = buildViewCharacterRedirect($input);
		header($location_header);
		exit;
		break;
	case "editCharacter":
		// Get player name
		getPlayerName($errors, $input);

		// Get Character Name
		getCharacterName($errors, $input);

		$location_header = buildEditCharacterRedirect($input);
		header($location_header);
		exit;
		break;
	case "editSpellBook":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get character class name	
		getCharacterClassName($errors, $input);

		//Get page action
		getPageAction($errors, $input);

		$location_header = buildEditSpellBookRedirect($input);
		header($location_header);
		exit;
		break;
	case "updateSpellPool":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get character class name	
		getCharacterClassName($errors, $input);

		// Get Spell Catalog ID
		getSpellCatalogId($errors, $input);

		// Get Spell Pool ID
		getSpellPoolSlotId($errors, $input);

		//Get page action
		getPageAction($errors, $input);

		$url_update_spell_pool = CurlHelper::buildUrl('updateSpellPoolSlot');
		$params_update_spell_pool = buildUpdateSpellPoolParams($input);
		$raw_result = CurlHelper::performGetRequest($url_update_spell_pool, $params_update_spell_pool);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditSpellBookRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}
		break;
	case "editWeaponTalents":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		$location_header = buildEditWeaponTalentsRedirect($input);
		header($location_header);
		exit;
		break;
	case "addWeaponTalent":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Weapon Proficiency (Talent) ID
		getRequiredIntegerParameter($errors, $input, __FILE__, WEAPON_PROFICIENCY_ID);

		// Preferred weapon (Cavalier)
		getOptionalStringParameter($errors, $input, __FILE__, IS_PREFERRED, 'not preferred');

		$url_add_weapon_talent = CurlHelper::buildUrl('addWeaponTalent');
		$params_add_weapon_talent = buildAddWeaponTalentParams($input);
		$raw_result = CurlHelper::performGetRequest($url_add_weapon_talent, $params_add_weapon_talent);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditWeaponTalentsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}

		break;
	case "deleteWeaponTalent":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Weapon Proficiency (Talent) ID
		getRequiredIntegerParameter($errors, $input, __FILE__, WEAPON_PROFICIENCY_ID);

		$url_delete_weapon_talent = CurlHelper::buildUrl('deleteWeaponTalent');
		$params_delete_weapon_talent = buildDeleteWeaponTalentParams($input);
		$raw_result = CurlHelper::performGetRequest($url_delete_weapon_talent, $params_delete_weapon_talent);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildDeleteWeaponTalentRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}

		break;
	case "editSkills":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		$location_header = buildEditSkillsRedirect($input);
		header($location_header);
		exit;
		break;
	case "deleteCharacterSkill":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get the Character's Skill ID
		getRequiredIntegerParameter($errors, $input, __FILE__, 'playerCharacterSkillId');

		$url_delete_skill = CurlHelper::buildUrl('deleteCharacterSkill');
		$params_delete_skill = buildDeleteCharacterSkillsParams($input);
		$raw_result = CurlHelper::performGetRequest($url_delete_skill, $params_delete_skill);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditSkillsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			$errors[] = $input;
			die(json_encode($errors));
		}
		exit;
		break;

	case "home":
		// Get player name
		getPlayerName($errors, $input);

		$url_home = buildHomeRedirect($input);
		header($url_home);
		exit;
		break;
	case "updateReadySpellSlot":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get Spell Slot ID
		getSpellSlotId($errors, $input);

		// Get Spell Catalog ID
		getSpellCatalogId($errors, $input);

		$raw_result = '';
		if ($input['spellCatalogId'] == CANTRIP_SLOT_SPELL_CATALOG_ID) {
			// Get character class name	
			getCharacterClassName($errors, $input);

			// Get the Spell Level
			getSpellLevel($errors, $input);
	
			$url_allocate_cantrips = CurlHelper::buildUrl('allocateCantripsForSlot');
			$params_allocate_cantrips = buildAllocateCantripsParams($input);
			$raw_result = CurlHelper::performGetRequest($url_allocate_cantrips, $params_allocate_cantrips);
		}
			
		$url_update_ready_slot = CurlHelper::buildUrl('updateReadySpellSlot');
		$params_update_ready_slot = buildUpdateReadySlotParams($input);
		$raw_result = CurlHelper::performGetRequest($url_update_ready_slot, $params_update_ready_slot);

		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditReadySpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			$errors[] = $input;
			die(json_encode($errors));
		}
		exit;
		break;
	case "reclaimCantripSlots":

		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get Spell Slot ID
		getSpellSlotId($errors, $input);

		$url_reclaim_cantrips = CurlHelper::buildUrl('reclaimCantripSlots');
		$params_reclaim_cantrips = buildReallocateCantripsParams($input);
		$raw_result = CurlHelper::performGetRequest($url_reclaim_cantrips, $params_reclaim_cantrips);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditReadySpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			$errors[] = $input;
			die(json_encode($errors));
		}
		exit;
		break;
	case "editReadySpells":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		$location_header = buildEditReadySpellsRedirect($input);
		header($location_header);
		exit;
		break;
	case "editGMSpells":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		$location_header = buildEditGMSpellsRedirect($input);
		header($location_header);
		exit;
		break;
	case "castSpellSlot":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get Spell Slot ID
		getSpellSlotId($errors, $input);

		// Get Spell Duration
		getSpellCastingTime($errors, $input);

		// Get Spell Casting Time
		getSpellDuration($errors, $input);

		$url_cast_slot = CurlHelper::buildUrl('setSlotCastStatus');
		$params_cast_slot = buildCastSlotParams($input);
		$raw_result = CurlHelper::performGetRequest($url_cast_slot, $params_cast_slot);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditReadySpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			$errors[] = $input;
			die(json_encode($errors));
		}
		exit;
		break;
	case "resetSpellSlot":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get Spell Slot ID
		getSpellSlotId($errors, $input);

		// Get Spell Duration
		getSpellCastingTime($errors, $input);

		// Get Spell Casting Time
		getSpellDuration($errors, $input);

		$url_reset_slot = CurlHelper::buildUrl('setSlotCastStatus');
		$params_reset_slot = buildResetSlotParams($input);
		$raw_result = CurlHelper::performGetRequest($url_reset_slot, $params_reset_slot);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditReadySpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}
		exit;
		break;
	case "stopCastingSpellSlot":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get Spell Slot ID
		getSpellSlotId($errors, $input);

		// Get Spell Duration
		getSpellCastingTime($errors, $input);

		// Get Spell Casting Time
		getSpellDuration($errors, $input);

		$url_stop_casting_slot = CurlHelper::buildUrl('stopCastingSpellSlot');
		$params_stop_casting_slot = buildStopCastingSlotParams($input);
		$raw_result = CurlHelper::performGetRequest($url_stop_casting_slot, $params_stop_casting_slot);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditReadySpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}

		exit;
		break;
	case "stopRunningSpellSlot":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get Spell Slot ID
		getSpellSlotId($errors, $input);

		// Get Spell Duration
		getSpellCastingTime($errors, $input);

		// Get Spell Casting Time
		getSpellDuration($errors, $input);

		$url_stop_running_slot = CurlHelper::buildUrl('stopRunningSpellSlot');
		$params_stop_running_slot = buildStopCastingSlotParams($input);
		$raw_result = CurlHelper::performGetRequest($url_stop_running_slot, $params_stop_running_slot);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditReadySpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}

		exit;
		break;
	case "castGMSpell":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get the Spell Catalog ID of the spell being cast
		getSpellCatalogId($errors, $input);

		// Get the Spell Level of the spell being cast
		getSpellLevel($errors, $input);

		// Get the Spell Duration of the spell being cast
		getSpellDuration($errors, $input);

		// Get the Spell Casting time of the spell being cast
		getSpellCastingTime($errors, $input);

		$url_adjust_spell_points = CurlHelper::buildUrl('decreaseSpellPoints');
		$params_adjust_spell_points = buildAdjustSpellPointsParams($input);		
		$raw_result = CurlHelper::performGetRequest($url_adjust_spell_points, $params_adjust_spell_points);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			if($input['spellDuration'] > 0 || $input['spellCastingTime'] > 0) {
				$url_cast_gm_spell = CurlHelper::buildUrl('castGMSpell');
				$params_cast_gm_spell = buildCastGMSpellParams($input);
				$raw_result = CurlHelper::performGetRequest($url_cast_gm_spell, $params_cast_gm_spell);
				$result = json_decode($raw_result);

				// If NOT successful, exit with error
				if (!str_starts_with($result[0],  "SUCCESS|")) {
					RestHeaderHelper::emitRestHeaders();
					$errors[] = "Execution Error|";
					$errors[] = $character_action . "|";
					$errors[] = __FILE__ . "|";
					$errors[] = $result;
					die(json_encode($errors));
				}
			}
			$location_header = buildEditGMSpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}

		break;
		exit;
	case "recoverSpellPointsBySleep":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name
		getCharacterName($errors, $input);

		// Get character level
		getCharacterLevel($errors, $input);

		// Get hours of sleep
		getHoursOfSleep($errors, $input);

		$url_adjust_spell_points = CurlHelper::buildUrl('recoverSpellPointsBySleep');
		$params_adjust_spell_points = buildRecoverSpellPointsParams($input);		
		$raw_result = CurlHelper::performGetRequest($url_adjust_spell_points, $params_adjust_spell_points);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditGMSpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}

		break;
		exit;
	case "stopCastingGMSpellSlot":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name
		getCharacterName($errors, $input);

		// Get Spell Slot ID
		getSpellSlotId($errors, $input);

		$url_deallocate_slot = CurlHelper::buildUrl('deallocateExtraSlot');
		$params_deallocate_slot = buildDeallocateExtraSlotParams($input);
		$raw_result = CurlHelper::performGetRequest($url_deallocate_slot, $params_deallocate_slot);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditGMSpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}

		break;
		exit;
	case "stopRunninGMSpellSlot":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name
		getCharacterName($errors, $input);

		// Get Spell Slot ID
		getSpellSlotId($errors, $input);

		$url_deallocate_slot = CurlHelper::buildUrl('deallocateExtraSlot');
		$params_deallocate_slot = buildDeallocateExtraSlotParams($input);
		$raw_result = CurlHelper::performGetRequest($url_deallocate_slot, $params_deallocate_slot);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditGMSpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}

		break;
		exit;
	case "dailyReset":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		$url_reset_daily = CurlHelper::buildUrl('resetSlotsForDaily');
		$params_daily_reset = buildDailyResetParams($input);
		$raw_result = CurlHelper::performGetRequest($url_reset_daily, $params_daily_reset);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0],  "SUCCESS|")) {
			$location_header = buildEditReadySpellsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}
		break;
		exit;
	case "editExtraSlots":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		$location_header = buildEditExtraSlotsRedirect($input);
		header($location_header);

		break;
		exit;
	case "deallocateExtraSlot":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get Spell Slot ID
		getSpellSlotId($errors, $input);

		$url_deallocate_extra_slot = CurlHelper::buildUrl('deallocateExtraSlot');
		$params_deallocate_extra_slot = buildDeallocateExtraSlotParams($input);
		$raw_result = CurlHelper::performGetRequest($url_deallocate_extra_slot, $params_deallocate_extra_slot);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0], "SUCCESS|")) {
			$location_header = buildEditExtraSlotsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}
		break;
		exit;
	case "allocateExtraSlot":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get the Player Character Class ID
		getPlayerCharacterClassId($errors, $input);

		// Get the level of the spell slot
		getSpellSlotLevel($errors, $input);

		// Get the ID of the spell type for the slot
		getSpellTypeId($errors, $input);

		$url_allocate_extra_slot = CurlHelper::buildUrl('allocateExtraSlot');
		$params_allocate_extra_slot = buildAllocateExtraSlotParams($input);
		$raw_result = CurlHelper::performGetRequest($url_allocate_extra_slot, $params_allocate_extra_slot);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0], "SUCCESS|")) {
			$location_header = buildEditExtraSlotsRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}
		break;
		exit;
	case "playerCharacterWeaponMain":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		$location_header = buildPlayerCharacterWeaponMainRedirect($input);
		header($location_header);

		break;
		exit;

	case "updatePlayerCharacterWeapon":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get the Player Character Weapon ID
		getPlayerCharacterWeaponId($errors, $input);

		$location_header = buildPlayerCharacterWeaponUpdateRedirect($input);
		header($location_header);

		break;
		exit;

	case "deletePlayerCharacterWeapon":
		// Get player name
		getPlayerName($errors, $input);

		// Get character name	
		getCharacterName($errors, $input);

		// Get the Player Character Weapon ID
		getPlayerCharacterWeaponId($errors, $input);

		$url_delete_weapon = CurlHelper::buildUrl('deletePlayerCharacterWeapon');
		$params_delete_weapon = buildDeleteWeaponParams($input);
		$raw_result = CurlHelper::performGetRequest($url_delete_weapon, $params_delete_weapon);
		$result = json_decode($raw_result);
		if (str_starts_with($result[0], "SUCCESS|")) {
			$location_header = buildPlayerCharacterWeaponMainRedirect($input);
			header($location_header);
			exit;
		} else {
			RestHeaderHelper::emitRestHeaders();
			$errors[] = "Execution Error|";
			$errors[] = $character_action . "|";
			$errors[] = __FILE__ . "|";
			$errors[] = $result;
			die(json_encode($errors));
		}

		die(json_encode($log));
		break;
		exit;

	default:
		RestHeaderHelper::emitRestHeaders();
		$errors[] = "Input Error|";
		$errors[] = __FILE__ . "|";
		$errors[] = 'Unhandled route: [' . $character_action . ']';
		die(json_encode($errors));
}

function initiateSession($pdo, $player_name, $errors) {
	deleteSessionTicket($pdo, $player_name, $errors);
	createSessionTicket($pdo, $player_name, $errors);
	if (count($errors) > 0) {
		RestHeaderHelper::emitRestHeaders();
		$errors[] = "Input Error|";
		$errors[] = __FILE__ . "|";
		$errors[] = 'Create Session: [' . $character_action . ']';
		die(json_encode($errors));
	}
}

function deleteSessionTicket($pdo, $player_name, $errors) {
	$sql_exec = "CALL deleteSessionTicket(:playerName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in deleteSessionTicket : " . $e->getMessage();
	}

	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function createSessionTicket($pdo, $player_name, &$errors) {
	// Create timestamp 16 hours in the future
	$session_expiration = time() + calculateExpirationInSeconds();
	$session_ticket = GUIDv4(true);

	$sql_exec = "CALL createSessionTicket(:playerName, :sessionTicket, :sessionTimestamp)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	$statement->bindParam(':sessionTicket', $session_ticket, PDO::PARAM_STR);
	$statement->bindParam(':sessionTimestamp', $session_expiration, PDO::PARAM_INT);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in createSessionTicket : " . $e->getMessage();
	}

	// Stash the session ticket in a cookie
	if (!setcookie(SESSION_COOKIE_NAME, $session_ticket, time() + 60 * 60 * 24, '/', THE_DOMAIN)) {
		RestHeaderHelper::emitRestHeaders();
		$errors[] = "Input Error|";
		$errors[] = __FILE__ . "|";
		$errors[] = 'Cookie Creation';
		die(json_encode($errors));
	}
}

function calculateExpirationInSeconds() {
	return 60 * 60 * 16;
}

function getPlayerPermissions(\PDO $pdo, $player_name, &$errors) {
	$sql_exec = "CALL getPlayerPermissions(:playerName)";

	$statement = $pdo->prepare($sql_exec);
	$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
	try {
		$statement->execute();
	} catch(Exception $e) {
		$errors[] = "Exception in getPlayerPermissions : " . $e->getMessage();
	}

	return $statement->fetch(PDO::FETCH_ASSOC);
}

function buildCharacterListRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('characterList');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	return 'Location:' . $redirect_url;
}

function buildDmDashboardRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('dmDashboard');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildEditSpellBookRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('editSpellBook');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, 'characterClassName', $input['characterClassName']);
	$redirect_url = CurlHelper::addParameter($redirect_url, 'pageAction',  $input['pageAction']);

	return 'Location:' . $redirect_url;
}

function buildEditReadySpellsRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('editReadySpells');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildEditGMSpellsRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('editReadyGMSpells');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildEditExtraSlotsRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('editExtraSlots');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildHomeRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('home');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildCreateCharacterRedirect($input) {
	return $redirect_url = buildCRUDCharacterRedirect($input, 'create');
}

function buildViewCharacterRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('dcs');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildEditCharacterRedirect($input) {
	return $redirect_url = buildCRUDCharacterRedirect($input, 'update');
}

function buildLoginRedirect() {
	$redirect_url = CurlHelper::buildUrl('login');
	return 'Location:' . $redirect_url;
}

function buildCRUDCharacterRedirect($input, $crud_action) {
	$redirect_url = CurlHelper::buildUrl('crudCharacter');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	if (isset($input[CHARACTER_NAME])) {
		$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);
	}
	$redirect_url = CurlHelper::addParameter($redirect_url, 'pageAction',  $crud_action);
	
	return 'Location:' . $redirect_url;
}

function buildEditWeaponTalentsRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('editWeaponTalents');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildEditSkillsRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('editSkills');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildDeleteWeaponTalentRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('editWeaponTalents');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildEditCharacterWeaponsRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('editPlayerCharacterWeapons');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildPlayerCharacterWeaponMainRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('playerCharacterWeaponMain');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);

	return 'Location:' . $redirect_url;
}

function buildPlayerCharacterWeaponUpdateRedirect($input) {
	$redirect_url = CurlHelper::buildUrl('updatePlayerCharacterWeapon');
	$redirect_url = CurlHelper::addParameter($redirect_url, PLAYER_NAME, $input[PLAYER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, CHARACTER_NAME, $input[CHARACTER_NAME]);
	$redirect_url = CurlHelper::addParameter($redirect_url, 'playerCharacterWeaponId', $input['playerCharacterWeaponId']);

	return 'Location:' . $redirect_url;
}

function buildUpdateSpellPoolParams($input) {
	$parmas = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['spellCatalogId'] = $input['spellCatalogId'];
	$params['spellPoolSlotId'] = $input['spellPoolSlotId'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildUpdateReadySlotParams($input) {
	$parmas = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['spellCatalogId'] = $input['spellCatalogId'];
	$params['spellSlotId'] = $input['spellSlotId'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildAllocateCantripsParams($input) {
	$parmas = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params['spellSlotId'] = $input['spellSlotId'];
	$params['spellLevel'] = $input['spellLevel'];
	$params['characterClassName'] = $input['characterClassName'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildCastSlotParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['spellSlotId'] = $input['spellSlotId'];
	$params['castStatus'] = True;
	$params['spellDuration'] = $input['spellDuration'];
	$params['spellCastingTime'] = $input['spellCastingTime'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildResetSlotParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['spellSlotId'] = $input['spellSlotId'];
	$params['castStatus'] = false;
	$params['spellDuration'] = $input['spellDuration'];
	$params['spellCastingTime'] = $input['spellCastingTime'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildStopCastingSlotParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['spellSlotId'] = $input['spellSlotId'];
	$params['spellDuration'] = $input['spellDuration'];
	$params['spellCastingTime'] = $input['spellCastingTime'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildStopRunningSlotParams($input) {
	$params = [];
	$params['spellSlotId'] = $input['spellSlotId'];
	$params['spellDuration'] = $input['spellDuration'];
	$params['spellCastingTime'] = $input['spellCastingTime'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildReallocateCantripsParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['spellSlotId'] = $input['spellSlotId'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildAllocateExtraSlotParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['playerCharacterClassId'] = $input['playerCharacterClassId'];
	$params['slotLevel'] = $input['slotLevel'];
	$params['spellTypeId'] = $input['spellTypeId'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildDeallocateExtraSlotParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['spellSlotId'] = $input['spellSlotId'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildDeleteCharacterParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildDailyResetParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildPromoteClassParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params['characterClassName'] = $input['characterClassName'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildGetPasswordParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];

	return $params;
}

function buildEditWeaponTalentsParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildAddWeaponTalentParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];
	$params[WEAPON_PROFICIENCY_ID] = $input[WEAPON_PROFICIENCY_ID];
	$params[IS_PREFERRED] = $input[IS_PREFERRED];
	
	return $params;
}

function buildDeleteWeaponTalentParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];
	$params[WEAPON_PROFICIENCY_ID] = $input[WEAPON_PROFICIENCY_ID];
	
	return $params;
}

function buildEditSkillsParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];
	
	return $params;
}

function buildDeleteCharacterSkillsParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['playerCharacterSkillId'] = $input['playerCharacterSkillId'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];
	
	return $params;
}

function buildAdjustSpellPointsParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params['spellLevel'] = $input['spellLevel'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];
	
	return $params;
}

function buildRecoverSpellPointsParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params['characterLevel'] = $input['characterLevel'];
	$params['hoursOfSleep'] = $input['hoursOfSleep'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildCastGMSpellParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params['spellCatalogId'] = $input['spellCatalogId'];
	$params['spellLevel'] = $input['spellLevel'];
	$params['spellDuration'] = $input['spellDuration'];
	$params['spellCastingTime'] = $input['spellCastingTime'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildEditCharacterWeaponsParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params[CHARACTER_NAME] = $input[CHARACTER_NAME];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}

function buildDeleteWeaponParams($input) {
	$params = [];
	$params[PLAYER_NAME] = $input[PLAYER_NAME];
	$params['playerCharacterWeaponId'] = $input['playerCharacterWeaponId'];
	$params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];

	return $params;
}
