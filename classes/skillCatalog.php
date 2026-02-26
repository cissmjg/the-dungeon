<?php
    require_once 'skillDetail.php';
    require_once '/../dbio/constants/skillReserveIds.php';

    class SkillCatalog implements JsonSerializable {

        private $all_skills = [];

        public function init(\PDO $pdo, &$errors) {

            $skill_list = $this->getAllSkillsWithPrerequisites($pdo, $errors);
            if (count($errors) > 0) {
                return;
            }

            foreach($skill_list AS $skill_item) {
                $skill_catalog_id = $skill_item['skill_catalog_id'];
                $skill = null;
                if(array_key_exists($skill_catalog_id, $this->all_skills)) {
                    $skill = $this->all_skills[$skill_catalog_id];
                } else {
                    $skill = new SkillDetail();
                    $skill->init($skill_item);
                    $this->all_skills[$skill_catalog_id] = $skill;
                }

                // Add to prerequisites
                if ($skill_item['skill_catalog_prerequisite_id'] != SKILL_ID_NO_PREREQUISITE) {
                    $skill->addSkillPrerequisite($skill_item['skill_catalog_prerequisite_id']);
                }
            }
        }

        private function getAllSkillsWithPrerequisites(\PDO $pdo, &$errors) {
            $sql_exec = "CALL getAllSkillsWithPrerequisites()";

            $statement = $pdo->prepare($sql_exec);

            try {
                $statement->execute();
            } catch(Exception $e) {
                $errors[] = "Exception in getAllSkillsWithPrerequisites : " . $e->getMessage();
            } 

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } 

        public function jsonSerialize() {
            return get_object_vars($this);
        }

        public function getAllSkills() {
            return $this->all_skills;
        }

        public function getSkill($skill_catalog_id) {
            return $this->all_skills[$skill_catalog_id];
        }
    }
?>