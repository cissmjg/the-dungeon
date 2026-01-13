<?php

require_once __DIR__ . '/../dbio/constants/weaponType.php';

class PlayerCharacterWeapon implements JsonSerializable {

    private $weaponId;
    private $weaponProficiencyId;
    private $weaponDescription;
    private $weaponLocation;
    private $isReady;
    private $isPreferred;
    private $craftStatus;
    private $strengthBonusAvailable;
    private $playerNote1;
    private $playerNote2;
    private $playerNote3;
    private $mastercraftHitDescription;
    private $mastercraftDamageDescription;
    private $meleeWeaponType;
    private $meleeWeaponSubtype;
    private $meleeWeaponSpeed;
    private $meleeWeaponDamage;
    private $meleeAttacksPerRound;
    private $meleeNumberOfHands;
    private $meleeAdditionalText;
    private $meleeHitBonus;
    private $meleeDamageBonus;
    private $meleeSpec1HitBonus;
    private $meleeSpec1DamageBonus;
    private $meleeSpec1Description;
    private $meleeSpec2HitBonus;
    private $meleeSpec2DamageBonus;
    private $meleeSpec2Description;
    private $meleeSpec3HitBonus;
    private $meleeSpec3DamageBonus;
    private $meleeSpec3Description;
    private $missileWeaponType;
    private $missileWeaponSubtype;
    private $missileWeaponSpeed;
    private $missileWeaponDamage;
    private $missileAttacksPerRound;
    private $missileAdditionalText;
    private $missileHitBonus;
    private $missileDamageBonus;
    private $missileSpec1HitBonus;
    private $missileSpec1DamageBonus;
    private $missileSpec1Description;
    private $missileSpec2HitBonus;
    private $missileSpec2DamageBonus;
    private $missileSpec2Description;
    private $missileSpec3HitBonus;
    private $missileSpec3DamageBonus;
    private $missileSpec3Description;
    private $missileShortRange;
    private $missileMediumRange;
    private $missileLongRange;

    public function init(\PDO $pdo, $player_character_weapon_id, &$errors) {

        $weapon_details = $this->getPlayerCharacterWeapon($pdo, $player_character_weapon_id, $errors);
        if(count($errors) > 0) {
            return;
        }

        foreach($weapon_details AS $weapon_detail) {
            if ($weapon_detail['player_character_weapon_type'] == WEAPON_TYPE_MELEE) {
                $this->meleeWeaponType              = $weapon_detail['player_character_weapon_type'];
                $this->meleeWeaponSubtype           = $weapon_detail['player_character_weapon_subtype'];
                $this->weaponId                     = $weapon_detail['player_character_weapon_id'];
                $this->weaponProficiencyId          = $weapon_detail['player_character_weapon_proficiency_id'];
                $this->craftStatus                  = $weapon_detail['player_character_weapon_craft_status'];
                $this->weaponDescription            = $weapon_detail['player_character_weapon_description'];
                $this->isPreferred                  = $weapon_detail['player_character_weapon_is_preferred'];
                $this->isReady                      = $weapon_detail['player_character_weapon_is_ready'];
                $this->weaponLocation               = $weapon_detail['player_character_weapon_location'];
                $this->playerNote1                  = $weapon_detail['player_character_weapon_player_note1'];
                $this->playerNote2                  = $weapon_detail['player_character_weapon_player_note2'];
                $this->playerNote3                  = $weapon_detail['player_character_weapon_player_note3'];
                $this->strengthBonusAvailable       = $weapon_detail['player_character_weapon_strength_bonus_available'];
                $this->meleeWeaponSpeed             = $weapon_detail['player_character_weapon_speed'];
                $this->meleeWeaponDamage            = $weapon_detail['player_character_weapon_damage'];
                $this->meleeAttacksPerRound         = $weapon_detail['player_character_weapon_attacks_per_round'];
                $this->meleeNumberOfHands           = $weapon_detail['player_character_weapon_number_of_hands'];
                $this->meleeHitBonus                = $weapon_detail['player_character_weapon_hit_bonus'];
                $this->meleeDamageBonus             = $weapon_detail['player_character_weapon_damage_bonus'];
                $this->mastercraftHitDescription    = $weapon_detail['player_character_weapon_mastercraft_hit_description'];
                $this->mastercraftDamageDescription = $weapon_detail['player_character_weapon_mastercraft_damage_description'];
                $this->meleeSpec1HitBonus           = $weapon_detail['player_character_weapon_spec1_hit_bonus'];
                $this->meleeSpec1DamageBonus        = $weapon_detail['player_character_weapon_spec1_damage_bonus'];
                $this->meleeSpec1Description        = $weapon_detail['player_character_weapon_spec1_description'];
                $this->meleeSpec2HitBonus           = $weapon_detail['player_character_weapon_spec2_hit_bonus'];
                $this->meleeSpec2DamageBonus        = $weapon_detail['player_character_weapon_spec2_damage_bonus'];
                $this->meleeSpec2Description        = $weapon_detail['player_character_weapon_spec2_description'];
                $this->meleeSpec3HitBonus           = $weapon_detail['player_character_weapon_spec3_hit_bonus'];
                $this->meleeSpec3DamageBonus        = $weapon_detail['player_character_weapon_spec3_damage_bonus'];
                $this->meleeSpec3Description      = $weapon_detail['player_character_weapon_spec3_description'];
                $this->meleeAdditionalText          = $weapon_detail['player_character_weapon_short_range'];
            }

            if ($weapon_detail['player_character_weapon_type'] == WEAPON_TYPE_MISSILE) {
                $this->missileWeaponType            = $weapon_detail['player_character_weapon_type'];
                $this->missileWeaponSubtype         = $weapon_detail['player_character_weapon_subtype'];
                $this->weaponId                     = $weapon_detail['player_character_weapon_id'];
                $this->weaponProficiencyId          = $weapon_detail['player_character_weapon_proficiency_id'];
                $this->craftStatus                  = $weapon_detail['player_character_weapon_craft_status'];
                $this->weaponDescription            = $weapon_detail['player_character_weapon_description'];
                $this->isPreferred                  = $weapon_detail['player_character_weapon_is_preferred'];
                $this->isReady                      = $weapon_detail['player_character_weapon_is_ready'];
                $this->weaponLocation               = $weapon_detail['player_character_weapon_location'];
                $this->playerNote1                  = $weapon_detail['player_character_weapon_player_note1'];
                $this->playerNote2                  = $weapon_detail['player_character_weapon_player_note2'];
                $this->playerNote3                  = $weapon_detail['player_character_weapon_player_note3'];
                $this->strengthBonusAvailable       = $weapon_detail['player_character_weapon_strength_bonus_available'];
                $this->missileWeaponSpeed           = $weapon_detail['player_character_weapon_speed'];
                $this->missileWeaponDamage          = $weapon_detail['player_character_weapon_damage'];
                $this->missileAttacksPerRound       = $weapon_detail['player_character_weapon_attacks_per_round'];
                $this->missileNumberOfHands         = $weapon_detail['player_character_weapon_number_of_hands'];
                $this->missileHitBonus              = $weapon_detail['player_character_weapon_hit_bonus'];
                $this->missileDamageBonus           = $weapon_detail['player_character_weapon_damage_bonus'];
                $this->mastercraftHitDescription    = $weapon_detail['player_character_weapon_mastercraft_hit_description'];
                $this->mastercraftDamageDescription = $weapon_detail['player_character_weapon_mastercraft_damage_description'];
                $this->missileSpec1HitBonus         = $weapon_detail['player_character_weapon_spec1_hit_bonus'];
                $this->missileSpec1DamageBonus      = $weapon_detail['player_character_weapon_spec1_damage_bonus'];
                $this->missileSpec1Description      = $weapon_detail['player_character_weapon_spec1_description'];
                $this->missileSpec2HitBonus         = $weapon_detail['player_character_weapon_spec2_hit_bonus'];
                $this->missileSpec2DamageBonus      = $weapon_detail['player_character_weapon_spec2_damage_bonus'];
                $this->missileSpec2Description      = $weapon_detail['player_character_weapon_spec2_description'];
                $this->missileSpec3HitBonus         = $weapon_detail['player_character_weapon_spec3_hit_bonus'];
                $this->missileSpec3DamageBonus      = $weapon_detail['player_character_weapon_spec3_damage_bonus'];
                $this->missileSpec3Description      = $weapon_detail['player_character_weapon_spec3_description'];
                $this->missileAdditionalText        = $weapon_detail['player_character_weapon_additional_text'];
                $this->missileShortRange            = $weapon_detail['player_character_weapon_short_range'];
                $this->missileMediumRange           = $weapon_detail['player_character_weapon_medium_range'];
                $this->missileLongRange             = $weapon_detail['player_character_weapon_long_range'];
            }
        }
    }

    public function getPlayerCharacterWeapon(\PDO $pdo, $player_character_weapon_id, &$errors) {
        $sql_exec = "CALL getPlayerCharacterWeapon(:playerCharacterWeaponId)";

        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerCharacterWeaponId', $player_character_weapon_id, PDO::PARAM_INT);
        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getPlayerCharacterWeapon : " . $e->getMessage();
        }

		return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

	// function called when encoded with json_encode
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getWeaponId() {
        return $this->weaponId;
    }

    public function getWeaponProficiencyId() {
        return $this->weaponProficiencyId;
    }

    public function getWeaponDescription() {
        return $this->weaponDescription;
    }

    public function getWeaponLocation() {
        return $this->weaponLocation;
    }

    public function getIsReady() {
        return $this->isReady;
    }

    public function getIsPreferred() {
        return $this->isPreferred;
    }

    public function getCraftStatus() {
        return $this->craftStatus;
    }
    
    public function getStrengthBonusAvailable() {
        return $this->strengthBonusAvailable;
    }
    
    public function getPlayerNote1() {
        return $this->playerNote1;
    }

    public function getPlayerNote2() {
        return $this->playerNote2;
    }

    public function getPlayerNote3() {
        return $this->playerNote3;
    }

    public function getMastercraftHitDescription() {
        return $this->mastercraftHitDescription;
    }

    public function getMastercraftDamageDescription() {
        return $this->mastercraftDamageDescription;
    }

    public function getMeleeWeaponType() {
        return $this->meleeWeaponType;
    }

    public function getMeleeWeaponSubtype() {
        return $this->meleeWeaponSubtype;
    }

    public function getMeleeWeaponSpeed() {
        return $this->meleeWeaponSpeed;
    }

    public function getMeleeWeaponDamage() {
        return $this->meleeWeaponDamage;
    }

    public function getMeleeAttacksPerRound() {
        return $this->meleeAttacksPerRound;
    }

    public function getMeleeNumberOfHands() {
        return $this->meleeNumberOfHands;
    }

    public function getMeleeAdditionalText() {
        return $this->meleeAdditionalText;
    }

    public function getMeleeHitBonus() {
        return $this->meleeHitBonus;
    }

    public function getMeleeDamageBonus() {
        return $this->meleeDamageBonus;
    }

    public function getMeleeSpec1HitBonus() {
        return $this->meleeSpec1HitBonus;
    }

    public function getMeleeSpec1DamageBonus() {
        return $this->meleeSpec1DamageBonus;
    }

    public function getMeleeSpec1Description() {
        return $this->meleeSpec1Description;
    }

    public function getMeleeSpec2HitBonus() {
        return $this->meleeSpec2HitBonus;
    }

    public function getMeleeSpec2DamageBonus() {
        return $this->meleeSpec2DamageBonus;
    }

    public function getMeleeSpec2Description() {
        return $this->meleeSpec2Description;
    }

    public function getMeleeSpec3HitBonus() {
        return $this->meleeSpec3HitBonus;
    }

    public function getMeleeSpec3DamageBonus() {
        return $this->meleeSpec3DamageBonus;
    }

    public function getMeleeSpec3Description() {
        return $this->meleeSpec3Description;
    }

    public function getMissileWeaponType() {
        return $this->missileWeaponType;
    }

    public function getMissileWeaponSubtype() {
        return $this->missileWeaponSubtype;
    }

    public function getMissileWeaponSpeed() {
        return $this->missileWeaponSpeed;
    }

    public function getMissileWeaponDamage() {
        return $this->missileWeaponDamage;
    }

    public function getMissileAttacksPerRound() {
        return $this->missileAttacksPerRound;
    }

    public function getMissileAdditionalText() {
        return $this->missileAdditionalText;
    }

    public function getMissileHitBonus() {
        return $this->missileHitBonus;
    }

    public function getMissileDamageBonus() {
        return $this->missileDamageBonus;
    }

    public function getMissileSpec1HitBonus() {
        return $this->missileSpec1HitBonus;
    }

    public function getMissileSpec1DamageBonus() {
        return $this->missileSpec1DamageBonus;
    }

    public function getMissileSpec1Description() {
        return $this->missileSpec1Description;
    }

    public function getMissileSpec2HitBonus() {
        return $this->missileSpec2HitBonus;
    }

    public function getMissileSpec2DamageBonus() {
        return $this->missileSpec2DamageBonus;
    }

    public function getMissileSpec2Description() {
        return $this->missileSpec2Description;
    }

    public function getMissileSpec3HitBonus() {
        return $this->missileSpec3HitBonus;
    }

    public function getMissileSpec3DamageBonus() {
        return $this->missileSpec3DamageBonus;
    }

    public function getMissileSpec3Description() {
        return $this->missileSpec3Description;
    }

    public function getMissileShortRange() {
        return $this->missileShortRange;
    }

    public function getMissileMediumRange() {
        return $this->missileMediumRange;
    }

    public function getMissileLongRange() {
        return $this->missileLongRange;
    }
}
