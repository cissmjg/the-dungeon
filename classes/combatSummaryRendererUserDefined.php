<?php
    require_once 'combatSummaryRenderer.php';
    require_once 'combatSummaryUserDefinedWeaponOrder.php';
    require_once 'playerCharacterWeaponRenderer.php';
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

    class CombatSummaryRendererUserDefined extends CombatSummaryRenderer {

    private $is_empty = true;
    public function isEmpty() {
        return $this->is_empty;
    }

    private $combat_summary_user_defined_weapon_order;
    public function getCombatSummaryUserDefinedWeaponOrder() {
        return $this->combat_summary_user_defined_weapon_order;
    }

    public function setCombatSummaryUserDefinedWeaponOrder($combat_summary_user_defined_weapon_order) {
        $this->combat_summary_user_defined_weapon_order = $combat_summary_user_defined_weapon_order;
    }

    private $is_mounted_section_needed = false;

    private $renderers = [];

    public function __construct(PlayerCharacterWeaponSet $player_character_weapon_set, PlayerCharacterSkillSet $player_character_skill_set, CharacterDetails $character_details, AttributeMetadata $attribute_metadata, TwoWeaponFightingConfigurationSet $two_weapon_fighting_configuration_set) {
        $this->player_character_weapon_set = $player_character_weapon_set;
        $this->player_character_skill_set = $player_character_skill_set;
        $this->character_details = $character_details;
        $this->attribute_metadata = $attribute_metadata;
        $this->two_weapon_fighting_configuration_set = $two_weapon_fighting_configuration_set;
        $this->row_class_manager = new RowClassManager();

        $this->is_mounted_section_needed = $this->isMountedSectionNeeded($this->getCharacterDetails(), $this->getPlayerCharacterSkillSet());
    }

    public function render() {

        // Create and categorize all the required renderers. If User Defined Weapon Order is not initialized, fill it up with weapon in 'default' order
        $this->populateRenderers($this->getCombatSummaryUserDefinedWeaponOrder()->isInitialized());
        
        if ($this->is_mounted_section_needed) {
            echo $this->renderCollapsibleSectionStart(COMBAT_MODE_MOUNTED);
            echo $this->renderHeader();
            echo $this->renderSection(COMBAT_MODE_MOUNTED);
            echo $this->renderCollapsibleSectionEnd();
            echo HtmlHelper::buildSpacerDivTag();
        }

        if ($this->is_mounted_section_needed) {
            echo $this->renderCollapsibleSectionStart(COMBAT_MODE_UNMOUNTED);
        }

        echo $this->renderHeader();
        echo $this->renderSection(COMBAT_MODE_UNMOUNTED);
        
        if ($this->is_mounted_section_needed) {
            echo $this->renderCollapsibleSectionEnd();
        }
    }

    protected function renderSection($combat_mode) {
        $user_defined_item_list = $this->getCombatSummaryUserDefinedWeaponOrder()->getItemsForSection($combat_mode);
        foreach($user_defined_item_list AS $user_defined_item) {
            $renderer_id = $user_defined_item->getRendererId();
            $weapon_renderer = $this->renderers[$renderer_id];
            echo $weapon_renderer->render();
        }
    }

    private function populateRenderers($weapon_order_initialized) {

        foreach($this->getTwoWeaponFightingConfigurationSet() AS $two_weapon_config) {
            $this->is_empty = false;
            $two_weapon_renderer = new TwoWeaponFightingRenderer($two_weapon_config, $this->getPlayerCharacterWeaponSet(), $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
            $this->renderers[$two_weapon_renderer->getId()] = $two_weapon_renderer;

            if (!$weapon_order_initialized) {
                $this->getCombatSummaryUserDefinedWeaponOrder()->newUserDefinedItemFromRenderer($two_weapon_renderer->getId(), COMBAT_MODE_UNMOUNTED);
            }
        }

        foreach($this->getPlayerCharacterWeaponSet() AS $player_character_weapon) {
            $this->is_empty = false;
            $player_character_weapon_renderer = new PlayerCharacterWeaponRenderer($player_character_weapon, $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
            $player_character_weapon_renderer->setCombatMode(COMBAT_MODE_UNMOUNTED);
            $this->renderers[$player_character_weapon_renderer->getId()] = $player_character_weapon_renderer;

            if (!$weapon_order_initialized) {
                $this->getCombatSummaryUserDefinedWeaponOrder()->newUserDefinedItemFromRenderer($player_character_weapon_renderer->getId(), COMBAT_MODE_UNMOUNTED);
            }

            if ($this->is_mounted_section_needed) {
                $player_character_weapon_renderer = new PlayerCharacterWeaponRenderer($player_character_weapon, $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
                $player_character_weapon_renderer->setCombatMode(getMountedCombatModeDescription(COMBAT_MODE_MOUNTED));
                $this->renderers[$player_character_weapon_renderer->getId()] = $player_character_weapon_renderer;
                
                if (!$weapon_order_initialized) {
                    $this->getCombatSummaryUserDefinedWeaponOrder()->newUserDefinedItemFromRenderer($player_character_weapon_renderer->getId(), COMBAT_MODE_MOUNTED);
                }
            }
        }

        $this->getCombatSummaryUserDefinedWeaponOrder()->setInitialized(true);
    }
}
?>