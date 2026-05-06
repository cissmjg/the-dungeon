<?php
    require_once 'rmFactor.php';

    require_once __DIR__ . '/../characterDetails.php';
    require_once __DIR__ . '/../playerCharacterSkillSet.php';
    require_once __DIR__ . '/../playerCharacterWeapon.php';
    require_once __DIR__ . '/../attributeMetadata.php';

    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';

    class MissileArcherMediumRangeDamageRmCollectionCalculator extends MissileMediumRangeDamageRmCollectionCalculator {

        private $damage_bonus = [];

        public function __construct() {
            parent::__construct();

            // Medium Range Damage modifiers
            $this->damage_bonus[1] = 0;
            $this->damage_bonus[2] = 0;
            $this->damage_bonus[3] = 0;
            $this->damage_bonus[4] = 0;
            $this->damage_bonus[5] = 0;
            $this->damage_bonus[6] = 0;
            $this->damage_bonus[7] = 0;
            $this->damage_bonus[8] = 0;
            $this->damage_bonus[9] = 1;
            $this->damage_bonus[10] = 1;
            $this->damage_bonus[11] = 1;
            $this->damage_bonus[12] = 1;
            $this->damage_bonus[13] = 2;
            $this->damage_bonus[14] = 2;
        }

        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            parent::gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
            
            $archer_level = $character_details->getLevelForClass(ARCHER);
            $archer_level = $archer_level == 0 ? $character_details->getLevelForClass(ARCHER_RANGER) : $archer_level;
            $rm_damage = $this->getDamageBonus($archer_level);
            $this->rm_medium_collection->add($rm_damage);
        }

        private function getDamageBonus($archer_level) {
            $normalized_archer_level = min($archer_level, 14);
            $rm_damage_desc = "Archer bonus";
            $rm_damage_modifier = $this->damage_bonus[$normalized_archer_level];
            $rm_damage = new RmFactor($rm_damage_desc, $rm_damage_modifier);

            return $rm_damage;
        }
    }
?>