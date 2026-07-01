<?php

require_once 'combatSummaryRenderer.php';
require_once 'combatSummaryUserDefinedWeaponOrder.php';
require_once 'playerCharacterWeaponSet.php';
require_once 'playerCharacterSkillSet.php';
require_once 'characterDetails.php';
require_once 'attributeMetadata.php';
require_once 'twoWeaponFightingConfigurationSet.php';
require_once 'rowClassManager.php';

require_once 'playerCharacterWeaponRenderer.php';
require_once 'twoWeaponFightingRenderer.php';

require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';

require_once __DIR__ . '/../helper/HtmlHelper.php';
require_once __DIR__ . '/../helper/WebParameterHelper.php';

require_once __DIR__ . '/../webio/characterAction.php';
require_once __DIR__ . '/../webio/playerName.php';
require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../webio/displayCount.php';
require_once __DIR__ . '/../webio/sectionName.php';

require_once __DIR__ . '/../characterActionRoutes.php';

class CombatSummaryRendererEditWeaponOrder extends CombatSummaryRenderer {

    private $combat_summary_user_defined_weapon_order;
    private $renderers;
    private $is_mounted_section_needed;
    private $player_name;
    public function getPlayerName() {
        return $this->player_name;
    }

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
        $this->player_name = $player_name;

        $this->combat_summary_user_defined_weapon_order = new CombatSummaryUserDefinedWeaponOrder();
        $this->combat_summary_user_defined_weapon_order->init($pdo, $player_name, $character_name, $errors);

        // If there are no previously added CombatSummaryUserDefinedItem instances (in CombatSummaryUserDefinedWeaponOrder)
        // then add CombatSummaryUserDefinedItem instances in default order.
        $this->populateRenderers($pdo, $player_name, $character_name, $this->combat_summary_user_defined_weapon_order->isEmpty(), $errors);

        if ($this->combat_summary_user_defined_weapon_order->isEmpty()) {            
            // Initialize again to pick up newly inserted records
            $this->combat_summary_user_defined_weapon_order->init($pdo, $player_name, $character_name, $errors);
        }
    }

    protected function renderSection($combat_mode) {
        
        $user_defined_item_list = $this->combat_summary_user_defined_weapon_order->getItemsForSection($combat_mode);
        $section_name = getMountedCombatModeDescription($combat_mode);

        $form_name = 'edit-weapon-order-' . $section_name;
        $output_html  = '<form id="' . $form_name . '" name="' . $form_name . '" method="POST" action="' . CurlHelper::buildCharacterActionRouterUrl() . '">' . PHP_EOL;
        $output_html .= HtmlHelper::buildHiddenTag(CHARACTER_ACTION, CHARACTER_ACTION_UPDATE_COMBAT_SUMMARY_WEAPON_ORDER);
        $output_html .= HtmlHelper::buildHiddenTag(PLAYER_NAME, $this->getPlayerName());
        $output_html .= HtmlHelper::buildHiddenTag(CHARACTER_NAME, $this->getCharacterDetails()->getCharacterName());
        $output_html .= HtmlHelper::buildHiddenTag(SECTION_NAME, $section_name);
        $output_html .= HtmlHelper::buildHiddenTag(DISPLAY_COUNT, count($user_defined_item_list));
        $output_html .= '<table style="width: 100%;">' . PHP_EOL;
        $output_html .= '<tr><td style="width: 5%;">&nbsp;</td><td style="width: 95%;">' . $this->formatColumnHeaders() . '</td></tr>' . PHP_EOL;
        
        foreach($user_defined_item_list AS $user_defined_item) {
            $renderer_id = $user_defined_item->getRendererId();
            $output_html .= '<tr>' . PHP_EOL;
            $output_html .= '<td style="text-align: center;">' . PHP_EOL;
            $output_html .= '<input type="checkbox" id="cb-' . $renderer_id . '" name="display-order-' . $user_defined_item->getDisplayOrder() . '" value="' . $renderer_id . '" onchange="weaponCheckboxChanged(\'' . $form_name . '\');">';
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

    private function populateRenderers(PDO $pdo, $player_name, $character_name, $combat_summary_user_defined_weapon_order_is_empty, &$errors) {

        $renderer_index = 1;
        foreach($this->getTwoWeaponFightingConfigurationSet() AS $two_weapon_config) {
            $two_weapon_renderer = new TwoWeaponFightingRenderer($two_weapon_config, $this->getPlayerCharacterWeaponSet(), $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
            $this->renderers[$two_weapon_renderer->getId()] = $two_weapon_renderer;

            if ($combat_summary_user_defined_weapon_order_is_empty) {
                $this->addTwoWeaponCombatSummaryItem($pdo, $player_name, $character_name, getMountedCombatModeDescription(COMBAT_MODE_UNMOUNTED), $two_weapon_renderer->getId(), $two_weapon_renderer->getMainHandWeapon()->getWeaponId(), $two_weapon_renderer->getOffHandWeapon()->getWeaponId(), $renderer_index, $errors);
                if (count($errors) > 0) {
                    die(json_encode($errors));
                }

                $renderer_index++;
            }
        }

        foreach($this->getPlayerCharacterWeaponSet() AS $player_character_weapon) {
            $player_character_weapon_renderer = new PlayerCharacterWeaponRenderer($player_character_weapon, $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
            $player_character_weapon_renderer->setCombatMode(COMBAT_MODE_UNMOUNTED);
            $this->renderers[$player_character_weapon_renderer->getId()] = $player_character_weapon_renderer;

            if ($combat_summary_user_defined_weapon_order_is_empty) {
                $this->addOneWeaponCombatSummaryItem($pdo, $player_name, $character_name, getMountedCombatModeDescription(COMBAT_MODE_UNMOUNTED), $player_character_weapon_renderer->getId(), $player_character_weapon_renderer->getPlayerCharacterWeapon()->getWeaponId(), $renderer_index, $errors);
                if (count($errors) > 0) {
                    die(json_encode($errors));
                }

                $renderer_index++;
            }

            if ($this->is_mounted_section_needed) {
                $player_character_weapon_renderer = new PlayerCharacterWeaponRenderer($player_character_weapon, $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
                $player_character_weapon_renderer->setCombatMode(getMountedCombatModeDescription(COMBAT_MODE_MOUNTED));
                $this->renderers[$player_character_weapon_renderer->getId()] = $player_character_weapon_renderer;

                if ($combat_summary_user_defined_weapon_order_is_empty) {
                    $this->addOneWeaponCombatSummaryItem($pdo, $player_name, $character_name, getMountedCombatModeDescription(COMBAT_MODE_MOUNTED), $player_character_weapon_renderer->getId(), $player_character_weapon_renderer->getPlayerCharacterWeapon()->getWeaponId(), $renderer_index, $errors);
                    if (count($errors) > 0) {
                        die(json_encode($errors));
                    }

                    $renderer_index++;
                }
            }
        }
    }

    private function addOneWeaponCombatSummaryItem(PDO $pdo, $player_name, $character_name, $section_name, $renderer_id, $player_character_weapon_id, $display_order, &$errors) {
        $sql_exec = "CALL addCombatSummaryWeaponOrderItem(:playerName, :characterName, :sectionName, :rendererId, :playerCharacterWeaponId, :playerCharacterWeapon2Id, :displayOrder)";

        $null_value = NULL;
        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        $statement->bindParam(':sectionName', $section_name, PDO::PARAM_STR);
        $statement->bindParam(':rendererId', $renderer_id, PDO::PARAM_STR);
        $statement->bindParam(':playerCharacterWeaponId', $player_character_weapon_id, PDO::PARAM_INT);
        $statement->bindParam(':playerCharacterWeapon2Id', $null_value, PDO::PARAM_NULL);
        $statement->bindParam(':displayOrder', $display_order, PDO::PARAM_INT);

        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in CombatSummaryRendererEditWeaponOrder.addOneWeaponCombatSummaryItem : " . $e->getMessage();
        }
    }

    private function addTwoWeaponCombatSummaryItem(PDO $pdo, $player_name, $character_name, $section_name, $renderer_id, $player_character_weapon_id, $player_character_weapon2_id, $display_order, &$errors) {
        $sql_exec = "CALL addCombatSummaryWeaponOrderItem(:playerName, :characterName, :sectionName, :rendererId, :playerCharacterWeaponId, :playerCharacterWeapon2Id, :displayOrder)";

        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        $statement->bindParam(':sectionName', $section_name, PDO::PARAM_STR);
        $statement->bindParam(':rendererId', $renderer_id, PDO::PARAM_STR);
        $statement->bindParam(':playerCharacterWeaponId', $player_character_weapon_id, PDO::PARAM_INT);
        $statement->bindParam(':playerCharacterWeapon2Id', $player_character_weapon2_id, PDO::PARAM_INT);
        $statement->bindParam(':displayOrder', $display_order, PDO::PARAM_INT);

        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in CombatSummaryRendererEditWeaponOrder.addTwoWeaponCombatSummaryItem : " . $e->getMessage();
        }
    }
}
?>