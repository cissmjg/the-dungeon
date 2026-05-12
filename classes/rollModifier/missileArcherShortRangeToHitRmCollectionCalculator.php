<?php
    require_once 'rmFactor.php';
    require_once 'missileShortRangeToHitRmCollectionCalculator.php';

    require_once __DIR__ . '/../characterDetails.php';
    require_once __DIR__ . '/../playerCharacterSkillSet.php';
    require_once __DIR__ . '/../playerCharacterWeapon.php';
    require_once __DIR__ . '/../attributeMetadata.php';

    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';

    class MissileArcherShortRangeToHitRmCollectionCalculator extends MissileShortRangeToHitRmCollectionCalculator {

        private $to_hit_bonus = [];

        public function __construct() {
            parent::__construct();

            // Short Range To Hit modifiers
            $this->to_hit_bonus[1] = 0;
            $this->to_hit_bonus[2] = 0;
            $this->to_hit_bonus[3] = 1;
            $this->to_hit_bonus[4] = 1;
            $this->to_hit_bonus[5] = 1;
            $this->to_hit_bonus[6] = 2;
            $this->to_hit_bonus[7] = 2;
            $this->to_hit_bonus[8] = 2;
            $this->to_hit_bonus[9] = 2;
            $this->to_hit_bonus[10] = 2;
            $this->to_hit_bonus[11] = 2;
            $this->to_hit_bonus[12] = 2;
            $this->to_hit_bonus[13] = 2;
            $this->to_hit_bonus[14] = 3;
        }

        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            parent::gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
            
            // Archer bonuses only apply to Bow type weapons
            if ($player_character_weapon->getMissileWeaponSubtype() != WEAPON_SUBTYPE_BOW) {
                return;
            }

            $archer_level = $character_details->getFighterTypeLevel();
            $rm_to_hit = $this->getToHitBonus($archer_level);
            $this->rm_short_collection->add($rm_to_hit);
        }

        private function getToHitBonus($archer_level) {
            $normalized_archer_level = min($archer_level, 14);
            $rm_to_hit_desc = "Archer bonus";
            $rm_to_hit_modifier = $this->to_hit_bonus[$normalized_archer_level];
            $rm_to_hit = new RmFactor($rm_to_hit_desc, $rm_to_hit_modifier);

            return $rm_to_hit;
        }
    }
?>