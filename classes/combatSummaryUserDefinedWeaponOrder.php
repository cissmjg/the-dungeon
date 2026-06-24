<?php

require_once 'combatSummaryUserDefinedItem.php';
require_once 'playerCharacterWeaponRendererBase.php';
require_once __DIR__ . '/../dbio/constants/mountedCombatMode.php';

class CombatSummaryUserDefinedWeaponOrder implements JsonSerializable {

    private $is_initialized = false;
    public function isInitialized() {
        return $this->is_initialized;
    }

    public function setInitialized($is_initialized) {
        if ($this->is_initialized) {
            return;
        }

        $this->is_initialized = $is_initialized;
    }

    private $next_display_order = 1;

    /** @var CombatSummaryUserDefinedItem[] */
    private array $combatSummarySections = [];

    public function init(PDO $pdo, $player_name, $character_name, &$errors) {
        $combat_summary_items = $this->getCombatSummaryUserDefinedItems($pdo, $player_name, $character_name, $errors);
        if (count($errors) > 0) {
            die(json_encode($errors));
        }

        foreach($combat_summary_items AS $combat_summary_item) {
            $current_combat_summary_item = new CombatSummaryUserDefinedItem();
            $current_combat_summary_item->init($combat_summary_item);

            $this->addForSection($combat_summary_item, $current_combat_summary_item->getSectionName());
        }

        $this->is_initialized = true;
    }

    public function newUserDefinedItemFromRenderer($renderer_id, $combat_mode) {

        $combat_summary_item = new CombatSummaryUserDefinedItem();
        $combat_section = getMountedCombatModeDescription($combat_mode);

        // Use $this->next_display_order as a temporary ID
        $combat_summary_item->initFromRenderer($renderer_id, $this->next_display_order, $this->next_display_order, $combat_section);
        $this->addForSection($combat_summary_item, $combat_section);

        $this->next_display_order++;
    }

    /** @return CombatSummaryUserDefinedItem[] */
    public function getItemsForSection($combat_mode) {
        $items_for_section = [];
        $combat_section = getMountedCombatModeDescription($combat_mode);
        if (array_key_exists($combat_section, $this->combatSummarySections)) {
            $items_for_section = $this->combatSummarySections[$combat_section];
        }

        return $items_for_section;
    }

    private function addForSection(CombatSummaryUserDefinedItem $combat_summary_item, $section_name) {
        if (!array_key_exists($section_name, $this->combatSummarySections)) {
            $this->combatSummarySections[$section_name] = [];
        }

        $this->combatSummarySections[$section_name][] = $combat_summary_item;
    }

    private function getCombatSummaryUserDefinedItems(PDO $pdo, $player_name, $character_name, &$errors) {
        $sql_exec = "CALL getCombatSummaryUserDefinedItems(:playerName, :characterName)";
        
        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);

        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in CombatSummaryUserDefinedWeaponOrder.getCombatSummaryUserDefinedItems : " . $e->getMessage();
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
?>
