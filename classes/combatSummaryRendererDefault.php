<?php
    require_once 'combatSummaryRenderer.php';
    require_once 'playerCharacterMeleeWeaponRenderer.php';
    require_once 'playerCharacterMissileWeaponRenderer.php';
    require_once 'playerCharacterSkillSet.php';
    require_once 'playerCharacterWeaponSet.php';
    require_once 'twoWeaponFightingConfigurationSet.php';
    require_once 'twoWeaponFightingRenderer.php';
    require_once 'characterDetails.php';
    require_once 'attributeMetadata.php';
    require_once 'rowClassManager.php';

    require_once __DIR__ . '/../dbio/constants/skills.php';
    require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';
    require_once __DIR__ . '/../dbio/constants/characterClasses.php';

    require_once __DIR__ . '/../helper/HtmlHelper.php';

    class CombatSummaryRendererDefault extends CombatSummaryRenderer {

    public function __construct(PlayerCharacterWeaponSet $player_character_weapon_set, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, TwoWeaponFightingConfigurationSet $two_weapon_fighting_configuration_set) {
        $this->player_character_weapon_set = $player_character_weapon_set;
        $this->player_character_skill_set = $player_character_skill_set;
        $this->character_details = $character_details;
        $this->attribute_metadata = $attribute_metadata;
        $this->two_weapon_fighting_configuration_set = $two_weapon_fighting_configuration_set;
        $this->row_class_manager = new RowClassManager();
    }

    public function render() {
        $is_mounted_section_needed = $this->isMountedSectionNeeded($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet());
        if ($is_mounted_section_needed) {
            echo $this->renderCollapsibleSectionStart(COMBAT_MODE_MOUNTED);
            echo $this->renderHeader();
            echo $this->renderSection(COMBAT_MODE_MOUNTED);
            echo $this->renderCollapsibleSectionEnd();
            echo HtmlHelper::buildSpacerDivTag();
        }

        if ($is_mounted_section_needed) {
            echo $this->renderCollapsibleSectionStart(COMBAT_MODE_UNMOUNTED);
        }

        echo $this->renderHeader();
        echo $this->renderSection(COMBAT_MODE_UNMOUNTED);
        
        if ($is_mounted_section_needed) {
            echo $this->renderCollapsibleSectionEnd();
        }
    }
}
?>