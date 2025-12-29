import { buildURL, addParameter } from './RestHelper.js';

const initialWeaponSelectPrompt = "[Select a Weapon]";

export function populateWeaponList(weaponListName, weaponSearchTextboxName) {
    const weaponQueryPattern = '#' + weaponSearchTextboxName;
    const weaponList = "#" + weaponListName;
    let weaponQueryAPI = buildURL('getWeaponProficiencyByPattern');
    weaponQueryAPI = addParameter(weaponQueryAPI, 'textInput', $(weaponQueryPattern).val());
    $(weaponList).empty();
    $.getJSON(weaponQueryAPI,
        function(data, textStatus, jqXHR) {
            $(weaponList).append(new Option(initialWeaponSelectPrompt, 0));
            $.each(data, function(i, weapon_proficiency_object) {
                $(weaponList).append(new Option(weapon_proficiency_object.weapon_proficiency_name, weapon_proficiency_object.weapon_proficiency_id));
            });
        }
    );
    $(weaponList).show();
}

export function weaponListChanged(selectWeaponButtonName, weaponListName) {
    const jqSelectWeaponButtonName = '#' + selectWeaponButtonName
    const jqWeaponListName = '#' + weaponListName;
    const weaponSelected = $(jqWeaponListName).val();

    if (weaponSelected == 0) {
        $(jqSelectWeaponButtonName).hide();
    } else {
        $(jqSelectWeaponButtonName).show();
    }
}

export function confirmPlayerCharacterWeaponDelete(formId, playerCharacterWeaponId, playerCharacterWeaponIdValue, weaponDescription) {
    if (confirm("Are you sure you want to delete the weapon named '" + weaponDescription + "'") == false) {
        return false;
    }

    const jqPlayerCharacterWeaponId = '#' + playerCharacterWeaponId;
    const jqFormId = '#' + formId;

    $(jqPlayerCharacterWeaponId).val(playerCharacterWeaponIdValue);
    $(jqFormId).submit();
}