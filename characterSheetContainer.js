$(document).ready(function() {
  $(".characterSheetFeature > a").on("click", function() {
    if ($(this).hasClass("active")) {
      $(this).removeClass("active");
      $(this)
        .siblings(".characterSheetFeatureContent")
        .slideUp(200);
      $(this)
        .find("i")
        .removeClass("fa-minus")
        .addClass("fa-plus");
    } else {
      $(this)
        .find("i")
        .removeClass("fa-plus")
        .addClass("fa-minus");
      $(this).addClass("active");
      $(this)
        .siblings(".characterSheetFeatureContent")
        .slideDown(200);
    }
  });
});

function showRollModifierSectionClick(contentID, contentIconID) {
    
    let rollModifierSectionElement = document.getElementById(contentID);
    if (rollModifierSectionElement == null) {
        alert("Roll Modifier section with ID: " + contentID + " not found");
        return;
    }
    
    rollModifierSectionElement.hidden = !rollModifierSectionElement.hidden;
    
    const jqContentIconId = "#" + contentIconID;
    
    if ($(jqContentIconId).hasClass("fa-chevron-down")) {
        $(jqContentIconId)
          .removeClass("fa-chevron-down")
          .addClass("fa-chevron-up");
    }
    else {
        $(jqContentIconId)
          .removeClass("fa-chevron-up")
          .addClass("fa-chevron-down");
    }
}

function arrowSelectionChange(weaponName, arrowType) {
    
    const pbBowRowID = weaponName + "-PB";
    let pbBowRowElement = document.getElementById(pbBowRowID);
    if (pbBowRowElement == null) {
        alert("Can't find element: " + pbBowRowID);
        return;
    }
    
    const shortBowRowID = weaponName + "-Short";
    let shortBowRowElement = document.getElementById(shortBowRowID);
    if (shortBowRowElement == null) {
        alert("Can't find element: " + shortBowRowID);
        return;
    }

    const mediumBowRowID = weaponName + "-Med";
    let mediumBowRowElement = document.getElementById(mediumBowRowID);
    if (mediumBowRowElement == null) {
        alert("Can't find element: " + mediumBowRowID);
        return;
    }
    
    const medBowRowSwiftWingID = weaponName + "-Med-SwiftWing";
    let medBowRowSwiftWingElement = document.getElementById(medBowRowSwiftWingID);
    if (medBowRowSwiftWingElement == null) {
        alert("Can't find element: " + medBowRowSwiftWingID);
        return;
    }

    const longBowRowID = weaponName + "-Long";
    let longBowRowElement = document.getElementById(longBowRowID);
    if (longBowRowElement == null) {
        alert("Can't find element: " + longBowRowID);
        return;
    }
    
    const longBowRowSwiftWingID = weaponName + "-Long-SwiftWing";
    let longBowRowSwiftWingElement = document.getElementById(longBowRowSwiftWingID);
    if (longBowRowSwiftWingElement == null) {
        alert("Can't find element: " + longBowRowID);
        return;
    }
    
    switch(arrowType) {
        case "Blunt":
            longBowRowElement.hidden = true;
            longBowRowSwiftWingElement.hidden = true;
            medBowRowSwiftWingElement.hidden = true;
            mediumBowRowElement.hidden = false;
            break;
        case "SwiftWing":
            longBowRowElement.hidden = true;
            longBowRowSwiftWingElement.hidden = false;
            medBowRowSwiftWingElement.hidden = false;
            mediumBowRowElement.hidden = true;
            break;
        default:
            medBowRowSwiftWingElement.hidden = true;
            mediumBowRowElement.hidden = false;
            longBowRowElement.hidden = false;
            longBowRowSwiftWingElement.hidden = true;
            break;
    }
}

function togglePhalanx(mode, newAC) {
    let armorClassPartneredElement = document.getElementById("armorClassPartnered");
    if (armorClassPartneredElement == null) {
        alert("Armor Class element with ID: 'armorClassPartnered' not found");
        return;
    }

    let armorClassSoloElement = document.getElementById("armorClassSolo");
    if (armorClassSoloElement == null) {
        alert("Armor Class element with ID: 'armorClassSolo' not found");
        return;
    }
    
    let armorClassMainElement = document.getElementById("armorClassMain");
    if (armorClassMainElement == null) {
        alert("Armor Class element with ID: 'armorClassMain' not found");
        return;
    }
    
    let armorClassSummaryElement = document.getElementById("armorClassSummary");
    if (armorClassSummaryElement == null) {
        alert("Armor Class element with ID: 'armorClassSummary' not found");
        return;
    }    
    
    switch(mode) {
        case "partnered":
            armorClassPartneredElement.hidden = false;
            armorClassSoloElement.hidden = true;
            armorClassMainElement.innerText = newAC;
            armorClassSummaryElement.innerText = newAC;
            break;
        case "solo":
            armorClassPartneredElement.hidden = true;
            armorClassSoloElement.hidden = false;
            armorClassMainElement.innerText = newAC;
            armorClassSummaryElement.innerText = newAC;
            break;
        default:
            break;
    }
}