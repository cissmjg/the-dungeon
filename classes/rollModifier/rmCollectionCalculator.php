<?php
    abstract class RmCollectionCalculator {
        abstract protected function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata);
        abstract protected function getRmCollection();

        public function aggregate() {
            $rmFactorResult = 0;
            foreach($this->getRmCollection() AS $rmFactor) {
                $rmFactorResult += $rmFactor->getRMData();
            }

            return $rmFactorResult;
        }

    }
?>