<?php

require_once 'thiefClimbWallRmCollectionCalculator.php';
require_once 'thiefHideInShadowRmCollectionCalculator.php';

class BarbarianClassAttributeBuilder extends ClassAttributeBuilder {

    private $character_details;
    public function getCharacterDetails() {
        return $this->character_details;
    }

    public function setCharacterDetails($character_details) {
        $this->character_details = $character_details;
    }

    private $player_character_skill_set;
    public function getPlayerCharacterSkillSet() {
        return $this->player_character_skill_set;
    }

    public function setPlayerCharacterSkillSet($player_character_skill_set) {
        $this->player_character_skill_set = $player_character_skill_set;
    }

    private $player_character_weapon = null;
    public function getPlayerCharacterWeapon() {
        return $this->player_character_weapon;
    }

    private $attribute_metadata;
    public function getAttributeMetadata() {
        return $this->attribute_metadata;
    }

    public function setAttributeMetadata($attribute_metadata) {
        $this->attribute_metadata = $attribute_metadata;
    }

    private $climb_walls_calculator;
    public function getClimbWallsCalculator() {
        return $this->climb_walls_calculator;
    }

    private $hide_in_shadows_calculator;
    public function getHideInShadowsCalculator() {
        return $this->hide_in_shadows_calculator;
    }

    public function init() {
        $character_level = $this->getAccountClassSummary()->getClassLevel();
        $class_attribute_set = $this->getClassAttributeSet();

        if ($character_level >= 1) {
            $this->climb_walls_calculator = new ThiefClimbWallRmCollectionCalculator();
            $this->climb_walls_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $climb_walls_value = $this->climb_walls_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Climb Walls', $this->climb_walls_calculator->getRmCollection(), sprintf("%d%%", $climb_walls_value));

            $this->hide_in_shadows_calculator = new ThiefHideInShadowTrapRmCollectionCalculator();
            $this->hide_in_shadows_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $hide_in_shadows_value = $this->hide_in_shadows_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Hide in Shadows', $this->hide_in_shadows_calculator->getRmCollection(), sprintf("%d%%", $hide_in_shadows_value));

            $class_attribute_set->addClassAttribute('Surprise opponents on a 3 in 6 chance, are surprised only 10%');

            $back_protection = $character_level * 5;
            $class_attribute_set->addClassAttributeWithTotal('Back Protection', sprintf("%d%%", $back_protection));

            $max_detect_illusion = 75;  // Maximum value for Detect Illusion is 75%
            $detect_illusion = min($character_level * 5, $max_detect_illusion);
            $class_attribute_set->addClassAttributeWithTotal('Detect Illusion', sprintf("%d%%", $detect_illusion));

            $max_detect_magic = 90;     // Maximum value for Detect Magic is 90%
            $detect_magic = min($character_level * 5, $max_detect_magic);
            $class_attribute_set->addClassAttributeWithTotal('Detect Magic', sprintf("%d%%", $detect_magic));

            $class_attribute_set->addClassAttribute('Leadership: add level to Charisma when dealing with other barbarians');
            $class_attribute_set->addClassAttribute("Leaping and springing: 10' forward, 3' up/back. Running (15+d6)' forward, (4 + d4 (0.5 foot))' up");            
        }

        if ($character_level >= 4 && $character_level < 6) {
            $class_attribute_set->addClassAttribute('Affect creatures which require a +1 or better weapon to hit');
            $class_attribute_set->addClassAttribute('+1 on save versus spell');
        }

        if (character_level >= 6 && $character_level < 8) {
            $class_attribute_set->addClassAttribute('Affect creatures which require a +2 or better weapon to hit');
            $class_attribute_set->addClassAttribute('+1 on save versus spell');
        }

        if (character_level >= 8 && $character_level < 10) {
            $class_attribute_set->addClassAttribute('Affect creatures which require a +3 or better weapon to hit');
            $class_attribute_set->addClassAttribute('+2 on save versus spell');
        }

        if (character_level >= 10 && $character_level < 12) {
            $class_attribute_set->addClassAttribute('Affect creatures which require a +4 or better weapon to hit');
            $class_attribute_set->addClassAttribute('+2 on save versus spell');
        }

        if (character_level >= 12) {
            $class_attribute_set->addClassAttribute('Affect creatures which require a +5 or better weapon to hit');
            $class_attribute_set->addClassAttribute('+3 on save versus spell');
        }
    }
}
?>
