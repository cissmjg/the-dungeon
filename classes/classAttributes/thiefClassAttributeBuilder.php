<?php
require_once 'characterDetails.php';
require_once 'playerCharacterSkillSet.php';
require_once 'attributeMetadata.php';
require_once 'rmFactor.php';

require_once 'thiefPickPocketRmCollectionCalculator.php';
require_once 'thiefOpenLockRmCollectionCalculator.php';
require_once 'thiefFindRemoveTrapRmCollectionCalculator.php';
require_once 'thiefMoveSilentTrapRmCollectionCalculator.php';
require_once 'thiefHideInShadowTrapRmCollectionCalculator.php';
require_once 'thiefHearNoiseRmCollectionCalculator.php';
require_once 'thiefClimbWallRmCollectionCalculator.php';
require_once 'thiefReadLanguageRmCollectionCalculator.php';

class ThiefClassAttributeBuilder extends ClassAttributeBuilder {

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

    private $pick_pocket_calculator;
    public function getPickPocketCalculator() {
        return $this->pick_pocket_calculator;
    }
    
    private $open_lock_calculator;
    public function getOpenLockCalculator() {
        return $this->open_lock_calculator;
    }

    private $find_remove_traps_calculator;
    public function getFindRemoveTrapsCalculator() {
        return $this->find_remove_traps_calculator;
    }

    private $move_silently_calculator;
    public function getMoveSilentlyCalculator() {
        return $this->move_silently_calculator;
    }

    private $hide_in_shadows_calculator;
    public function getHideInShadowsCalculator() {
        return $this->hide_in_shadows_calculator;
    }

    private $hear_noise_calculator;
    public function getHearNoiseCaclulator() {
        return $this->hear_noise_calculator;
    }

    private $climb_walls_calculator;
    public function getClimbWallsCalculator() {
        return $this->climb_walls_calculator;
    }

    private $read_languages_calculator;
    public function getReadLanguagesCalculator() {
        return $this->read_languages_calculator;
    }

    public function init() {
        $character_level = $this->getAccountClassSummary()->getClassLevel();
        $class_attribute_set = $this->getClassAttributeSet();

        if ($character_level >= 1) {
            $class_attribute_set->addClassAttribute('Gain a new language: Thieves Cant');

            $back_stab_dmg_multiplier = 'None';
            if ($character_level >= 1 && $character_level <= 4) {
                $back_stab_dmg_multiplier = 'Double';
            } else if ($character_level >= 5 && $character_level <= 8) {
                $back_stab_dmg_multiplier = 'Triple';
            } else if ($character_level >= 9 && $character_level <= 12) {
                $back_stab_dmg_multiplier = 'Quadruple';
            } else if ($character_level >= 13) {
                $back_stab_dmg_multiplier = 'Quintuple';
            }

            $class_attribute_set->addClassAttributeWithTotal('Backstab damage multiplier', $back_stab_dmg_multiplier);

            $this->pick_pocket_calculator = new ThiefPickPocketRmCollectionCalculator();
            $this->pick_pocket_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $pick_pocket_value = $this->pick_pocket_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Pick Pocket', $this->pick_pocket_calculator->getRmCollection(), sprintf("%d%%", $pick_pocket_value));

            $this->open_lock_calculator = new ThiefOpenLockRmCollectionCalculator();
            $this->open_lock_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $open_lock_value = $this->open_lock_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Open Lock', $this->open_lock_calculator->getRmCollection(), sprintf("%d%%", $open_lock_value));

            $this->find_remove_traps_calculator = new ThiefFindRemoveTrapRmCollectionCalculator();
            $this->find_remove_traps_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $find_remove_traps_value = $this->find_remove_traps_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Find/Remove Traps', $this->find_remove_traps_calculator->getRmCollection(), sprintf("%d%%", $find_remove_traps_value));

            $this->move_silently_calculator = new ThiefMoveSilentTrapRmCollectionCalculator();
            $this->move_silently_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $move_silent_value = $this->move_silently_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Move Silently', $this->move_silently_calculator->getRmCollection(), sprintf("%d%%", $move_silent_value));

            $this->hide_in_shadows_calculator = new ThiefHideInShadowTrapRmCollectionCalculator();
            $this->hide_in_shadows_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $hide_in_shadows_value = $this->hide_in_shadows_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Hide in Shadows', $this->hide_in_shadows_calculator->getRmCollection(), sprintf("%d%%", $hide_in_shadows_value));

            $this->hear_noise_calculator = new ThiefHearNoiseRmCollectionCalculator();
            $this->hear_noise_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $hear_noise_value = $this->hear_noise_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Hear Noise', $this->hear_noise_calculator->getRmCollection(), sprintf("%d%%", $hear_noise_value));

            $this->climb_walls_calculator = new ThiefClimbWallRmCollectionCalculator();
            $this->climb_walls_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $climb_walls_value = $this->climb_walls_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Climb Walls', $this->climb_walls_calculator->getRmCollection(), sprintf("%d%%", $climb_walls_value));

            $this->read_languages_calculator = new ThiefReadLanguageRmCollectionCalculator();
            $this->read_languages_calculator->gather($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet(), $this->getPlayerCharacterWeapon(), $this->getAttributeMetadata());
            $read_languages_value = $this->read_languages_calculator->aggregate();
            $class_attribute_set->addClassAttributeWithCollection('Read Languages', $this->read_languages_calculator->getRmCollection(), sprintf("%d%%", $read_languages_value));
        }

        if ($character_level >= 10) {
            $class_attribute_set->addClassAttribute('Read Magic scrolls (except Clerical). 25% failure');
        }
    }
}
?>