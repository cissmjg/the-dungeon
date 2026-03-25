export function submitAddSkillForm(form_id, skill_catalog_element_id, skill_catalog_value) {

    const jqAddSkillForm = $('#' + form_id);
    const jqSkillCatalogElement = $('#' + skill_catalog_element_id);

    jqSkillCatalogElement.val(skill_catalog_value);
    jqAddSkillForm.submit();
}

export function confirmPlayerCharacterSkillDelete(formId, playerCharacterSkillElementId, playerCharacterSkillElementIdValue, skillDescription) {
    if (confirm("Are you sure you want to delete '" + skillDescription + "'") == false) {
        return false;
    }

    const jqplayerCharacterSkillElementId = '#' + playerCharacterSkillElementId;
    const jqFormId = '#' + formId;

    $(jqplayerCharacterSkillElementId).val(playerCharacterSkillElementIdValue);
    $(jqFormId).submit();
}
