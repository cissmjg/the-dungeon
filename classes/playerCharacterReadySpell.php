<?php
require_once __DIR__ . '/../dbio/constants/readySpellState.php';

class PlayerCharacterReadySpell implements JsonSerializable {

    private $spell_type;
    private $player_slot_level;
    private $player_slot_spell_slot_type_id;
    private $spell_name;
    private $spell_link;
    private $spell_slot_id;
    private $has_spell_cast;
    private $character_class_name;
    private $spell_casting_time;
    private $spell_range;
    private $spell_duration;
    private $spell_area_of_effect;
    private $player_slot_casting_time_remaining;
    private $player_slot_running_time_remaining;
    private $spell_duration_in_rounds;
    private $spell_casting_time_in_rounds;
    private $spell_state;

    public function init($ready_spell) {
        $this->spell_type = $ready_spell['spell_type'];
        $this->player_slot_level = $ready_spell['player_slot_level'];
        $this->player_slot_spell_slot_type_id = $ready_spell['player_slot_spell_slot_type_id'];
        $this->spell_name = $ready_spell['spell_name'];
        $this->spell_link = $ready_spell['spell_link'];
        $this->spell_slot_id = $ready_spell['spell_slot_id'];
        $this->has_spell_cast = $ready_spell['has_spell_cast'];
        $this->character_class_name = $ready_spell['character_class_name'];
        $this->spell_casting_time = $ready_spell['spell_casting_time'];
        $this->spell_range = $ready_spell['spell_range'];
        $this->spell_duration = $ready_spell['spell_duration'];
        $this->spell_area_of_effect = $ready_spell['spell_area_of_effect'];
        $this->player_slot_casting_time_remaining = $ready_spell['player_slot_casting_time_remaining'];
        $this->player_slot_running_time_remaining = $ready_spell['player_slot_running_time_remaining'];
        $this->spell_duration_in_rounds = empty($ready_spell['spell_duration_in_rounds']) ? 0 : $ready_spell['spell_duration_in_rounds'];
        $this->spell_casting_time_in_rounds = empty($ready_spell['spell_casting_time_in_rounds']) ? 0 : $ready_spell['spell_casting_time_in_rounds'];
        
        $this->updateSpellState();
    }

    public function fromJSON($player_character_ready_spell) {
        $this->spell_type = $player_character_ready_spell->spell_type;
        $this->player_slot_level = $player_character_ready_spell->player_slot_level;
        $this->player_slot_spell_slot_type_id = $player_character_ready_spell->player_slot_spell_slot_type_id;
        $this->spell_name = $player_character_ready_spell->spell_name;
        $this->spell_link = $player_character_ready_spell->spell_link;
        $this->spell_slot_id = $player_character_ready_spell->spell_slot_id;
        $this->has_spell_cast = $player_character_ready_spell->has_spell_cast;
        $this->character_class_name = $player_character_ready_spell->character_class_name;
        $this->spell_casting_time = $player_character_ready_spell->spell_casting_time;
        $this->spell_range = $player_character_ready_spell->spell_range;
        $this->spell_duration = $player_character_ready_spell->spell_duration;
        $this->spell_area_of_effect = $player_character_ready_spell->spell_area_of_effect;
        $this->player_slot_casting_time_remaining = $player_character_ready_spell->player_slot_casting_time_remaining;
        $this->player_slot_running_time_remaining = $player_character_ready_spell->player_slot_running_time_remaining;
        $this->spell_duration_in_rounds = empty($player_character_ready_spell->spell_duration_in_rounds) ? 0 : $player_character_ready_spell->spell_duration_in_rounds;
        $this->spell_casting_time_in_rounds = empty($player_character_ready_spell->spell_casting_time_in_rounds) ? 0 : $player_character_ready_spell->spell_duration_in_rounds;
        
        $this->updateSpellState();
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }

    public function getSpellTypeName() {
        return $this->spell_type;
    }

    public function getPlayerSlotLevel() {
        return $this->player_slot_level;
    }

    public function getPlayerSlotSpellSlotTypeId() {
        return $this->player_slot_spell_slot_type_id;
    }

    public function getSpellName() {
        return $this->spell_name;
    }

    public function getSpellLink() {
        return $this->spell_link;
    }

    public function getSpellSlotId() {
        return $this->spell_slot_id;
    }

    public function isSpellCast() {
        return $this->has_spell_cast;
    }

    public function getCharacterClassName() {
        return $this->character_class_name;
    }

    public function getSpellCastingTime() {
        return $this->spell_casting_time;
    }

    public function getSpellRange() {
        return $this->spell_range;
    }

    public function getSpellDuration() {
        return $this->spell_duration;
    }

    public function getSpellAreaOfEffect() {
        return $this->spell_area_of_effect;
    }

    public function getPlayerSlotCastingTimeRemaining() {
        return $this->player_slot_casting_time_remaining;
    }

    public function getPlayerSlotRunningTimeRemaining() {
        return $this->player_slot_running_time_remaining;
    }

    public function getSpellDurationInRounds() {
        return $this->spell_duration_in_rounds;
    }

    public function getSpellCastingTimeInRounds() {
        return $this->spell_casting_time_in_rounds;
    }

    public function getSpellState() {
        return $this->spell_state;
    }

    private function updateSpellState() {
        if (!$this->isSpellCast()) {
            $this->spell_state = ReadySpellState::Ready;
        } else {
            $slotCastingTime = $this->getPlayerSlotCastingTimeRemaining();
            $slotRunningTime = $this->getPlayerSlotRunningTimeRemaining();
            if(($slotCastingTime == -1 && $slotRunningTime == -1) || ($slotCastingTime == 0 && $slotRunningTime == 0)) {
                // Refresh 
                $this->spell_state = ReadySpellState::AlreadyCast;
            }

            if($slotCastingTime > 0) {
                // Casting
                $this->spell_state = ReadySpellState::Casting;
            }

            if(($slotCastingTime == -1 || $slotCastingTime == 0) && $slotRunningTime > 0) {
                // Running 
                $this->spell_state = ReadySpellState::Running;
            }
        }

    }
}

?>
