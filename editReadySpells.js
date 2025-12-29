function toggleVisibility(elementId) {
    let element = document.getElementById(elementId);
    if (element != null) {
        let elementHidden = !element.hidden;
        element.hidden = elementHidden;
    }
}

function hideSpellShowChangeForm(row_id, change_slot_id) {
    let spell_row = document.getElementById(row_id);
    if (spell_row != null) {
        spell_row.hidden = true;
    }

    let change_slot_form = document.getElementById(change_slot_id);
    if (change_slot_form != null) {
        change_slot_form.hidden = false;
    }
}

function showSpellHideChangeForm(row_id, change_slot_id) {
    let spell_row = document.getElementById(row_id);
    if (spell_row != null) {
        spell_row.hidden = false;
    }

    let change_slot_form = document.getElementById(change_slot_id);
    if (change_slot_form != null) {
        change_slot_form.hidden = true;
    }
}

function hideUpdateContainer(update_container_id, all_occupied_slot_ids, update_container_spell_name_id) {
    // Show the other spells for this level
    let other_slots = all_occupied_slot_ids.split(",");
    other_slots.forEach(function (other_slot, index) {
        let other_slot_element = document.getElementById(other_slot);
        if (other_slot_element != null) {
            other_slot_element.hidden = false;
        } else {
            alert ("Element with ID " + other_slot + " not found");
            return false;
        }
    });

    // Hide the update container
    let update_container = document.getElementById(update_container_id);
    if (update_container != null) {
        update_container.hidden = true;
        } else {
    alert ("Update Container with ID " + update_container_id + " not found");
        return false;
    }
}

function showUpdateContainer(update_container_id, all_occupied_slot_ids, update_container_spell_name_id, update_spell_name, update_form_spell_slot_id, update_spell_slot_id) {
    // Hide the other spells for this level
    let other_slots = all_occupied_slot_ids.split(",");
    other_slots.forEach(function (other_slot, index) {
        let other_slot_element = document.getElementById(other_slot);
        if (other_slot_element != null) {
            other_slot_element.hidden = true;
        } else {
            alert ("Element with ID " + other_slot + " not found");
            return false;
        }
    });

    // Show the update container
    let update_container = document.getElementById(update_container_id);
    if (update_container != null) {
        update_container.hidden = false;
    } else {
        alert ("Update Container with ID " + update_container_id + " not found");
        return false;
    }

    // Update the container with the name of the spell
    let update_container_spell_name = document.getElementById(update_container_spell_name_id);
    if (update_container_spell_name != null) {
        update_container_spell_name.innerText = update_spell_name;
    } else {
        alert ("Update Spell Name Container with ID " + update_container_spell_name_id + " not found");
        return false;
    }

    // Update the form spell slot input tag
    let update_form_spell_slot = document.getElementById(update_form_spell_slot_id);
    if (update_form_spell_slot != null) {
        update_form_spell_slot.value = update_spell_slot_id;
    } else {
        alert ("Update Form Spell Slot with ID " + update_form_spell_slot_id + " not found");
        return false;
    }
}

function submitSlotActionForm(spell_slot_id, character_action, spell_duration, spell_casting_time) {
    let spell_slot_id_element = document.getElementById("spellSlotId");
    if (spell_slot_id_element != null) {
    } else {
        alert ("Slot Action Form 'Spell Slot ID' with ID " + spell_slot_id + " not found");
        return false;
    }
    spell_slot_id_element.value = spell_slot_id;

    let spell_duration_element = document.getElementById("spellDuration");
    if (spell_duration_element != null) {
    } else {
        alert ("Slot Action Form Spell 'Duration' not found");
        return false;
    }
    spell_duration_element.value = spell_duration;

    let spell_casting_time_element = document.getElementById("spellCastingTime");
    if (spell_casting_time_element != null) {
    } else {
        alert ("Slot Action Form Spell 'Casting Time' not found");
        return false;
    }
    spell_casting_time_element.value = spell_casting_time;

    submitTheForm("slot-action-form", "characterAction", character_action);
}