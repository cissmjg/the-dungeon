<?php

require_once 'playerCharacterReadySpell.php';

require_once __DIR__ . '/../helper/SpellCalculationHelper.php';
require_once __DIR__ . '/../dbio/constants/emptySpellSlot.php';

class PlayerCharacterReadySpellSet implements IteratorAggregate, JsonSerializable {

    /** @var playerCharacterReadySpell[] */
    private array $playerCharacterReadySpellList = [];

    private $remove_empty = false;
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

    public function init(PDO $pdo, $player_name, $character_name, &$errors) {
        $character_classes = $this->getCharacterClasses($pdo, $player_name, $character_name, $errors);
        if (count($errors) > 0) {
            die(json_encode($errors));
        }

        foreach($character_classes AS $character_class) {
            $character_class_name = $character_class['class_name'];
            $spells_for_class = $this->getReadySpells($pdo, $player_name, $character_name, $character_class_name, $errors);
            if (count($errors) > 0) {
                die(json_encode($errors));
            }

            if (count($spells_for_class) > 0) {
                foreach($spells_for_class AS $spell_for_class) {
                    // If only populated slot are desired, then skip [NONE] spells
                    if ($this->getRemoveEmpty() && $spell_for_class['spell_name'] == EMPTY_SLOT_SPELL_NAME) {
                        continue;
                    }

                    // 'Normalize' Casting time, Duration, Range and spellcaster adjusted level
                    $normalized_spell = $this->normalizeReadySpells($spell_for_class, $character_class);

                    $player_character_ready_spell = new PlayerCharacterReadySpell();
                    $player_character_ready_spell->init($normalized_spell);
                    $this->add($player_character_ready_spell);
                }
            }
        }
    }

    public function fromJSON($player_character_ready_spell_set_json) {
        for ($i = 0; $i < count($player_character_ready_spell_set_json); $i++) {
            $ready_spell_json = $player_character_ready_spell_set_json[$i];

            $ready_spell = new PlayerCharacterReadySpell();
            $ready_spell->fromJSON($ready_spell_json);
            $this->add($ready_spell);
        }
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public function getIterator(): Traversable {
        return new ArrayIterator($this->playerCharacterReadySpellList);
    }

    public function add(PlayerCharacterReadySpell $playerCharacterReadySpell): void {
        $this->playerCharacterReadySpellList[] = $playerCharacterReadySpell;

        $character_class_name = $playerCharacterReadySpell->getCharacterClassName();
        if (!array_key_exists($character_class_name, $this->spellMap)) {
            $this->spellMap[$character_class_name] = [];
        }

        $spell_type_name = $playerCharacterReadySpell->getSpellTypeName();
        if (!array_key_exists($spell_type_name, $this->spellMap[$character_class_name])) {
            $this->spellMap[$character_class_name][$spell_type_name] = [];
        }

        $spell_level = empty($playerCharacterReadySpell->getPlayerSlotLevel()) ? "Cantrip" : $playerCharacterReadySpell->getPlayerSlotLevel();
        if (!array_key_exists($spell_level, $this->spellMap[$character_class_name][$spell_type_name])) {
            $this->spellMap[$character_class_name][$spell_type_name][$spell_level] = [];
        }

         $this->spellMap[$character_class_name][$spell_type_name][$spell_level][] =  $playerCharacterReadySpell;
    }

    /** @return playerCharacterReadySpell[] */
    public function getAll(): array {
        return $this->playerCharacterReadySpellList;
    }

    public function isEmpty() {
        return count($this->playerCharacterReadySpellList) == 0;
    }

    private function normalizeReadySpells($spell_for_class, $character_class) {
        $character_level = SpellCalculationHelper::getAdjustedCasterLevel($character_class, $character_class['character_level'], $spell_for_class['player_slot_spell_type_id']);
        $normalized_spell_for_class = [];
        $normalized_spell_for_class['spell_type'] = $spell_for_class['spell_type'];
        $normalized_spell_for_class['player_slot_level'] = $spell_for_class['player_slot_level'];
        $normalized_spell_for_class['player_slot_spell_slot_type_id'] = $spell_for_class['player_slot_spell_slot_type_id'];
        $normalized_spell_for_class['spell_name'] = $spell_for_class['spell_name'];
        $normalized_spell_for_class['spell_link'] = $spell_for_class['spell_link'];
        $normalized_spell_for_class['spell_slot_id'] = $spell_for_class['spell_slot_id'];
        $normalized_spell_for_class['has_spell_cast'] = $spell_for_class['has_spell_cast'];
        $normalized_spell_for_class['character_class_name'] = $spell_for_class['character_class_name'];
        $normalized_spell_for_class['spell_casting_time'] = SpellCalculationHelper::getSpellCastingTime($spell_for_class);
        $normalized_spell_for_class['spell_range'] = SpellCalculationHelper::getSpellRange($spell_for_class, $character_level);
        $normalized_spell_for_class['spell_duration'] = SpellCalculationHelper::getSpellDuration($spell_for_class, $character_level);
        $normalized_spell_for_class['spell_area_of_effect'] = $spell_for_class['spell_area_of_effect'];
        $normalized_spell_for_class['player_slot_casting_time_remaining'] = $spell_for_class['player_slot_casting_time_remaining'];
        $normalized_spell_for_class['player_slot_running_time_remaining'] = $spell_for_class['player_slot_running_time_remaining'];

        // Does the spell take 1 round or more to cast
        $cast_time_exceeds_round = $spell_for_class['spell_casting_time_in_rounds'] != NULL;

        // Calculate spell duration in terms of rounds
        $spell_duration_in_rounds = SpellCalculationHelper::calculateDurationInRounds($character_level, $spell_for_class['spell_duration_time_fixed'], $spell_for_class['spell_duration_time_fixed_uom'], $spell_for_class['spell_duration_time_per_level'], $spell_for_class['spell_duration_time_per_level_uom'], $spell_for_class['spell_duration_level_factor'], $cast_time_exceeds_round);
        if($spell_duration_in_rounds != 0) {
            $normalized_spell_for_class['spell_duration_in_rounds'] = $spell_duration_in_rounds;
        }

        if($spell_for_class['spell_casting_time_in_rounds'] != NULL) {
            $normalized_spell_for_class['spell_casting_time_in_rounds'] = $spell_for_class['spell_casting_time_in_rounds'];
        }

        return $normalized_spell_for_class;
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

    private function getReadySpells(\PDO $pdo, $player_name, $character_name, $character_class_name, &$errors) {
        $sql_exec = "CALL getReadySpells(:playerName, :characterName, :characterClassName)";

        $statement = $pdo->prepare($sql_exec);
        $statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
        $statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
        $statement->bindParam(':characterClassName', $character_class_name, PDO::PARAM_STR);
        try {
            $statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getReadySpells : " . $e->getMessage();
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
/*
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a nested associative array
$company = [
    "HR" => [
        "Manager" => "Alice Johnson",
        "Employees" => [
            "E001" => "John Smith",
            "E002" => "Maria Garcia",
            "E003" => "David Lee"
        ]
    ],
    "IT" => [
        "Manager" => "Robert Brown",
        "Employees" => [
            "E101" => "Sophia Martinez",
            "E102" => "James Wilson"
        ]
    ],
    "Finance" => [
        "Manager" => "Emily Davis",
        "Employees" => [
            "E201" => "Michael Clark"
        ]
    ]
];

// Accessing nested values
echo "HR Manager: " . $company["HR"]["Manager"] . "<br>";
echo "IT Employee E102: " . $company["IT"]["Employees"]["E102"] . "<br>";

// Adding a new employee to Finance
$company["Finance"]["Employees"]["E202"] = "Olivia Taylor";

// Loop through departments
foreach ($company as $department => $details) {
    echo "<h3>$department Department</h3>";
    echo "Manager: " . $details["Manager"] . "<br>";
    echo "Employees:<br>";
    foreach ($details["Employees"] as $id => $name) {
        echo "&nbsp;&nbsp;$id: $name<br>";
    }
}
    */
?>