<?php
    abstract class RmCollectionCalculator {
        abstract protected function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata);
    }
?>