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
    require_once __DIR__ . '/../helper/CurlHelper.php';

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

    public function init() {
        $this->populateRenderers($this->getCombatSummaryUserDefinedWeaponOrder()->isInitialized());
    }

    protected function renderSection($combat_mode) {
        $form_name = 'edit-weapon-order-' . getMountedCombatModeDescription($combat_mode);
        $output_html  = '<form id="' . $form_name . '" name="' . $form_name . '" method="POST" action="' . CurlHelper::buildCharacterActionRouterUrl() . '">' . PHP_EOL;
        $output_html .= '<table style="width: 100%;">' . PHP_EOL;
        $output_html .= '<tr><td style="width: 5%;">&nbsp;</td><td style="width: 95%;">' . $this->formatColumnHeaders() . '</td></tr>' . PHP_EOL;
        $user_defined_item_list = $this->getCombatSummaryUserDefinedWeaponOrder()->getItemsForSection($combat_mode);
        foreach($user_defined_item_list AS $user_defined_item) {
            $renderer_id = $user_defined_item->getRendererId();
            $output_html .= '<tr>' . PHP_EOL;
            $output_html .= '<td style="text-align: center;">' . PHP_EOL;
            $output_html .= '<input type="checkbox" id="cb-' . $renderer_id . '" name="weapons" value="' . $renderer_id . '" onchange="weaponCheckboxChanged(\'' . $form_name . '\');">';
            $output_html .= '</td>' . PHP_EOL . '<td>';
            $weapon_renderer = $this->renderers[$renderer_id];
            $output_html .= $weapon_renderer->render();
            $output_html .= '</td>';
            $output_html .= '</tr>' . PHP_EOL;
        }
        $output_html .= '</table>' . PHP_EOL; 
        $output_html .= '</form>' . PHP_EOL;

        echo $output_html;
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

    // Override the standard render header function so that we can control where the columns appear
    protected function renderHeader() {
        return '';
    }
}
?>