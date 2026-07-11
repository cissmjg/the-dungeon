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
require_once __DIR__ . '/../dbio/constants/rendererType.php';

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
    private $renderers = [];
    private $is_mounted_section_needed;
    private $pdo;
    private function getPdo() {
        return $this->pdo;
    }
    private $player_name;
    private function getPlayerName() {
        return $this->player_name;
    }
    private $character_name;
    private function getCharacterName() {
        return $this->character_name;
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
        $this->pdo = $pdo;
        $this->player_name = $player_name;
        $this->character_name = $character_name;

        $this->combat_summary_user_defined_weapon_order = new CombatSummaryUserDefinedWeaponOrder();
        $this->combat_summary_user_defined_weapon_order->init($pdo, $player_name, $character_name, $errors);

        $this->populateRenderers();

        // If there are no previously added CombatSummaryUserDefinedItem instances (in CombatSummaryUserDefinedWeaponOrder)
        // then add CombatSummaryUserDefinedItem instances in default order.
        if ($this->combat_summary_user_defined_weapon_order->isEmpty()) {

            $this->populateDefaultCombatSummaryItems($pdo, $player_name, $character_name, $errors);

            // Initialize again to pick up newly inserted records
            $this->combat_summary_user_defined_weapon_order->init($pdo, $player_name, $character_name, $errors);
        }
    }

    protected function renderSection($combat_mode) {
        error_log("renderSection($combat_mode)");

        $user_defined_item_list = $this->combat_summary_user_defined_weapon_order->getItemsForSection($combat_mode);
        $section_name = getMountedCombatModeDescription($combat_mode);

        $form_name = "edit-weapon-order-$section_name";
        $output_html  = $this->formatFormStartHtml($form_name, $section_name);
        $output_html .= $this->formatTableStartHtml();
        
        $max_display_order = -1;
        foreach($user_defined_item_list AS $user_defined_item) {
            if ($user_defined_item->getDisplayOrder() > $max_display_order) {
                $max_display_order = $user_defined_item->getDisplayOrder();
            }

            $renderer_id = $user_defined_item->getRendererId();

            $output_html .= '<tr>' . PHP_EOL;
            $output_html .= $this->formatCheckboxCell($form_name, $renderer_id, $user_defined_item->getDisplayOrder());

            $weapon_renderer = $this->renderers[$renderer_id];
            if (!empty($weapon_renderer)) {
                $output_html .= $this->formatRendererCell($weapon_renderer);
                unset($this->renderers[$renderer_id]);
            } else {
                $this->logMissingRenderer($renderer_id, $user_defined_item);
                $output_html .= '<td>&nbsp;</td>' . PHP_EOL;
            }

            $output_html .= '</tr>' . PHP_EOL;
        }

        $remaining_renderer_count = count($this->renderers) > 0 ? count($this->renderers) : "0";
        $log_output = "Count of remaining renderers: $remaining_renderer_count";
        error_log($log_output);
        foreach($this->renderers AS $renderer_id_key => $renderer) {

            $max_display_order++;

            $renderer_id = $renderer->getId();
            $log_output = "Renderer ID: $renderer_id  has not rendered. Default adding. Max Display Order: $max_display_order";
            error_log($log_output);

            $errors = [];
            // Populate a Combat Summary Item instance at the bottom of the Combat Summary section
            $this->populateDefaultCombatSummaryItem($renderer, $combat_mode, $max_display_order, $errors);

            $output_html .= '<tr>' . PHP_EOL;

            $output_html .= $this->formatCheckboxCell($form_name, $renderer->getId(), $max_display_order);
            $output_html .= $this->formatRendererCell($renderer);
            $renderer->setHasRendered(true);

            $output_html .= '</tr>' . PHP_EOL;
        }

        $output_html .= HtmlHelper::buildHiddenTag(DISPLAY_COUNT, $max_display_order) . PHP_EOL;

        $output_html .= '</table>' . PHP_EOL; 
        $output_html .= '</form>' . PHP_EOL;

        echo $output_html;
    }

    private function populateRenderers() {
        error_log("populateRenderers");

        foreach($this->getTwoWeaponFightingConfigurationSet() AS $two_weapon_config) {
            $two_weapon_renderer = new TwoWeaponFightingRenderer($two_weapon_config, $this->getPlayerCharacterWeaponSet(), $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
            $this->renderers[$two_weapon_renderer->getId()] = $two_weapon_renderer;
            $two_weapon_renderer_id = $two_weapon_renderer->getId();
            $log_output = "Two renderer Weapon ID: $two_weapon_renderer_id";
            error_log($log_output);
        }

        foreach($this->getPlayerCharacterWeaponSet() AS $player_character_weapon) {
            $player_character_weapon_renderer = new PlayerCharacterWeaponRenderer($player_character_weapon, $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
            $player_character_weapon_renderer->setCombatMode(COMBAT_MODE_UNMOUNTED);
            $this->renderers[$player_character_weapon_renderer->getId()] = $player_character_weapon_renderer;
            $unmounted_one_hand_renderer_id = $player_character_weapon_renderer->getId();
            $log_output = "Unmounted One hand renderer: $unmounted_one_hand_renderer_id";
            error_log($log_output);

            if ($this->is_mounted_section_needed) {
                $player_character_weapon_renderer = new PlayerCharacterWeaponRenderer($player_character_weapon, $this->getPlayerCharacterSkillSet(), $this->getCharacterDetails(), $this->getAttributeMetadata(), $this->getRowClassManager());
                $player_character_weapon_renderer->setCombatMode(getMountedCombatModeDescription(COMBAT_MODE_MOUNTED));
                $this->renderers[$player_character_weapon_renderer->getId()] = $player_character_weapon_renderer;
                $mounted_one_hand_renderer_id = $player_character_weapon_renderer->getId();
                $log_output = "Mounted One hand renderer: $mounted_one_hand_renderer_id";
            }
        }
    }

    protected function renderHeader() {
        return '';
    }

    private function populateDefaultCombatSummaryItems(PDO $pdo, $player_name, $character_name, &$errors) {
        error_log("populateDefaultCombatSummaryItems");
        $renderer_index = 1;
        foreach($this->renderers AS $renderer_id_key => $renderer) {
            if ($renderer->getType() == RendererType::weapon) {
                $this->addOneWeaponCombatSummaryItem($pdo, $player_name, $character_name, getMountedCombatModeDescription(COMBAT_MODE_UNMOUNTED), $renderer->getId(), $renderer->getPlayerCharacterWeapon()->getWeaponId(), $renderer_index, $errors);
                $renderer_id = $renderer->getId();
                $weapon_id = $renderer->getPlayerCharacterWeapon()->getWeaponId();
                $log_output = "Player name: $player_name  Character name: $character_name  Renderer ID: $renderer_id  Weapon ID: $weapon_id";
                error_log($log_output);
                if (count($errors) > 0) {
                    die(json_encode($errors));
                }
            }

            if ($renderer->getType() == RendererType::twoWeapon) {
                $this->addTwoWeaponCombatSummaryItem($pdo, $player_name, $character_name, getMountedCombatModeDescription(COMBAT_MODE_UNMOUNTED), $renderer->getId(), $renderer->getMainHandWeapon()->getWeaponId(), $renderer->getOffHandWeapon()->getWeaponId(), $renderer->getTwoWeaponFightingConfig()->getTwoWeaponConfigurationId(), $renderer_index, $errors);
                $renderer_id = $renderer->getId();
                $two_weapon_id = $renderer->getTwoWeaponFightingConfig()->getTwoWeaponConfigurationId();
                $log_output = "Player name: $player_name  Character name: $character_name  Renderer ID: $renderer_id  Two Weapon Config ID: $two_weapon_id";
                error_log($log_output);
                if (count($errors) > 0) {
                    die(json_encode($errors));
                }
            }

            $renderer_index++;
        }
    }

    private function populateDefaultCombatSummaryItem($renderer, $combat_mode, $max_display_order, &$errors) {
        if ($renderer->getType() == RendererType::weapon) {
            $this->addOneWeaponCombatSummaryItem($this->getPdo(), $this->getPlayerName(), $this->getCharacterName(), getMountedCombatModeDescription($combat_mode), $renderer->getId(), $renderer->getPlayerCharacterWeapon()->getWeaponId(), $max_display_order, $errors);
            if (count($errors) > 0) {
                die(json_encode($errors));
            }
        }

        // Insert Combat Summary Item for a two weapon config
        if ($renderer->getType() == RendererType::twoWeapon) {
            $this->addTwoWeaponCombatSummaryItem($this->getPdo(), $this->getPlayerName(), $this->getCharacterName(), getMountedCombatModeDescription($combat_mode), $renderer->getId(), $renderer->getMainHandWeapon()->getWeaponId(), $renderer->getOffHandWeapon()->getWeaponId(), $renderer->getTwoWeaponFightingConfig()->getTwoWeaponConfigurationId(), $max_display_order, $errors);
            if (count($errors) > 0) {
                die(json_encode($errors));
            }
        }
    }

    private function formatFormStartHtml($form_name, $section_name) {
        $output_html  = '<form id="' . $form_name . '" name="' . $form_name . '" method="POST" action="' . CurlHelper::buildCharacterActionRouterUrl() . '">' . PHP_EOL;
        $output_html .= HtmlHelper::buildHiddenTag(CHARACTER_ACTION, CHARACTER_ACTION_UPDATE_COMBAT_SUMMARY_WEAPON_ORDER);
        $output_html .= HtmlHelper::buildHiddenTag(PLAYER_NAME, $this->getPlayerName());
        $output_html .= HtmlHelper::buildHiddenTag(CHARACTER_NAME, $this->getCharacterDetails()->getCharacterName());
        $output_html .= HtmlHelper::buildHiddenTag(SECTION_NAME, $section_name);

        return $output_html;
    }

    private function formatTableStartHtml() {
        $output_html  = '<table style="width: 100%;">' . PHP_EOL;
        $output_html .= '<tr><td style="width: 5%;">&nbsp;</td><td style="width: 95%;">' . $this->formatColumnHeaders() . '</td></tr>' . PHP_EOL;

        return $output_html;
    }

    private function formatCheckboxCell($form_name, $renderer_id, $display_order) {
        $output_html  = '<td style="text-align: center;">' . PHP_EOL;
        $form_name_quoted = "'" . $form_name . "'";
        $output_html .= '<input type="checkbox" id="cb-' . $renderer_id . '" name="display-order-' . $display_order . '" value="' . $renderer_id . '" onchange="weaponCheckboxChanged(' . $form_name_quoted . ');">';
        $output_html .= '</td>' . PHP_EOL;

        return $output_html;
    }

    private function formatRendererCell($renderer) {
        $output_html  = '<td>';
        $output_html .= $renderer->render();
        $output_html .= '</td>' . PHP_EOL;

        return $output_html;
    }

    private function logMissingRenderer($renderer_id, CombatSummaryUserDefinedItem $user_defined_item) {
        $player_name = $this->getPlayerName();
        $character_name = $this->getCharacterName();
        $db_id = !empty($user_defined_item->getPlayerCharacterWeaponId()) ? $user_defined_item->getPlayerCharacterWeaponId() : $user_defined_item->getTwoWeaponFightingConfigId();

        $log_text = "!!! Missing Renderer!!!  Player name: $player_name  Character name: $character_name  Renderer ID: $renderer_id  DB ID: $db_id";
        error_log($log_text);
    }

    private function addOneWeaponCombatSummaryItem(PDO $pdo, $player_name, $character_name, $section_name, $renderer_id, $player_character_weapon_id, $display_order, &$errors) {
        $sql_exec = "CALL addCombatSummaryWeaponOrderItem(:playerName, :characterName, :sectionName, :rendererId, :playerCharacterWeaponId, :playerCharacterWeapon2Id, :twoWeaponFightingConfigurationId, :displayOrder)";

        $null_value = NULL;
        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        $statement->bindParam(':sectionName', $section_name, PDO::PARAM_STR);
        $statement->bindParam(':rendererId', $renderer_id, PDO::PARAM_STR);
        $statement->bindParam(':playerCharacterWeaponId', $player_character_weapon_id, PDO::PARAM_INT);
        $statement->bindParam(':playerCharacterWeapon2Id', $null_value, PDO::PARAM_NULL);
        $statement->bindParam(':twoWeaponFightingConfigurationId', $null_value, PDO::PARAM_NULL);
        $statement->bindParam(':displayOrder', $display_order, PDO::PARAM_INT);

        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in CombatSummaryRendererEditWeaponOrder.addOneWeaponCombatSummaryItem : " . $e->getMessage();
        }
    }

    private function addTwoWeaponCombatSummaryItem(PDO $pdo, $player_name, $character_name, $section_name, $renderer_id, $player_character_weapon_id, $player_character_weapon2_id, $two_weapon_fighting_configuration_id, $display_order, &$errors) {
        $sql_exec = "CALL addCombatSummaryWeaponOrderItem(:playerName, :characterName, :sectionName, :rendererId, :playerCharacterWeaponId, :playerCharacterWeapon2Id, :displayOrder)";

        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        $statement->bindParam(':sectionName', $section_name, PDO::PARAM_STR);
        $statement->bindParam(':rendererId', $renderer_id, PDO::PARAM_STR);
        $statement->bindParam(':playerCharacterWeaponId', $player_character_weapon_id, PDO::PARAM_INT);
        $statement->bindParam(':playerCharacterWeapon2Id', $player_character_weapon2_id, PDO::PARAM_INT);
        $statement->bindParam(':twoWeaponFightingConfigurationId', $two_weapon_fighting_configuration_id, PDO::PARAM_INT);
        $statement->bindParam(':displayOrder', $display_order, PDO::PARAM_INT);

        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in CombatSummaryRendererEditWeaponOrder.addTwoWeaponCombatSummaryItem : " . $e->getMessage();
        }
    }
}
?>