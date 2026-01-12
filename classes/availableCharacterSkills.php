<?php

require_once __DIR__ . '/../webio/characterName.php';
require_once __DIR__ . '/../dbio/constants/characterClasses.php';
require_once __DIR__ . '/../dbio/constants/characterRaces.php';
require_once __DIR__ . '/../dbio/constants/skillReserveIds.php';

require_once 'availableCharacterSkill.php';
require_once 'accountCharacterSummary.php';

class AvailableCharacterSkills implements JsonSerializable {

    private $available_skills = [];

    public function init(\PDO $pdo, $player_name, $character_name, $character_skills, &$errors, &$log) {
        $character_details = $this->getExistingCharacter($player_name, $character_name);
        $character_race_id = getGenericRaceID($character_details->race);
        $character_intelligence = $character_details->characterIntelligence;
        $character_dexterity = $character_details->characterDexterity;
        $character_charisma = $character_details->characterCharisma;

        $available_skill_results_per_class = [];
        foreach($character_details->character_classes AS $character_class) {
            $character_class_id = $character_class->class_id;
            $character_class_level = $character_class->class_level;
    
            $available_skill_results_per_class[] = $this->getSkillsAvailableForPlayerCharacterClass($pdo, $character_class_id, $character_race_id, $character_class_level, $character_intelligence, $character_dexterity, $character_charisma, $errors);
        }

        // Collapse any duplicate skills available to all classes
        $all_available_skills = array_unique($available_skill_results_per_class);

        $this->available_skills = [];
        
        // Form a map of skill_catalog IDs the character already has with the value being the count of the number of occurences of that skill as the value
        $existing_skill_count_map = $this->createSkillCountLookup($character_skills);
        error_log('existing_skill_count_map');
        error_log(print_r($existing_skill_count_map, true));

        // Form a map of skill_catalog IDs the character already has with the value being whether or not that skill has a Skill Focus of true or false
        error_log('existing_skill_focus_map');
        $existing_skill_focus_map = $this->createSkillFocusLookup($character_skills);
        error_log(print_r($existing_skill_focus_map, true));

        error_log("Skill Dump");
        $skill_count = 1;
        foreach($all_available_skills[0] AS $available_skill) {

            error_log("Skill #${skill_count}");
            error_log(print_r($available_skill, true));

            // Get Skill ID
            $available_skill_id = $available_skill['skill_catalog_id'];

            // Get max count
            $available_max_count = $available_skill['skill_catalog_max_count'];

            // Get the count of occurrences in characters existing skills
            $existing_count = 0;
            if (!empty($existing_skill_count_map[$available_skill_id])) {
                $existing_count = $existing_skill_count_map[$available_skill_id];
            }

            // Determine if the character has taken a skill focus on the current skill
            $skill_focus_taken = $this->skillFocusTaken($existing_skill_focus_map, $available_skill_id);

            // Can the skill be taken with a skill focus
            $skill_can_focus = $available_skill['skill_catalog_skill_focus'];

            $log_output = 'Skill ID: [' . $available_skill_id . '] Skill max count: [' . $available_max_count . '] Player Skill count: [' .  $existing_count . '] Skill Focus taken: [' . $skill_focus_taken . '] Skill can Focus: [' . $skill_can_focus . ']';
            error_log($log_output);

            $skill_count++;

            // If the existing count = available max count and the skill has NO skill focus, 
            //      then do not include that skill in the list
            if($existing_count == $available_max_count && $skill_can_focus == FALSE) {
                continue;
            }

            // If the existing count = available max count and the skill HAS a skill focus AND the character HAS already taken the skill focus, 
            //      then do not include that skill in the list
            if($existing_count == $available_max_count && $skill_can_focus == TRUE && $skill_focus_taken) {
                continue;
            }

            // If the existing count = available max count and the skill HAS a skill focus AND the character has NOT already taken the skill focus,
            //      leave the skill focus to TRUE. The UI will then show the skill focus as available to be taken
            // No code ... available_skill is in the correct state

            // If the existing count < available max count and the skill HAS a skill focus AND the character has NOT already taken the skill focus,
            //      set the skill focus in the available skill to FALSE. The UI will simply show the skill as available to be taken
            if($existing_count < $available_max_count && $skill_can_focus == TRUE && !$skill_focus_taken) {
                $available_skill['skill_catalog_skill_focus'] = FALSE;
            }

            // If this skill has no prerequisite skill, then add it to the list
            if($available_skill['skill_prerequisite_skill_id'] === SKILL_ID_NO_PREREQUISITE) {
                $this->available_skills[] = $available_skill;
            }
            else {
                $character_skill_ids = array_column($character_skills, 'skill_catalog_id');
                $pass_prerequisite_skills = $this->check_prerequisite_skills($available_skill, $all_available_skills, $character_skill_ids, $this->available_skills);
                if($pass_prerequisite_skills) {
                    $this->available_skills[] = $available_skill;
                }
            }
        }

        // Skills WITH prerequisite skills
        $available_skills_with_prerequisite = array_filter($this->available_skills, static function ($element) {
            return $element['skill_prerequisite_skill_id'] !== SKILL_ID_NO_PREREQUISITE;
        });

        $log[] = 'Skills WITH prerequisite skills';
        $log[] = $available_skills_with_prerequisite;

        // Skills with NO prerequisite skills
        $available_skills_with_no_prerequisite = array_filter($this->available_skills, static function ($element) {
            return $element['skill_prerequisite_skill_id'] === SKILL_ID_NO_PREREQUISITE;
        });

        $log[] = 'Skills with NO prerequisite skills';
        $log[] = $available_skills_with_no_prerequisite;

        // Remove skills where prerequisite skills are missing
        // $this->available_skills = $this->filter_prerequisite_skills($available_skills_with_prerequisite, $character_skill_ids);
    }

    public function getSkillsAvailableForPlayerCharacterClass(\PDO $pdo, $character_class, $character_race, $character_class_level, $character_intelligence, $character_dexterity, $character_charisma, &$errors) {
        $sql_exec = "CALL getSkillsAvailableForPlayerCharacterClass(:playerCharacterClass, :playerCharacterRace, :playerCharacterLevel, :playerCharacterIntelligence, :playerCharacterDexterity, :playerCharacterCharisma)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerCharacterClass', $character_class, PDO::PARAM_INT);
		$statement->bindParam(':playerCharacterRace', $character_race, PDO::PARAM_INT);
		$statement->bindParam(':playerCharacterLevel', $character_class_level, PDO::PARAM_INT);
		$statement->bindParam(':playerCharacterIntelligence', $character_intelligence, PDO::PARAM_INT);
		$statement->bindParam(':playerCharacterDexterity', $character_dexterity, PDO::PARAM_INT);
		$statement->bindParam(':playerCharacterCharisma', $character_charisma, PDO::PARAM_INT);

		try {
			$statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getSkillsAvailableForPlayerCharacterClass : " . $e->getMessage();
        }    

		return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    function getExistingCharacter($player_name, $character_name) {
        $params = [];
        $params[PLAYER_NAME] = $player_name;
        $params[CHARACTER_NAME] = $character_name;
        $params[SESSION_COOKIE_NAME] = $_COOKIE[SESSION_COOKIE_NAME];
        
        $url = CurlHelper::buildUrl('getPlayerCharacterDetails');
        $raw_results = CurlHelper::performGetRequest($url, $params);
    
        return json_decode($raw_results);
    }

    function check_prerequisite_skills($current_available_skill, $all_candidate_skills, $character_skill_ids, $already_qualified_skills) {
        $qualified_skill_ids = array_column($already_qualified_skills, 'skill_catalog_id');
        if(in_array($current_available_skill['skill_catalog_id'], $qualified_skill_ids)) {
            return false;   // Skill previously added. Don't add again.
        }

        // Check prerequisite skills. If ALL prerequisite skills NOT present, don't add
        $skill_catalog_id = $current_available_skill['skill_catalog_id'];
        $all_skill_rows_for_current_skill =  array_filter($all_candidate_skills, static function ($skill_catalog_id) {
            return $all_candidate_skills['skill_catalog_id'] = $skill_catalog_id;
        });

        $prerequisite_skill_ids = array_column($all_skill_rows_for_current_skill, 'skill_prerequisite_skill_id');
        $matching_prerequisite_skill_ids = array_intersect($prerequisite_skill_ids, $character_skill_ids);

        return count($prerequisite_skill_ids) == count($matching_prerequisite_skill_ids);
    }

    function skillFocusTaken($existing_skill_focus_map, $available_skill_id) {
        $result = FALSE;
        if (!empty($existing_skill_focus_map[$available_skill_id])) {
            $result = $existing_skill_focus_map[$available_skill_id];
        }

        return $result;
    }

    //$character_skills :: skill_catalog_id, is_skill_focus, player_skill_count
    function createSkillCountLookup($current_skills) {
        $map_skill_count = [];
        foreach($current_skills AS $current_skill) {
            $skill_id = $current_skill['skill_catalog_id'];
            $skill_count = $current_skill['player_skill_count'];
            $map_skill_count[$skill_id] = $skill_count;
        }

        return $map_skill_count;
    }

    function createSkillFocusLookup($current_skills) {
        $map_skill_focus = [];
        foreach($current_skills AS $current_skill) {
            $skill_id = $current_skill['skill_catalog_id'];
            $is_skill_focus = $current_skill['is_skill_focus'];
            $map_skill_focus[$skill_id] = $is_skill_focus;
        }

        return $map_skill_focus;
    }


    // function called when encoded with json_encode
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}

?>