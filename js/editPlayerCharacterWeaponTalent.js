
export function submitAddWeaponTalentForm(form_id, skill_catalog_element_id, skill_catalog_value, weapon_talent_element_id, weapon_talent_element_value, weapon2_talent_element_id, weapon2_talent_value) {

    const jqAddWeaponTalentForm = $('#' + form_id);
    const jqSkillCatalogElement = $('#' + skill_catalog_element_id);
    const jqWeaponTalentElement = $('#' + weapon_talent_element_id);
    const jqWeapon2TalentElement = $('#' + weapon2_talent_element_id);

    jqSkillCatalogElement.val(skill_catalog_value);
    jqWeaponTalentElement.val(weapon_talent_element_value);
    jqWeapon2TalentElement.val(weapon2_talent_value);
    jqAddWeaponTalentForm.submit();
}

export function confirmPlayerCharacterWeaponTalentDelete(formId, playerCharacterWeaponTalentId, playerCharacterWeaponTalentIdValue, weaponTalentDescription) {
    if (confirm("Are you sure you want to delete the weapon proficiency for '" + weaponTalentDescription + "'") == false) {
        return false;
    }

    const jqPlayerCharacterWeaponTalentId = '#' + playerCharacterWeaponTalentId;
    const jqFormId = '#' + formId;

    $(jqPlayerCharacterWeaponTalentId).val(playerCharacterWeaponTalentIdValue);
    $(jqFormId).submit();
}

window.confirmPlayerCharacterWeaponTalentDelete = confirmPlayerCharacterWeaponTalentDelete;
window.submitAddWeaponTalentForm = submitAddWeaponTalentForm;
