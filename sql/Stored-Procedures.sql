CREATE PROCEDURE addClassToCharacter
(IN playerCharacterId INT,
 IN characterClassId INT)
BEGIN
	INSERT INTO player_character_class (player_character_id, character_class_id, character_level, number_of_experience_points)
	VALUES (playerCharacterId, characterClassId, 0, 0);
END

CREATE PROCEDURE addPreferredWeaponForCavalier
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterLevel INT,
 IN weaponProficiencyId INT)
BEGIN
	
	DECLARE weaponProficiencySkillId INT DEFAULT 179;
	DECLARE playerCharacterId INT DEFAULT 0;
	DECLARE level3PreferredWeapon BOOLEAN DEFAULT 0;	
	DECLARE level5PreferredWeapon BOOLEAN DEFAULT 0;	

	SELECT id
	INTO weaponProficiencySkillId
	FROM skill_catalog
	WHERE name = 'Weapon Proficiency';
	
	SELECT player_character.id 
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	IF characterLevel = 3 THEN
		SET level3PreferredWeapon = TRUE;
	END IF;

	IF characterLevel = 5 THEN
		SET level5PreferredWeapon = TRUE;
	END IF;
	
	INSERT INTO player_character_skill
		(player_character_id, skill_catalog_id, is_skill_focus, weapon_proficiency_id, is_preferred_cavalier_level3, is_preferred_cavalier_level5)
	VALUES
		(playerCharacterId, weaponProficiencySkillId, FALSE, weaponProficiencyId, level3PreferredWeapon, level5PreferredWeapon);
END

CREATE PROCEDURE addPreferredWeaponForElvenCavalier
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterLevel INT,
 IN weaponProficiencyId INT)
BEGIN
	
	DECLARE weaponProficiencySkillId INT DEFAULT 179;
	DECLARE playerCharacterId INT DEFAULT 0;
	DECLARE level4PreferredWeapon BOOLEAN DEFAULT 0;	
	DECLARE level6PreferredWeapon BOOLEAN DEFAULT 0;	

	SELECT id
	INTO weaponProficiencySkillId
	FROM skill_catalog
	WHERE name = 'Weapon Proficiency';
	
	SELECT player_character.id 
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	IF characterLevel = 4 THEN
		SET level4PreferredWeapon = TRUE;
	END IF;

	IF characterLevel = 6 THEN
		SET level6PreferredWeapon = TRUE;
	END IF;
	
	INSERT INTO player_character_skill
		(player_character_id, skill_catalog_id, is_skill_focus, weapon_proficiency_id, is_preferred_elven_cavalier_level4, 	is_preferred_elven_cavalier_level6)
	VALUES
		(playerCharacterId, weaponProficiencySkillId, FALSE, weaponProficiencyId, level4PreferredWeapon, level6PreferredWeapon);
END

CREATE PROCEDURE addSkill
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN skillCatalogId INT,
 IN playerSkillName VARCHAR(64),
 IN isSkillFocus BOOLEAN,
 IN weaponProficiencyId INT,
 IN weapon2ProficiencyId INT)
 BEGIN
	DECLARE playerCharacterSkillId INT DEFAULT 0;
	
	CALL addSkillToPlayerCharacter(playerName, characterName, skillCatalogId, playerSkillName, isSkillFocus, weaponProficiencyId, weapon2ProficiencyId, playerCharacterSkillId);
	SELECT playerCharacterSkillId as player_character_skill_id;
 END

CREATE PROCEDURE addSkillToPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN skillCatalogId INT,
 IN playerSkillName VARCHAR(64),
 IN isSkillFocus BOOLEAN,
 IN weaponProficiencyId INT,
 IN weapon2ProficiencyId INT,
 OUT playerCharacterSkillId INT)
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	
	SELECT player_character.id 
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;
	
	INSERT INTO player_character_skill(player_character_id, skill_catalog_id, player_character_skill_name, is_skill_focus, weapon_proficiency_id, weapon2_proficiency_id)
	VALUES(playerCharacterId, skillCatalogId, playerSkillName, isSkillFocus, weaponProficiencyId, weapon2ProficiencyId);

	SELECT LAST_INSERT_ID()
	INTO playerCharacterSkillId;
END

CREATE PROCEDURE addTwoWeaponConfiguration
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN weapon1Id INT,
 IN weapon2Id INT)
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	
	SELECT player_character.id 
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	INSERT INTO player_character_two_weapon_fighting(player_character_id, player_character_weapon1_id, player_character_weapon2_id)
	VALUES(playerCharacterId, weapon1Id, weapon2Id);
END

CREATE PROCEDURE addWeaponProficiencyToPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN weaponProficiencyId INT,
 INOUT playerCharacterWeaponProficiencyId INT)
 BEGIN
	
	DECLARE weaponProficiencySkillId INT DEFAULT 179;

	SELECT id
	INTO weaponProficiencySkillId
	FROM skill_catalog
	WHERE name = 'Weapon Proficiency';

	CALL addSkillToPlayerCharacter(playerName, characterName, weaponProficiencySkillId, NULL, false, weaponProficiencyId, NULL, playerCharacterWeaponProficiencyId);
 END

CREATE PROCEDURE addWeaponProficiency
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN weaponProficiencyId INT)
 BEGIN
	
	DECLARE playerCharacterSkillId INT DEFAULT 0;

	Call addWeaponProficiencyToPlayerCharacter(playerName, characterName, weaponProficiencyId, playerCharacterSkillId);
 END

CREATE PROCEDURE addWeaponToPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN weaponProficiencyId INT,
 IN weaponDescription VARCHAR(32),
 IN weaponLocation VARCHAR(32),
 IN isProficient BOOLEAN,
 IN isReady BOOLEAN,
 IN craftStatus INT,
 IN strengthBonusAvailable BOOLEAN,
 IN playerNote1 VARCHAR(32),
 IN playerNote2 VARCHAR(32),
 IN playerNote3 VARCHAR(32),
 IN mastercraftHitDescription VARCHAR(32),
 IN mastercraftDamageDescription VARCHAR(32),
 IN meleeWeaponType INT,
 IN meleeWeaponSubtype INT,
 IN meleeWeaponSpeed VARCHAR(32),
 IN meleeWeaponDamage VARCHAR(32),
 IN meleeAttacksPerRound VARCHAR(32),
 IN meleeNumberOfHands VARCHAR(32),
 IN meleeAdditionalText VARCHAR(255),
 IN meleeHitBonus INT,
 IN meleeDamageBonus INT,
 IN meleeSpec1HitBonus INT,
 IN meleeSpec1DamageBonus INT,
 IN meleeSpec1Description VARCHAR(32),
 IN meleeSpec2HitBonus INT,
 IN meleeSpec2DamageBonus INT,
 IN meleeSpec2Description VARCHAR(32),
 IN meleeSpec3HitBonus INT,
 IN meleeSpec3DamageBonus INT,
 IN meleeSpec3Description VARCHAR(32),
 IN missileWeaponType INT,
 IN missileWeaponSubtype INT,
 IN missileWeaponSpeed VARCHAR(32),
 IN missileWeaponDamage VARCHAR(32),
 IN missileAttacksPerRound VARCHAR(32),
 IN missileAdditionalText VARCHAR(255),
 IN missileHitBonus INT,
 IN missileDamageBonus INT,
 IN missileSpec1HitBonus INT,
 IN missileSpec1DamageBonus INT,
 IN missileSpec1Description VARCHAR(32),
 IN missileSpec2HitBonus INT,
 IN missileSpec2DamageBonus INT,
 IN missileSpec2Description VARCHAR(32),
 IN missileSpec3HitBonus INT,
 IN missileSpec3DamageBonus INT,
 IN missileSpec3Description VARCHAR(32),
 IN missileShortRange VARCHAR(32),
 IN missileMediumRange VARCHAR(32),
 IN missileLongRange VARCHAR(32))
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	DECLARE weaponTypeMelee INT DEFAULT 1;
	DECLARE weaponTypeMissile INT DEFAULT 2;
	DECLARE playerCharacterWeaponId INT DEFAULT 0;
	DECLARE skillCatalogWeaponProficiency INT DEFAULT 179;
	DECLARE playerCharacterSkillId INT DEFAULT 0;
	
	SELECT player_character.id 
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	SELECT id
	INTO skillCatalogWeaponProficiency
	FROM skill_catalog
	WHERE name = 'Weapon Proficiency';

	SELECT id
	INTO weaponTypeMelee
	FROM weapon_type
	WHERE name = 'Melee';

	SELECT id
	INTO weaponTypeMissile
	FROM weapon_type
	WHERE name = 'Missile';

	START TRANSACTION;
		IF isProficient = true THEN
			IF EXISTS (SELECT 1 FROM player_character_skill WHERE player_character_id = playerCharacterId AND skill_catalog_id = skillCatalogWeaponProficiency AND weapon_proficiency_id = weaponProficiencyId) THEN
				SELECT id
				INTO playerCharacterSkillId
				FROM player_character_skill
				WHERE player_character_id = playerCharacterId AND skill_catalog_id = skillCatalogWeaponProficiency AND weapon_proficiency_id = weaponProficiencyId;
			ELSE
				Call addWeaponProficiencyToPlayerCharacter(playerName, characterName, weaponProficiencyId, playerCharacterSkillId);
			END IF;
		END IF;

		INSERT INTO player_character_weapon(
			player_character_id,
			weapon_proficiency_id,
			player_character_skill_id,
			description,
			location,
			is_ready,
			craft_status,
			strength_bonus_available,
			player_note1,
			player_note2,
			player_note3)
		VALUES(
			playerCharacterId,
			weaponProficiencyId,
			playerCharacterSkillId,
			weaponDescription,
			weaponLocation,
			isReady,
			craftStatus,
			strengthBonusAvailable,
			playerNote1,
			playerNote2,
			playerNote3);

		SELECT LAST_INSERT_ID()
		INTO playerCharacterWeaponId;

		-- INSERT Melee entry
		IF(meleeWeaponType = weaponTypeMelee) THEN
			INSERT INTO player_character_weapon_mode(
				player_character_weapon_id,
				weapon_type,
				weapon_subtype,
				speed,
				base_damage,
				attacks_per_round,
				number_of_hands,
				additional_text,
				damage_bonus,
				hit_bonus,
				mastercraft_damage_description,
				mastercraft_hit_description,
				spec1_damage_bonus,
				spec1_description,
				spec1_hit_bonus,
				spec2_damage_bonus,
				spec2_description,
				spec2_hit_bonus,
				spec3_damage_bonus,
				spec3_description,
				spec3_hit_bonus
			)
			VALUES(
				playerCharacterWeaponId,
				meleeWeaponType,
				meleeWeaponSubtype,
				meleeWeaponSpeed,
				meleeWeaponDamage,
				meleeAttacksPerRound,
				meleeNumberOfHands,
				meleeAdditionalText,
				meleeDamageBonus,
				meleeHitBonus,
				mastercraftDamageDescription,
				mastercraftHitDescription,
				meleeSpec1DamageBonus,
				meleeSpec1Description,
				meleeSpec1HitBonus,
				meleeSpec2DamageBonus,
				meleeSpec2Description,
				meleeSpec2HitBonus,
				meleeSpec3DamageBonus,
				meleeSpec3Description,
				meleeSpec3HitBonus
			);
		END IF;

		-- INSERT Missile entry
		IF(missileWeaponType = weaponTypeMissile) THEN
			INSERT INTO player_character_weapon_mode(
				player_character_weapon_id,
				weapon_type,
				weapon_subtype,
				speed,
				base_damage,
				attacks_per_round,
				additional_text,
				damage_bonus,
				hit_bonus,				
				mastercraft_damage_description,
				mastercraft_hit_description,
				spec1_damage_bonus,
				spec1_description,
				spec1_hit_bonus,
				spec2_damage_bonus,
				spec2_description,
				spec2_hit_bonus,
				spec3_damage_bonus,
				spec3_description,
				spec3_hit_bonus,
				short_range,
				medium_range,
				long_range
				)
			VALUES(
				playerCharacterWeaponId,
				missileWeaponType,
				missileWeaponSubtype,
				missileWeaponSpeed,
				missileWeaponDamage,
				missileAttacksPerRound,
				missileAdditionalText,
				missileDamageBonus,
				missileHitBonus,
				mastercraftDamageDescription,
				mastercraftHitDescription,
				missileSpec1DamageBonus,
				missileSpec1Description,
				missileSpec1HitBonus,
				missileSpec2DamageBonus,
				missileSpec2Description,
				missileSpec2HitBonus,
				missileSpec3DamageBonus,
				missileSpec3Description,
				missileSpec3HitBonus,
				missileShortRange,
				missileMediumRange,
				missileLongRange
			);
		END IF;

	COMMIT;

	SELECT playerCharacterWeaponId;
END

CREATE PROCEDURE adjustSpellPoints
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN spellPointsAdjustText VARCHAR(32))
BEGIN
    DECLARE slotLevelSum FLOAT DEFAULT 0;
    DECLARE spellPointsAvailable FLOAT DEFAULT 0;
	DECLARE maxSpellPointsAvailable FLOAT DEFAULT 0;
	DECLARE currentSpellPoints FLOAT DEFAULT 0;
	DECLARE finalSpellPointsResult FLOAT DEFAULT 0;
	DECLARE characterId INT DEFAULT 0;
	DECLARE spellPointsIncrease DECIMAL(4,2) DEFAULT 0.0;
	DECLARE extraSlotType INT DEFAULT 3;


	SET spellPointsIncrease = CAST(spellPointsAdjustText AS DECIMAL(4,2));

	SELECT SUM(slot_level), spell_points.spell_points_available, player_character.spell_points, player_character.id
	INTO slotLevelSum, spellPointsAvailable, currentSpellPoints, characterId
	FROM player
	JOIN player_character ON player_character.player_id = player.id
	JOIN player_character_class ON player_character_class.player_character_id = player_character.id
	JOIN player_spell_slot ON player_spell_slot.player_character_class_id = player_character_class.id
	JOIN spell_points ON spell_points.character_level = player_character_class.character_level
	WHERE player.name = playerName AND player_character.name = characterName AND player_spell_slot.spell_slot_type_id = extraSlotType;

	SET maxSpellPointsAvailable = slotLevelSum + spellPointsAvailable;
	IF currentSpellPoints + spellPointsIncrease > maxSpellPointsAvailable THEN
		SET finalSpellPointsResult = maxSpellPointsAvailable;
	ELSE
		SET finalSpellPointsResult = currentSpellPoints + spellPointsIncrease;
	END IF;

	UPDATE player_character SET spell_points = finalSpellPointsResult WHERE id = characterId;
END

CREATE PROCEDURE allocateCantripForSlot
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32),
 IN parentSpellSlotId INT)
BEGIN
	DECLARE numberOfCantripSlots INT DEFAULT 0;
	DECLARE nullSpellId INT DEFAULT 0;
	DECLARE cantripSpellId INT DEFAULT 0;
	DECLARE baseSlotType INT DEFAULT 1;
    DECLARE playerCharacterClassId INT DEFAULT 0;
	DECLARE spellTypeId INT DEFAULT 0;
	
	-- Get the ID of the one (and only) spell of spell_type 7 (NONE)
	SELECT spell_catalog.id 
	INTO nullSpellId
	FROM spell_catalog
	WHERE spell_type_id = 7;
	
	-- Get the ID of the one (and only) spell of spell_type 8 (CANTRIP)
	SELECT spell_catalog.id 
	INTO cantripSpellId
	FROM spell_catalog
	WHERE spell_type_id = 8;
	
	-- Get the player character ID
    SELECT player_character_class.id
    INTO playerCharacterClassId
	FROM player_character_class
		JOIN player_character ON player_character.id = player_character_class.player_character_id
		JOIN player ON player.id = player_character.player_id
		JOIN character_class ON character_class.id = player_character_class.character_class_id
	WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName;
		
	-- Get the spell level and spell type based on the parent slot
	SELECT spell_type_id
	INTO spellTypeId
	FROM player_spell_slot
	WHERE id = parentSpellSlotId;

	INSERT INTO player_spell_slot (player_character_class_id, skill_slot_id, slot_level, spell_catalog_id, spell_slot_type_id, spell_type_id, parent_spell_slot_id)
	VALUES (playerCharacterClassId, NULL, 0, nullSpellId, baseSlotType, spellTypeId, parentSpellSlotId);
END

CREATE PROCEDURE allocateGMExtraSpellSlot
(IN playerCharacterClassId INT,
 IN spellCatalogId INT,
 IN slotLevel INT,
 IN spellTypeId INT)
BEGIN
	DECLARE extraSlotType INT DEFAULT 4;
	
	START TRANSACTION;
		INSERT INTO player_spell_slot (player_character_class_id, skill_slot_id, slot_level, spell_catalog_id, spell_slot_type_id, spell_type_id)
		VALUES (playerCharacterClassId, NULL, slotLevel, spellCatalogId, extraSlotType, spellTypeId);
	COMMIT;
	
	SELECT playerCharacterClassId, spellTypeId, slotLevel, spellCatalogId, LAST_INSERT_ID() AS player_spell_slot_id;
END

CREATE PROCEDURE allocateReadyBaseSpellSlot
(IN playerCharacterClassId INT,
 IN slotLevel INT,
 IN spellTypeId INT)
BEGIN
	DECLARE baseSlotType INT DEFAULT 1;
	DECLARE nullSpellId INT DEFAULT 0;
	
	-- Get the ID of the one (and only) spell of spell_type 7 (NONE)
	SELECT spell_catalog.id 
	INTO nullSpellId
	FROM spell_catalog
	WHERE spell_type_id = 7;
	
	START TRANSACTION;
		INSERT INTO player_spell_slot (player_character_class_id, skill_slot_id, slot_level, spell_catalog_id, spell_slot_type_id, spell_type_id)
		VALUES (playerCharacterClassId, NULL, slotLevel, nullSpellId, baseSlotType, spellTypeId);
	COMMIT;
	
	SELECT playerCharacterClassId, spellTypeId, slotLevel, nullSpellId;
END

CREATE PROCEDURE allocateReadyWisdomSpellSlot
(IN playerCharacterClassId INT,
 IN slotLevel INT,
 IN spellTypeId INT)
BEGIN
	DECLARE wisdomSlotType INT DEFAULT 2;
	DECLARE nullSpellId INT DEFAULT 0;
	
	-- Get the ID of the one (and only) spell of spell_type 7 (NONE)
	SELECT spell_catalog.id 
	INTO nullSpellId
	FROM spell_catalog
	WHERE spell_type_id = 7;
	
	START TRANSACTION;
		INSERT INTO player_spell_slot (player_character_class_id, skill_slot_id, slot_level, spell_catalog_id, spell_slot_type_id, spell_type_id)
		VALUES (playerCharacterClassId, NULL, slotLevel, nullSpellId, wisdomSlotType, spellTypeId);
	COMMIT;
	
	SELECT playerCharacterClassId, spellTypeId, slotLevel, nullSpellId;
END

CREATE PROCEDURE allocateReadyExtraSpellSlot
(IN playerCharacterClassId INT,
 IN slotLevel INT,
 IN spellTypeId INT)
BEGIN
	DECLARE extraSlotType INT DEFAULT 3;
	DECLARE nullSpellId INT DEFAULT 0;
	
	-- Get the ID of the one (and only) spell of spell_type 7 (NONE)
	SELECT spell_catalog.id 
	INTO nullSpellId
	FROM spell_catalog
	WHERE spell_type_id = 7;
	
	START TRANSACTION;
		INSERT INTO player_spell_slot (player_character_class_id, skill_slot_id, slot_level, spell_catalog_id, spell_slot_type_id, spell_type_id)
		VALUES (playerCharacterClassId, NULL, slotLevel, nullSpellId, extraSlotType, spellTypeId);
	COMMIT;
	
	SELECT playerCharacterClassId, spellTypeId, slotLevel, nullSpellId;
END

CREATE PROCEDURE allocateGMCapabilities
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	DECLARE playerCharacterClassId INT DEFAULT 0;
	DECLARE spellCatalogIdESP INT DEFAULT 0;
	DECLARE spellCatalogIdLevitate INT DEFAULT 0;
	DECLARE magicUserSpellTypeId INT DEFAULT 4;

	SELECT player_character_class.id
	INTO playerCharacterClassId
	FROM player_character_class
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	SELECT id
	INTO spellCatalogIdESP
	FROM spell_catalog
	WHERE spell_type_id = magicUserSpellTypeId AND level = 1 AND name = 'ESP';

	SELECT id
	INTO spellCatalogIdLevitate
	FROM spell_catalog
	WHERE spell_type_id = magicUserSpellTypeId AND level = 1 AND name = 'Levitate';

	START TRANSACTION;

		INSERT player_spell_pool (player_character_class_id, spell_catalog_id, spell_level)
		VALUES(playerCharacterClassId, spellCatalogIdESP, 1);

		INSERT player_spell_pool (player_character_class_id, spell_catalog_id, spell_level)
		VALUES(playerCharacterClassId, spellCatalogIdLevitate, 1);

	COMMIT;

	SELECT playerCharacterClassId, spellCatalogIdESP, spellCatalogIdLevitate;
END

CREATE PROCEDURE allocateMUPoolSlots
(IN playerCharacterClassId INT,
 IN spellLevel INT,
 IN numberOfSlots INT)
BEGIN
	DECLARE nullSpellId INT DEFAULT 0;
	DECLARE slotCount INT DEFAULT 0;
	
	-- Get the ID of the one (and only) spell of spell_type 7 (NONE)
	SELECT spell_catalog.id 
	INTO nullSpellId
	FROM spell_catalog
	WHERE spell_type_id = 7;
	
	SET slotCount = 1;
	WHILE slotCount <= numberOfSlots DO
		INSERT INTO player_spell_pool (player_character_class_id, spell_level, spell_catalog_id)
		VALUES (playerCharacterClassId, spellLevel, nullSpellId);
		SET slotCount = slotCount + 1;
	END WHILE;
	
	SELECT nullSpellId;
END

CREATE PROCEDURE checkDuplicateCharacterName
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64)
)
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;

	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	WHERE player_character.name = characterName;

	SELECT playerCharacterId;
END

CREATE PROCEDURE createBaseCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterStrength INT,
 IN characterSuperStrength INT,
 IN characterIntelligence INT,
 IN characterSuperIntelligence INT,
 IN characterWisdom INT,
 IN characterSuperWisdom INT,
 IN characterDexterity INT,
 IN characterSuperDexterity INT,
 IN characterConstitution INT,
 IN characterSuperConstitution INT,
 IN characterCharisma INT,
 IN characterComeliness INT,
 IN raceId INT,
 IN armorClass INT,
 IN hitPoints INT,
 IN genderIn CHAR(1))
BEGIN
	DECLARE playerID INT DEFAULT 0;
	DECLARE playerCharacterId INT DEFAULT 0;
	DECLARE playerCharacterSkillId INT DEFAULT 0;
	DECLARE fistWeaponProficiencyId INT DEFAULT 118;
	
	SELECT player.id
	INTO playerID
	FROM player
	WHERE name = playerName;

	SELECT id
	INTO fistWeaponProficiencyId
	FROM weapon_proficiency
	WHERE name = 'Fist';
	
	START TRANSACTION;
	INSERT INTO player_character (
		player_id, 
		name, 
		strength, 
		super_strength, 
		intelligence, 
		super_intelligence, 
		wisdom, 
		super_wisdom, 
		dexterity, 
		super_dexterity, 
		constitution, 
		super_constitution, 
		charisma, 
		comeliness, 
		race_id, 
		armor_class, 
		hit_points, gender
	)
	VALUES (
		playerID, 
		characterName, 
		characterStrength, 
		characterSuperStrength, 
		characterIntelligence, 
		characterSuperIntelligence, 
		characterWisdom, 
		characterSuperWisdom, 
		characterDexterity, 
		characterSuperDexterity, 
		characterConstitution, 
		characterSuperConstitution, 
		characterCharisma, 
		characterComeliness, 
		raceId, 
		armorClass, 
		hitPoints, 
		genderIn
	);
	
	SELECT LAST_INSERT_ID()
	INTO playerCharacterId;
	
	SELECT playerID, playerCharacterId;

	Call addWeaponProficiencyToPlayerCharacter(playerName, characterName, fistWeaponProficiencyId, playerCharacterSkillId);
	COMMIT;
END

CREATE PROCEDURE createSessionTicket
(IN playerName VARCHAR(32),
 IN sessionTicket CHAR(37),
 IN sessionTimestamp INT)
BEGIN
	DECLARE playerId INT DEFAULT 0;
	DECLARE sessionCount INT DEFAULT 0;
	
	SELECT id
	INTO playerId
	FROM player
	WHERE name = playerName;
	
	CALL deleteSessionTicket(playerName);
	
	INSERT INTO player_cred(player_id, session_ticket, session_timestamp)
	VALUES(playerId, sessionTicket, sessionTimestamp);
END

CREATE PROCEDURE deallocateCantripsForSlot
(IN parentSpellSlotId INT)
BEGIN

	DECLARE nullSpellId INT DEFAULT 0;

	-- Get the ID of the one (and only) spell of spell_type 7 (NONE)
	SELECT spell_catalog.id 
	INTO nullSpellId
	FROM spell_catalog
	WHERE spell_type_id = 7;

	START TRANSACTION;
		DELETE FROM player_spell_slot WHERE parent_spell_slot_id = parentSpellSlotId;
		UPDATE player_spell_slot SET spell_catalog_id = nullSpellId WHERE id = parentSpellSlotId;
	COMMIT;
END

CREATE PROCEDURE deallocateExtraSlot
(IN extraSlotId INT)
BEGIN
	DELETE FROM player_spell_slot WHERE id = extraSlotId;
END

CREATE PROCEDURE deletePlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN

	DECLARE playerCharacterId INT DEFAULT 0;
	
	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	CREATE TEMPORARY TABLE ids( id INT );
	CREATE TEMPORARY TABLE playerCharacterWeaponModeIds(id INT);
	
	INSERT INTO ids (id) 
	SELECT player_character_class.id FROM player_character_class
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player on player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	INSERT INTO playerCharacterWeaponModeIds (id)
	SELECT player_character_weapon_mode.id FROM player_character_weapon_mode
	JOIN player_character_weapon ON player_character_weapon.id = player_character_weapon_mode.player_character_weapon_id
	WHERE player_character_weapon.player_character_id =  playerCharacterId;
	
	START TRANSACTION;
		DELETE FROM player_spell_pool WHERE player_spell_pool.player_character_class_id IN (SELECT id FROM ids);
		DELETE FROM player_spell_slot WHERE player_spell_slot.player_character_class_id IN (SELECT id FROM ids);
		DELETE FROM player_character_skill  WHERE player_character_skill.player_character_id  = playerCharacterId;
		DELETE FROM player_character_weapon WHERE player_character_weapon.player_character_id = playerCharacterId;
		DELETE FROM player_character_weapon_mode WHERE player_character_weapon_mode.id IN (SELECT id FROM playerCharacterWeaponModeIds);
		DELETE FROM player_character_two_weapon_fighting WHERE player_character_two_weapon_fighting.player_character_id = playerCharacterId;

		DELETE FROM player_character_class WHERE player_character_class.id IN (SELECT id FROM ids);
		DELETE FROM player_character WHERE player_character.name = characterName;
	COMMIT;
	
	DROP TEMPORARY TABLE ids;
	DROP TEMPORARY TABLE playerCharacterWeaponModeIds;
END

CREATE PROCEDURE deleteSessionTicket
(IN playerName VARCHAR(32))
BEGIN
	DELETE FROM player_cred WHERE player_id = 
	(
		SELECT id FROM player
		WHERE name = playerName
	);
END

CREATE PROCEDURE deleteSkillForPlayerCharacter
(IN playerCharacterSkillId INT)
BEGIN
	DELETE FROM player_character_skill WHERE id = playerCharacterSkillId;
END

CREATE PROCEDURE deleteTwoWeaponConfiguration
(IN playerCharacterTwoWeaponConfigId INT)
BEGIN
	DELETE FROM player_character_two_weapon_fighting WHERE id = playerCharacterTwoWeaponConfigId;
END

CREATE PROCEDURE deleteWeaponForPlayerCharacter
(IN characterWeaponId INT)
BEGIN
	START TRANSACTION;
		DELETE FROM player_character_weapon_mode WHERE player_character_weapon_id = characterWeaponId;
		DELETE FROM player_character_weapon WHERE id = characterWeaponId;
		DELETE FROM player_character_two_weapon_fighting 
			WHERE player_character_two_weapon_fighting.player_character_weapon1_id = characterWeaponId OR 
			      player_character_two_weapon_fighting.player_character_weapon2_id = characterWeaponId;
	COMMIT;
END

CREATE PROCEDURE deleteWeaponProficiencyForPlayerCharacter
(IN playerCharacterWeaponProficiencyId INT)
BEGIN
	DECLARE fistWeaponProficiencyId INT DEFAULT 118;
    
	SELECT id
	INTO fistWeaponProficiencyId
	FROM weapon_proficiency 
	WHERE name = 'Fist';

	DELETE FROM player_character_skill 
	WHERE id = playerCharacterWeaponProficiencyId AND weapon_proficiency_id <> fistWeaponProficiencyId;
END

CREATE PROCEDURE getActiveSpells()
BEGIN
	SELECT 
		player_character.name AS character_name, 
		spell_catalog.name AS spell_name, 
		player_spell_slot.casting_time_remaining AS casting_time_remaining, 
		player_spell_slot.running_time_remaining AS running_time_remaining
	FROM player_spell_slot
	JOIN player_character_class ON player_character_class.id = player_spell_slot.player_character_class_id
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN spell_catalog on spell_catalog.id = player_spell_slot.spell_catalog_id
	WHERE player_spell_slot.casting_time_remaining > 0 || player_spell_slot.running_time_remaining > 0
	ORDER BY player_spell_slot.casting_time_remaining DESC, player_spell_slot.running_time_remaining DESC;
END

CREATE PROCEDURE getAllCharacterClasses()
BEGIN
	SELECT id AS character_class_id, name AS character_class_name FROM character_class
	WHERE is_active = 1
	ORDER BY name;
END

CREATE PROCEDURE getAllRaces()
BEGIN
	SELECT id AS race_id, name AS race_name FROM character_race
	WHERE is_active = 1
	ORDER BY name;
END

CREATE PROCEDURE getAllSkills()
BEGIN
	SELECT id AS skill_id, name AS skill_name, attribute AS skill_attribute
	FROM skill_catalog;
END

CREATE PROCEDURE getAllSkillsWithPrerequisites()
BEGIN
	SELECT
		skill_catalog.id AS skill_catalog_id,
		skill_catalog.name AS skill_catalog_name,
		skill_catalog.attribute AS skill_catalog_attribute,
		skill_catalog.skill_focus	AS skill_catalog_skill_focus,
		skill_catalog.max_count AS skill_catalog_max_count,
		skill_catalog.required_class AS skill_catalog_required_class_id,
		skill_catalog.required_race AS skill_catalog_required_race_id,
		skill_catalog.required_level AS skill_catalog_required_level,
		skill_catalog.minimum_charisma AS skill_catalog_minimum_charisma,
		skill_catalog.minimum_dexterity AS skill_catalog_minimum_dexterity,
		skill_catalog.minimum_intelligence AS skill_catalog_minimum_intelligence,
		skill_catalog.roll_name AS skill_catalog_roll_name,
		skill_catalog.ability_text AS skill_catalog_ability_text,
		skill_catalog.attribute_bonus AS skill_catalog_attribute_bonus,
		skill_prerequisite.prerequisite_skill_id AS skill_catalog_prerequisite_id
	FROM skill_catalog
	JOIN skill_prerequisite ON skill_prerequisite.skill_catalog_id = skill_catalog.id
	WHERE skill_catalog.is_active = true
	ORDER BY skill_catalog.id;
END

CREATE PROCEDURE getAllSpellCastingClasses()
BEGIN
	SELECT id AS character_class_id, name AS character_class_name FROM character_class
	WHERE NOT spell_type_1 IS NULL
	ORDER BY character_class.name;
END

CREATE PROCEDURE getBaseSpellSlotCount
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
BEGIN
	SELECT 
		spell_type_id, 
		number_level_1, 
		number_level_2, 
		number_level_3, 
		number_level_4, 
		number_level_5, 
		number_level_6, 
		number_level_7, 
		number_level_8, 
		number_level_9 
	FROM character_class_spell_count
	JOIN player_character_class ON player_character_class.character_class_id = character_class_spell_count.character_class_id
	JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player_character on player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
	WHERE	player.name = playerName AND 
			player_character.name = characterName AND 
			character_class.name = characterClassName AND 
			player_character_class.character_level = character_class_spell_count.character_level; 
END

CREATE PROCEDURE getCharacterClassSpellCount
(IN characterClassName VARCHAR(32))
BEGIN
	SELECT 
		character_level, number_level_1, number_level_2, number_level_3, number_level_4, 
		number_level_5, number_level_6, number_level_7, number_level_8, number_level_9, spell_type.name AS spell_type_name
	FROM character_class_spell_count
	JOIN character_class ON character_class.id = character_class_spell_count.character_class_id
	JOIN spell_type ON spell_type.id = character_class_spell_count.spell_type_id
	WHERE character_class.name = characterClassName
	ORDER BY character_level, spell_type_id;
END

CREATE PROCEDURE getCharacterClasses
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	SELECT	character_class.name AS class_name, 
			player_character_class.character_level,
			player_character_class.id AS player_character_class_id,
			character_class.icon_file_name as class_icon_file_location, 
			character_class.spell_type_1 AS spell_type_1,
			character_class.spell_type_2 AS spell_type_2,
			character_class.spell_type_1_offset AS spell_type_1_offset,
			character_class.spell_type_2_offset AS spell_type_2_offset
	FROM player
	JOIN player_character ON player_character.player_id = player.id
	JOIN player_character_class ON player_character_class.player_character_id = player_character.id
	JOIN character_class ON character_class.id = player_character_class.character_class_id
	WHERE player.name = playerName AND player_character.name = characterName;
END

CREATE PROCEDURE getCharacterDetails
(IN playerName VARCHAR(32),
 in characterName VARCHAR(64))
BEGIN
	SELECT 
		player_character.armor_class AS player_character_armor_class,
		player_character.armor_bulk_factor AS player_character_armor_bulk_factor,
		player_character.charisma AS player_character_charisma,
		player_character.comeliness AS player_character_comeliness,
		player_character.constitution AS player_character_constitution,
		player_character.dexterity AS player_character_dexterity,
		player_character.Gender AS player_character_gender,
		player_character.hit_points AS player_character_hit_points,
		player_character.intelligence AS player_character_intelligence,
		player_character.name AS player_character_name,
		player_character.strength AS player_character_strength,
		player_character.super_dexterity AS player_character_super_dexterity,
		player_character.super_intelligence AS player_character_super_intelligence,
		player_character.super_constitution AS player_character_super_constitution,
		player_character.super_strength AS player_character_super_strength,
		player_character.super_wisdom AS player_character_super_wisdom,
		player_character.wisdom AS player_character_wisdom,
		player_character.movement AS player_character_movement,
		player_character.alignment AS player_character_alignment,
		player_character.religion AS player_character_religion,
		player_character.deity AS player_character_deity,
		player_character.hometown AS player_character_hometown,
		player_character.hit_die AS player_character_hit_die,
		player_character.age AS player_character_age,
		player_character.apparent_age AS player_character_apparent_age,
		player_character.unnatural_age AS player_character_unnatural_age,
		player_character.social_class AS player_character_social_class,
		player_character.height AS player_character_height,
		player_character.weight AS player_character_weight,
		player_character.hair AS player_character_hair,
		player_character.eyes AS player_character_eyes,
		player_character.siblings AS player_character_siblings,
		player_character.parents_married as player_character_parents_married,
		character_race.name AS player_character_race,
		player_character_class.character_level AS player_character_class_level,
		player_character_class.number_of_experience_points AS player_character_class_experience_points,
		character_class.id AS character_class_Id,
		character_class.name AS player_character_class_name
	FROM player_character
	JOIN character_race on character_race.id = player_character.race_id
	JOIN player ON player.id = player_character.player_id
	JOIN player_character_class ON player_character_class.player_character_id = player_character.id
	JOIN character_class ON character_class.id = player_character_class.character_class_id
	WHERE player.name = playerName and player_character.name = characterName;
END

CREATE PROCEDURE getCharacterIds
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
BEGIN
	SELECT player_character_class.id as player_character_class_id, player_character_class.character_class_id AS player_character_class_character_class_id, player_character.id AS player_character_id, player_character.race_id AS player_character_race_id, character_race.generic_race_id AS generic_race_id, player.id AS player_id, character_class.spell_type_1 AS spell_type_id_1, character_class.spell_type_2 AS spell_type_id_2 FROM player
	JOIN player_character ON player_character.player_id = player.id
	JOIN character_race ON character_race.id = player_character.race_id
	JOIN player_character_class ON player_character_class.player_character_id = player_character.id
	JOIN character_class ON character_class.id = player_character_class.character_class_id
	WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName;
END

CREATE PROCEDURE getCharacterSummary
(IN playerName VARCHAR(64),
 IN characterName VARCHAR(64))
BEGIN
	SELECT strength, super_strength, intelligence, super_intelligence,
           wisdom, super_wisdom, dexterity, super_dexterity,
           constitution, super_constitution, charisma, comeliness, armor_class, hit_points, spell_points
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player_character.name = characterName
	AND player.name = playerName;
END

CREATE PROCEDURE getCharactersForPlayer
(IN playerName VARCHAR(32))
BEGIN
	SELECT player_character.name AS character_name, player_character.portrait as player_character_portrait FROM player
	JOIN player_character ON player_character.player_id = player.id
	WHERE player.name = playerName
    ORDER BY player_character.name;
END

CREATE PROCEDURE getMaxExtraSlotLevelBySpellType
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
BEGIN
	SELECT MAX(spell_level) - 1 AS spell_level, spell_type_id 
	FROM spell_level_availability
	JOIN player_character_class ON player_character_class.character_class_id = spell_level_availability.character_class_id
	JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
	WHERE	player.name = playerName AND 
			player_character.name = characterName AND 
			character_class.name = characterClassName AND 
			spell_level_availability.character_level <= player_character_class.character_level
	GROUP BY spell_type_id;
END

CREATE PROCEDURE getMUSpellsForCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32),
 IN spellLevel INT)
BEGIN 
	SELECT spell_pool.id AS spell_pool_id, spell_catalog.id AS spell_catalog_id, spell_catalog.name as spell_name, spell_catalog.link AS spell_link FROM spell_pool
	JOIN player_character_class ON player_character_class.character_class_id = spell_pool.character_class_id
	JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
	JOIN spell_catalog ON spell_catalog.id = spell_pool.spell_catalog_id
	WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName AND spell_pool.level = spellLevel
	AND spell_catalog.spell_type_id IN (4, 5);
END

CREATE PROCEDURE getNewSpellLevelForCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
BEGIN
	SELECT spell_level, spell_type_id FROM spell_level_availability
	JOIN character_class ON character_class.id = spell_level_availability.character_class_id
	JOIN player_character_class ON player_character_class.character_class_id = character_class.id
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName
	AND player_character_class.character_level = spell_level_availability.character_level;
END

CREATE PROCEDURE getPasswordHash
(IN playerName VARCHAR(32))
BEGIN
	SELECT password FROM player 
	WHERE name = playerName;
END

CREATE PROCEDURE getPlayerList()
BEGIN
	SELECT player.name AS player_name FROM player
    ORDER BY player.name;
END

CREATE PROCEDURE getPlayerPermissions
(IN playerName VARCHAR(32))
BEGIN
	SELECT is_dm, is_admin FROM player WHERE player.name = playerName;
END

CREATE PROCEDURE getPlayerCharacterWeapon
(IN playerCharacterWeaponId INT)
BEGIN
	SELECT
		player_character_weapon.id AS player_character_weapon_id,
		player_character_weapon_mode.weapon_type AS player_character_weapon_type,
		player_character_weapon_mode.weapon_subtype  AS player_character_weapon_subtype,
		player_character_weapon.craft_status AS player_character_weapon_craft_status,
		player_character_weapon.weapon_proficiency_id AS player_character_weapon_proficiency_id, 
		player_character_weapon.description AS player_character_weapon_description,
		player_character_weapon.is_ready AS player_character_weapon_is_ready,
		player_character_weapon.location AS player_character_weapon_location,
		player_character_weapon.player_note1 AS player_character_weapon_player_note1,
		player_character_weapon.player_note2 AS player_character_weapon_player_note2,
		player_character_weapon.player_note3 AS player_character_weapon_player_note3,
		player_character_weapon.strength_bonus_available AS player_character_weapon_strength_bonus_available,
		player_character_weapon_mode.speed AS player_character_weapon_speed,
		player_character_weapon_mode.base_damage AS player_character_weapon_damage,
		player_character_weapon_mode.attacks_per_round AS player_character_weapon_attacks_per_round,
		player_character_weapon_mode.number_of_hands AS player_character_weapon_number_of_hands,
		player_character_weapon_mode.hit_bonus AS player_character_weapon_hit_bonus,
		player_character_weapon_mode.damage_bonus AS player_character_weapon_damage_bonus,
		player_character_weapon_mode.mastercraft_hit_description AS player_character_weapon_mastercraft_hit_description,
		player_character_weapon_mode.mastercraft_damage_description AS player_character_weapon_mastercraft_damage_description,
		player_character_weapon_mode.spec1_hit_bonus AS player_character_weapon_spec1_hit_bonus,
		player_character_weapon_mode.spec1_damage_bonus AS player_character_weapon_spec1_damage_bonus,
		player_character_weapon_mode.spec1_description AS player_character_weapon_spec1_description,
		player_character_weapon_mode.spec2_hit_bonus AS player_character_weapon_spec2_hit_bonus,
		player_character_weapon_mode.spec2_damage_bonus AS player_character_weapon_spec2_damage_bonus,
		player_character_weapon_mode.spec2_description AS player_character_weapon_spec2_description,
		player_character_weapon_mode.spec3_hit_bonus AS player_character_weapon_spec3_hit_bonus,
		player_character_weapon_mode.spec3_damage_bonus AS player_character_weapon_spec3_damage_bonus,
		player_character_weapon_mode.spec3_description AS player_character_weapon_spec3_description,
		player_character_weapon_mode.short_range AS player_character_weapon_short_range,
		player_character_weapon_mode.medium_range AS player_character_weapon_medium_range,
		player_character_weapon_mode.long_range AS player_character_weapon_long_range,
		player_character_weapon_mode.additional_text AS player_character_weapon_additional_text
	FROM player_character_weapon
	JOIN player_character_weapon_mode ON player_character_weapon_mode.player_character_weapon_id = player_character_weapon.id
	WHERE player_character_weapon.id = playerCharacterWeaponId;
END

CREATE PROCEDURE getPlayerCharacterWeaponList
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	SELECT
		player_character_weapon.id AS player_character_weapon_id,
		player_character_weapon_mode.weapon_type AS player_character_weapon_type,
		player_character_weapon_mode.weapon_subtype  AS player_character_weapon_subtype,
		player_character_weapon.craft_status AS player_character_weapon_craft_status,
		player_character_weapon.weapon_proficiency_id AS player_character_weapon_proficiency_id, 
		player_character_weapon.description AS player_character_weapon_description,
		player_character_weapon.is_preferred AS player_character_weapon_is_preferred,
		player_character_weapon.is_ready AS player_character_weapon_is_ready,
		player_character_weapon.location AS player_character_weapon_location,
		player_character_weapon.player_note1 AS player_character_weapon_player_note1,
		player_character_weapon.player_note2 AS player_character_weapon_player_note2,
		player_character_weapon.player_note3 AS player_character_weapon_player_note3,
		player_character_weapon.strength_bonus_available AS player_character_weapon_strength_bonus_available,
		player_character_weapon_mode.speed AS player_character_weapon_speed,
		player_character_weapon_mode.base_damage AS player_character_weapon_damage,
		player_character_weapon_mode.attacks_per_round AS player_character_weapon_attacks_per_round,
		player_character_weapon_mode.number_of_hands AS player_character_weapon_number_of_hands,
		player_character_weapon_mode.hit_bonus AS player_character_weapon_hit_bonus,
		player_character_weapon_mode.damage_bonus AS player_character_weapon_damage_bonus,
		player_character_weapon_mode.mastercraft_hit_description AS player_character_weapon_mastercraft_hit_description,
		player_character_weapon_mode.mastercraft_damage_description AS player_character_weapon_mastercraft_damage_description,
		player_character_weapon_mode.spec1_hit_bonus AS player_character_weapon_spec1_hit_bonus,
		player_character_weapon_mode.spec1_damage_bonus AS player_character_weapon_spec1_damage_bonus,
		player_character_weapon_mode.spec1_description AS player_character_weapon_spec1_description,
		player_character_weapon_mode.spec2_hit_bonus AS player_character_weapon_spec2_hit_bonus,
		player_character_weapon_mode.spec2_damage_bonus AS player_character_weapon_spec2_damage_bonus,
		player_character_weapon_mode.spec2_description AS player_character_weapon_spec2_description,
		player_character_weapon_mode.spec3_hit_bonus AS player_character_weapon_spec3_hit_bonus,
		player_character_weapon_mode.spec3_damage_bonus AS player_character_weapon_spec3_damage_bonus,
		player_character_weapon_mode.spec3_description AS player_character_weapon_spec3_description,
		player_character_weapon_mode.short_range AS player_character_weapon_short_range,
		player_character_weapon_mode.medium_range AS player_character_weapon_medium_range,
		player_character_weapon_mode.long_range AS player_character_weapon_long_range,
		player_character_weapon_mode.additional_text AS player_character_weapon_additional_text
	FROM player_character_weapon
	JOIN player_character_weapon_mode ON player_character_weapon_mode.player_character_weapon_id = player_character_weapon.id
	JOIN player_character ON player_character.id = player_character_weapon.player_character_id
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;
END

CREATE PROCEDURE getPlayerCharacterTwoWeaponConfigurations
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;

	SELECT player_character.id 
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	SELECT 
		player_character_two_weapon_fighting.id AS player_character_two_weapon_fighting_id,
		w1.description AS player_character_weapon1_description,
		w1.location AS player_character_weapon1_location,
		w2.description AS player_character_weapon2_description,
		w2.location AS player_character_weapon2_location
	FROM player_character_two_weapon_fighting
	JOIN player_character_weapon w1 ON w1.id = player_character_two_weapon_fighting.player_character_weapon1_id
	JOIN player_character_weapon w2 ON w2.id = player_character_two_weapon_fighting.player_character_weapon2_id
	WHERE player_character_two_weapon_fighting.player_character_id = playerCharacterId;
END

CREATE PROCEDURE getReadySpells
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
BEGIN
SELECT 
		spell_type.name AS spell_type, 
		player_spell_slot.slot_level AS player_slot_level, 
		player_spell_slot.spell_type_id AS player_slot_spell_type_id,
		player_spell_slot.spell_slot_type_id as player_slot_spell_slot_type_id,
		player_spell_slot.casting_time_remaining AS player_slot_casting_time_remaining,
		player_spell_slot.running_time_remaining AS player_slot_running_time_remaining,
		spell_catalog.name AS spell_name, 
		spell_catalog.link AS spell_link, 
		spell_catalog.casting_time AS spell_casting_time,
		spell_catalog.casting_time_speed AS spell_casting_time_speed,
		spell_catalog.casting_time_in_rounds AS spell_casting_time_in_rounds,
		spell_catalog.spell_range AS spell_range,
		spell_catalog.range_hex_distance AS spell_range_hex_distance,
		spell_catalog.range_distance_per_level AS spell_range_distance_per_level,
		spell_catalog.range_level_factor AS spell_range_level_factor,
		spell_catalog.range_fixed AS spell_range_fixed,
		spell_catalog.range_uom AS spell_range_uom,
		spell_catalog.duration AS spell_duration,
		spell_catalog.duration_time_per_level AS spell_duration_time_per_level,
		spell_catalog.duration_time_per_level_uom AS spell_duration_time_per_level_uom,
		spell_catalog.duration_level_factor AS spell_duration_level_factor,
		spell_catalog.duration_time_fixed AS spell_duration_time_fixed,
		spell_catalog.duration_time_fixed_uom AS spell_duration_time_fixed_uom,
		spell_catalog.area_of_effect AS spell_area_of_effect,		
		player_spell_slot.id AS spell_slot_id,
		player_spell_slot.is_cast AS has_spell_cast, 
		character_class.name AS character_class_name 
	FROM player_spell_slot
	JOIN spell_catalog on spell_catalog.id = player_spell_slot.spell_catalog_id
	JOIN spell_type ON spell_type.id = player_spell_slot.spell_type_id
	JOIN player_character_class ON player_character_class.id = player_spell_slot.player_character_class_id
	JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player_character on player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName
	ORDER BY spell_type.id, player_spell_slot.slot_level, player_spell_slot.id;
END

CREATE PROCEDURE getSessionTicketTimestamp
(IN playerName VARCHAR(32),
 IN sessionTicket CHAR(37))
BEGIN
	SELECT session_timestamp
	FROM player_cred
	JOIN player ON player.id = player_cred.player_id
	WHERE player.name = playerName AND player_cred.session_ticket = sessionTicket 
END

CREATE PROCEDURE getSkillListForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	SELECT skill_catalog_id, is_skill_focus, COUNT(skill_catalog_id) AS player_character_skill_count
	FROM player_character_skill
	JOIN player_character on player_character.id = player_character_skill.player_character_id
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName
    GROUP BY skill_catalog_id;
END

CREATE PROCEDURE getSkillsAvailableForPlayerCharacterClass
(IN playerCharacterClass INT,
 IN playerCharacterRace INT,
 IN playerCharacterLevel INT,
 IN playerCharacterIntelligence INT,
 IN playerCharacterDexterity INT,
 IN playerCharacterCharisma INT)
BEGIN
	SELECT 
		skill_catalog.id AS skill_catalog_id,
		skill_catalog.name AS skill_catalog_name,
		skill_catalog.attribute AS skill_catalog_attribute,
		skill_catalog.max_count AS skill_catalog_max_count,
		skill_catalog.skill_focus AS skill_catalog_skill_focus,
		skill_prerequisite.prerequisite_skill_id AS skill_prerequisite_skill_id
	FROM skill_catalog
	JOIN skill_prerequisite ON skill_prerequisite.skill_catalog_id = skill_catalog.id
	WHERE skill_catalog.is_active = TRUE
	AND playerCharacterCharisma >= skill_catalog.minimum_charisma
	AND playerCharacterDexterity >= skill_catalog.minimum_dexterity
	AND playerCharacterIntelligence >= skill_catalog.minimum_intelligence
	AND playerCharacterLevel >= skill_catalog.required_level
	AND (skill_catalog.required_class = 0 OR skill_catalog.required_class = playerCharacterClass)
	AND (skill_catalog.required_race = 0 OR skill_catalog.required_race = playerCharacterRace)
	ORDER BY skill_catalog.id;
END

CREATE PROCEDURE getSkillsAndRollsAvailableForPlayerCharacterClass
(IN playerCharacterClass INT,
 IN playerCharacterRace INT,
 IN playerCharacterLevel INT,
 IN playerCharacterIntelligence INT,
 IN playerCharacterDexterity INT,
 IN playerCharacterCharisma INT)
BEGIN
	SELECT 
		skill_catalog.id AS skill_catalog_id,
		skill_catalog.name AS skill_catalog_name,
		skill_catalog.attribute AS skill_catalog_attribute,
		skill_catalog.max_count AS skill_catalog_max_count,
		skill_catalog.skill_focus AS skill_catalog_skill_focus,
		skill_catalog.roll_name AS skill_catalog_roll_name,
		skill_catalog.attribute_bonus AS skill_catalog_attribute_bonus,
		skill_catalog.ability_text AS skill_catalog_ability_text,
		skill_prerequisite.prerequisite_skill_id AS skill_prerequisite_skill_id
	FROM skill_catalog
	JOIN skill_prerequisite ON skill_prerequisite.skill_catalog_id = skill_catalog.id
	WHERE skill_catalog.is_active = TRUE
	AND playerCharacterCharisma >= skill_catalog.minimum_charisma
	AND playerCharacterDexterity >= skill_catalog.minimum_dexterity
	AND playerCharacterIntelligence >= skill_catalog.minimum_intelligence
	AND playerCharacterLevel >= skill_catalog.required_level
	AND (skill_catalog.required_class = 0 OR skill_catalog.required_class = playerCharacterClass)
	AND (skill_catalog.required_race = 0 OR skill_catalog.required_race = playerCharacterRace)
	ORDER BY skill_catalog.id;
END

CREATE PROCEDURE getSkillsForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	SELECT
		player_character_skill.id AS player_character_skill_id,
		player_character_skill.skill_catalog_id AS skill_catalog_id,
		IFNULL(player_character_skill.player_character_skill_name,skill_catalog.name) AS skill_name,
		player_character_skill.is_skill_focus AS player_character_skill_is_skill_focus,
		player_character_skill.weapon_proficiency_id AS player_character_weapon_proficiency_id,
		player_character_skill.weapon2_proficiency_id AS player_character_weapon2_proficiency_id,
		is_preferred_cavalier_level3 AS player_character_cavalier_level3_preferred,
		is_preferred_cavalier_level5 AS player_character_cavalier_level5_preferred,
		is_preferred_elven_cavalier_level4 AS player_character_elven_cavalier_level4_preferred,
		is_preferred_elven_cavalier_level6 AS player_character_elven_cavalier_level6_preferred
	FROM player_character_skill
	JOIN skill_catalog ON skill_catalog.id = player_character_skill.skill_catalog_id
	JOIN player_character ON player_character.id = player_character_skill.player_character_id
	JOIN player ON player.id = player_character.player_id
	WHERE player_character.name = characterName AND player.name = playerName;
END

CREATE PROCEDURE getSkillsAndRollsForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	SELECT
		player_character_skill.id AS player_character_skill_id,
		player_character_skill.skill_catalog_id AS skill_catalog_id,
		IFNULL(player_character_skill.player_character_skill_name,skill_catalog.name) AS skill_catalog_name,
		skill_catalog.attribute AS skill_catalog_attribute,
		skill_catalog.attribute_bonus AS skill_catalog_attribute_bonus,
		skill_catalog.roll_name AS skill_catalog_roll_name,
		skill_catalog.ability_text AS skill_catalog_ability_text,
		skill_adjustment.name AS skill_adjustment_name,
		skill_adjustment.type AS skill_adjustment_type,
		skill_adjustment.bonus_adjustment AS skill_adjustment_attribute_bonus,
		player_character_skill.is_skill_focus AS player_character_skill_is_skill_focus,
		player_character_skill.weapon_proficiency_id AS player_character_weapon_proficiency_id,
		player_character_skill.weapon2_proficiency_id AS player_character_weapon2_proficiency_id
	FROM player_character_skill
	JOIN skill_catalog ON skill_catalog.id = player_character_skill.skill_catalog_id
	LEFT OUTER JOIN skill_adjustment ON skill_adjustment.skill_catalog_id = skill_catalog.id
	JOIN player_character ON player_character.id = player_character_skill.player_character_id
	JOIN player ON player.id = player_character.player_id
	WHERE player_character.name = characterName AND player.name = playerName;
END

CREATE PROCEDURE getSpellBookForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
 BEGIN
	SELECT 
		spell_type.name AS spell_type_name, 
		player_spell_pool.id AS spell_pool_id, 
		spell_catalog.id AS spell_catalog_id, 
		spell_catalog.name AS spell_name, 
		spell_catalog.link AS spell_link, 
		player_spell_pool.spell_level AS spell_level from spell_catalog
	JOIN player_spell_pool ON player_spell_pool.spell_catalog_id = spell_catalog.id
    JOIN player_character_class ON player_character_class.id = player_spell_pool.player_character_class_id
    JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
	JOIN spell_type ON spell_type.id = spell_catalog.spell_type_id
    WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName AND spell_catalog.spell_type_id IN (4,5,7,10) AND player_spell_pool.spell_level > 0
    ORDER BY player_spell_pool.spell_level, spell_catalog.name;
END

CREATE PROCEDURE getSpellBookForGreaterMage
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	DECLARE magicUserSpellType INT DEFAULT 4;
	DECLARE characterClassName VARCHAR(32) DEFAULT 'Greater Mage';

	SELECT
		player_spell_pool.id AS player_spell_pool_id,
		spell_catalog.id AS spell_catalog_id,
		spell_catalog.name AS spell_name, 
		spell_catalog.link AS spell_link,
		player_spell_pool.spell_level AS spell_level,
		spell_catalog.casting_time AS spell_casting_time,
		spell_catalog.casting_time_speed AS spell_casting_time_speed,
		spell_catalog.casting_time_in_rounds AS spell_casting_time_in_rounds,
		spell_catalog.spell_range AS spell_range,
		spell_catalog.range_hex_distance AS spell_range_hex_distance,
		spell_catalog.range_distance_per_level AS spell_range_distance_per_level,
		spell_catalog.range_level_factor AS spell_range_level_factor,
		spell_catalog.range_fixed AS spell_range_fixed,
		spell_catalog.range_uom AS spell_range_uom,
		spell_catalog.duration AS spell_duration,
		spell_catalog.duration_time_per_level AS spell_duration_time_per_level,
		spell_catalog.duration_time_per_level_uom AS spell_duration_time_per_level_uom,
		spell_catalog.duration_level_factor AS spell_duration_level_factor,
		spell_catalog.duration_time_fixed AS spell_duration_time_fixed,
		spell_catalog.duration_time_fixed_uom AS spell_duration_time_fixed_uom,
		spell_catalog.area_of_effect AS spell_area_of_effect
	FROM spell_catalog
	JOIN player_spell_pool ON player_spell_pool.spell_catalog_id = spell_catalog.id
    JOIN player_character_class ON player_character_class.id = player_spell_pool.player_character_class_id
    JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
	JOIN spell_type ON spell_type.id = spell_catalog.spell_type_id
    WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName AND spell_catalog.spell_type_id = magicUserSpellType
    ORDER BY player_spell_pool.spell_level, spell_catalog.name;
END

CREATE PROCEDURE getSpellClassesForCharacterClass
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
BEGIN
	DECLARE spellClass1 VARCHAR(32);
    DECLARE spellClass2 VARCHAR(32);

	SELECT spell_type.name
    INTO spellClass1
    FROM character_class
	JOIN spell_type ON spell_type.id = character_class.spell_type_1
    JOIN player_character_class ON player_character_class.character_class_id = character_class.id
    JOIN player_character ON player_character.id = player_character_class.player_character_id
    JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName AND character_class.NAME = characterClassName; 

	SELECT spell_type.name
    INTO spellClass2
    FROM character_class
	JOIN spell_type ON spell_type.id = character_class.spell_type_2
    JOIN player_character_class ON player_character_class.character_class_id = character_class.id
    JOIN player_character ON player_character.id = player_character_class.player_character_id
    JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName AND character_class.NAME = characterClassName; 
	
	SELECT spellClass1, spellClass2;
END

CREATE PROCEDURE getSpellPoolForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32),
 IN spellLevel INT)
BEGIN
	SELECT 
		spell_type.name AS spell_type_name, 
		player_spell_pool.id AS spell_pool_id, 
		spell_catalog.id AS spell_catalog_id, 
		spell_catalog.name AS spell_name, 
		spell_catalog.link AS spell_link, 
		player_spell_pool.spell_level AS spell_level from spell_catalog
	JOIN player_spell_pool ON player_spell_pool.spell_catalog_id = spell_catalog.id
    JOIN player_character_class ON player_character_class.id = player_spell_pool.player_character_class_id
    JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
	JOIN spell_type ON spell_type.id = spell_catalog.spell_type_id
    WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName AND player_spell_pool.spell_level = spellLevel
    ORDER BY spell_type.id, player_spell_pool.spell_level, spell_catalog.name;
END

CREATE PROCEDURE getSpellPoolByClass 
(IN className VARCHAR(32)) 
BEGIN
	SELECT spell_type.name AS spell_type, spell_pool.level AS spell_level, spell_catalog.name AS spell_name from spell_catalog
	JOIN spell_pool ON spell_pool.spell_catalog_id = spell_catalog.id
	JOIN character_class ON character_class.id = spell_pool.character_class_id
	JOIN spell_type on spell_type.id = spell_catalog.spell_type_id
	WHERE character_class.name = className
	ORDER BY spell_pool.level, spell_type.id, spell_catalog.name;
END

CREATE PROCEDURE getSpellsByType
(IN spellTypeName VARCHAR(32)) 
BEGIN
	SELECT * FROM spell_catalog
    JOIN spell_type ON spell_type.id = spell_catalog.spell_type_id
    WHERE spell_type.name = spellTypeName
    ORDER BY spell_catalog.level, spell_catalog.name;
END

CREATE PROCEDURE getSpellsForExtraSlots
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	SELECT 
		spell_catalog.name as spell_name, 
		player_spell_slot.id AS player_spell_slot_id, 
		player_spell_slot.slot_level AS player_slot_level, 
		spell_type.name AS spell_type_name,
		player_spell_slot.spell_type_id AS player_spell_slot_type_id
    FROM spell_catalog
    JOIN player_spell_slot ON player_spell_slot.spell_catalog_id = spell_catalog.id
    JOIN player_character_class ON player_character_class.id = player_spell_slot.player_character_class_id
    JOIN player_character ON player_character.id = player_character_class.player_character_id
    JOIN player ON player.id = player_character.player_id
    JOIN spell_type ON spell_type.id = player_spell_slot.spell_type_id
    WHERE player.name = playerName AND player_character.name = characterName AND player_spell_slot.spell_slot_type_id = 3
    ORDER BY spell_type.name, player_spell_slot.slot_level DESC, spell_catalog.name;
END

CREATE PROCEDURE getSpellsForExtraSlotsBySpellType
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN spellTypeId INT)
BEGIN
	SELECT 
		spell_catalog.name as spell_name, 
		player_spell_slot.id AS player_spell_slot_id, 
		player_spell_slot.slot_level AS player_slot_level, 
		spell_type.name AS spell_type_name,
		player_spell_slot.spell_type_id AS player_spell_slot_type_id
    FROM spell_catalog
    JOIN player_spell_slot ON player_spell_slot.spell_catalog_id = spell_catalog.id
    JOIN player_character_class ON player_character_class.id = player_spell_slot.player_character_class_id
    JOIN player_character ON player_character.id = player_character_class.player_character_id
    JOIN player ON player.id = player_character.player_id
    JOIN spell_type ON spell_type.id = player_spell_slot.spell_type_id
    WHERE player.name = playerName AND player_character.name = characterName AND player_spell_slot.spell_type_id = spellTypeId AND player_spell_slot.spell_slot_type_id = 3
    ORDER BY spell_type.name, player_spell_slot.slot_level DESC, spell_catalog.name;
END

CREATE PROCEDURE getWeaponDetail
(IN weaponProficiencyId INT)
BEGIN
	SELECT	
		name AS weapon_name,  
		weapon_proficiency.id AS weapon_id,
		additional_text AS weapon_additional_text,
		damage AS weapon_damage,
		long_range AS weapon_long_range,
		medium_range as weapon_medium_range,
		number_of_hands AS weapon_number_of_hands,
		short_range AS weapon_short_range,
		speed AS weapon_speed,
		subtype AS weapon_subtype,
		type AS weapon_type
	FROM weapon_proficiency
	JOIN weapon_catalog ON weapon_catalog.weapon_proficiency_id = weapon_proficiency.id
	WHERE weapon_proficiency.id = weaponProficiencyId
	ORDER BY weapon_catalog.type;
END

CREATE PROCEDURE getWeaponsByClass
(IN characterClassId INT)
BEGIN
	SELECT weapon_subtype.name AS weapon_subtype, weapon_proficiency.name AS weapon, weapon_type.name AS weapon_type, weapon_catalog.speed AS weapon_speed, weapon_catalog.damage AS weapon_damage, weapon_catalog.range_values AS weapon_range FROM weapon_proficiency
	JOIN weapon_catalog on weapon_catalog.weapon_proficiency_id = weapon_proficiency.id
	JOIN character_class_weapon_proficiency ON character_class_weapon_proficiency.weapon_proficiency_id = weapon_proficiency.id
	JOIN weapon_type ON weapon_type.id = weapon_catalog.type
	JOIN weapon_subtype on weapon_subtype.id = weapon_catalog.subtype
	WHERE character_class_weapon_proficiency.character_class_id = characterClassId
	ORDER BY weapon_subtype.name, weapon_proficiency.name, weapon_catalog.type;
END

CREATE PROCEDURE getWeaponsByClassAndSubtype
(IN characterClassId INT,
 IN weaponSubtype INT)
BEGIN
	SELECT weapon_subtype.name AS weapon_subtype, weapon_proficiency.name AS weapon, weapon_type.name AS weapon_type, weapon_catalog.speed AS weapon_speed, weapon_catalog.damage AS weapon_damage, weapon_catalog.range_values AS weapon_range FROM weapon_proficiency
	JOIN weapon_catalog on weapon_catalog.weapon_proficiency_id = weapon_proficiency.id
	JOIN character_class_weapon_proficiency ON character_class_weapon_proficiency.weapon_proficiency_id = weapon_proficiency.id
	JOIN weapon_type ON weapon_type.id = weapon_catalog.type
	JOIN weapon_subtype on weapon_subtype.id = weapon_catalog.subtype
	WHERE character_class_weapon_proficiency.character_class_id = characterClassId AND weapon_catalog.subtype = weaponSubtype
	ORDER BY weapon_proficiency.name, weapon_catalog.type;
END

CREATE PROCEDURE getWeaponProficiencyByPattern
(IN weaponPatternName VARCHAR(32))
BEGIN

	DECLARE fistWeaponProficiencyId INT DEFAULT 118;
    
	SELECT id
	INTO fistWeaponProficiencyId
	FROM weapon_proficiency 
	WHERE name = 'Fist';

	SELECT 
		name AS weapon_proficiency_name, 
		id AS weapon_proficiency_id 
	FROM weapon_proficiency 
	WHERE 
		name LIKE CONCAT('%', weaponPatternName, '%') AND
		id <> fistWeaponProficiencyId;
END

CREATE PROCEDURE getWeaponProficienciesAvailableForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN weaponPatternName VARCHAR(32))
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	DECLARE skillCatalogWeaponProficiency INT DEFAULT 179;

	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	SELECT id
	INTO skillCatalogWeaponProficiency
	FROM skill_catalog
	WHERE name = 'Weapon Proficiency';

	SELECT 
		weapon_proficiency.id AS weapon_proficiency_id, 
		weapon_proficiency.name AS weapon_proficiency_name
	FROM weapon_proficiency
	WHERE 
		name LIKE CONCAT('%', weaponPatternName, '%') AND
		weapon_proficiency.id NOT IN 
		(
			SELECT player_character_skill.weapon_proficiency_id
			FROM player_character_skill
			WHERE 
				player_character_skill.player_character_id = playerCharacterId AND
				player_character_skill.skill_catalog_id = skillCatalogWeaponProficiency
		);
END

CREATE PROCEDURE getWeaponProficienciesForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	DECLARE skillCatalogWeaponProficiency INT DEFAULT 179;

	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	SELECT id
	INTO skillCatalogWeaponProficiency
	FROM skill_catalog
	WHERE name = 'Weapon Proficiency';

	SELECT
		player_character_skill.id AS player_weapon_proficiency_id,
		weapon_proficiency.id AS weapon_proficiency_id,
		weapon_proficiency.name AS weapon_proficiency_description,
		is_preferred_cavalier_level3,
		is_preferred_cavalier_level5,
		is_preferred_elven_cavalier_level4,
		is_preferred_elven_cavalier_level6
	FROM player_character_skill
	JOIN weapon_proficiency ON weapon_proficiency.id = player_character_skill.weapon_proficiency_id
	WHERE
		player_character_skill.skill_catalog_id = skillCatalogWeaponProficiency AND
		player_character_skill.player_character_id = playerCharacterId;
END

CREATE PROCEDURE getWeaponProficienciesOneHandedForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	DECLARE weaponProficiencySkillId INT DEFAULT 179;
    DECLARE fistWeaponProficiencyId INT DEFAULT 118;

	SELECT id
	INTO weaponProficiencySkillId
	FROM skill_catalog
	WHERE name = 'Weapon Proficiency';
	

	SELECT id
	INTO fistWeaponProficiencyId
	FROM weapon_proficiency
	WHERE name = 'Fist';
    
SELECT 
	weapon_proficiency.name AS weapon_proficiency_name,
    weapon_proficiency.id AS weapon_proficiency_id
FROM weapon_proficiency
JOIN weapon_catalog ON weapon_catalog.weapon_proficiency_id = weapon_proficiency.id
WHERE weapon_catalog.number_of_hands = 1 AND weapon_catalog.type = 1 AND weapon_proficiency.id IN
(
	SELECT 
    	player_character_skill.weapon_proficiency_id
    FROM player_character_skill
    JOIN player_character ON player_character.id = player_character_skill.player_character_id
    JOIN player ON player.id = player_character.player_id
    WHERE 
        player.name = playerName AND 
        player_character.name = characterName AND 
        player_character_skill.skill_catalog_id = weaponProficiencySkillId AND
        player_character_skill.weapon_proficiency_id <> fistWeaponProficiencyId
);
END

CREATE PROCEDURE getWeaponProficiencyForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN weaponProficiencyId INT)
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	DECLARE playerCharacterSkillId INT DEFAULT 0;
	DECLARE skillCatalogWeaponProficiency INT DEFAULT 179;
	
	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	SELECT id
	INTO skillCatalogWeaponProficiency
	FROM skill_catalog
	WHERE name = 'Weapon Proficiency';

	SELECT id
	INTO playerCharacterSkillId
	FROM player_character_skill
	WHERE player_character_id = playerCharacterID AND skill_catalog_id = skillCatalogWeaponProficiency AND weapon_proficiency_id = weaponProficiencyId;

	SELECT playerCharacterSkillId;
END

CREATE PROCEDURE getWeaponProficiencyName
(IN weaponProficiencyId INT)
BEGIN
	SELECT name AS weapon_proficiency_name
	FROM weapon_proficiency 
	WHERE id = weaponProficiencyId;
END

CREATE PROCEDURE getWeaponProficiencyNameFromPlayerCharacterSkillId
(IN playerCharacterWeaponSkillId INT)
BEGIN
	SELECT 
		weapon_proficiency.name AS weapon_proficiency_name,
		weapon_proficiency.id AS weapon_proficiency_id 
	FROM weapon_proficiency
    JOIN player_character_skill ON player_character_skill.weapon_proficiency_id = weapon_proficiency.id
	WHERE player_character_skill.id = playerCharacterWeaponSkillId;
END

CREATE PROCEDURE getWeaponSubtypes
(IN characterClassId INT)
BEGIN
	SELECT DISTINCT(weapon_subtype.name) AS weapon_subtype FROM weapon_proficiency
	JOIN weapon_catalog on weapon_catalog.weapon_proficiency_id = weapon_proficiency.id
	JOIN character_class_weapon_proficiency ON character_class_weapon_proficiency.weapon_proficiency_id = weapon_proficiency.id
	JOIN weapon_subtype on weapon_subtype.id = weapon_catalog.subtype
	WHERE character_class_weapon_proficiency.character_class_id = characterClassId
	ORDER BY weapon_subtype.name;
END

CREATE PROCEDURE getWeaponSummaryForPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	
	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	SELECT
		id AS player_character_weapon_id,
		weapon_proficiency_id,
		description AS weapon_description, 
		location AS weapon_location, 
		craft_status AS weapon_craft_status
	FROM player_character_weapon
	WHERE player_character_weapon.player_character_id = playerCharacterId
	ORDER BY is_ready, description;
END

CREATE PROCEDURE getWeaponsForPlayerCharacterByProficiency
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN weaponProficiencyId INT)
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	DECLARE longSwordProficiencyId INT DEFAULT 117;
	DECLARE shortSwordProficiencyId INT DEFAULT 100;
	DECLARE twoHandedSwordProficiencyId INT DEFAULT 101;
	DECLARE associatedWeaponProficiency INT DEFAULT 0;

	SELECT weapon_proficiency.id
	INTO longSwordProficiencyId
	FROM weapon_proficiency
	WHERE weapon_proficiency.name = 'Long Sword';

	SELECT weapon_proficiency.id
	INTO shortSwordProficiencyId
	FROM weapon_proficiency
	WHERE weapon_proficiency.name = 'Short Sword';

	SELECT weapon_proficiency.id
	INTO twoHandedSwordProficiencyId
	FROM weapon_proficiency
	WHERE weapon_proficiency.name = 'Two-Handed Sword';

	-- Assume no 'associated' weapon proficiency
	SET associatedWeaponProficiency = weaponProficiencyId;

	-- Check for Long Sword
	IF weaponProficiencyId = longSwordProficiencyId THEN
		SELECT weapon_proficiency.id
		INTO associatedWeaponProficiency
		FROM weapon_proficiency
		WHERE weapon_proficiency.name = 'Elven Thin Blade';
	END IF;

	-- Check for Short Sword
	IF weaponProficiencyId = shortSwordProficiencyId THEN
		SELECT weapon_proficiency.id
		INTO associatedWeaponProficiency
		FROM weapon_proficiency
		WHERE weapon_proficiency.name = 'Elven Lightblade';
	END IF;

	-- Check for Two-Handed Sword
	IF weaponProficiencyId = twoHandedSwordProficiencyId THEN
		SELECT weapon_proficiency.id
		INTO associatedWeaponProficiency
		FROM weapon_proficiency
		WHERE weapon_proficiency.name = 'Elven Court Blade';
	END IF;
	
	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	SELECT 
		player_character_weapon.id AS player_character_weapon_id,
		player_character_weapon.description AS player_character_weapon_description,
		player_character_weapon.location AS player_character_weapon_location,
		player_character_weapon.craft_status AS player_character_weapon_craft_status
	FROM player_character_weapon 
	WHERE 
		player_character_weapon.player_character_id = playerCharacterId AND
		(player_character_weapon.weapon_proficiency_id = weaponProficiencyId OR
		 player_character_weapon.weapon_proficiency_id = associatedWeaponProficiency);
END

CREATE PROCEDURE getUnallocatedSpellsForSpellBook
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32),
 IN spellLevel INT)
BEGIN
SELECT 
		spell_catalog.id AS spell_catalog_id, 
		spell_catalog.name AS spell_name, 
		spell_catalog.link AS spell_link, 
		spell_pool.level AS spell_level from spell_catalog
	JOIN spell_pool ON spell_pool.spell_catalog_id = spell_catalog.id
    JOIN player_character_class ON player_character_class.character_class_id = spell_pool.character_class_id
    JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player ON player.id = player_character.player_id
    WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName AND spell_pool.level = spellLevel AND spell_catalog.spell_type_id IN (4,5,10)
    AND spell_catalog.id NOT IN(
        SELECT 
            spell_catalog.id AS spell_catalog_id from spell_catalog
        JOIN player_spell_pool ON player_spell_pool.spell_catalog_id = spell_catalog.id
        JOIN player_character_class ON player_character_class.id = player_spell_pool.player_character_class_id
        JOIN character_class ON character_class.id = player_character_class.character_class_id
        JOIN player_character ON player_character.id = player_character_class.player_character_id
        JOIN player ON player.id = player_character.player_id
        JOIN spell_type ON spell_type.id = spell_catalog.spell_type_id
        WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName AND player_spell_pool.spell_level = spellLevel AND spell_catalog.spell_type_id IN (4,5,10)
    )
    ORDER BY spell_catalog.name;
END

CREATE PROCEDURE promoteCharacterClass
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
BEGIN
    
    DECLARE currentCharacterLevel INT DEFAULT 0;
    DECLARE newCharacterLevel INT DEFAULT 0;
    DECLARE playerCharacterClassId INT DEFAULT 0;
    
    SELECT player_character_class.character_level, player_character_class.id
    INTO currentCharacterLevel, playerCharacterClassId
	FROM player_character_class
		JOIN player_character ON player_character.id = player_character_class.player_character_id
		JOIN player ON player.id = player_character.player_id
		JOIN character_class ON character_class.id = player_character_class.character_class_id
		WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName;
    
	SET newCharacterLevel = currentCharacterLevel + 1;

	START TRANSACTION;
		UPDATE player_character_class SET character_level = newCharacterLevel
		WHERE id = playerCharacterClassId;
	COMMIT;
	
	SELECT characterName, playerCharacterClassId, characterClassName, newCharacterLevel as characterLevel FROM player_character_class
	WHERE ID = playerCharacterClassId;
END

CREATE PROCEDURE populateCantripsForPlayerCharacterClass
(IN playerCharacterClassId INT,
 IN spellTypeId INT)
BEGIN
	INSERT INTO player_spell_pool (player_character_class_id, spell_catalog_id, spell_level)
	SELECT playerCharacterClassId, spell_catalog.id, 0 FROM spell_pool
	JOIN spell_catalog ON spell_catalog.id = spell_pool.spell_catalog_id
	JOIN character_class ON character_class.id = spell_pool.character_class_id
    JOIN player_character_class ON player_character_class.character_class_id = character_class.id
	JOIN spell_type ON spell_type.id = spell_catalog.spell_type_id
	WHERE spell_pool.level = 0 AND spell_catalog.spell_type_id = spellTypeId AND player_character_class.id = playerCharacterClassId;
END

CREATE PROCEDURE populateClericSpells
(IN playerCharacterClassId INT,
 IN playerRace INT,
 IN spellLevel INT)
BEGIN
	INSERT INTO player_spell_pool (player_character_class_id, spell_catalog_id, spell_level)
	SELECT playerCharacterClassId, spell_pool.spell_catalog_id, spellLevel FROM spell_pool
	JOIN player_character_class on player_character_class.character_class_id = spell_pool.character_class_id
    JOIN spell_catalog ON spell_catalog.id = spell_pool.spell_catalog_id
	WHERE player_character_class.id = playerCharacterClassId AND spell_pool.level = spellLevel
    AND spell_catalog.spell_type_id IN (1, 2, 3, 9)
	AND spell_catalog.racial_restriction IN (1, playerRace);
END

CREATE PROCEDURE resetDailyForSlots()
BEGIN
	DECLARE extraSlotTypeGM INT DEFAULT 4;

	-- Reset any 'standard' slots 
	UPDATE player_spell_slot 
		SET	is_cast = FALSE,
			running_time_remaining = 0,
			casting_time_remaining = 0;

	-- Remove any GM running/casting GM spells
	DELETE FROM player_spell_slot WHERE spell_slot_type_id = extraSlotTypeGM;
	
END

CREATE PROCEDURE resetPlayerCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64))
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	DECLARE fistWeaponProficiencyId INT DEFAULT 118;
	
	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	CREATE TEMPORARY TABLE ids( id INT );
	CREATE TEMPORARY TABLE playerCharacterWeaponModeIds(id INT);
	
	INSERT INTO ids (id) 
	SELECT player_character_class.id FROM player_character_class
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN player on player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	INSERT INTO playerCharacterWeaponModeIds (id)
	SELECT player_character_weapon_mode.id FROM player_character_weapon_mode
	JOIN player_character_weapon ON player_character_weapon.id = player_character_weapon_mode.player_character_weapon_id
	WHERE player_character_weapon.player_character_id =  playerCharacterId;
	
	START TRANSACTION;
	DELETE FROM player_spell_pool WHERE player_spell_pool.player_character_class_id IN (SELECT id FROM ids);
	DELETE FROM player_spell_slot WHERE player_spell_slot.player_character_class_id IN (SELECT id FROM ids);

	DELETE FROM player_character_skill  WHERE player_character_skill.player_character_id  = playerCharacterId;
	DELETE FROM player_character_weapon WHERE player_character_weapon.player_character_id = playerCharacterId;
	DELETE FROM player_character_weapon_mode WHERE player_character_weapon_mode.id IN (SELECT id FROM playerCharacterWeaponModeIds);
	DELETE FROM player_character_two_weapon_fighting WHERE player_character_two_weapon_fighting.player_character_id = playerCharacterId;
	UPDATE player_character_class SET character_level = 0 WHERE player_character_class.id IN (SELECT id FROM ids);

	-- Reinsert FIST proficiency
	SELECT id
	INTO fistWeaponProficiencyId
	FROM weapon_proficiency
	WHERE name = 'Fist';

	CALL addWeaponProficiency(playerName, characterName, fistWeaponProficiencyId);
	COMMIT;
	
	DROP TEMPORARY TABLE ids;
	DROP TEMPORARY TABLE playerCharacterWeaponModeIds;
END

CREATE PROCEDURE resetPlayerCharacterClass
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
BEGIN
    DECLARE playerCharacterClassId INT DEFAULT 0;
	
	SELECT player_character_class.id
	INTO playerCharacterClassId
	FROM player_character_class
	JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName;
	
	START TRANSACTION;
	UPDATE player_character_class SET character_level = 0 WHERE player_character_class.id = playerCharacterClassId;
	DELETE FROM player_spell_pool WHERE player_spell_pool.player_character_class_id = playerCharacterClassId;
	DELETE FROM player_spell_slot WHERE player_spell_slot.player_character_class_id = playerCharacterClassId;
	COMMIT;
END

CREATE PROCEDURE resetSlots
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32))
BEGIN
UPDATE player_spell_slot SET is_cast = FALSE 
WHERE player_character_class_id = 
	(
        SELECT player_character_class.id FROM player_character_class
		JOIN player_character ON player_character.id = player_character_class.player_character_id
		JOIN player ON player.id = player_character.player_id
		JOIN character_class ON character_class.id = player_character_class.character_class_id
		WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName
    );
END

CREATE PROCEDURE setSlotCastStatus
(IN spellSlotId INT,
 IN castStatus BOOLEAN)
BEGIN
	UPDATE player_spell_slot SET is_cast = castStatus WHERE id = spellSlotId;
END

CREATE PROCEDURE setSlotCastTimeAndDuration
(IN spellSlotId INT,
 IN castingTime INT,
 IN durationTime INT)
BEGIN
	UPDATE player_spell_slot 
		SET	casting_time_remaining = castingTime,
			running_time_remaining = durationTime
	WHERE id = spellSlotId;
END

CREATE PROCEDURE updateBaseCharacter
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterStrength INT,
 IN characterSuperStrength INT,
 IN characterIntelligence INT,
 IN characterSuperIntelligence INT,
 IN characterWisdom INT,
 IN characterSuperWisdom INT,
 IN characterDexterity INT,
 IN characterSuperDexterity INT,
 IN characterConstitution INT,
 IN characterSuperConstitution INT,
 IN characterCharisma INT,
 IN characterComeliness INT,
 IN raceId INT,
 IN armorClass INT,
 IN armorBulkFactor INT,
 IN hitPoints INT,
 IN genderIn CHAR(1))
BEGIN
	DECLARE playerId INT DEFAULT 0;
	
	SELECT id
	INTO playerId
	FROM player
	WHERE player.name = playerName;
	
	UPDATE player_character
		SET strength = characterStrength, super_strength = characterSuperStrength, intelligence = characterIntelligence, super_intelligence = characterSuperIntelligence, 
            wisdom = characterWisdom, super_wisdom = characterSuperWisdom, dexterity = characterDexterity, super_dexterity = characterSuperDexterity,
			constitution = characterConstitution, super_constitution = characterSuperConstitution, charisma = characterCharisma, comeliness = characterComeliness, 
			race_id = raceId, armor_class = armorClass, armor_bulk_factor = armorBulkFactor, hit_points = hitPoints, gender = genderIn
	WHERE player_id = playerId AND name = characterName; 
END

CREATE PROCEDURE updateCharacterPortrait
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN portraitFileLocation VARCHAR(255))
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	
	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;
	
	UPDATE player_character set portrait = portraitFileLocation WHERE id = playerCharacterId;
END

CREATE PROCEDURE updateForEndOfRoundForSlots()
BEGIN
	DECLARE extraSlotTypeGM INT DEFAULT 4;

	START TRANSACTION;
		UPDATE player_spell_slot SET running_time_remaining = running_time_remaining - 1
		WHERE casting_time_remaining <= 0 AND running_time_remaining > 0;
		
		UPDATE player_spell_slot SET casting_time_remaining = casting_time_remaining - 1
		WHERE casting_time_remaining > 0;

		DELETE FROM player_spell_slot 
		WHERE 
			spell_slot_type_id = extraSlotTypeGM AND
			running_time_remaining <= 0 AND 
			casting_time_remaining <= 0;
	COMMIT;
END

CREATE PROCEDURE updateOptionalCharacterData
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN movementIn INT,
 IN alignmentIn VARCHAR(32),
 IN religionIn  VARCHAR(32),
 IN deityIn VARCHAR(32),
 IN hometownIn  VARCHAR(32),
 IN hit_dieIn VARCHAR(32),
 IN ageIn INT,
 IN apparent_ageIn INT,
 IN unnatural_ageIn VARCHAR(32),
 IN social_classIn VARCHAR(32),
 IN heightIn VARCHAR(32),
 IN weightIn VARCHAR(32),
 IN hairIn VARCHAR(32),
 IN eyesIn VARCHAR(32),
 IN siblingsIn INT,
 IN parents_marriedIn BOOLEAN)
BEGIN
	DECLARE playerId INT DEFAULT 0;
	
	SELECT id
	INTO playerId
	FROM player
	WHERE player.name = playerName;
	
	UPDATE player_character
		SET	movement = movementIn,
			alignment = alignmentIn,
			religion = religionIn,
			deity = deityIn,
			hometown = hometownIn,
			hit_die = hit_dieIn,
			age = ageIn,
			apparent_age = apparent_ageIn,
			unnatural_age = unnatural_ageIn,
			social_class = social_classIn,
			height = heightIn,
			weight = weightIn,
			hair = hairIn,
			eyes = eyesIn,
			siblings = siblingsIn,
			parents_married = parents_marriedIn
	WHERE player_id = playerId AND name = characterName;
END

CREATE PROCEDURE updatePlayerCharacterSpellPoints
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN numPoints INT)
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;

	SELECT player_character.id
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;

	UPDATE player_character SET spell_points = numPoints WHERE id = playerCharacterId;
END

CREATE PROCEDURE updateSpellPoolSlot
(IN spellCatalogId INT,
 IN spellPoolSlotId INT)
BEGIN
	UPDATE player_spell_pool SET spell_catalog_id = spellCatalogId WHERE id = spellPoolSlotId;
END

CREATE PROCEDURE updateReadySpellSlot
(IN spellCatalogId INT,
 IN spellSlotId INT)
BEGIN
	UPDATE player_spell_slot 
	SET 
		spell_catalog_id = spellCatalogId,
		is_cast = false
		WHERE id = spellSlotId;
END

CREATE PROCEDURE updateXPForPlayerCharacterClass
(IN playerName VARCHAR(32),
 IN characterName VARCHAR(64),
 IN characterClassName VARCHAR(32),
 IN numXP INT)
BEGIN

	DECLARE playerCharacterClassId INT DEFAULT 0;
	
	SELECT player_character_class.id 
	INTO playerCharacterClassId
    FROM player_character_class
    JOIN player_character ON player_character.id = player_character_class.player_character_id
	JOIN character_class ON character_class.id = player_character_class.character_class_id
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName AND character_class.name = characterClassName;
	
	UPDATE player_character_class SET number_of_experience_points = numXP WHERE id = playerCharacterClassId;
END

CREATE PROCEDURE updateWeaponForPlayerCharacter
(IN weaponId INT,
 IN weaponDescription VARCHAR(32),
 IN weaponLocation VARCHAR(32),
 IN isReady BOOLEAN,
 IN craftStatus INT,
 IN strengthBonusAvailable BOOLEAN,
 IN playerNote1 VARCHAR(32),
 IN playerNote2 VARCHAR(32),
 IN playerNote3 VARCHAR(32),
 IN mastercraftHitDescription VARCHAR(32),
 IN mastercraftDamageDescription VARCHAR(32),
 IN meleeWeaponSpeed VARCHAR(32),
 IN meleeWeaponDamage VARCHAR(32),
 IN meleeAttacksPerRound VARCHAR(32),
 IN meleeNumberOfHands VARCHAR(32),
 IN meleeAdditionalText VARCHAR(255),
 IN meleeHitBonus INT,
 IN meleeDamageBonus INT,
 IN meleeSpec1HitBonus INT,
 IN meleeSpec1DamageBonus INT,
 IN meleeSpec1Description VARCHAR(32),
 IN meleeSpec2HitBonus INT,
 IN meleeSpec2DamageBonus INT,
 IN meleeSpec2Description VARCHAR(32),
 IN meleeSpec3HitBonus INT,
 IN meleeSpec3DamageBonus INT,
 IN meleeSpec3Description VARCHAR(32),
 IN missileWeaponSpeed VARCHAR(32),
 IN missileWeaponDamage VARCHAR(32),
 IN missileAttacksPerRound VARCHAR(32),
 IN missileAdditionalText VARCHAR(255),
 IN missileHitBonus INT,
 IN missileDamageBonus INT,
 IN missileSpec1HitBonus INT,
 IN missileSpec1DamageBonus INT,
 IN missileSpec1Description VARCHAR(32),
 IN missileSpec2HitBonus INT,
 IN missileSpec2DamageBonus INT,
 IN missileSpec2Description VARCHAR(32),
 IN missileSpec3HitBonus INT,
 IN missileSpec3DamageBonus INT,
 IN missileSpec3Description VARCHAR(32),
 IN missileShortRange VARCHAR(32),
 IN missileMediumRange VARCHAR(32),
 IN missileLongRange VARCHAR(32))
BEGIN
	DECLARE weaponTypeMelee INT DEFAULT 1;
	DECLARE weaponTypeMissile INT DEFAULT 2;

	SELECT id
	INTO weaponTypeMelee
	FROM weapon_type
	WHERE name = 'Melee';

	SELECT id
	INTO weaponTypeMissile
	FROM weapon_type
	WHERE name = 'Missile';

	START TRANSACTION;

		UPDATE player_character_weapon 
			SET description = weaponDescription,
				location = weaponLocation,
				is_ready = isReady,
				craft_status = craftStatus,
				strength_bonus_available = strengthBonusAvailable,
				player_note1 = playerNote1,
				player_note2 = playerNote2,
				player_note3 = playerNote3
			WHERE player_character_weapon.id = weaponId;

		IF EXISTS (SELECT 1 FROM player_character_weapon_mode WHERE player_character_weapon_mode.player_character_weapon_id = weaponId AND player_character_weapon_mode.weapon_type = weaponTypeMelee) THEN
			UPDATE player_character_weapon_mode
				SET	speed = meleeWeaponSpeed,
					base_damage = meleeWeaponDamage,
					attacks_per_round = meleeAttacksPerRound,
					number_of_hands = meleeNumberOfHands,
					additional_text = meleeAdditionalText,
					damage_bonus = meleeDamageBonus,
					hit_bonus = meleeHitBonus,
					mastercraft_damage_description = mastercraftDamageDescription,
					mastercraft_hit_description = mastercraftHitDescription,
					spec1_damage_bonus = meleeSpec1DamageBonus,
					spec1_description = meleeSpec1Description,
					spec1_hit_bonus = meleeSpec1HitBonus,
					spec2_damage_bonus = meleeSpec2DamageBonus,
					spec2_description = meleeSpec2Description,
					spec2_hit_bonus = meleeSpec2HitBonus,
					spec3_damage_bonus = meleeSpec3DamageBonus,
					spec3_description = meleeSpec3Description,
					spec3_hit_bonus = meleeSpec3HitBonus
				WHERE player_character_weapon_mode.player_character_weapon_id = weaponId AND player_character_weapon_mode.weapon_type = weaponTypeMelee;
		END IF;

		IF EXISTS (SELECT 1 FROM player_character_weapon_mode WHERE player_character_weapon_mode.player_character_weapon_id = weaponId AND player_character_weapon_mode.weapon_type = weaponTypeMissile) THEN
			UPDATE player_character_weapon_mode
				SET	speed = missileWeaponSpeed,
					base_damage = missileWeaponDamage,
					attacks_per_round = missileAttacksPerRound,
					additional_text = missileAdditionalText,
					damage_bonus = missileDamageBonus,
					hit_bonus = missileHitBonus,
					mastercraft_damage_description = mastercraftDamageDescription,
					mastercraft_hit_description = mastercraftHitDescription,
					spec1_damage_bonus = missileSpec1DamageBonus,
					spec1_description = missileSpec1Description,
					spec1_hit_bonus = missileSpec1HitBonus,
					spec2_damage_bonus = missileSpec2DamageBonus,
					spec2_description = missileSpec2Description,
					spec2_hit_bonus = missileSpec2HitBonus,
					spec3_damage_bonus = missileSpec3DamageBonus,
					spec3_description = missileSpec3Description,
					spec3_hit_bonus = missileSpec3HitBonus,
					short_range = missileShortRange,
					medium_range = missileMediumRange,
					long_range = missileLongRange
				WHERE player_character_weapon_mode.player_character_weapon_id = weaponId AND player_character_weapon_mode.weapon_type = weaponTypeMissile;
		END IF;

	COMMIT;
END

CREATE PROCEDURE updateWeaponToPlayerCharacter
(IN playerName VARCHAR(32),
    characterName VARCHAR(64),
	weaponCatalogId INT,
	playerWeaponProficiencyId INT,
	weaponDescription VARCHAR(32),
	isReady BOOLEAN,
	craftStatus INT,
	hitBonus INT,
	damageBonus INT,
	weaponSpeed VARCHAR(32),
	weaponRange VARCHAR(32),
	weaponDamage VARCHAR(32),
	attacksPerRound VARCHAR(32),
	playerNote1 VARCHAR(32),
	playerNote2 VARCHAR(32),
	playerNote3 VARCHAR(32)
)
BEGIN
	DECLARE playerCharacterId INT DEFAULT 0;
	
	SELECT player_character.id 
	INTO playerCharacterId
	FROM player_character
	JOIN player ON player.id = player_character.player_id
	WHERE player.name = playerName AND player_character.name = characterName;
	
	UPDATE player_character_weapon
		SET description = weaponDescription, is_ready = isReady, craft_status = craftStatus, 
		hit_bonus = hitBonus, damage_bonus = damageBonus, speed = weaponSpeed, weapon_range = weaponRange, damage = weaponDamage, 
		attacks_per_round = attacksPerRound, player_note_1 = playerNote1, player_note_2 = playerNote2, player_note_3 = playerNote3
	WHERE player_character_id = playerCharacterId;
END
