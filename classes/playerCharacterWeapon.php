<?php

require_once __DIR__ . '/../dbio/constants/weaponType.php';
require_once __DIR__ . '/../dbio/constants/weaponSubtype.php';
require_once __DIR__ . '/../dbio/constants/weapons.php';
require_once __DIR__ . '/../dbio/constants/skills.php';

require_once 'playerCharacterSkillSet.php';
require_once __DIR__ . '/skills/fistOfIron.php';

class PlayerCharacterWeapon implements JsonSerializable {

    private $weaponId;
    private $weaponProficiencyId;
    private $weaponDescription;
    private $weaponLocation;
    private $isReady;
    private $isProficient = false;
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

    public function init(\PDO $pdo, $player_character_weapon_id, PlayerCharacterSkillSet $player_character_skill_set, &$errors) {

        $weapon_details = $this->getPlayerCharacterWeapon($pdo, $player_character_weapon_id, $errors);
        if(count($errors) > 0) {
            return;
        }

        foreach($weapon_details AS $weapon_detail) {
            $this->populate($weapon_detail, $player_character_skill_set);
        }
    }

    public function populate($weapon_detail, PlayerCharacterSkillSet $player_character_skill_set) {
        if ($weapon_detail['player_character_weapon_type'] == WEAPON_TYPE_MELEE) {
            $this->meleeWeaponType              = $weapon_detail['player_character_weapon_type'];
            $this->meleeWeaponSubtype           = $weapon_detail['player_character_weapon_subtype'];
            $this->weaponId                     = $weapon_detail['player_character_weapon_id'];
            $this->weaponProficiencyId          = $weapon_detail['player_character_weapon_proficiency_id'];
            $this->craftStatus                  = $weapon_detail['player_character_weapon_craft_status'];
            $this->weaponDescription            = $weapon_detail['player_character_weapon_description'];
            $this->isReady                      = $weapon_detail['player_character_weapon_is_ready'];
            $this->weaponLocation               = $weapon_detail['player_character_weapon_location'];
            $this->playerNote1                  = $weapon_detail['player_character_weapon_player_note1'];
            $this->playerNote2                  = $weapon_detail['player_character_weapon_player_note2'];
            $this->playerNote3                  = $weapon_detail['player_character_weapon_player_note3'];
            $this->strengthBonusAvailable       = $weapon_detail['player_character_weapon_strength_bonus_available'];
            $this->meleeWeaponSpeed             = $weapon_detail['player_character_weapon_speed'];

            $this->meleeWeaponDamage            = $weapon_detail['player_character_weapon_damage'];
            $count_fist_of_iron = count($player_character_skill_set->getAllSkillInstances(FIST_OF_IRON));
            if ($this->weaponProficiencyId == FIST && $count_fist_of_iron > 0) {
                $this->meleeWeaponDamage .= ' ' . FistOfIron::formatFistOfIronDamage($count_fist_of_iron);
            }

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
            $this->meleeSpec3Description        = $weapon_detail['player_character_weapon_spec3_description'];
            $this->meleeAdditionalText          = $weapon_detail['player_character_weapon_additional_text'];
        }

        if ($weapon_detail['player_character_weapon_type'] == WEAPON_TYPE_MISSILE) {
            $this->missileWeaponType            = $weapon_detail['player_character_weapon_type'];
            $this->missileWeaponSubtype         = $weapon_detail['player_character_weapon_subtype'];
            $this->weaponId                     = $weapon_detail['player_character_weapon_id'];
            $this->weaponProficiencyId          = $weapon_detail['player_character_weapon_proficiency_id'];
            $this->craftStatus                  = $weapon_detail['player_character_weapon_craft_status'];
            $this->weaponDescription            = $weapon_detail['player_character_weapon_description'];
            $this->isReady                      = $weapon_detail['player_character_weapon_is_ready'];
            $this->weaponLocation               = $weapon_detail['player_character_weapon_location'];
            $this->playerNote1                  = $weapon_detail['player_character_weapon_player_note1'];
            $this->playerNote2                  = $weapon_detail['player_character_weapon_player_note2'];
            $this->playerNote3                  = $weapon_detail['player_character_weapon_player_note3'];
            $this->strengthBonusAvailable       = $weapon_detail['player_character_weapon_strength_bonus_available'];
            $this->missileWeaponSpeed           = $weapon_detail['player_character_weapon_speed'];
            $this->missileWeaponDamage          = $weapon_detail['player_character_weapon_damage'];
            $this->missileAttacksPerRound       = $weapon_detail['player_character_weapon_attacks_per_round'];
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

        $this->isProficient = $this->determineIsProficient($player_character_skill_set);
    }

    public function fromJSON($weapon_detail_json, $player_character_skill_set) {
        if ($weapon_detail_json->meleeWeaponType == WEAPON_TYPE_MELEE) {
            $this->meleeWeaponType              = $weapon_detail_json->meleeWeaponType;
            $this->meleeWeaponSubtype           = $weapon_detail_json->meleeWeaponSubtype;
            $this->weaponId                     = $weapon_detail_json->weaponId;
            $this->weaponProficiencyId          = $weapon_detail_json->weaponProficiencyId;
            $this->craftStatus                  = $weapon_detail_json->craftStatus;
            $this->weaponDescription            = $weapon_detail_json->weaponDescription;
            $this->isReady                      = $weapon_detail_json->isReady;
            $this->weaponLocation               = $weapon_detail_json->weaponLocation;
            $this->playerNote1                  = $weapon_detail_json->playerNote1;
            $this->playerNote2                  = $weapon_detail_json->playerNote2;
            $this->playerNote3                  = $weapon_detail_json->playerNote3;
            $this->strengthBonusAvailable       = $weapon_detail_json->strengthBonusAvailable;
            $this->meleeWeaponSpeed             = $weapon_detail_json->meleeWeaponSpeed;
            $this->meleeWeaponDamage            = $weapon_detail_json->meleeWeaponDamage;
            $this->meleeAttacksPerRound         = $weapon_detail_json->meleeAttacksPerRound;
            $this->meleeNumberOfHands           = $weapon_detail_json->meleeNumberOfHands;
            $this->meleeHitBonus                = $weapon_detail_json->meleeHitBonus;
            $this->meleeDamageBonus             = $weapon_detail_json->meleeDamageBonus;
            $this->mastercraftHitDescription    = $weapon_detail_json->mastercraftHitDescription;
            $this->mastercraftDamageDescription = $weapon_detail_json->mastercraftDamageDescription;
            $this->meleeSpec1HitBonus           = $weapon_detail_json->meleeSpec1HitBonus;
            $this->meleeSpec1DamageBonus        = $weapon_detail_json->meleeSpec1DamageBonus;
            $this->meleeSpec1Description        = $weapon_detail_json->meleeSpec1Description;
            $this->meleeSpec2HitBonus           = $weapon_detail_json->meleeSpec2HitBonus;
            $this->meleeSpec2DamageBonus        = $weapon_detail_json->meleeSpec2DamageBonus;
            $this->meleeSpec2Description        = $weapon_detail_json->meleeSpec2Description;
            $this->meleeSpec3HitBonus           = $weapon_detail_json->meleeSpec3HitBonus;
            $this->meleeSpec3DamageBonus        = $weapon_detail_json->meleeSpec3DamageBonus;
            $this->meleeSpec3Description        = $weapon_detail_json->meleeSpec3Description;
            $this->meleeAdditionalText          = $weapon_detail_json->meleeAdditionalText;
        }

        if ($weapon_detail_json->missileWeaponType == WEAPON_TYPE_MISSILE) {
            $this->missileWeaponType            = $weapon_detail_json->missileWeaponType;
            $this->missileWeaponSubtype         = $weapon_detail_json->missileWeaponSubtype;
            $this->weaponId                     = $weapon_detail_json->weaponId;
            $this->weaponProficiencyId          = $weapon_detail_json->weaponProficiencyId;
            $this->craftStatus                  = $weapon_detail_json->craftStatus;
            $this->weaponDescription            = $weapon_detail_json->weaponDescription;
            $this->isReady                      = $weapon_detail_json->isReady;
            $this->weaponLocation               = $weapon_detail_json->weaponLocation;
            $this->playerNote1                  = $weapon_detail_json->playerNote1;
            $this->playerNote2                  = $weapon_detail_json->playerNote2;
            $this->playerNote3                  = $weapon_detail_json->playerNote3;
            $this->strengthBonusAvailable       = $weapon_detail_json->strengthBonusAvailable;
            $this->missileWeaponSpeed           = $weapon_detail_json->missileWeaponSpeed;
            $this->missileWeaponDamage          = $weapon_detail_json->missileWeaponDamage;
            $this->missileAttacksPerRound       = $weapon_detail_json->missileAttacksPerRound;
            $this->missileHitBonus              = $weapon_detail_json->missileHitBonus;
            $this->missileDamageBonus           = $weapon_detail_json->missileDamageBonus;
            $this->mastercraftHitDescription    = $weapon_detail_json->mastercraftHitDescription;
            $this->mastercraftDamageDescription = $weapon_detail_json->mastercraftDamageDescription;
            $this->missileSpec1HitBonus         = $weapon_detail_json->missileSpec1HitBonus;
            $this->missileSpec1DamageBonus      = $weapon_detail_json->missileSpec1DamageBonus;
            $this->missileSpec1Description      = $weapon_detail_json->missileSpec1Description;
            $this->missileSpec2HitBonus         = $weapon_detail_json->missileSpec2HitBonus;
            $this->missileSpec2DamageBonus      = $weapon_detail_json->missileSpec2DamageBonus;
            $this->missileSpec2Description      = $weapon_detail_json->missileSpec2Description;
            $this->missileSpec3HitBonus         = $weapon_detail_json->missileSpec3HitBonus;
            $this->missileSpec3DamageBonus      = $weapon_detail_json->missileSpec3DamageBonus;
            $this->missileSpec3Description      = $weapon_detail_json->missileSpec3Description;
            $this->missileAdditionalText        = $weapon_detail_json->missileAdditionalText;
            $this->missileShortRange            = $weapon_detail_json->missileShortRange;
            $this->missileMediumRange           = $weapon_detail_json->missileMediumRange;
            $this->missileLongRange             = $weapon_detail_json->missileLongRange;
        }

        $this->isProficient = $this->determineIsProficient($player_character_skill_set);
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

    private function determineIsProficient(PlayerCharacterSkillSet $player_character_skill_set) {
        if ($player_character_skill_set->isProficientWithWeapon($this->getWeaponProficiencyId())) {
            return true;
        }

        /*- Look at weapon subtypes -*/

        // Individual weapon proficiencies
        if ($this->meleeWeaponSubtype == WEAPON_SUBTYPE_MISC_MELEE) {
            return false;
        }

        if ($this->missileWeaponSubtype == WEAPON_SUBTYPE_MISC_MISSILE) {
            return false;
        }

        if ($this->meleeWeaponSubtype == WEAPON_SUBTYPE_POLE_ARM) {
            return false;
        }

        if ($this->meleeWeaponSubtype ==  WEAPON_SUBTYPE_CLUB) {
            return false;
        }

        if ($this->meleeWeaponSubtype == WEAPON_SUBTYPE_HAMMER) {
            return false;
        }

        if ($this->meleeWeaponSubtype == WEAPON_SUBTYPE_SLING) {
            return false;
        }

        if ($this->meleeWeaponSubtype == WEAPON_SUBTYPE_BLOW_GUN) {
            return false;
        }

        if ($this->meleeWeaponSubtype == WEAPON_SUBTYPE_ONE_HANDED_SWORD) {
            return $this->isProficientWithOneHandedSword($player_character_skill_set);
        }

        if ($this->meleeWeaponSubtype == WEAPON_SUBTYPE_TWO_HANDED_SWORD) {
            return $this->isProficientWithTwoHandedSword($player_character_skill_set);
        }

        if ($this->missileWeaponSubtype == WEAPON_SUBTYPE_BOW) {
            return $this->isProficientWithStringedBow($player_character_skill_set);
        }

        if ($this->missileWeaponSubtype == WEAPON_SUBTYPE_CROSSBOW) {
            return $this->isProficientWithMechanicalBow($player_character_skill_set);
        }

        if ($this->missileWeaponSubtype == WEAPON_SUBTYPE_LANCE) {
            return $this->isProficientWithLance($player_character_skill_set);
        }

        return false;
    }

    private function isProficientWithOneHandedSword(PlayerCharacterSkillSet $player_character_skill_set) {
        if ($player_character_skill_set->isProficientWithWeapon(LONG_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SHORT_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SCIMITAR)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(BROAD_SWORD)) {
            return true;
        }
        
        if ($player_character_skill_set->isProficientWithWeapon(KHOPESH_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SHORT_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SABRE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(MARINERS_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(PIERCER_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(ELVEN_LIGHTBLADE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(ELVEN_THIN_BLADE)) {
            return true;
        }

        return false;
    }

    private function isProficientWithTwoHandedSword(PlayerCharacterSkillSet $player_character_skill_set) {

        if ($player_character_skill_set->isProficientWithWeapon(TWO_HANDED_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(FLAMBERGE_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(BASTARD_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(FALCHION_SWORD)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(ELVEN_COURT_BLADE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(DWARVEN_CLAYMORE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(FALCHION_SWORD)) {
            return true;
        }

        return false;
    }

    private function isProficientWithStringedBow(PlayerCharacterSkillSet $player_character_skill_set){

        if ($player_character_skill_set->isProficientWithWeapon(LONG_BOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(LONG_COMPOSITE_BOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SHORT_BOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(SHORT_COMPOSITE_BOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(ELVEN_CRAFT_BOW)) {
            return true;
        }

        return false;
    }

    private function isProficientWithMechanicalBow(PlayerCharacterSkillSet $player_character_skill_set) {

        if ($player_character_skill_set->isProficientWithWeapon(LIGHT_CROSSBOW)) {
            return true;
        }
        if ($player_character_skill_set->isProficientWithWeapon(HEAVY_CROSSBOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(GREAT_CROSSBOW)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(DOKYU)) {
            return true;
        }

        return false;
    }

    private function isProficientWithLance(PlayerCharacterSkillSet $player_character_skill_set) {

        if ($player_character_skill_set->isProficientWithWeapon(LIGHT_LANCE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(MEDIUM_LANCE)) {
            return true;
        }

        if ($player_character_skill_set->isProficientWithWeapon(HEAVY_LANCE)) {
            return true;
        }

        return false;
    }

	// function called when encoded with json_encode
    public function jsonSerialize(): mixed
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

    public function getIsProficient() {
        return $this->isProficient;
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
