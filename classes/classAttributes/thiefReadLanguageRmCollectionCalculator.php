<?php
require_once 'characterDetails.php';
require_once 'playerCharacterSkillSet.php';
require_once 'playerCharacterWeapon.php';
require_once 'attributeMetadata.php';
require_once 'rmFactor.php';

require_once __DIR__ . '/../../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../../dbio/constants/skills.php';

class ThiefReadLanguageRmCollectionCalculator extends ThiefAbilityBaseRmCollectionCalculator {

    private $attribute_metadata;

    public function gather(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set, PlayerCharacterWeapon $player_character_weapon, AttributeMetadata $attribute_metadata) {
        $this->attribute_metadata = $attribute_metadata;

        $thief_level = $this->getEquivalentReadLanguageThiefLevel($character_details, $player_character_skill_set);
        if ($thief_level < 1) {
            return;
        }
        
        $base_read_language = $this->getBaseReadLanguagePct($thief_level);
        $this->getRmCollection()->add($base_read_language);

        $thief_race = $character_details->getRace();
        $thief_race_id = $character_details->getRaceId();
        $racial_read_language = $this->getRacialReadLanguagePct($thief_race, $thief_race_id);
        $this->getRmCollection()->add($racial_read_language);
    }

    private function getBaseReadLanguagePct($thief_level) {
        $base_read_language_desc = 'Base Hear Noise';
        $base_read_language_pct = $this->getThiefLookupFunction()->getBaseReadLanguages($thief_level);
        $base_read_language = new RmFactor($base_read_language_desc, $base_read_language_pct);

        return $base_read_language;
    }

    private function getRacialReadLanguagePct($thief_race, $thief_race_id) {
        $racial_adjustment_read_language_desc = $thief_race . ' racial adjustment';
        $racial_adjustment_read_language_pct = $this->getThiefLookupFunction()->getRacialAdjustmentForReadLanguages($thief_race_id);
        $racial_read_language = new RmFactor($racial_adjustment_read_language_desc, $racial_adjustment_read_language_pct);

        return $racial_read_language;
    }

    private function getEquivalentReadLanguageThiefLevel(CharacterDetails $character_details, PlayerCharacterSkillSet $player_character_skill_set) {
        $decipher_script_count = count($player_character_skill_set->getAllSkillInstances(DECIPHER_SCRIPT));

        $thief_level = $character_details->getLevelForClass(THIEF);
        if ($thief_level > 0) {
            // If the thief's level is less than 4 and the thief has Decipher Script, then the level is 4
            if ($thief_level <= 3) {
                if ($decipher_script_count > 0) {
                    return 4;
                } else {
                    return $thief_level;
                }
            } else {
                return $thief_level + $decipher_script_count;
            }
        }

        $assassin_level = $character_details->getLevelForClass(ASSASSIN);
        if ($assassin_level > 0) {
            // If the assassin's level is less than 6 and the assassin has Decipher Script, then the level is 6 (Thief - 2 levels)
            if ($assassin_level <= 5) {
                if ($decipher_script_count > 0) {
                    return 4;
                } else {
                    return $assassin_level - 2;
                }
            } else {
                return ($assassin_level - 2) + $decipher_script_count;
            }
        }

        // Non Thief/Assassin
        if ($decipher_script_count > 0) {
            return 4 + ($decipher_script_count - 1);    // One skill level gets to level 4
        }

        return -1;
    }
}
?>
