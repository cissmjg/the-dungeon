<?php
require_once 'weaponType.php';

class WeaponDetail implements JsonSerializable
{
    private $weapon_name;
	private $weapon_id;
    private $weapon_proficiency_id;
    private $playerCharacterWeaponSkillId;

    private $weapon_melee_subtype = 0;    
    private $weapon_melee_type = 0;
	private $weapon_melee_additional_text = '';
    private $weapon_melee_damage = '';
	private $weapon_melee_number_of_hands = '';
	private $weapon_melee_speed = '';

    private $weapon_missile_type = 0;
    private $weapon_missile_subtype = 0;        
	private $weapon_missile_additional_text = '';	
	private $weapon_missile_damage = '';
	private $weapon_missile_number_of_hands = '';
	private $weapon_missile_speed = '';
    private $weapon_missile_long_range = '';
	private $weapon_missile_medium_range = '';
    private $weapon_missile_short_range = '';

    public function init(\PDO $pdo, $playerName, $characterName, $weaponProficiencyId, &$errors) {
		
		$weapon_details = $this->getWeaponDetail($pdo, $weaponProficiencyId, $errors);
        foreach($weapon_details AS $weapon_detail) {
            $this->weapon_name = $weapon_detail['weapon_name'];
            $this->weapon_id = $weapon_detail['weapon_id'];
            $this->weapon_proficiency_id = $weaponProficiencyId;
            if ($weapon_detail['weapon_type'] == WEAPON_TYPE_MELEE) {
                $this->weapon_melee_type = WEAPON_TYPE_MELEE;
                $this->weapon_melee_subtype = $weapon_detail['weapon_subtype'];
	            $this->weapon_melee_additional_text= $weapon_detail['weapon_additional_text'];
                $this->weapon_melee_damage = $weapon_detail['weapon_damage'];
	            $this->weapon_melee_number_of_hands = $weapon_detail['weapon_number_of_hands'];
	            $this->weapon_melee_speed = $weapon_detail['weapon_speed'];
            }

            if ($weapon_detail['weapon_type'] == WEAPON_TYPE_MISSILE) {
                $this->weapon_missile_type = WEAPON_TYPE_MISSILE;
                $this->weapon_missile_subtype = $weapon_detail['weapon_subtype'];
                $this->weapon_missile_additional_text = $weapon_detail['weapon_additional_text'];
                $this->weapon_missile_damage = $weapon_detail['weapon_damage'];
                $this->weapon_missile_number_of_hands = $weapon_detail['weapon_number_of_hands'];
                $this->weapon_missile_speed = $weapon_detail['weapon_speed'];
                $this->weapon_missile_long_range = $weapon_detail['weapon_long_range'];
                $this->weapon_missile_medium_range = $weapon_detail['weapon_medium_range'];
                $this->weapon_missile_short_range = $weapon_detail['weapon_short_range'];
            }
        }
        
        $playerCharacterWeaponSkill = $this->getPlayerCharacterWeaponProficiency($pdo, $playerName, $characterName, $weaponProficiencyId, $errors);
        $this->playerCharacterWeaponSkillId = $playerCharacterWeaponSkill['playerCharacterSkillId'];
        
    }

    private function getWeaponDetail(\PDO $pdo, $weaponProficiencyId, &$errors) {
		$sql_exec = "CALL getWeaponDetail(:weaponProficiencyId)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':weaponProficiencyId', $weaponProficiencyId, PDO::PARAM_INT);
		try {
			$statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getWeaponDetail : " . $e->getMessage();
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getPlayerCharacterWeaponProficiency(\PDO $pdo, $player_name, $character_name, $weaponProficiencyId, &$errors) {
		$sql_exec = "CALL getWeaponProficiencyForPlayerCharacter(:playerName, :characterName, :weaponProficiencyId)";

		$statement = $pdo->prepare($sql_exec);
		$statement->bindParam(':playerName', $player_name, PDO::PARAM_STR);
		$statement->bindParam(':characterName', $character_name, PDO::PARAM_STR);
		$statement->bindParam(':weaponProficiencyId', $weaponProficiencyId, PDO::PARAM_INT);

        try {
			$statement->execute();
        } catch(Exception $e) {
            $errors[] = "Exception in getWeaponProficiencyForPlayerCharacter : " . $e->getMessage();
        }

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // function called when encoded with json_encode
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getWeaponName() {
        return $this->weapon_name;
    }

	public function getWeaponId() {
        return $this->weapon_id;
    }

    public function getWeaponProficiencyId() {
        return $this->weapon_proficiency_id;
    }

    public function getPlayerCharacterWeaponSkillId() {
        return $this->playerCharacterWeaponSkillId;
    }

    /*-- Melee --*/
    public function getMeleeWeaponType() {
        return $this->weapon_melee_type;
    }

    public function getMeleeWeaponSubtype() {
        return $this->weapon_melee_subtype;
    }

    public function getMeleeWeaponAdditionalText() {
        return $this->weapon_melee_additional_text;
    } 

	public function getMeleeWeaponDamage() {
        return $this->weapon_melee_damage;
    }

	public function getMeleeWeaponNumberOfHands() {
        return $this->weapon_melee_number_of_hands;
    }

	public function getMeleeWeaponSpeed() {
        return $this->weapon_melee_speed;
    }

    /*-- Missile --*/
    public function getMissileWeaponType() {
        return $this->weapon_missile_type;
    }

    public function getMissileWeaponSubtype() {
        return $this->weapon_missile_subtype;
    }
    
    public function getMissileWeaponAdditionalText() {
        return $this->weapon_missile_additional_text;
    } 

	public function getMissileWeaponDamage() {
        return $this->weapon_missile_damage;
    }

	public function getMissileWeaponLongRange() {
        return $this->weapon_missile_long_range;
    }

	public function getMissileWeaponMediumRange() {
        return $this->weapon_missile_medium_range;
    }

	public function getMissileWeaponNumberOfHands() {
        return $this->weapon_missile_number_of_hands;
    }

    public function getMissileWeaponShortRange() {
        return $this->weapon_missile_short_range;
    }

	public function getMissileWeaponSpeed() {
        return $this->weapon_missile_speed;
    }
}
