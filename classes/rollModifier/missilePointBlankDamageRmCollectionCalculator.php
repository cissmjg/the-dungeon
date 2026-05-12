<?php
    require_once 'rmFactor.php';
    require_once 'rmCollection.php';
    require_once 'rmCategory.php';
    require_once 'rmCollectionCalculator.php';
    require_once __DIR__ . '/../characterDetails.php';
    require_once __DIR__ . '/../playerCharacterSkillSet.php';
    require_once __DIR__ . '/../playerCharacterWeapon.php';
    require_once __DIR__ . '/../attributeMetadata.php';

    require_once __DIR__ . '/../../dbio/constants/skills.php';
    require_once __DIR__ . '/../../dbio/constants/weapons.php';
    require_once __DIR__ . '/../../dbio/constants/weaponType.php';
    require_once __DIR__ . '/../../dbio/constants/weaponSubtype.php';
    require_once __DIR__ . '/../../dbio/constants/characterRaces.php';
    require_once __DIR__ . '/../../webio/craftStatus.php';

    class MissilePointBlankDamageRmCollectionCalculator extends RmCollectionCalculator {

        protected $rm_pb_collection;
        public function getRmCollection() {
            return $this->rm_pb_collection;
        }

        public function __construct() {
            $this->rm_pb_collection = new RmCollection();
        }

        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {

            // Only Archers wielding a bow (that is not a short bow) and Fighters that specialize in a Bow or Crossbow are entitled to Point Blank range
            $point_blank_available = $this->isEntitledToPointBlank($character_details, $player_character_weapon, $player_character_skill_set);
            if (!$point_blank_available) {
                return;
            }

            // Proficiency check
            if ($player_character_weapon->getIsProficient()) {

                // Skills
                $rm_skill_collection = $this->getRmSkills($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
                $this->rm_pb_collection->addAll($rm_skill_collection);

                // Bow (Strength bonus for weapon)
                $rm_strength_bonus = $this->getStrengthBonus($character_details, $player_character_weapon, $attribute_metadata);
                if (!empty($rm_strength_bonus)) {
                    $this->rm_pb_collection->add($rm_strength_bonus);
                }
            }

            // Weapon
            $rm_weapon = $this->getWeaponBonus($player_character_weapon);
            if (!empty($rm_weapon)) {
                $this->rm_pb_collection->add($rm_weapon);
            }
        }

        private function isEntitledToPointBlank(CharacterDetails $character_details, PlayerCharacterWeapon $player_character_weapon, PlayerCharacterSkillSet $player_character_skill_set) {
            if (!$character_details->isFighterType()) {
                return false;
            }

            $character_class_id = $character_details->getFighterTypeClassId();

            // Barbarians cannot specialize
            if ($character_class_id == BARBARIAN) {
                return false;
            }

            if ($character_class_id == ARCHER || $character_class_id == ARCHER_RANGER) {
                // Archers do not get point blank with a short Bow
                if ($player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW && $player_character_weapon->getWeaponProficiencyId() != SHORT_BOW) {
                    return true;
                }
            } else {
                // Fighter can specialize in ANY kind of Bow or Crossbow
                if ($player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW || $player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_CROSSBOW) {
                    $has_specialization = count($player_character_skill_set->getAllSkillInstancesForWeapon(SPECIALIZATION, $player_character_weapon->getWeaponProficiencyId())) > 0;
                    return $has_specialization;
                }
            }

            return false;
        }

        private function getStrengthBonus(CharacterDetails $character_details, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            $rm_strength_bonus = null;

            // Fighters get to add their strength with ANY type of bow (UA page 18), Archers need a composite Bow
            $character_class_id = $character_details->getFighterTypeClassId();
            if ($character_class_id == ARCHER || $character_class_id == ARCHER_RANGER) {
                if ($player_character_weapon->getWeaponProficiencyId() == LONG_COMPOSITE_BOW) {
                    $rm_strength_bonus_desc = "Strength Bonus";
                    $rm_strength_bonus_modifier = $attribute_metadata->getStrengthDamageAdjustment();
                    $rm_strength_bonus = new RmFactor($rm_strength_bonus_desc, $rm_strength_bonus_modifier);
                }
            } else {
                // No Strength for crossbow
                if ($player_character_weapon->getMissileWeaponSubtype() != WEAPON_SUBTYPE_CROSSBOW) {
                    $rm_strength_bonus_desc = "Strength Bonus";
                    $rm_strength_bonus_modifier = $attribute_metadata->getStrengthDamageAdjustment();
                    $rm_strength_bonus = new RmFactor($rm_strength_bonus_desc, $rm_strength_bonus_modifier);
                }
            }

            return $rm_strength_bonus;
        }

        private function getRmSkills(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            $rm_collection = new RmCollection();

            // Specialization
            $rm_weapon_specialization = $this->getWeaponSpecialization($player_character_skill_set, $player_character_weapon);
            if (!empty($rm_weapon_specialization)) {
                $rm_collection->add($rm_weapon_specialization);
            }

            return $rm_collection;
        }

        private function getWeaponSpecialization(PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon_specialization = null;
            $existing_weapon_specialization = $player_character_skill_set->getAllSkillInstances(SPECIALIZATION);
            $has_weapon_specialization = count($existing_weapon_specialization) > 0;
            if ($has_weapon_specialization) {
                $first_weapon_specialization = $existing_weapon_specialization[0];
                if ($first_weapon_specialization->getWeaponProficiencyId() == $player_character_weapon->getWeaponProficiencyId()) {
                    $rm_weapon_specialization_desc = "Specialization";
                    $rm_weapon_specialization_modifier = $this->getWeaponSpecializationModifier($player_character_weapon);
                    $rm_weapon_specialization = new RmFactor($rm_weapon_specialization_desc, $rm_weapon_specialization_modifier);
                }
            }

            return $rm_weapon_specialization;
        }
        private function getWeaponSpecializationModifier(PlayerCharacterWeapon $player_character_weapon) {
            if ($player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_BOW) {
                return 2;
            } else if ($player_character_weapon->getMissileWeaponSubtype() == WEAPON_SUBTYPE_CROSSBOW) {
                return 2;
            } else {
                return 0;
            }
        }

        private function getWeaponBonus(PlayerCharacterWeapon $player_character_weapon) {
            $rm_weapon = null;
            $rm_weapon_desc = '';
            if ($player_character_weapon->getMissileDamageBonus() != 0) {
                if ($player_character_weapon->getCraftStatus() == CRAFT_STATUS_MASTERCRAFT) {
                    $rm_weapon_desc = 'Mastercraft Bonus';
                } else if ($player_character_weapon->getCraftStatus() == CRAFT_STATUS_MAGIC) {
                    $rm_weapon_desc = 'Magic Bonus';
                }
                
                $rm_weapon_modifier = $player_character_weapon->getMissileDamageBonus();
                $rm_weapon = new RmFactor($rm_weapon_desc, $rm_weapon_modifier);
                if ($rm_weapon_modifier < 0) {
                    $rm_weapon->setRmCategory(ROLL_MODIFIER_PENALTY);
                }
            }
            return $rm_weapon;
        }
    }
?>
