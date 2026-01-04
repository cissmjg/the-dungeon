<?php

require_once __DIR__ . '/helper/CurlHelper.php';
require_once __DIR__ . '/webio/characterName.php';

class ActionBarHelper {

    static function buildActionBar($playerName, $characterName) {
        return ActionBarHelper::buildUserViewIcon($playerName, $characterName) . '&nbsp;' . PHP_EOL;
    }

    static function buildUserActionBar($player_name, $character_name) {
        $user_view_icon = ActionBarHelper::buildUserViewIcon($player_name, $character_name) . PHP_EOL;
        $user_edit_icon = ActionBarHelper::buildUserEditIcon($player_name, $character_name) . PHP_EOL;

        return '<span>' . $user_view_icon . $user_edit_icon . '</span>';
    }

    static function buildUserViewIcon($player_name, $character_name) {
        $url_view_character = CurlHelper::buildCharacterActionRouterUrl($player_name, 'viewCharacter');
        $url_view_character = CurlHelper::addParameter($url_view_character, CHARACTER_NAME, $character_name);

        $icon_view_character = '<span class="fa-solid fa-user" style="color: black; cursor: pointer; title="View ' . $character_name . '"></span>';
        $anchor_view_character = '<a href="' . $url_view_character . '">' . $icon_view_character . '</a>';

        return $anchor_view_character;
    }

    static function buildUserEditIcon($player_name, $character_name) {
        $url_edit_character = CurlHelper::buildCharacterCRUDUrl($player_name, $character_name, 'editCharacter');
        $icon_edit_character = '<span class="fa-solid fa-user-pen" style="color: black; cursor: pointer;" title="Update ' . $character_name . '"></span>';
        $anchor_edit_character = '<a href="' . $url_edit_character . '">' . $icon_edit_character . '</a>';

        return $anchor_edit_character;
    }

    static function buildUserDeleteIcon($player_name, $character_name) {
        $url_delete_character = ActionBarHelper::buildDeleteCharacterUrl($player_name, $character_name);
        $icon_delete_character = '<span class="fa-solid fa-user-xmark" style="color: red; cursor: pointer;" title="Delete ' . $character_name . '"></span>';
        $anchor_edit_character = '<a href="' . $url_delete_character . '">' . $icon_delete_character . '</a>';

        return $anchor_edit_character;
    }

    static function buildDeleteCharacterUrl($player_name, $character_name) {
        $url = CurlHelper::buildUrl('crudCharacter');
        $url = CurlHelper::addParameter($url, PAGE_ACTION, 'delete');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
    
        return $url;
    }

    static function buildEditSpellBookIcon($player_name, $character_name, $character_class_name) {
        $url_edit_spellbook = ActionBarHelper::buildEditSpellBookUrl($player_name, $character_name, $character_class_name, 'edit');
        $spell_book_icon = '<span class="fa-solid fa-book" style="cursor: pointer;" title="Edit Spellbook"></span>';

        return '<a href="' . $url_edit_spellbook . '">' . $spell_book_icon . '</a>';
    }

    static function buildEditSpellBookIconWithPadding($player_name, $character_name, $character_class_name) {
        $url_edit_spellbook = ActionBarHelper::buildEditSpellBookUrl($player_name, $character_name, $character_class_name, 'edit');
        $spell_book_icon = '<span class="fa-solid fa-book" style="cursor: pointer;" title="Edit Spellbook"></span>';

        return '<a style="padding: 10px;" href="' . $url_edit_spellbook . '">' . $spell_book_icon . '</a>';
    }

    static function buildDailyResetIcon($player_name, $character_name) {
        $url = CurlHelper::buildCharacterActionRouterUrl($player_name, 'dailyReset');
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
        $spell_book_icon = '<a href="' . $url . '">';
        $spell_book_icon .= '<span class="fa-solid fa-sun" style="cursor: pointer;" title="Daily Spell Reset"></span>';
        $spell_book_icon .= '</a>';

        return $spell_book_icon;
    }
    
    static function buildEditSpellBookUrl($player_name, $character_name, $character_class, $page_action) {
        $url = CurlHelper::buildUrl('characterActionRouter');
        $url = CurlHelper::addParameter($url, 'characterAction', 'editSpellBook');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
        $url = CurlHelper::addParameter($url, CHARACTER_CLASS_NAME, $character_class);
        $url = CurlHelper::addParameter($url, PAGE_ACTION, $page_action);

        return $url;
    }

    static function buildReadySpellsIcon($player_name, $character_name) {
        $output_html = '';
        $url_ready_spells = ActionBarHelper::buildEditReadySpellsUrl($player_name, $character_name);
        $ready_spells_icon = '<span class="fa-solid fa-wand-sparkles" style="color: dodgerblue; cursor: pointer;" title="Use Spells"></span>';
        $output_html .= '<a href="' . $url_ready_spells . '">' . $ready_spells_icon . '</a>';
    
        return $output_html;
    }
    
    static function buildEditReadySpellsUrl($player_name, $character_name) {
        $url = CurlHelper::buildUrl('characterActionRouter');
        $url = CurlHelper::addParameter($url, 'characterAction', 'editReadySpells');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
    
        return $url;
    }

    static function buildReadyGMSpellsIcon($player_name, $character_name) {
        $output_html = '';
        $url_ready_spells = ActionBarHelper::buildEditGMReadySpellsUrl($player_name, $character_name);
        $ready_spells_icon = '<span class="fa-solid fa-wand-sparkles" style="color: dodgerblue; cursor: pointer;" title="Use Spells"></span>';
        $output_html .= '<a href="' . $url_ready_spells . '">' . $ready_spells_icon . '</a>';
    
        return $output_html;
    }
    
    static function buildEditGMReadySpellsUrl($player_name, $character_name) {
        $url = CurlHelper::buildUrl('characterActionRouter');
        $url = CurlHelper::addParameter($url, 'characterAction', 'editGMSpells');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
    
        return $url;
    }

    static function buildEditWeaponTalentsIcon($player_name, $character_name) {
        $output_html = '';
        $title = "Update Weapon Talents for " . $character_name;
        $url = ActionBarHelper::buildEditWeaponTalentsUrl($player_name, $character_name);
        $edit_weapons_icon = '<span class="fa-solid fa-user-shield" style="cursor: pointer;" title="' . $title . '"></span>';
        $output_html .= '<a href="' . $url . '">' . $edit_weapons_icon . '</a>';

        return $output_html;
    }

    static function buildEditWeaponTalentsUrl($player_name, $character_name) {
        $url = CurlHelper::buildUrl('characterActionRouter');
        $url = CurlHelper::addParameter($url, 'characterAction', 'editWeaponTalents');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
    
        return $url;
    }

    static function buildEditWeaponsIcon($player_name, $character_name) {
        $output_html = '';
        $url = ActionBarHelper::buildEditWeaponsUrl($player_name, $character_name);
        $edit_weapons_icon = '<span class="fa-solid fa-sword" style="cursor: pointer;" title="Edit Weapons"></span>';
        $output_html .= '<a href="' . $url . '">' . $edit_weapons_icon . '</a>';

        return $output_html;
    }

    static function buildEditWeaponsUrl($player_name, $character_name) {
        $url = CurlHelper::buildUrl('characterActionRouter');
        $url = CurlHelper::addParameter($url, 'characterAction', 'playerCharacterWeaponMain');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
    
        return $url;
    }

    static function buildEditPlayerCharacterWeapon($player_name, $character_name, $player_character_weapon_id) {
        $url = ActionBarHelper::buildEditPlayerCharacterWeaponUrl($player_name, $character_name, $player_character_weapon_id);
        $edit_weapon_icon = '<span class="fa-solid fa-pen" style="cursor: pointer; color: black;" title="Edit Weapon"></span>';
        $output_html = '<a href="' . $url . '">' . $edit_weapon_icon . '</a>';

        return $output_html;
    } 

    static function buildEditPlayerCharacterWeaponUrl($player_name, $character_name, $player_character_weapon_id) {
        $url = CurlHelper::buildUrl('characterActionRouter');
        $url = CurlHelper::addParameter($url, 'characterAction', 'updatePlayerCharacterWeapon');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
        $url = CurlHelper::addParameter($url, 'playerCharacterWeaponId', $player_character_weapon_id);

        return $url;
    }

    static function buildEditSkillsIcon($player_name, $character_name) {
        $output_html = '';
        $url = ActionBarHelper::buildEditSkillsUrl($player_name, $character_name);
        $edit_skills_icon = '<span class="fa-solid fa-school" style="cursor: pointer;" title="Edit Skills"></span>';
        $output_html .= '<a href="' . $url . '">' . $edit_skills_icon . '</a>';

        return $output_html;
    }

    static function buildEditSkillsUrl($player_name, $character_name) {
        $url = CurlHelper::buildUrl('characterActionRouter');
        $url = CurlHelper::addParameter($url, 'characterAction', 'editSkills');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
    
        return $url;
    }

    static function buildPromoteClassIcon($player_name, $character_name, $character_class_name) {
        $output_html = '$nbsp;';
        $url_promote_class = ActionBarHelper::buildPromoteUrl($player_name, $character_name, $character_class_name);
        $url_promote_icon = '<span class="fa-solid fa-arrow-up-right-dots" style="color: blue; cursor: pointer;" title="Promote"></span>';
        $output_html .= '<a href="' . $url_promote_class . '">' . $url_promote_icon . '</a>';
    }

    static function buildPromoteUrl($player_name, $character_name, $character_class_name) {
        $url = CurlHelper::buildUrl('characterActionRouter');
        $url = CurlHelper::addParameter($url, 'characterAction', 'promote');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);
        $url = CurlHelper::addParameter($url, CHARACTER_CLASS_NAME, $character_class_name);
        
        return $url;
    }
    
    static function buildEditExtraSlotIcon($player_name, $character_name) {
        $output_html  = '&nbsp;';
        $url_edit_extra_slot = ActionBarHelper::buildEditExtraSlotUrl($player_name, $character_name);
        $edit_extra_slot_icon = '<span class="fa-solid fa-square-minus" style="color: blue; cursor: pointer;" title="Edit Extra Slot"></span>';
        $output_html .= '<a href="' . $url_edit_extra_slot . '">' . $edit_extra_slot_icon . '</a>';
        
        return $output_html;
    }
    
    static function buildEditExtraSlotUrl($player_name, $character_name) {
        $url = CurlHelper::buildUrl('characterActionRouter');
        $url = CurlHelper::addParameter($url, 'characterAction', 'editExtraSlots');
        $url = CurlHelper::addParameter($url, PLAYER_NAME, $player_name);
        $url = CurlHelper::addParameter($url, CHARACTER_NAME, $character_name);

        return $url;
    }
}
