var API = require("./FFXIPackageHelper_ActionAPI.js");
var Data = require("./FFXIPackageHelper_DataManager.js");

var ModalCharAddWindow = require("./FFXIPackageHelper_ModalCharAdd.js");
var ModalCharRemoveWindow = require("./FFXIPackageHelper_ModalCharRemove.js");

module.exports.setLinks = function (){

    const modalCharAdd = new ModalCharAddWindow({ saveCallback: saveCharacterClicked});
    const newCharButton = document.getElementById("FFXIPackageHelper_newCharButton");
    newCharButton.addEventListener("click", function (e) {
        modalCharAdd.open();
    });


    const charButtons = document.getElementsByClassName("FFXIPackageHelper_charButton");

    const refreshButton = document.getElementById("FFXIPackageHelper_refreshStatsButton");
    refreshButton.addEventListener("click", function () {

        Array.from(charButtons).forEach((button) => {
            if ( button.classList.contains('FFXIPackageHelper_charButtonselected') ) button.classList.toggle('FFXIPackageHelper_charButtonselected');
          });

        Data.resetStats();
        scrollToTop();
        Data.setHeaderCharacterDetails();

    });

    const removeChar = document.getElementById("FFXIPackageHelper_deleteCharButton");
    removeChar.addEventListener("click", function () {
        let charname = null;
        Array.from(charButtons).forEach((button) => {
            if ( button.classList.contains('FFXIPackageHelper_charButtonselected') ) charname = button.innerHTML;
          });
          //console.log(charname);
        if ( charname ) removeCharacter(charname);
    });

    if ( charButtons ) { addCharButtonEvents(charButtons); }

    const raceDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectRace");
    raceDropdown.addEventListener("change", () =>  {
        //console.log(e.target.value);
        Data.updateStats();
        resetCharSelection();
        Data.setHeaderCharacterDetails();

    });

    const counterbox = document.querySelectorAll('#FFXIPackageHelper_dynamiccontent_counterbox');
        for (var c = 0; c < counterbox.length; c++) {
            //console.log(counterbox[c].querySelectorAll(".FFXIPackageHelper_dynamiccontent_incrementButton"));
            const buttons = counterbox[c].querySelectorAll(".FFXIPackageHelper_dynamiccontent_incrementButton");
            const input = counterbox[c].querySelector(".FFXIPackageHelper_dynamiccontent_incrementInput");
            buttons[0].addEventListener("click", function (e) {
                changeMeritValues(input, -1);
            });
            buttons[1].addEventListener("click", function (e) {
                changeMeritValues(input, 1);
            });
        }

    // Set all associated layouts for the Edit button and associated events
    const editMerits = document.getElementById("FFXIPackageHelper_dynamiccontent_changeMerits");
    editMerits.addEventListener("click", function (e) {
        // Adjust Edit button
        if ( editMerits.innerText == "Edit") {
            editMerits.innerText = "Apply";
            editMerits.className = "FFXIPackageHelper_dynamiccontent_customButton";
        }
        else {
            editMerits.innerText = "Edit";
            editMerits.className = "FFXIPackageHelper_dynamiccontent_shareButton";
            resetCharSelection();
            Data.updateStats(null);
            Data.setHeaderCharacterDetails();
        }

        // Adjust merit edit buttons for all stats
        for (var c = 0; c < counterbox.length; c++) {

            let meritschildren = counterbox[c].children;
            for (var i = 0; i < meritschildren.length; i++) {
                //console.log(meritschildren[i].tagName);
                if (meritschildren[i].tagName == "BUTTON" ){
                    //console.log(meritschildren[i]);
                    meritschildren[i].classList.toggle('incrementButton_show');
                }
            }
        }
    });

     Data.setHeaderCharacterDetails();
}

function selectCharClicked(charname){
    //console.log("selectCharClicked");

    const data = {
        action: "equipsets_selectchar",
        charname: charname,
    }

    show_RemoveCharacterButton();

    API.actionAPI(data, data.action, null, Data);
    scrollToTop();
}

function saveCharacterClicked(charName){
    //console.log(charName);

    const data = {
        action: "equipsets_savechar",
        race: document.getElementById("FFXIPackageHelper_equipsets_selectRace").value,
        merits: encodeURIComponent(btoa(Data.getMeritsData())),
        charname: charName
    }

    API.actionAPI(data, data.action, null, characterSaved);
    scrollToTop();
}

function removeCharacter(charname){
    //console.log("should remove " + charname);

    const data = {
        action: "equipsets_removechar",
        charname: charname
    }

    API.actionAPI(data, data.action, null, resetCharList);
    Data.resetStats();
    scrollToTop();
    Data.setHeaderCharacterDetails();

}

function addCharButtonEvents(charButtons){
    Array.from(charButtons).forEach((button) => {
        button.addEventListener("click", function () {

           selectCharClicked(button.innerHTML);

            Array.from(charButtons).forEach((btn) => {
                if ( btn.classList.contains('FFXIPackageHelper_charButtonselected') ) btn.classList.toggle('FFXIPackageHelper_charButtonselected');
            });

            button.classList.toggle('FFXIPackageHelper_charButtonselected');
        });
    });
}

function resetCharList(incCharsList){
    //remove all old list items
    clearCharList();

    //add new list items
    if (incCharsList){
        var charSelectDIV = document.getElementById("FFXIPackageHelper_equipsets_charSelect");
        incCharsList.map((details) => {
            var btn = document.createElement('button')
            btn.appendChild(document.createTextNode(details["charname"]));
            btn.id = 'FFXIPackageHelper_charButton_' + details["charname"];
            //btn.id = 'FFXIPackageHelper_charButton';
            btn.classList.add("FFXIPackageHelper_charButton");
            charSelectDIV.appendChild(btn);

            btn.addEventListener("click", function (){
                selectCharClicked(details["charname"]);
            });
        });
    }

    //add event listeners to new items
    const charButtons = document.getElementsByClassName("FFXIPackageHelper_charButton");
    if ( charButtons ) addCharButtonEvents(charButtons);
}

function characterSaved(results){
    const recentCharSaved = results[0];
    const newUserList = results[1];

    resetCharList(newUserList);

    const charButton = document.getElementById('FFXIPackageHelper_charButton_' + recentCharSaved);
    charButton.classList.toggle('FFXIPackageHelper_charButtonselected');

    show_RemoveCharacterButton();
    scrollToTop();
    Data.setHeaderCharacterDetails();
}

function clearCharList(){
    var charSelectDIV = document.getElementById("FFXIPackageHelper_equipsets_charSelect");
    const buttons = charSelectDIV.querySelectorAll('button');
    Array.from(buttons).forEach((button) => {
      if ( button.classList.contains("FFXIPackageHelper_charButton") ) charSelectDIV.removeChild(button);
    });
}

function show_RemoveCharacterButton(){
    const removeButton = document.getElementById("FFXIPackageHelper_deleteCharButton");
    removeButton.style.display = "inline-block";
}

// function remove_RemoveCharacterButton(){
//     const removeButton = document.getElementById("FFXIPackageHelper_deleteCharButton");
//     removeButton.style.display = "none";
// }

function resetCharSelection(){
    const removeButton = document.getElementById("FFXIPackageHelper_deleteCharButton");
    removeButton.style.display = "none";

    const selectedChars = document.getElementsByClassName("FFXIPackageHelper_charButtonselected");
    Array.from(selectedChars).forEach((btn) => {
        btn.classList.toggle('FFXIPackageHelper_charButtonselected');
    });
}

function scrollToTop() {
    //const top = document.getElementById("FFXIPackageHelper_dynamiccontent_changeMerits_top");
    //window.scrollIntoView({ behavior: "smooth", block: "start" });
}

function changeMeritValues(forInput, val){
    // < 0 - make it 0 and return
    if ( Number(forInput.value) + val < 0 ) {
        mw.notify( "Skills must remain > 0.", { autoHide: true,  type: 'error' } );
        return;
    }

    //Number(forInput.value) + val;

    // stats in the array are numbered <= 14
    const statID = "FFXIPackageHelper_equipsets_merits_stats";
    if ( forInput.id.includes(statID)){
        const stat = Number(forInput.id.replace(statID, ""));
        if ( stat <= 14 ) {
            if ( (Number(forInput.value) + val) > 5 ) {
                mw.notify( "This stat is capped at 5.", { autoHide: true,  type: 'error' } );
                return;
            }
            else forInput.value = Number(forInput.value) + val;
        }
        else forInput.value = Number(forInput.value) + val;
        return;
    }

    /**
     * skill in the array are numbered between 80 and 110
     *
     *
     */
    const skillID = "FFXIPackageHelper_equipsets_merits_skill";
    if ( forInput.id.includes(skillID)){
        if ( val == -1 ) {
            forInput.value = Number(forInput.value) + val;
            return;
        }

        let combatSkills_total = 0, magicSkills_total = 0;
        const skills = document.querySelectorAll('[id*=' + skillID + ']');
        const forInputID = Number(forInput.id.replace(skillID, ""));

        for( let s = 0; s < skills.length; s++) {
            // console.log(s);
            // console.log(skills[s].id, );
            const id =  Number(skills[s].id.replace(skillID, ""));

            // Combat Skills
            // cap at 8 each, 12 total
            if ( skills[s].value > 0 ) console.log(skills[s].value, forInputID, id, skills[s].id);
            if ( id >= 80 && id <= 110){
                if ( id < 107 && Number(skills[s].value) == 8 && forInputID == id ){
                    mw.notify( "Offensive combat skill capped at 8.", { autoHide: true,  type: 'error' } );
                    return;
                }
                if ( id >= 107 && Number(skills[s].value) == 4 && forInputID == id ){
                    mw.notify( "Defensive combat skill capped at 4.", { autoHide: true,  type: 'error' } );
                    return;
                }
        console.log(combatSkills_total, Number(skills[s].value));
                if ( combatSkills_total == 12 ) {
                    mw.notify( "Total combat skills already capped at 12 points.", { autoHide: true,  type: 'error' } );
                    return;
                }

                combatSkills_total += Number(skills[s].value);
            }
            // Magic Skills
            else {
                if ( Number(skills[s].value) == 8 && forInputID == id ){
                    mw.notify( "Magic skill capped at 8.", { autoHide: true,  type: 'error' } );
                    return;
                }

                if ( magicSkills_total == 8 ) {
                    mw.notify( "Total magic skills already capped at 8 points.", { autoHide: true,  type: 'error' } );
                    return;
                }

                magicSkills_total += Number(skills[s].value);
            }
        };

        // Start the logic checking for caps

        // if ( combatSkills_total == 12 ) {
        //     mw.notify( "Skills already capped at 12 total points.", { autoHide: true,  type: 'error' } );
        //     return;
        // }
        // else {
            forInput.value = Number(forInput.value) + val;
            //return;
        //}
    }

}






