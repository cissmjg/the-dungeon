        function hideOtherElements(all_occupied_slots, submit_icon_id, cancel_icon_id, hide_element_icon_id, update_existing_slot_submit_icon_id) {

            // Hide the other spells for this level
            let other_slots = all_occupied_slots.split(",");
            other_slots.forEach(function (other_slot, index) {
                let other_slot_element = document.getElementById(other_slot);
                if (other_slot_element != null) {
                    other_slot_element.style.opacity = "0.0";
                } else {
                    alert ("Element with ID " + other_slot + " not found");
                    return false;
                }
            });

            // Hide the feather that updates unallocated slots
            let submit_icon = document.getElementById(submit_icon_id);
            if (submit_icon != null) {
                submit_icon.hidden = true;
            } else {
                alert ("Submit icon " + submit_icon_id + " not found");
                return false;
            }

            // Make the cancel icon visible
            let cancelEditIcon = document.getElementById(cancel_icon_id);
            if (cancelEditIcon != null) {
                cancelEditIcon.hidden = false;
            } else {
                alert ("Cancel icon with ID " + cancel_icon_id + " not found");
                return false;
            }

            // Hide the 'hide elements' icon
            let hideElementIcon = document.getElementById(hide_element_icon_id);
            if (hideElementIcon != null) {
                hideElementIcon.hidden = true;
            } else {
                alert ("Hide icon with ID " + hide_element_icon_id + " not found");
                return false;
            }

            // Show the feather that updates existing slots
            let updateExistingSlotSubmitIcon = document.getElementById(update_existing_slot_submit_icon_id);
            if (updateExistingSlotSubmitIcon != null) {
                updateExistingSlotSubmitIcon.hidden = false;
            } else {
                alert ("Update existing slot icon with ID " + update_existing_slot_submit_icon_id + " not found");
                return false;
            }
        }

        function unhideOtherElements(all_occupied_slots, submit_icon_id, cancel_icon_id, hide_element_icon_id, update_existing_slot_submit_icon_id) {

            // Show the other spells for this level
            let other_slots = all_occupied_slots.split(",");
            other_slots.forEach(function (other_slot, index) {
                let other_slot_element = document.getElementById(other_slot);
                if (other_slot_element != null) {
                    other_slot_element.style.opacity = "1.0";
                } else {
                    alert ("Element with ID " + other_slot + " not found");
                    return false;
                }
            });

            // Show the feather that updates unallocated slots
            let submit_icon = document.getElementById(submit_icon_id);
            if (submit_icon != null) {
                submit_icon.hidden = false;
            } else {
                alert ("Submit icon " + submit_icon_id + " not found");
                return false;
            }

            // Make the cancel icon hidden
            let cancelEditIcon = document.getElementById(cancel_icon_id);
            if (cancelEditIcon != null) {
                cancelEditIcon.hidden = true;
            } else {
                alert ("Cancel icon with ID " + cancel_icon_id + " not found");
                return false;
            }

            // Show the 'hide elements' icon
            let hideElementIconId = document.getElementById(hide_element_icon_id);
            if (hideElementIconId != null) {
                hideElementIconId.hidden = false;
            } else {
                alert ("Hide icon with ID " + hide_element_icon_id + " not found");
                return false;
            }

            // Hide the feather that updates existing slots
            let updateExistingSlotSubmitIcon = document.getElementById(update_existing_slot_submit_icon_id);
            if (updateExistingSlotSubmitIcon != null) {
                updateExistingSlotSubmitIcon.hidden = true;
            } else {
                alert ("Update existing slot icon with ID " + update_existing_slot_submit_icon_id + " not found");
                return false;
            }
        }
