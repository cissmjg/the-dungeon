<?php

require_once 'playerCharacterPoolSpell.php';

require_once __DIR__ . '/../dbio/constants/emptySpellSlot.php';

class PlayerCharacterSpellPool implements IteratorAggregate, JsonSerializable {
    /** @var playerCharacterPoolSpell[] */
    private array $playerCharacterPoolSpellList = [];

    private $remove_empty = true;
    public function getRemoveEmpty() {
        return $this->remove_empty;
    }

    public function setRemoveEmpty($remove_empty) {
        $this->remove_empty = $remove_empty;
    }
    
    private array $spellMap = [];
    public function getSpellMap() {
        return $this->spellMap;
    }

    private array $optionsMap = [];
    public function getOptionsMap() {
        return $this->optionsMap;
    }

    private $numberFormatter;
    public function getNumberFormatter() {
        return $this->numberFormatter;
    }

    public function init(PDO $pdo, $player_name, $character_name, &$errors) {
        $locale = 'en_US';
        $this->numberFormatter = new NumberFormatter($locale, NumberFormatter::ORDINAL);

        $character_classes = $this->getCharacterClasses($pdo, $player_name, $character_name, $errors);
        if (count($errors) > 0) {
            die(json_encode($errors));
        }

        foreach($character_classes AS $character_class) {
            $character_class_name = $character_class['class_name'];
            $spell_pool_class = $this->getSpellBookForPlayerCharacter($pdo, $player_name, $character_name, $character_class_name, $errors);
            if (count($errors) > 0) {
                die(json_encode($errors));
            }

            if (count($spell_pool_class) > 0) {
                foreach($spell_pool_class AS $spell) {
                    // Prune empty spellbook slots?
                    if ($this->getRemoveEmpty() && $spell['spell_name'] == EMPTY_SLOT_SPELL_NAME) {
                        continue;
                    }

                    $player_character_pool_spell = new PlayerCharacterPoolSpell();
                    $player_character_pool_spell->init($spell);
                    $this->add($player_character_pool_spell);
                }
            }
        }
    }
    
    public function fromJSON($spell_pool_json) {
        $locale = 'en_US';
        $this->numberFormatter = new NumberFormatter($locale, NumberFormatter::ORDINAL);

        for ($i = 0; $i < count($spell_pool_json); $i++) {
            $spell_pool_entry_json = $spell_pool_json[$i];
            if ($this->getRemoveEmpty() && $spell_pool_entry_json->spell_name == EMPTY_SLOT_SPELL_NAME) {
                continue;
            }

            $player_character_pool_spell = new PlayerCharacterPoolSpell();
            $player_character_pool_spell->fromJSON($spell_pool_entry_json);
            $this->add($player_character_pool_spell);
        }
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->playerCharacterPoolSpellList);
    }

    public function add(PlayerCharacterPoolSpell $playerCharacterPoolSpell) {
        $this->playerCharacterPoolSpellList[] = $playerCharacterPoolSpell;

        $character_class_name = $playerCharacterPoolSpell->getCharacterClassName();
        if (!array_key_exists($character_class_name, $this->spellMap)) {
            $this->spellMap[$character_class_name] = [];
            $this->optionsMap[$character_class_name] = [];
        }

        $spell_type_name = $playerCharacterPoolSpell->getSpellTypeName();
        if (!array_key_exists($spell_type_name, $this->spellMap[$character_class_name])) {
            $this->spellMap[$character_class_name][$spell_type_name] = [];
            $this->optionsMap[$character_class_name][$spell_type_name] = [];
        }

        $spell_level = empty($playerCharacterPoolSpell->getSpellLevel()) ? "Cantrip" : $playerCharacterPoolSpell->getSpellLevel();
        if (!array_key_exists($spell_level, $this->spellMap[$character_class_name][$spell_type_name])) {
            $this->spellMap[$character_class_name][$spell_type_name][$spell_level] = [];
            $this->optionsMap[$character_class_name][$spell_type_name][$spell_level] = [];
            $this->optionsMap[$character_class_name][$spell_type_name][$spell_level][] = $this->formatOptGroup($spell_level);
        }

        $this->spellMap[$character_class_name][$spell_type_name][$spell_level][] = $playerCharacterPoolSpell;
        $this->optionsMap[$character_class_name][$spell_type_name][$spell_level][] = $this->formatOptionTag($playerCharacterPoolSpell->getSpellName(), $playerCharacterPoolSpell->getSpellCatalogId());
    }

    private function formatOptionTag($spell_name, $spell_catalog_id) {
        return '<option value="' . $spell_catalog_id . '">' . $spell_name . '</option>' . PHP_EOL;
    }

    private function formatOptGroup($spell_level) {
        $opt_group_label = '';
        if ($spell_level == 'Cantrip') {
            $opt_group_label = 'Cantrips';
        } else {
            $opt_group_label =$this->getNumberFormatter()->format($spell_level) . ' level';
        }

        return '<optgroup label="' . $opt_group_label . '">' . PHP_EOL;
    }

    /** @return playerCharacterPoolSpell[] */
    public function getAll() {
        return $this->playerCharacterPoolSpellList;
    }

    public function isEmpty() {
        return count($this->playerCharacterPoolSpellList) == 0;
    }

    public function getPool($character_class_name, $spell_type_name, $spell_level) {
        if (!empty($this->spellMap[$character_class_name][$spell_type_name][$spell_level])) {
            return $this->spellMap[$character_class_name][$spell_type_name][$spell_level];
        }

        return [];
    }

    public function getOptions($character_class_name, $spell_type_name, $spell_level) {
        if (!empty($this->optionsMap[$character_class_name][$spell_type_name][$spell_level])) {
            return $this->optionsMap[$character_class_name][$spell_type_name][$spell_level];
        }

        return [];
    }

    private function getCharacterClasses(PDO $pdo, $player_name, $character_name, &$errors) {
        $sql_exec = "CALL getCharacterClasses(:playerName, :characterName)";

        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getCharacterClasses : " . $e->getMessage();
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getSpellBookForPlayerCharacter(\PDO $pdo, $player_name, $character_name, $character_class_name, &$errors) {
        $sql_exec = "CALL getSpellPoolFullForPlayerCharacter(:playerName, :characterName, :characterClassName)";

        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        $statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in promoteCharacterClass : " . $e->getMessage();
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>