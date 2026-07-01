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

    require_once __DIR__ . '/../webio/playerName.php';
    require_once __DIR__ . '/../webio/characterName.php';
    require_once __DIR__ . '/../webio/displayCount.php';

    require_once __DIR__ . '/../helper/HtmlHelper.php';
    require_once __DIR__ . '/../helper/CurlHelper.php';

    class CombatSummaryRendererUserDefined extends CombatSummaryRenderer {

    private $is_empty = true;
    public function isEmpty() {
        return $this->is_empty;
    }

    private $player_name;
    public function getPlayerName() {
        return $this->player_name;
    }

    public function setPlayerName($player_name) {
        $this->player_name = $player_name;
    }

    private $combat_summary_user_defined_weapon_order;

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

    public function init(PDO $pdo, $player_name, $character_name, &$errors) {
        $this->combat_summary_user_defined_weapon_order = new CombatSummaryUserDefinedWeaponOrder();
        $this->combat_summary_user_defined_weapon_order->init($pdo, $player_name, $character_name, $errors);

        // If the User Defined Weapon Order is empty, then this renderer is considered 'empty'
        $this->is_empty = $this->combat_summary_user_defined_weapon_order->isEmpty();

        // If the renderer is NOT empty, then initialize the renderers
        if (!$this->is_empty) {
            $this->populateRenderers();
        }
    }

    protected function renderSection($combat_mode) {
        $output_html  = '';
        $user_defined_item_list = $this->combat_summary_user_defined_weapon_order->getItemsForSection($combat_mode);
        foreach($user_defined_item_list AS $user_defined_item) {
            $renderer_id = $user_defined_item->getRendererId();

            // Check to make sure the weapon for this renderer is still available
            if (array_key_exists($renderer_id, $this->renderers)) {
                $weapon_renderer = $this->renderers[$renderer_id];
                $output_html .= $weapon_renderer->render();
            } else {
                $section_name = $user_defined_item->getSectionName();
                $display_order = $user_defined_item->getDisplayOrder();
                $error_output = "CombatSummaryRendererUserDefined missing renderer ID: [$renderer_id] in section: [$section_name] with display order: [$display_order]";
                error_log($error_output);
            }
        }

        echo $output_html;
    }

    private function populateRenderers() {

        foreach($this->getTwoWeaponFightingConfigurationSet() AS $two_weapon_config) {
            $two_weapon_renderer = new TwoWeaponFightingRenderer($two_weapon_config, $this->getPlayerCharacterWeaponSet(), $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
            $this->renderers[$two_weapon_renderer->getId()] = $two_weapon_renderer;
        }

        foreach($this->getPlayerCharacterWeaponSet() AS $player_character_weapon) {
            $player_character_weapon_renderer = new PlayerCharacterWeaponRenderer($player_character_weapon, $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
            $player_character_weapon_renderer->setCombatMode(COMBAT_MODE_UNMOUNTED);
            $this->renderers[$player_character_weapon_renderer->getId()] = $player_character_weapon_renderer;

            if ($this->is_mounted_section_needed) {
                $player_character_weapon_renderer = new PlayerCharacterWeaponRenderer($player_character_weapon, $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
                $player_character_weapon_renderer->setCombatMode(getMountedCombatModeDescription(COMBAT_MODE_MOUNTED));
                $this->renderers[$player_character_weapon_renderer->getId()] = $player_character_weapon_renderer;
            }
        }
    }
}

?>
