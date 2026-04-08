import { addParameter, buildDbioDirURL } from './RestHelper.js';
import { submitAddSkillForm, confirmPlayerCharacterSkillDelete } from './candidateSkill.js';

const initialWeaponSelectPrompt = "[Select a Weapon]";

export function populateWeaponList(weaponListId, playerName, characterName, weaponSearchTextboxName) {
    let jqWeaponQueryPattern = $('#' + weaponSearchTextboxName);
    const jqWeaponListid = "#" + weaponListId;

    let weaponQueryAPI = buildDbioDirURL('getWeaponProficienciesAvailableForPlayerCharacter');
    weaponQueryAPI = addParameter(weaponQueryAPI, 'playerName', playerName);
    weaponQueryAPI = addParameter(weaponQueryAPI, 'characterName', characterName);
    weaponQueryAPI = addParameter(weaponQueryAPI, 'textInput', jqWeaponQueryPattern.val());

    $(jqWeaponListid).empty();
    $.getJSON(weaponQueryAPI,
        function(data, textStatus, jqXHR) {
            $(jqWeaponListid).append(new Option(initialWeaponSelectPrompt, 0));
            $.each(data, function(i, weapon_proficiency_object) {
                $(jqWeaponListid).append(new Option(weapon_proficiency_object.weapon_proficiency_name, weapon_proficiency_object.weapon_proficiency_id));
            });
        }
    );

    $(jqWeaponListid).show();
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

export function confirmPlayerCharacterWeaponProficiencyDelete(formId, playerCharacterWeaponProficiencyId, playerCharacterWeaponProficiencyIdValue, weaponDescription) {
    if (weaponDescription == 'Fist') {
        alert("'Fist' cannot be deleted");
        return false;
    }

    if (confirm("Are you sure you want to delete the weapon proficiency for '" + weaponDescription + "'") == false) {
        return false;
    }

    const jqPlayerCharacterWeaponProficiencyId = '#' + playerCharacterWeaponProficiencyId;
    const jqFormId = '#' + formId;

    $(jqPlayerCharacterWeaponProficiencyId).val(playerCharacterWeaponProficiencyIdValue);
    $(jqFormId).submit();
}

export function submitAddPreferredWeaponProficiencyForm(formId, preferredWeaponListId, preferredWeaponProficiencyElementId) {
    let jqForm = $('#' + formId);
    const jqPreferredWeaponList = $('#' + preferredWeaponListId);
    let preferredWeaponProficiencyElement = $('#' + preferredWeaponProficiencyElementId);

    const jqPreferredWeaponid = jqPreferredWeaponList.val();
    preferredWeaponProficiencyElement.val(jqPreferredWeaponid);
    
    jqForm.submit();
}

export function preferredWeaponChange(preferredWeaponListId, addPreferredWeaponButtonId, preferredWeaponProficiencyElementId) {
    const jqWeaponList = $('#' + preferredWeaponListId);
    let jqPreferredWeaponProficiencyElement = $('#' + preferredWeaponProficiencyElementId);
    let jqPreferredButton = $('#' + addPreferredWeaponButtonId);

    const jqSelectedWeaponId = jqWeaponList.val();

    if (jqSelectedWeaponId == 0) {
        jqPreferredButton.hide();
    } else {
        jqPreferredWeaponProficiencyElement.val(jqSelectedWeaponId);
        jqPreferredButton.show();
    }
}

window.populateWeaponList = populateWeaponList;
window.weaponListChanged = weaponListChanged;
window.confirmPlayerCharacterWeaponProficiencyDelete = confirmPlayerCharacterWeaponProficiencyDelete;

window.submitAddSkillForm = submitAddSkillForm;
window.confirmPlayerCharacterSkillDelete = confirmPlayerCharacterSkillDelete;

window.submitAddPreferredWeaponProficiencyForm = submitAddPreferredWeaponProficiencyForm;
window.preferredWeaponChange = preferredWeaponChange;