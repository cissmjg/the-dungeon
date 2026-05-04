<?php
    require_once 'rmFactor.php';

    require_once __DIR__ . '/../characterDetails.php';
    require_once __DIR__ . '/../playerCharacterSkillSet.php';
    require_once __DIR__ . '/../playerCharacterWeapon.php';
    require_once __DIR__ . '/../attributeMetadata.php';

    require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
    require_once __DIR__ . '/../../dbio/constants/skills.php';

    class MissileFighterShortRangeToHitRmCollectionCalculator extends MissileShortRangeToHitRmCollectionCalculator {

        public function __construct() {
            $this->rm_short_collection = new RmCollection();
        }

        public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
            parent::gather($character_details, $player_character_skill_set, $player_character_weapon, $attribute_metadata);
            
            $fighter_class_id = $character_details->getFighterTypeClassId();
            if ($fighter_class_id == 0) {
                return;
            }

            $has_specialization = count($player_character_skill_set->getAllSkillInstances(SPECIALIZATION)) > 0;
            if (!$has_specialization) {
                return;
            }

            $rm_to_hit = $this->getToHitBonus();
            $this->rm_short_collection->add($rm_to_hit);
        }

        private function getToHitBonus() {
            $rm_to_hit_desc = "Specialization";
            $rm_to_hit_modifier = 1;
            $rm_to_hit = new RmFactor($rm_to_hit_desc, $rm_to_hit_modifier);

            return $rm_to_hit;
        }
    }
?>