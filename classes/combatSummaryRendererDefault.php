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
    
    protected function renderSection($combat_mode) {
        if ($combat_mode == COMBAT_MODE_UNMOUNTED) {
            foreach($this->getTwoWeaponFightingConfigurationSet() AS $two_weapon_fighting_config) {
                $two_weapon_renderer = new TwoWeaponFightingRenderer($two_weapon_fighting_config, $this->getPlayerCharacterWeaponSet(), $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
                echo $two_weapon_renderer->render();
            }
        }

        foreach($this->getPlayerCharacterWeaponSet()->getAll() AS $player_character_weapon) {
            $player_character_weapon_renderer = new PlayerCharacterWeaponRenderer($player_character_weapon, $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
            $player_character_weapon_renderer->setCombatMode($combat_mode);
            echo $player_character_weapon_renderer->render();
        }
    }
}
?>