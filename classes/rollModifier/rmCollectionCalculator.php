<?php
    abstract class RmCollectionCalculator {
        abstract public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata);
        abstract public function getRmCollection();

        public function aggregate() {
            $rmFactorResult = 0;
            foreach($this->getRmCollection() AS $rmFactor) {
                $rmFactorResult += $rmFactor->getRMData();
            }

            return $rmFactorResult;
        }

    }
?>