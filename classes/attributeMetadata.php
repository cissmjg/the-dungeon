<?php
require_once 'characterDetails.php';
require_once __DIR__ . '/../dbio/constants/characterRaces.php';
require_once __DIR__ . '/../dbio/constants/characterClasses.php';

class AttributeMetadata {

    private $character_details;
    private $strength_metadata = [];
    private $strength_hit_adjustment = [];
    private $super_strength_hit_adjustment = [];
    private $strength_damage_adjustment = [];
    private $super_strength_damage_adjustment = [];

    private $super_strength_metadata = [];

    private $strength_high_jump_adjustment = [];
    private $strength_broad_jump_standing_adjustment = [];
    private $strength_broad_jump_running_adjustment = [];

    private $intelligence_additional_languages = [];
    private $super_intelligence_additional_languages = [];
    private $intelligence_spell_to_know_percent = [];

    private $wisdom_magic_attack_bonus = [];

    private $dexterity_reaction_missile_adjustment = [];
    private $dexterity_armor_class_adjustment = [];
    private $dexterity_pick_pockets_adjustment = [];
    private $dexterity_open_locks_adjustment = [];
    private $dexterity_find_remove_traps_adjustment = [];
    private $dexterity_move_silently_adjustment = [];
    private $dexterity_hide_in_shadows_adjustment = [];
    private $super_dexterity_pick_pockets_adjustment = [];
    private $super_dexterity_open_locks_adjustment = [];
    private $super_dexterity_find_remove_traps_adjustment = [];
    private $super_dexterity_move_silently_adjustment = [];
    private $super_dexterity_hide_in_shadows_adjustment = [];
    private $dexterity_tightrope_walking_adjustment = [];
    private $dexterity_pole_vaulting_adjustment = [];
    private $dexterity_tumbling_maneuvers_attack_adjustment = [];
    private $dexterity_tumbling_maneuvers_evasion_adjustment = [];
    private $dexterity_tumbling_maneuvers_falling_adjustment = [];
    private $super_dexterity_tightrope_walking_adjustment = [];
    private $super_dexterity_pole_vaulting_adjustment = [];
    private $super_dexterity_tumbling_maneuvers_attack_adjustment = [];
    private $super_dexterity_tumbling_maneuvers_evasion_adjustment = [];
    private $super_dexterity_tumbling_maneuvers_falling_adjustment = [];

    private $constitution_ss_rs = [];
    private $constitution_hp_adjustment = [];
    private $constitution_fighter_hp_adjustment = [];

    private $charisma_metadata = [];

    function __construct(\CharacterDetails $character_details) {
        $this->character_details = $character_details;

        $this->strength_hit_adjustment[3] = -3;
        $this->strength_hit_adjustment[4] = -2;
        $this->strength_hit_adjustment[5] = -2;
        $this->strength_hit_adjustment[6] = -1;
        $this->strength_hit_adjustment[7] = -1;
        $this->strength_hit_adjustment[8] = 0;
        $this->strength_hit_adjustment[9] = 0;
        $this->strength_hit_adjustment[10] = 0;
        $this->strength_hit_adjustment[11] = 0;
        $this->strength_hit_adjustment[12] = 0;
        $this->strength_hit_adjustment[13] = 0;
        $this->strength_hit_adjustment[14] = 0;
        $this->strength_hit_adjustment[15] = 0;
        $this->strength_hit_adjustment[16] = 0;
        $this->strength_hit_adjustment[17] = 1;
        $this->strength_hit_adjustment[18] = 1;
        $this->strength_hit_adjustment[19] = 3;
        $this->strength_hit_adjustment[20] = 3;
        $this->strength_hit_adjustment[21] = 4;
        $this->strength_hit_adjustment[22] = 4;

        $this->super_strength_hit_adjustment[50] = 1;
        $this->super_strength_hit_adjustment[75] = 2;
        $this->super_strength_hit_adjustment[90] = 2;
        $this->super_strength_hit_adjustment[99] = 2;
        $this->super_strength_hit_adjustment[100] = 3;

        $this->strength_damage_adjustment[3] = -1;
        $this->strength_damage_adjustment[4] = -1;
        $this->strength_damage_adjustment[5] = -1;
        $this->strength_damage_adjustment[6] = 0;
        $this->strength_damage_adjustment[7] = 0;
        $this->strength_damage_adjustment[8] = 0;
        $this->strength_damage_adjustment[9] = 0;
        $this->strength_damage_adjustment[10] = 0;
        $this->strength_damage_adjustment[11] = 0;
        $this->strength_damage_adjustment[12] = 0;
        $this->strength_damage_adjustment[13] = 0;
        $this->strength_damage_adjustment[14] = 0;
        $this->strength_damage_adjustment[15] = 0;
        $this->strength_damage_adjustment[16] = 1;
        $this->strength_damage_adjustment[17] = 1;
        $this->strength_damage_adjustment[18] = 2;
        $this->strength_damage_adjustment[19] = 7;
        $this->strength_damage_adjustment[20] = 8;
        $this->strength_damage_adjustment[21] = 9;
        $this->strength_damage_adjustment[22] = 10;

        $this->super_strength_damage_adjustment[50] = 3;
        $this->super_strength_damage_adjustment[75] = 3;
        $this->super_strength_damage_adjustment[90] = 4;
        $this->super_strength_damage_adjustment[99] = 5;
        $this->super_strength_damage_adjustment[100] = 6;

        $this->strength_metadata[3]  = 'Doors: 1/6 Bend Bars: 0%';
        $this->strength_metadata[4]  = 'Doors: 1/6 Bend Bars: 0%';
        $this->strength_metadata[5]  = 'Doors: 1/6 Bend Bars: 0%';
        $this->strength_metadata[6]  = 'Doors: 1/6 Bend Bars: 0%';
        $this->strength_metadata[7]  = 'Doors: 1/6 Bend Bars: 0%';
        $this->strength_metadata[8]  = 'Doors: 2/6 Bend Bars: 1%';
        $this->strength_metadata[9]  = 'Doors: 2/6 Bend Bars: 1%';
        $this->strength_metadata[10] = 'Doors: 2/6 Bend Bars: 2%';
        $this->strength_metadata[11] = 'Doors: 2/6 Bend Bars: 2%';
        $this->strength_metadata[12] = 'Doors: 2/6 Bend Bars: 4%';
        $this->strength_metadata[13] = 'Doors: 2/6 Bend Bars: 4%';
        $this->strength_metadata[14] = 'Doors: 2/6 Bend Bars: 7%';
        $this->strength_metadata[15] = 'Doors: 2/6 Bend Bars: 7%';
        $this->strength_metadata[16] = 'Doors: 3/6 Bend Bars: 10%';
        $this->strength_metadata[17] = 'Doors: 3/6 Bend Bars: 13%';
        $this->strength_metadata[18] = 'Doors: 3/6 Bend Bars: 16%';
        $this->strength_metadata[19] = 'Doors: 7/8 Bend Bars: 50%';
        $this->strength_metadata[20] = 'Doors: 7/8 Bend Bars: 60%';
        $this->strength_metadata[21] = 'Doors: 9/10 Bend Bars: 70%';
        $this->strength_metadata[22] = 'Doors: 11/12 Bend Bars: 80%';

        $this->super_strength_metadata[50]  = 'Doors: 3/6 Bend Bars: 20%';
        $this->super_strength_metadata[75]  = 'Doors: 4/6 Bend Bars: 25%';
        $this->super_strength_metadata[90]  = 'Doors: 4/6 Bend Bars: 30%';
        $this->super_strength_metadata[99]  = 'Doors: 4/6 Bend Bars: 35%';
        $this->super_strength_metadata[100] = 'Doors: 5/6 Bend Bars: 40%';

        $this->strength_high_jump_adjustment[3] = 0;
        $this->strength_high_jump_adjustment[4] = 0;
        $this->strength_high_jump_adjustment[5] = 0;
        $this->strength_high_jump_adjustment[6] = 0;
        $this->strength_high_jump_adjustment[7] = 0;
        $this->strength_high_jump_adjustment[8] = 0;
        $this->strength_high_jump_adjustment[9] = 0;
        $this->strength_high_jump_adjustment[10] = 0;
        $this->strength_high_jump_adjustment[11] = 0;
        $this->strength_high_jump_adjustment[12] = 0;
        $this->strength_high_jump_adjustment[13] = 0;
        $this->strength_high_jump_adjustment[14] = 0;
        $this->strength_high_jump_adjustment[15] = 0;
        $this->strength_high_jump_adjustment[16] = 0;
        $this->strength_high_jump_adjustment[17] = 0.25;    // In feet
        $this->strength_high_jump_adjustment[18] = 0.25;    // In feet

        $this->strength_broad_jump_standing_adjustment[3] = 0;
        $this->strength_broad_jump_standing_adjustment[4] = 0;
        $this->strength_broad_jump_standing_adjustment[5] = 0;
        $this->strength_broad_jump_standing_adjustment[6] = 0;
        $this->strength_broad_jump_standing_adjustment[7] = 0;
        $this->strength_broad_jump_standing_adjustment[8] = 0;
        $this->strength_broad_jump_standing_adjustment[9] = 0;
        $this->strength_broad_jump_standing_adjustment[10] = 0;
        $this->strength_broad_jump_standing_adjustment[11] = 0;
        $this->strength_broad_jump_standing_adjustment[12] = 0;
        $this->strength_broad_jump_standing_adjustment[13] = 0;
        $this->strength_broad_jump_standing_adjustment[14] = 0;
        $this->strength_broad_jump_standing_adjustment[15] = 0;
        $this->strength_broad_jump_standing_adjustment[16] = 0;
        $this->strength_broad_jump_standing_adjustment[17] = 0.25;  // In feet
        $this->strength_broad_jump_standing_adjustment[18] = 0.5;   // In feet

        $this->strength_broad_jump_running_adjustment[3] = 0;
        $this->strength_broad_jump_running_adjustment[4] = 0;
        $this->strength_broad_jump_running_adjustment[5] = 0;
        $this->strength_broad_jump_running_adjustment[6] = 0;
        $this->strength_broad_jump_running_adjustment[7] = 0;
        $this->strength_broad_jump_running_adjustment[8] = 0;
        $this->strength_broad_jump_running_adjustment[9] = 0;
        $this->strength_broad_jump_running_adjustment[10] = 0;
        $this->strength_broad_jump_running_adjustment[11] = 0;
        $this->strength_broad_jump_running_adjustment[12] = 0;
        $this->strength_broad_jump_running_adjustment[13] = 0;
        $this->strength_broad_jump_running_adjustment[14] = 0;
        $this->strength_broad_jump_running_adjustment[15] = 0;
        $this->strength_broad_jump_running_adjustment[16] = 0.5;    // In feet
        $this->strength_broad_jump_running_adjustment[17] = 1;      // In feet
        $this->strength_broad_jump_running_adjustment[18] = 2;      // In feet

        $this->intelligence_additional_languages[3] = 0;
        $this->intelligence_additional_languages[4] = 0;
        $this->intelligence_additional_languages[5] = 0;
        $this->intelligence_additional_languages[6] = 0;
        $this->intelligence_additional_languages[7] = 0;
        $this->intelligence_additional_languages[8] = 1;
        $this->intelligence_additional_languages[9] = 1;
        $this->intelligence_additional_languages[10] = 2;
        $this->intelligence_additional_languages[11] = 2;
        $this->intelligence_additional_languages[12] = 3;
        $this->intelligence_additional_languages[13] = 3;
        $this->intelligence_additional_languages[14] = 4;
        $this->intelligence_additional_languages[15] = 4;
        $this->intelligence_additional_languages[16] = 5;
        $this->intelligence_additional_languages[17] = 6;
        $this->intelligence_additional_languages[18] = 7;
        $this->intelligence_additional_languages[19] = 9;

        $this->super_intelligence_additional_languages[50] = 8;
        $this->super_intelligence_additional_languages[100] = 9;

        $this->intelligence_spell_to_know_percent[9] = 35;
        $this->intelligence_spell_to_know_percent[10] = 45;
        $this->intelligence_spell_to_know_percent[11] = 45;
        $this->intelligence_spell_to_know_percent[12] = 45;
        $this->intelligence_spell_to_know_percent[13] = 55;
        $this->intelligence_spell_to_know_percent[14] = 55;
        $this->intelligence_spell_to_know_percent[15] = 65;
        $this->intelligence_spell_to_know_percent[16] = 65;
        $this->intelligence_spell_to_know_percent[17] = 75;
        $this->intelligence_spell_to_know_percent[18] = 85;
        $this->intelligence_spell_to_know_percent[19] = 95;

        $this->wisdom_magic_attack_bonus[3] = -3;
        $this->wisdom_magic_attack_bonus[4] = -2;
        $this->wisdom_magic_attack_bonus[5] = -1;
        $this->wisdom_magic_attack_bonus[6] = -1;
        $this->wisdom_magic_attack_bonus[7] = -1;
        $this->wisdom_magic_attack_bonus[8] = 0;
        $this->wisdom_magic_attack_bonus[9] = 0;
        $this->wisdom_magic_attack_bonus[10] = 0;
        $this->wisdom_magic_attack_bonus[11] = 0;
        $this->wisdom_magic_attack_bonus[12] = 0;
        $this->wisdom_magic_attack_bonus[13] = 0;
        $this->wisdom_magic_attack_bonus[14] = 0;
        $this->wisdom_magic_attack_bonus[15] = +1;
        $this->wisdom_magic_attack_bonus[16] = +2;
        $this->wisdom_magic_attack_bonus[17] = +3;
        $this->wisdom_magic_attack_bonus[18] = +4;

        $this->dexterity_reaction_missile_adjustment[3] = -3;
        $this->dexterity_reaction_missile_adjustment[4] = -2;
        $this->dexterity_reaction_missile_adjustment[5] = -1;
        $this->dexterity_reaction_missile_adjustment[6] = 0;
        $this->dexterity_reaction_missile_adjustment[7] = 0;
        $this->dexterity_reaction_missile_adjustment[8] = 0;
        $this->dexterity_reaction_missile_adjustment[9] = 0;
        $this->dexterity_reaction_missile_adjustment[10] = 0;
        $this->dexterity_reaction_missile_adjustment[11] = 0;
        $this->dexterity_reaction_missile_adjustment[12] = 0;
        $this->dexterity_reaction_missile_adjustment[13] = 0;
        $this->dexterity_reaction_missile_adjustment[14] = 0;
        $this->dexterity_reaction_missile_adjustment[15] = 0;
        $this->dexterity_reaction_missile_adjustment[16] = 1;
        $this->dexterity_reaction_missile_adjustment[17] = 2;
        $this->dexterity_reaction_missile_adjustment[18] = 3;
        $this->dexterity_reaction_missile_adjustment[19] = 4;

        $this->dexterity_armor_class_adjustment[3] = 4;
        $this->dexterity_armor_class_adjustment[4] = 3;
        $this->dexterity_armor_class_adjustment[5] = 2;
        $this->dexterity_armor_class_adjustment[6] = 1;
        $this->dexterity_armor_class_adjustment[7] = 0;
        $this->dexterity_armor_class_adjustment[8] = 0;
        $this->dexterity_armor_class_adjustment[9] = 0;
        $this->dexterity_armor_class_adjustment[10] = 0;
        $this->dexterity_armor_class_adjustment[11] = 0;
        $this->dexterity_armor_class_adjustment[12] = 0;
        $this->dexterity_armor_class_adjustment[13] = 0;
        $this->dexterity_armor_class_adjustment[14] = 0;
        $this->dexterity_armor_class_adjustment[15] = -1;
        $this->dexterity_armor_class_adjustment[16] = -2;
        $this->dexterity_armor_class_adjustment[17] = -3;
        $this->dexterity_armor_class_adjustment[18] = -4;
        $this->dexterity_armor_class_adjustment[19] = -5;
    
        $this->dexterity_pick_pockets_adjustment[3] = -15;
        $this->dexterity_pick_pockets_adjustment[4] = -15;
        $this->dexterity_pick_pockets_adjustment[5] = -15;
        $this->dexterity_pick_pockets_adjustment[6] = -15;
        $this->dexterity_pick_pockets_adjustment[7] = -15;
        $this->dexterity_pick_pockets_adjustment[8] = -15;
        $this->dexterity_pick_pockets_adjustment[9] = -15;
        $this->dexterity_pick_pockets_adjustment[10] = -10;
        $this->dexterity_pick_pockets_adjustment[11] = -5;
        $this->dexterity_pick_pockets_adjustment[12] = 0;
        $this->dexterity_pick_pockets_adjustment[13] = 0;
        $this->dexterity_pick_pockets_adjustment[14] = 0;
        $this->dexterity_pick_pockets_adjustment[15] = 0;
        $this->dexterity_pick_pockets_adjustment[16] = 0;
        $this->dexterity_pick_pockets_adjustment[17] = 5;
        $this->dexterity_pick_pockets_adjustment[18] = 10;
        $this->dexterity_pick_pockets_adjustment[19] = 15;
        
        $this->dexterity_open_locks_adjustment[3] = -10;
        $this->dexterity_open_locks_adjustment[4] = -10;
        $this->dexterity_open_locks_adjustment[5] = -10;
        $this->dexterity_open_locks_adjustment[6] = -10;
        $this->dexterity_open_locks_adjustment[7] = -10;
        $this->dexterity_open_locks_adjustment[8] = -10;
        $this->dexterity_open_locks_adjustment[9] = -10;
        $this->dexterity_open_locks_adjustment[10] = -5;
        $this->dexterity_open_locks_adjustment[11] = 0;
        $this->dexterity_open_locks_adjustment[12] = 0;
        $this->dexterity_open_locks_adjustment[13] = 0;
        $this->dexterity_open_locks_adjustment[14] = 0;
        $this->dexterity_open_locks_adjustment[15] = 0;
        $this->dexterity_open_locks_adjustment[16] = 5;
        $this->dexterity_open_locks_adjustment[17] = 10;
        $this->dexterity_open_locks_adjustment[18] = 15;
        $this->dexterity_open_locks_adjustment[19] = 20;
        
        $this->dexterity_find_remove_traps_adjustment[3] = -10;
        $this->dexterity_find_remove_traps_adjustment[4] = -10;
        $this->dexterity_find_remove_traps_adjustment[5] = -10;
        $this->dexterity_find_remove_traps_adjustment[6] = -10;
        $this->dexterity_find_remove_traps_adjustment[7] = -10;
        $this->dexterity_find_remove_traps_adjustment[8] = -10;
        $this->dexterity_find_remove_traps_adjustment[9] = -10;
        $this->dexterity_find_remove_traps_adjustment[10] = -10;
        $this->dexterity_find_remove_traps_adjustment[11] = -5;
        $this->dexterity_find_remove_traps_adjustment[12] = 0;
        $this->dexterity_find_remove_traps_adjustment[13] = 0;
        $this->dexterity_find_remove_traps_adjustment[14] = 0;
        $this->dexterity_find_remove_traps_adjustment[15] = 0;
        $this->dexterity_find_remove_traps_adjustment[16] = 0;
        $this->dexterity_find_remove_traps_adjustment[17] = 0;
        $this->dexterity_find_remove_traps_adjustment[18] = 5;
        $this->dexterity_find_remove_traps_adjustment[19] = 10;
        
        $this->dexterity_move_silently_adjustment[3] = -20;
        $this->dexterity_move_silently_adjustment[4] = -20;
        $this->dexterity_move_silently_adjustment[5] = -20;
        $this->dexterity_move_silently_adjustment[6] = -20;
        $this->dexterity_move_silently_adjustment[7] = -20;
        $this->dexterity_move_silently_adjustment[8] = -20;
        $this->dexterity_move_silently_adjustment[9] = -20;
        $this->dexterity_move_silently_adjustment[10] = -15;
        $this->dexterity_move_silently_adjustment[11] = -10;
        $this->dexterity_move_silently_adjustment[12] = -5;
        $this->dexterity_move_silently_adjustment[13] = 0;
        $this->dexterity_move_silently_adjustment[14] = 0;
        $this->dexterity_move_silently_adjustment[15] = 0;
        $this->dexterity_move_silently_adjustment[16] = 0;
        $this->dexterity_move_silently_adjustment[17] = 5;
        $this->dexterity_move_silently_adjustment[18] = 10;
        $this->dexterity_move_silently_adjustment[19] = 12;
        
        $this->dexterity_hide_in_shadows_adjustment[3] = -10;
        $this->dexterity_hide_in_shadows_adjustment[4] = -10;
        $this->dexterity_hide_in_shadows_adjustment[5] = -10;
        $this->dexterity_hide_in_shadows_adjustment[6] = -10;
        $this->dexterity_hide_in_shadows_adjustment[7] = -10;
        $this->dexterity_hide_in_shadows_adjustment[8] = -10;
        $this->dexterity_hide_in_shadows_adjustment[9] = -10;
        $this->dexterity_hide_in_shadows_adjustment[10] = -5;
        $this->dexterity_hide_in_shadows_adjustment[11] = 0;
        $this->dexterity_hide_in_shadows_adjustment[12] = 0;
        $this->dexterity_hide_in_shadows_adjustment[13] = 0;
        $this->dexterity_hide_in_shadows_adjustment[14] = 0;
        $this->dexterity_hide_in_shadows_adjustment[15] = 0;
        $this->dexterity_hide_in_shadows_adjustment[16] = 0;
        $this->dexterity_hide_in_shadows_adjustment[17] = 5;
        $this->dexterity_hide_in_shadows_adjustment[18] = 10;
        $this->dexterity_hide_in_shadows_adjustment[19] = 12;
        
        $this->super_dexterity_pick_pockets_adjustment[20] = 11;
        $this->super_dexterity_pick_pockets_adjustment[40] = 12;
        $this->super_dexterity_pick_pockets_adjustment[60] = 13;
        $this->super_dexterity_pick_pockets_adjustment[80] = 14;
        $this->super_dexterity_pick_pockets_adjustment[100] = 15;
        
        $this->super_dexterity_open_locks_adjustment[20] = 16;
        $this->super_dexterity_open_locks_adjustment[40] = 17;
        $this->super_dexterity_open_locks_adjustment[60] = 18;
        $this->super_dexterity_open_locks_adjustment[80] = 19;
        $this->super_dexterity_open_locks_adjustment[100] = 20;
        
        $this->super_dexterity_find_remove_traps_adjustment[20] = 6;
        $this->super_dexterity_find_remove_traps_adjustment[40] = 7;
        $this->super_dexterity_find_remove_traps_adjustment[60] = 8;
        $this->super_dexterity_find_remove_traps_adjustment[70] = 9;
        $this->super_dexterity_find_remove_traps_adjustment[100] = 10;
        
        $this->super_dexterity_move_silently_adjustment[20] = 10;
        $this->super_dexterity_move_silently_adjustment[40] = 11;
        $this->super_dexterity_move_silently_adjustment[60] = 11;
        $this->super_dexterity_move_silently_adjustment[80] = 12;
        $this->super_dexterity_move_silently_adjustment[100] = 12;
        
        $this->super_dexterity_hide_in_shadows_adjustment[20] = 10;
        $this->super_dexterity_hide_in_shadows_adjustment[40] = 11;
        $this->super_dexterity_hide_in_shadows_adjustment[60] = 11;
        $this->super_dexterity_hide_in_shadows_adjustment[80] = 12;
        $this->super_dexterity_hide_in_shadows_adjustment[100] = 12;

        $this->dexterity_tightrope_walking_adjustment[3] = 0;
        $this->dexterity_tightrope_walking_adjustment[4] = 0;
        $this->dexterity_tightrope_walking_adjustment[5] = 0;
        $this->dexterity_tightrope_walking_adjustment[6] = 0;
        $this->dexterity_tightrope_walking_adjustment[7] = 0;
        $this->dexterity_tightrope_walking_adjustment[8] = 0;
        $this->dexterity_tightrope_walking_adjustment[9] = 0;
        $this->dexterity_tightrope_walking_adjustment[10] = 0;
        $this->dexterity_tightrope_walking_adjustment[11] = 0;
        $this->dexterity_tightrope_walking_adjustment[12] = 0;
        $this->dexterity_tightrope_walking_adjustment[13] = 0;
        $this->dexterity_tightrope_walking_adjustment[14] = 0;
        $this->dexterity_tightrope_walking_adjustment[15] = 0;
        $this->dexterity_tightrope_walking_adjustment[16] = 5;
        $this->dexterity_tightrope_walking_adjustment[17] = 10;
        $this->dexterity_tightrope_walking_adjustment[18] = 15;
        $this->dexterity_tightrope_walking_adjustment[19] = 20;

        $this->super_dexterity_tightrope_walking_adjustment[20] = 15;
        $this->super_dexterity_tightrope_walking_adjustment[40] = 16;
        $this->super_dexterity_tightrope_walking_adjustment[60] = 17;
        $this->super_dexterity_tightrope_walking_adjustment[80] = 18;
        $this->super_dexterity_tightrope_walking_adjustment[100] = 19;

        // In feet
        $this->dexterity_pole_vaulting_adjustment[3] = 0;
        $this->dexterity_pole_vaulting_adjustment[4] = 0;
        $this->dexterity_pole_vaulting_adjustment[5] = 0;
        $this->dexterity_pole_vaulting_adjustment[6] = 0;
        $this->dexterity_pole_vaulting_adjustment[7] = 0;
        $this->dexterity_pole_vaulting_adjustment[8] = 0;
        $this->dexterity_pole_vaulting_adjustment[9] = 0;
        $this->dexterity_pole_vaulting_adjustment[10] = 0;
        $this->dexterity_pole_vaulting_adjustment[11] = 0;
        $this->dexterity_pole_vaulting_adjustment[12] = 0;
        $this->dexterity_pole_vaulting_adjustment[13] = 0;
        $this->dexterity_pole_vaulting_adjustment[14] = 0;
        $this->dexterity_pole_vaulting_adjustment[15] = 0;
        $this->dexterity_pole_vaulting_adjustment[16] = 0;
        $this->dexterity_pole_vaulting_adjustment[17] = 1;
        $this->dexterity_pole_vaulting_adjustment[18] = 2;
        $this->dexterity_pole_vaulting_adjustment[19] = 3;

        // In feet
        $this->super_dexterity_pole_vaulting_adjustment[20] = 2;
        $this->super_dexterity_pole_vaulting_adjustment[40] = 2;
        $this->super_dexterity_pole_vaulting_adjustment[60] = 2;
        $this->super_dexterity_pole_vaulting_adjustment[80] = 3;
        $this->super_dexterity_pole_vaulting_adjustment[100] = 3;

        $this->dexterity_tumbling_maneuvers_attack_adjustment[3] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[4] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[5] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[6] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[7] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[8] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[9] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[10] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[11] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[12] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[13] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[14] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[15] = 0;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[16] = 1;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[17] = 2;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[18] = 3;
        $this->dexterity_tumbling_maneuvers_attack_adjustment[19] = 4;

        $this->super_dexterity_tumbling_maneuvers_attack_adjustment[20] = 3;
        $this->super_dexterity_tumbling_maneuvers_attack_adjustment[40] = 3;
        $this->super_dexterity_tumbling_maneuvers_attack_adjustment[60] = 3;
        $this->super_dexterity_tumbling_maneuvers_attack_adjustment[80] = 4;
        $this->super_dexterity_tumbling_maneuvers_attack_adjustment[100] = 4;

        $this->dexterity_tumbling_maneuvers_evasion_adjustment[3] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[4] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[5] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[6] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[7] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[8] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[9] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[10] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[11] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[12] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[13] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[14] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[15] = 0;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[16] = 2;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[17] = 3;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[18] = 5;
        $this->dexterity_tumbling_maneuvers_evasion_adjustment[19] = 8;

        $this->super_dexterity_tumbling_maneuvers_evasion_adjustment[20] = 5;
        $this->super_dexterity_tumbling_maneuvers_evasion_adjustment[40] = 6;
        $this->super_dexterity_tumbling_maneuvers_evasion_adjustment[60] = 6;
        $this->super_dexterity_tumbling_maneuvers_evasion_adjustment[80] = 7;
        $this->super_dexterity_tumbling_maneuvers_evasion_adjustment[100] = 8;

        $this->dexterity_tumbling_maneuvers_falling_adjustment[3] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[4] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[5] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[6] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[7] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[8] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[9] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[10] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[11] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[12] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[13] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[14] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[15] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[16] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[17] = 0;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[18] = 5;
        $this->dexterity_tumbling_maneuvers_falling_adjustment[19] = 10;

        $this->super_dexterity_tumbling_maneuvers_falling_adjustment[20] = 5;
        $this->super_dexterity_tumbling_maneuvers_falling_adjustment[40] = 6;
        $this->super_dexterity_tumbling_maneuvers_falling_adjustment[60] = 7;
        $this->super_dexterity_tumbling_maneuvers_falling_adjustment[80] = 8;
        $this->super_dexterity_tumbling_maneuvers_falling_adjustment[100] = 9;

        $this->constitution_hp_adjustment[3] = -2;
        $this->constitution_hp_adjustment[4] = -1;
        $this->constitution_hp_adjustment[5] = -1;
        $this->constitution_hp_adjustment[6] = -1;
        $this->constitution_hp_adjustment[7] = 0;
        $this->constitution_hp_adjustment[8] = 0;
        $this->constitution_hp_adjustment[9] = 0;
        $this->constitution_hp_adjustment[10] = 0;
        $this->constitution_hp_adjustment[11] = 0;
        $this->constitution_hp_adjustment[12] = 0;
        $this->constitution_hp_adjustment[13] = 0;
        $this->constitution_hp_adjustment[14] = 0;
        $this->constitution_hp_adjustment[15] = +1;
        $this->constitution_hp_adjustment[16] = +2;
        $this->constitution_hp_adjustment[17] = +2;
        $this->constitution_hp_adjustment[18] = +2;

        $this->constitution_fighter_hp_adjustment[17] = +3;
        $this->constitution_fighter_hp_adjustment[18] = +4;

        $this->constitution_ss_rs[3] = 'Sys Shock: 35% Res. survival: 40%';
        $this->constitution_ss_rs[4] = 'Sys Shock: 40% Res. survival: 45%';
        $this->constitution_ss_rs[5] = 'Sys Shock: 45% Res. survival: 50%';
        $this->constitution_ss_rs[6] = 'Sys Shock: 50% Res. survival: 55%';
        $this->constitution_ss_rs[7] = 'Sys Shock: 55% Res. survival: 60%';
        $this->constitution_ss_rs[8] = 'Sys Shock: 60% Res. survival: 65%';
        $this->constitution_ss_rs[9] = 'Sys Shock: 65% Res. survival: 70%';
        $this->constitution_ss_rs[10] = 'Sys Shock: 70% Res. survival: 75%';
        $this->constitution_ss_rs[11] = 'Sys Shock: 75% Res. survival: 80%';
        $this->constitution_ss_rs[12] = 'Sys Shock: 80% Res. survival: 85%';
        $this->constitution_ss_rs[13] = 'Sys Shock: 85% Res. survival: 90%';
        $this->constitution_ss_rs[14] = 'Sys Shock: 88% Res. survival: 92%';
        $this->constitution_ss_rs[15] = 'Sys Shock: 91% Res. survival: 94%';
        $this->constitution_ss_rs[16] = 'Sys Shock: 95% Res. survival: 96%';
        $this->constitution_ss_rs[17] = 'Sys Shock: 97% Res. survival: 98%';
        $this->constitution_ss_rs[18] = 'Sys Shock: 99% Res. survival: 100%';

        $this->charisma_metadata[3] = 'Max henchmen: 1 Loyalty base: -30% Reaction Adj,: -25%';
        $this->charisma_metadata[4] = 'Max henchmen: 1 Loyalty base: -25% Reaction Adj,: -20%';
        $this->charisma_metadata[5] = 'Max henchmen: 2 Loyalty base: -20% Reaction Adj,: -15%';
        $this->charisma_metadata[6] = 'Max henchmen: 2 Loyalty base: -15% Reaction Adj,: -10%';
        $this->charisma_metadata[7] = 'Max henchmen: 3 Loyalty base: -10% Reaction Adj,: -5%';
        $this->charisma_metadata[8] = 'Max henchmen: 3 Loyalty base: -5% Reaction Adj,: 0%';
        $this->charisma_metadata[9] = 'Max henchmen: 4 Loyalty base: 0% Reaction Adj,: 0%';
        $this->charisma_metadata[10] = 'Max henchmen: 4 Loyalty base: 0% Reaction Adj,: 0%';
        $this->charisma_metadata[11] = 'Max henchmen: 4 Loyalty base: 0% Reaction Adj,: 0%';
        $this->charisma_metadata[12] = 'Max henchmen: 5 Loyalty base: 0% Reaction Adj,: 0%';
        $this->charisma_metadata[13] = 'Max henchmen: 5 Loyalty base: 0% Reaction Adj,: +5%';
        $this->charisma_metadata[14] = 'Max henchmen: 6 Loyalty base: +5% Reaction Adj,: +10%';
        $this->charisma_metadata[15] = 'Max henchmen: 7 Loyalty base: +15% Reaction Adj,: +15%';
        $this->charisma_metadata[16] = 'Max henchmen: 8 Loyalty base: +20% Reaction Adj,: +25%';
        $this->charisma_metadata[17] = 'Max henchmen: 10 Loyalty base: +30% Reaction Adj,: +30%';
        $this->charisma_metadata[18] = 'Max henchmen: 15 Loyalty base: +40% Reaction Adj,: +35%';
    }

    public function getStrengthMetadata() {
        $strength_hit_adj = sprintf("%+.0f", $this->getStrengthHitAdjustment());
        $strength_dmg_adj = sprintf("%+.0f", $this->getStrengthDamageAdjustment());
        $door_kick_bend_bar = $this->getDoorKickBendBars();

        $hit_dmg_item = $this->buildItem("Hit/Dmg Adj.: $strength_hit_adj/$strength_dmg_adj");
        $door_bar_item = $this->buildItem($door_kick_bend_bar);

        $container = $this->openContainer();
        $container = $this->addItem($container, $hit_dmg_item);
        $container = $this->addItem($container, $door_bar_item);
        $container = $this->closeContainer($container);

        return $container;
    }

    public function getStrengthHitAdjustment() {
        if (!empty($this->character_details->getCharacterSuperStrength()) && $this->character_details->getCharacterStrength() == 18) {
            $super_strength_lookup = $this->lookupSuperStrengthIndex($this->character_details->getCharacterSuperStrength());
            return $this->super_strength_hit_adjustment[$super_strength_lookup];
        } else {
            return $this->strength_hit_adjustment[$this->character_details->getCharacterStrength()];
        }
    }

    public function getStrengthDamageAdjustment() {
        if (!empty($this->character_details->getCharacterSuperStrength()) && $this->character_details->getCharacterStrength() == 18) {
            $super_strength_lookup = $this->lookupSuperStrengthIndex($this->character_details->getCharacterSuperStrength());
            return $this->super_strength_damage_adjustment[$super_strength_lookup];
        } else {
            return $this->strength_damage_adjustment[$this->character_details->getCharacterStrength()];
        }
    }

    public function getStrengthHighJumpAdjusment() {
        // There is no code for super strength as only Thief-Acrobats will use this funcion
        return $this->strength_high_jump_adjustment[$this->character_details->getCharacterStrength()];
    }

    public function getStrengthBroadJumpStandingAdjustment() {
        // There is no code for super strength as only Thief-Acrobats will use this funcion
        return $this->strength_broad_jump_standing_adjustment[$this->character_details->getCharacterStrength()];
    }

    public function getStrengthBroadJumpRunningAdjustment() {
        // There is no code for super strength as only Thief-Acrobats will use this funcion
        return $this->strength_broad_jump_running_adjustment[$this->character_details->getCharacterStrength()];
    }

    private function getDoorKickBendBars() {
        if (!empty($this->character_details->getCharacterSuperStrength()) && $this->character_details->getCharacterStrength() == 18) {
            $super_strength_lookup = $this->lookupSuperStrengthIndex($this->character_details->getCharacterSuperStrength());
            return $this->super_strength_metadata[$super_strength_lookup];
        } else {
            return $this->strength_metadata[$this->character_details->getCharacterStrength()];
        }
    }

    private function lookupSuperStrengthIndex($character_super_strength) {
        if ($character_super_strength <= 50) {
            return 50;
        } else if ($character_super_strength > 50 && $character_super_strength <= 75) {
            return 75;
        } else if ($character_super_strength > 75 && $character_super_strength <= 90) {
            return 90;
        } else if ($character_super_strength > 90 && $character_super_strength <= 99) {
            return 99;
        } else {
            return 100;
        }
    }

    public function getIntelligenceMetadata() {
        $container = $this->openContainer();

        $number_of_additional_languages =  sprintf("%+.0f", $this->calculateAdditionalLanguages());
        $add_lang_item = $this->buildItem("Add. lang: $number_of_additional_languages");
        $container = $this->addItem($container, $add_lang_item);

        if ($this->character_details->isArcaneSpellcaster()) {
            $min_number_spells = $this->getMinNumberMUSpellSlots();
            $max_number_spells = $this->getMaxNumberMUSpellSlots();
            $percent_chance_to_know = $this->getPercentChanceToKnow();
            $spell_metadata_item = $this->buildItem(" Spells Min/Max: $min_number_spells/$max_number_spells To know: $percent_chance_to_know%");
            $container = $this->addItem($container, $spell_metadata_item);
        }

        $container = $this->closeContainer($container);

        return $container;
    }

    private function calculateAdditionalLanguages() {
        if ($this->character_details->isHalfElf()) {
            return max(0, $this->character_details->getCharacterIntelligence() - 16);
        }

        $generic_race_id = getGenericRaceID($this->character_details->getRace());
        switch ($generic_race_id) {
            case RACE_DWARF:
                return 2;
            case RACE_ELF:
                return max(0, $this->character_details->getCharacterIntelligence() - 15);
            case RACE_SURFACE_GNOME:
                return 2;
            case RACE_HALFLING:
                return max(0, $this->character_details->getCharacterIntelligence() - 16);
            case RACE_HALF_ORC:
                return 2;
            case RACE_HUMAN:
                return $this->calculateAdditionalLanguagesHuman();
            default:
                return 0;
        }  
    }

    private function calculateAdditionalLanguagesHuman() {
        if (!empty($this->character_details->getCharacterSuperIntelligence())) {
            $additional_language_index = $this->lookupSuperIntelligenceIndex($this->character_details->getCharacterSuperIntelligence());
            return $this->super_intelligence_additional_languages[$additional_language_index];
        }

        return $this->intelligence_additional_languages[$this->character_details->getCharacterIntelligence()];
    }

    private function lookupSuperIntelligenceIndex($character_super_intelligence) {
        if ($character_super_intelligence <= 50) {
            return 50;
        }

        return 100;
    }

    public function getMinNumberMUSpellSlots() {
        $intelligence = $this->character_details->getCharacterIntelligence();
        $super_intelligence = $this->character_details->getCharacterSuperIntelligence();

    	if ($intelligence < 9) {
            return 0;
        }

        if ($intelligence == 9) {
            return 4;
        }
        
        if ($intelligence <= 12) {
            return 5;
        }
        
        if ($intelligence <= 14) {
            return 6;
        }
        
        if ($intelligence <= 16) {
            return 7;
        }
        
        if($intelligence == 17) {
            return 8;
        }
        
        if ($intelligence == 18 && is_numeric($super_intelligence) == false) {
            return 9;
        } else {
            if ($super_intelligence <= 50) {
                return 10;
            } else {
                return 11;
            }
        }
        
        if ($intelligence == 19) {
            return 15;
        }

        return 0;
    }

    public function getMaxNumberMUSpellSlots() {
        $intelligence = $this->character_details->getCharacterIntelligence();
        $super_intelligence = $this->character_details->getCharacterSuperIntelligence();

    	if ($intelligence < 9) {
            return 0;
        }

        if ($intelligence == 9) {
            return 6;
        }
        
        if ($intelligence <= 12) {
            return 7;
        }
        
        if ($intelligence <= 14) {
            return 9;
        }
        
        if ($intelligence <= 16) {
            return 11;
        }
        
        if($intelligence == 17) {
            return 14;
        }
        
        if ($intelligence == 18 && is_numeric($super_intelligence) == false) {
            return 18;
        }
        
        if ($intelligence == 18 && is_numeric($super_intelligence) == true) {
            return 22;
        }
        
        if ($intelligence == 19) {
            return 30;
        }

        return 0;
    }

    public function getPercentChanceToKnow() {
        if (!empty($this->character_details->getCharacterSuperIntelligence())) {
            return 95;
        }

        return $this->intelligence_spell_to_know_percent[$this->character_details->getCharacterIntelligence()];
    }

    public function getWisdomMetadata() {
        $container = $this->openContainer();

        $magic_attack_adjustment = sprintf("%+.0f", $this->wisdom_magic_attack_bonus[$this->character_details->getCharacterWisdom()]);
        $magic_attack_adj_item = $this->buildItem("Magic Attack Adj.: $magic_attack_adjustment");
        $container = $this->addItem($container, $magic_attack_adj_item);

        if ($this->character_details->isDivineSpellcaster()) {

            $locale = 'en_US';
            $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);

            $spell_bonus = '';
            for($i = 1; $i <= 4; $i++) {
                $bonus_spell_number = $this->calculateWisdomBonus($i);
                if ($bonus_spell_number == 0) {
                    break;
                }

                if ($i == 1) {
                    $spell_bonus .= ' Spell bonus: ';
                }

                if ($i > 1) {
                    $spell_bonus .= ', '    ;
                }

                $spell_bonus .= $bonus_spell_number . ' ' . $nf->format($i);
            }

            $spell_bonus_item = $this->buildItem($spell_bonus);
            $container = $this->addItem($container, $spell_bonus_item);
        }

        $container = $this->closeContainer($container);

        return $container;
    }

    public function calculateWisdomBonus($new_spell_level) {
        $character_wisdom = $this->character_details->getCharacterWisdom();
        $has_exceptional_wisdom = !empty($this->character_details->getCharacterSuperWisdom());

        if ($new_spell_level == 1) {
            if ($character_wisdom < 13) {
                return 0;
            }
            
            if ($character_wisdom == 13) {
                return 1;
            }
            
            if ($character_wisdom >= 14) {
                return 2;
            }
        }
        
        if ($new_spell_level == 2) {
            if ($character_wisdom < 15) {
                return 0;
            }
            
            if ($character_wisdom == 15) {
                return 1;
            }
            
            if ($character_wisdom >= 16) {
                return 2;
            }
        }
        
        if ($new_spell_level == 3) {
            if ($character_wisdom < 17) {
                return 0;
            }

            if ($character_wisdom == 17) {
                return 1;
            }

            if ($character_wisdom == 18) {
                if ($has_exceptional_wisdom) {
                    return 2;
                } else {
                    return 1;
                }
            }
        }

        if ($new_spell_level == 4) {
            if ($character_wisdom < 18) {
                return 0;
            }

            if ($character_wisdom == 18) {
                if ($has_exceptional_wisdom) {
                    return 2;
                } else {
                    return 1;
                }
            }
        }
        
        return 0;
    }

    public function getMagicalAttackAdjustment() {
        $character_wisdom = $this->character_details->getCharacterWisdom();
        switch($character_wisdom) {
            case 3:
                return -3;
            case 4:
                return -2;
            case 5:
            case 6:
            case 7:
                return -1;
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
            case 13:
            case 14:
                return 0;
            case 15:
                return 1;
            case 16:
                return 2;
            case 17:
                return 3;
            case 18:
                return 4;
            default:
                return 0;
        }
    }

    public function getDexterityMetadata() {
        $reaction_missile_adj = sprintf("%+.0f", $this->getReactionMissileAdjustment());
        $armor_class_adj = sprintf("%+.0f", $this->getArmorClassAdjustment());

        $reaction_missile_adj_item = $this->buildItem("Reaction/Missile adj.: $reaction_missile_adj");
        $armor_class_adj_item = $this->buildItem("A/C adj.: $armor_class_adj");

        $container = $this->openContainer();

        $container = $this->addItem($container, $reaction_missile_adj_item);
        $container = $this->addItem($container, $armor_class_adj_item);

        $container = $this->closeContainer($container);

        return  $container;
    }

    public function getReactionMissileAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return 4;
        }

        return $this->dexterity_reaction_missile_adjustment[$this->character_details->getCharacterDexterity()];
    }

    public function getArmorClassAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return -5;
        }

        $armor_class_adjustment = 0;
        if ($this->character_details->containsClassId(BARBARIAN)) {
            $armor_class_adjustment = -($this->character_details->getCharacterDexterity() - 14) * 2;
        } else {
            $armor_class_adjustment =  $this->dexterity_armor_class_adjustment[$this->character_details->getCharacterDexterity()];
        }

        return $armor_class_adjustment;
    }

    public function getTwoWeaponDexterityPenalty() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return -1;
        }

        if ($this->character_details->getCharacterDexterity() == 19) {
            return 0;
        }

        if ($this->character_details->getCharacterDexterity() == 18) {
            return -2;
        }

        if ($this->character_details->getCharacterDexterity() == 17) {
            return -3;
        }
    }

    public function getPickPocketDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->lookupPickPocketSuperDexterity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_pick_pockets_adjustment[$this->character_details->getCharacterDexterity()];
        }
    }

    private function lookupPickPocketSuperDexterity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_pick_pockets_adjustment[$super_dexterity_index];
    }

    public function getOpenLocksDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->lookupOpenLockSuperDexterity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_open_locks_adjustment[$this->character_details->getCharacterDexterity()];
        }
    }

    private function lookupOpenLockSuperDexterity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_open_locks_adjustment[$super_dexterity_index];
    }

    public function getFindRemoveTrapsDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->lookupFindRemoveTrapsSuperDexterity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_find_remove_traps_adjustment[$this->character_details->getCharacterDexterity()];
        }
    }

    private function lookupFindRemoveTrapsSuperDexterity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_find_remove_traps_adjustment[$super_dexterity_index];
    }

    public function getMoveSilentlyDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->getMoveSilentlySuperDexterity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_move_silently_adjustment[$this->character_details->getCharacterDexterity()]; 
        }
    }

    private function getMoveSilentlySuperDexterity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_move_silently_adjustment[$super_dexterity_index];
    }

    public function getHideInShadowsDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->lookupHideInShadowsSuperDexerity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_hide_in_shadows_adjustment[$this->character_details->getCharacterDexterity()];
        }
    }

    private function lookupHideInShadowsSuperDexerity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_hide_in_shadows_adjustment[$super_dexterity_index];
    }

    public function getTightropeWalkingDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->lookupTightropeWalkingSuperDexterity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_tightrope_walking_adjustment[$this->character_details->getCharacterDexterity()];
        }
    }

    private function lookupTightropeWalkingSuperDexterity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_tightrope_walking_adjustment[$super_dexterity_index];
    }

    public function getPoleVaultingDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->lookupPoleVaultingSuperDexterity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_pole_vaulting_adjustment[$this->character_details->getCharacterDexterity()];
        }
    }

    private function lookupPoleVaultingSuperDexterity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_pole_vaulting_adjustment[$super_dexterity_index];
    }

    public function getTumblingAttacksDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->lookupTumblingAttacksSuperDexterity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_tumbling_maneuvers_attack_adjustment[$this->character_details->getCharacterDexterity()];
        }
    }

    private function lookupTumblingAttacksSuperDexterity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_tumbling_maneuvers_attack_adjustment[$super_dexterity_index];
    }

    public function getTumblingEvasionDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->lookupTumblingEvasionSuperDexterity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_tumbling_maneuvers_evasion_adjustment[$this->character_details->getCharacterDexterity()];
        }
    }

    private function lookupTumblingEvasionSuperDexterity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_tumbling_maneuvers_evasion_adjustment[$super_dexterity_index];
    }

    public function getTumblingFallingDistanceDexterityAdjustment() {
        if (!empty($this->character_details->getCharacterSuperDexterity()) && $this->character_details->getCharacterDexterity() == 18) {
            return $this->lookupTumblingFallingDistanceSuperDexterity($this->character_details->getCharacterSuperDexterity());
        } else {
            return $this->dexterity_tumbling_maneuvers_falling_adjustment[$this->character_details->getCharacterDexterity()];
        }
    }

    private function lookupTumblingFallingDistanceSuperDexterity($super_dexterity) {
        $super_dexterity_index = $this->getSuperDexterityIndex($super_dexterity);
        return $this->super_dexterity_tumbling_maneuvers_falling_adjustment[$super_dexterity_index];
    }

    private function getSuperDexterityIndex($super_dexterity) {
        if ($super_dexterity <= 20) {
            return 20;
        } else if ($super_dexterity >= 21 && $super_dexterity <= 40) {
            return 40;
        } else if ($super_dexterity >= 41 && $super_dexterity <= 60) {
            return 60;
        } else if ($super_dexterity >= 61 && $super_dexterity <= 80) {
            return  80;
        } else if ($super_dexterity >= 81 && $super_dexterity <= 100) {
            return 100;
        }
    }

    public function getConstitutionMetadata() {
        $hp_adj = sprintf("%+.0f", $this->getHitPointAdjustment());
        $ss_rs = $this->constitution_ss_rs[$this->character_details->getCharacterConstitution()];

        $hp_adj_item = $this->buildItem("HP adj.: $hp_adj");
        $ss_rs_item = $this->buildItem($ss_rs);

        $container = $this->openContainer();

        $container = $this->addItem($container, $hp_adj_item);
        $container = $this->addItem($container,  $ss_rs_item);

        $container = $this->closeContainer($container);

        return  $container;
    }

    public function getHitPointAdjustment() {
        $hp_adjustment = 0;
        $character_constitution = $this->character_details->getCharacterConstitution();

        if ($this->character_details->containsClassId(BARBARIAN)) {
            $hp_adjustment = ($character_constitution - 14) * 2;
        } else {
            if ($this->character_details->isFighterType()) {
                if ($character_constitution >= 17) {
                    $hp_adjustment = $this->constitution_fighter_hp_adjustment[$character_constitution];
                } else {
                    $hp_adjustment = $this->constitution_hp_adjustment[$character_constitution];
                }
            } else {
                $hp_adjustment = $this->constitution_hp_adjustment[$this->character_details->getCharacterConstitution()];
            }
        }

        return $hp_adjustment;
    }

    public function getCharismaMetadata() {
        $container = $this->openContainer();
        $charisma_metadata_item = $this->buildItem($this->charisma_metadata[$this->character_details->getCharacterCharisma()]);
        $container = $this->addItem($container, $charisma_metadata_item);
        $container = $this->closeContainer($container);

        return  $container;
    }

    public function getComelinessMetadata() {
        return "&nbsp;";
    }

    private function openContainer() {
        return '<div class="attributeMetaData">';
    }

    private function closeContainer($flex_container) {
        return "$flex_container</div>";
    }

    private function buildItem($flex_item) {
        return '<span class="attributeMetaDataItem">' . $flex_item . '</span>';
    }

    private function addItem($flex_container, $flex_item) {
        return "$flex_container$flex_item";
    }
}

?>
