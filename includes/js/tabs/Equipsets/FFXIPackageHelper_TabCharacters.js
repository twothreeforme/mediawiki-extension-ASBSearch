var API = require("./FFXIPackageHelper_ActionAPI.js");
var Data = require("./FFXIPackageHelper_DataManager.js");
var ActionButtons = require("./FFXIPackageHelper_ActionButtons.js");

// var ModalCharAddWindow = require("./FFXIPackageHelper_ModalCharAdd.js");
// var ModalCharRemoveWindow = require("./FFXIPackageHelper_ModalCharRemove.js");

const EDIT_BUTTON = document.getElementById("FFXIPackageHelper_editCharButton");
const REMOVE_BUTTON = document.getElementById("FFXIPackageHelper_deleteCharButton");
const SAVE_BUTTON = document.getElementById("FFXIPackageHelper_dynamiccontent_saveChar");
const DEFAULT_SWITCH = document.getElementById("FFXIPackageHelper_dynamiccontent_defaultChar");
const NEWCHAR_BUTTON = document.getElementById("FFXIPackageHelper_newCharButton");
const RACE_DROPDOWN = document.getElementById("FFXIPackageHelper_equipsets_selectRace");


const counterbox = document.querySelectorAll('#FFXIPackageHelper_dynamiccontent_counterbox');
const hiddenDiv = document.getElementById("FFXIPackageHelper_dynamiccontent_newCharSection");
hiddenDiv.offsetTop;

let characterComparison = null;
let currentCharacterName = null;

module.exports.setLinks = function (){

    const charButtons = document.getElementsByClassName("FFXIPackageHelper_charButton");
    if ( charButtons ) { addCharButtonEvents(charButtons); }

    // const refreshButton = document.getElementById("FFXIPackageHelper_refreshStatsButton");
    // refreshButton.addEventListener("click", function () {

    //     Array.from(charButtons).forEach((button) => {
    //         if ( button.classList.contains('FFXIPackageHelper_charButtonselected') ) button.classList.toggle('FFXIPackageHelper_charButtonselected');
    //       });

    //     Data.resetStats();
    //     scrollToTop();
    //     Data.setHeaderCharacterDetails();

    // });

    REMOVE_BUTTON.addEventListener("click", function () {
        let charname = null;
        Array.from(charButtons).forEach((button) => {
            if ( button.classList.contains('FFXIPackageHelper_charButtonselected') ) charname = button.innerHTML;
          });
          //console.log(charname);
        if ( charname ) removeCharacter(charname);
    });


    RACE_DROPDOWN.addEventListener("change", () =>  {
        //console.log(e.target.value);
        Data.updateStats();
        //resetCharSelection();
        Data.setHeaderCharacterDetails();

    });

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
    EDIT_BUTTON.addEventListener("click", function (e) {
        EDIT_BUTTON.classList.toggle("FFXIPackageHelper_editCharButtonSelected");
        //NEWCHAR_BUTTON.classList.toggle('FFXIPackageHelper_newCharButton_Grayed');

        // Adjust Edit button
        if ( EDIT_BUTTON.innerText == "Edit") {
            
            characterComparison = {
                def: DEFAULT_SWITCH.checked,
                race: RACE_DROPDOWN.value,
                merits: Data.getMeritsData()
            };

            EDIT_BUTTON.innerText = "Apply";
            setDisabledState_AllCharacterButtons(true);
            ActionButtons.hideButton(REMOVE_BUTTON);//hide remove button
            NEWCHAR_BUTTON.disabled = true;
        }
        else {
            
            //resetCharSelection();

            let charChanges = {
                def: DEFAULT_SWITCH.checked,
                race: RACE_DROPDOWN.value,
                merits: Data.getMeritsData()
            };

            //console.log(characterComparison, charChanges);
            
            if ( characterComparison.def != charChanges.def ||
                characterComparison.race != charChanges.race ||
                characterComparison.merits != charChanges.merits ){
                    if ( manualModeSelected() == true ) Data.updateStats();
                    else updateSavedCharacter();
                }
            // console.log(characterComparison, charChanges);
            EDIT_BUTTON.innerText = "Edit";
            characterComparison = null;
            charChanges = null;
            setDisabledState_AllCharacterButtons(false);
            if ( manualModeSelected() == false ) ActionButtons.showButton(REMOVE_BUTTON);//show remove button
            NEWCHAR_BUTTON.disabled = false;
        }

        toggleDefaultSwitch();
        toggleRaceDropDown();
        toggleMeritEditButtons(counterbox);
    });

    //const modalCharAdd = new ModalCharAddWindow({ saveCallback: saveCharacterClicked});
    NEWCHAR_BUTTON.addEventListener("click", function () {
        //console.log("NEWCHAR_BUTTON.addEventListener(\"click\",");
        if ( hiddenDiv.style.display != "none" ) {
            selectCharClicked(currentCharacterName);
            currentCharacterName = null;
        }
        else  {
            const selectedChar = document.getElementsByClassName("FFXIPackageHelper_charButton FFXIPackageHelper_charButtonselected");
            if ( selectedChar.length > 0 ) currentCharacterName = selectedChar[0].innerHTML;
        }

        //toggle New button
        toggleNewButton();
    });

    const inputElement = document.getElementById("FFXIPackageHelper_dynamiccontent_charNameInput");
    inputElement.addEventListener('input', function(event) {
        const value = event.target.value;
        const sanitizedValue = value.replace(/[^a-zA-Z0-9]/g, '');
        event.target.value = sanitizedValue;
        });

    SAVE_BUTTON.addEventListener('click', (e) =>  {
        saveCharacterClicked();
        });

    //selectDefaultCharacterOnLoad();
}

function toggleMeritEditButtons(counterbox){
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
}

function selectDefaultCharacterOnLoad(){
    //console.log("selectDefaultCharacterOnLoad");
    let defaultCharList = document.getElementsByClassName("FFXIPackageHelper_charButton_default");
    if ( defaultCharList.length > 0 ) {
        selectCharClicked(defaultCharList[0].innerHTML);
    }
    else { //select manual mode, NONE for character
        let manual = document.getElementById("FFXIPackageHelper_charButtonNone");
        selectCharClicked(manual, true);
    }
}


function selectCharClicked(character, isManual){
    //console.log("selectCharClicked:", character)
    if ( character == null ) return;

    let data = { action: "equipsets_selectchar" };
    if ( isManual == true ){
        let manualMode = document.getElementById("FFXIPackageHelper_charButtonNone");
        showCharButtonSelected(manualMode, true);
        ActionButtons.hideButton(REMOVE_BUTTON);
        //console.log('hidden');
        Data.resetStats(true);
    }
    else {
        data.charname = character;
        ActionButtons.showButton(REMOVE_BUTTON);
        let selectedCharButton = document.getElementById('FFXIPackageHelper_charButton_' + character);
        if ( selectedCharButton ) showCharButtonSelected(selectedCharButton, true);
    }
    //ActionButtons.showButton(EDIT_BUTTON);

    API.actionAPI(data, data.action, null, Data);
}

function getCharacter(){
    return {
        race: document.getElementById("FFXIPackageHelper_equipsets_selectRace").value,
        merits: encodeURIComponent(btoa(Data.getMeritsData())),
        charname: document.getElementById("FFXIPackageHelper_dynamiccontent_charNameInput").value,
        def: (document.getElementById("FFXIPackageHelper_dynamiccontent_defaultChar").checked == true) ? 1 : 0,
    }
}

function saveCharacterClicked(){
    const data = getCharacter();

    if ( data.charname.length == 0 ){
        mw.notify( "Character name must be filled.", { autoHide: true,  type: 'error' } );
        return;
    }

    data.action = "equipsets_savechar";
    API.actionAPI(data, data.action, null, characterSaved);
    //scrollToTop();

    // make New button green again
    toggleNewButton();

    //hide Save button
    ActionButtons.hideButton(SAVE_BUTTON);
}

function updateSavedCharacter(){
    //console.log("updateSavedCharacter");
    const data = {
        action: "equipsets_updatechar",
        race: document.getElementById("FFXIPackageHelper_equipsets_selectRace").value,
        merits: encodeURIComponent(btoa(Data.getMeritsData())),
        charname: getCharName(),
        def: (document.getElementById("FFXIPackageHelper_dynamiccontent_defaultChar").checked == true) ? 1 : 0,
    }

    //console.log(getCharName());
    API.actionAPI(data, data.action, null, characterSaved);
}

function removeCharacter(charname){
    //console.log("should remove " + charname);

    const data = {
        action: "equipsets_removechar",
        charname: charname
    }

    API.actionAPI(data, data.action, null, characterRemoved);
    

}



function addCharButtonEvents(charButtons){
    Array.from(charButtons).forEach((button) => {
        button.addEventListener("click", function () {
            //console.log("addCharButtonEvents")
            //if ( button.classList.contains('FFXIPackageHelper_charButtonselected') ) return;

            if ( manualModeSelected(button) == true ) {
                
                selectCharClicked(button.innerHTML, true);
            }
            else selectCharClicked(button.innerHTML); 

            Array.from(charButtons).forEach((btn) => {
                //if ( btn.classList.contains('FFXIPackageHelper_charButtonselected') ) btn.classList.toggle('FFXIPackageHelper_charButtonselected');
                showCharButtonSelected(btn, false);
            });

            //toggleSelected(button);\
            showCharButtonSelected(button, true);
            });
    });
}

function resetCharList(incCharsList){
    //remove all old list items
    clearCharList();

    //add new list items
    if ( incCharsList && typeof(incCharsList) == 'array' ){
        console.log("resetCharList:", incCharsList);
        var charSelectDIV = document.getElementById("FFXIPackageHelper_equipsets_charSelect");
        incCharsList.map((details) => {
            var btn = document.createElement('button')
            btn.appendChild(document.createTextNode(details["charname"]));
            btn.id = 'FFXIPackageHelper_charButton_' + details["charname"];
            //btn.id = 'FFXIPackageHelper_charButton';
            btn.classList.add("FFXIPackageHelper_charButton");
            if ( details["def"] != 0 ) btn.classList.add("FFXIPackageHelper_charButton_default");
            charSelectDIV.appendChild(btn);

            // btn.addEventListener("click", function (){
            //     console.log("btn.addEventListener(\"click\"");
            //     selectCharClicked(details["charname"]);
            // });
        });
    }
    else if (typeof(incCharsList) == 'string' ) {
        let charSelectButtonsBar = document.getElementById("FFXIPackageHelper_equipsets_charactersButtonsList");
        charSelectButtonsBar.innerHTML = incCharsList;
    }


    //add event listeners to new items
    const charButtons = document.getElementsByClassName("FFXIPackageHelper_charButton");
    if ( charButtons ) addCharButtonEvents(charButtons);

    //selectCharClicked("", true);

    //Data.setHeaderCharacterDetails();
}

function characterSaved(results, resetBypass){
    //console.log("characterSaved", results);
    resetCharList(results);
    
    Data.updateStats();
    //scrollToTop();
    Data.setHeaderCharacterDetails();
}

function characterRemoved(results){
    //console.log("characterRemoved", results);
    resetCharList(results);

    Data.resetStats();
    //scrollToTop();
    Data.setHeaderCharacterDetails();
}

function clearCharList(){
    // var charSelectDIV = document.getElementById("FFXIPackageHelper_equipsets_charSelect");
    var charSelectDIV = document.getElementById("FFXIPackageHelper_equipsets_charactersButtonsList");

    
    const buttons = charSelectDIV.querySelectorAll('button');
    Array.from(buttons).forEach((button) => {
      if ( button.classList.contains("FFXIPackageHelper_charButton") && button.id != "FFXIPackageHelper_charButtonNone" )  {
        let new_element = button.cloneNode(true);
        button.parentNode.replaceChild(new_element, button);
        
        //console.log("clearCharList:", charSelectDIV, new_element);

        charSelectDIV.removeChild(new_element);
      }
    });
}


function resetCharSelection(){
    // toggleButtonVisibility(REMOVE_BUTTON);
    if ( manualModeSelected() == true ) return;

    const selectedChars = document.getElementsByClassName("FFXIPackageHelper_charButtonselected");
    Array.from(selectedChars).forEach((btn) => {
        btn.classList.toggle('FFXIPackageHelper_charButtonselected');
    });

    let manualMode = document.getElementById("FFXIPackageHelper_charButtonNone");
    manualMode.classList.toggle('FFXIPackageHelper_charButtonselected');
    ActionButtons.hideButton(REMOVE_BUTTON);
}

function scrollToTop() {
    // const top = document.getElementById("FFXIPackageHelper_characterHeader_name");
    // top.scrollIntoView({ behavior: "smooth", block: "start" });
    $("html, body").animate({ scrollTop: 0 }, "slow");
    return false;
}

function changeMeritValues(forInput, val){
    // < 0 - make it 0 and return
    if ( Number(forInput.value) + val < 0 ) {
        mw.notify( "Skills must remain > 0.", { autoHide: true,  type: 'error' } );
        return;
    }

    //Number(forInput.value) + val;

    /**
     * Stats and Attributes
     */
    const statID = "FFXIPackageHelper_equipsets_merits_stats";
    if ( forInput.id.includes(statID)){
        if ( val == -1 ) {
            forInput.value = Number(forInput.value) + val;
            return;
        }
        const stat = Number(forInput.id.replace(statID, ""));
        /**
         * HP and MP
         */
        if ( stat <= 5 ) {
            const statHP = Number(document.getElementById("FFXIPackageHelper_equipsets_merits_stats2").value);
            const statMP = Number(document.getElementById("FFXIPackageHelper_equipsets_merits_stats5").value);
            //console.log(statHP + statMP);
            if ( (statHP + statMP) > 7   ){
                    mw.notify( "Base stats already capped at 8.", { autoHide: true,  type: 'error' } );
                    return;
            }
            else {
                if ( (Number(forInput.value) + val) > 8 ) {
                    mw.notify( "This stat is capped at 8.", { autoHide: true,  type: 'error' } );
                    return;
                }
                else forInput.value = Number(forInput.value) + val;
            }
        }
        /**
         * Attributes (STR, AGI, etc)
         */
        else {
            let attributes_total = 0;
            const forInputID = Number(forInput.id.replace(statID, ""));
            const attrbutes = document.querySelectorAll('[id*=' + statID + ']');
            for( let s = 0; s < attrbutes.length; s++) {
                const id =  Number(attrbutes[s].id.replace(statID, ""));
                if ( id == 2 || id == 5 ) continue; //skip HP and MP because they have their own logic outlined above
                if ( Number(attrbutes[s].value) == 5 && forInputID == id ) {
                    mw.notify( "Attribute capped at 5.", { autoHide: true,  type: 'error' } );
                    return;
                }

                attributes_total += Number(attrbutes[s].value);
            }

            if ( attributes_total == 5 ){
                mw.notify( "Total attributes capped at 5.", { autoHide: true,  type: 'error' } );
                return;
            }
            // increment the attribute if all checks were good
            forInput.value = Number(forInput.value) + val;
        }
    }

    /**
     * skill in the array are numbered between 80 and 110
     *
     */
    const skillID = "FFXIPackageHelper_equipsets_merits_skill";
    if ( forInput.id.includes(skillID)){
        if ( val == -1 ) {
            forInput.value = Number(forInput.value) + val;
            return;
        }

        const skills = document.querySelectorAll('[id*=' + skillID + ']');
        const forInputID = Number(forInput.id.replace(skillID, ""));
        let combatSkills_total = 0;
        let defenseSkills_total = 0;
        let magicSkills_total = 0;

        /**
         * Combat Skills
         */
        if ( forInputID <= 110 ) {

            for( let s = 0; s < skills.length; s++) {
                const id =  Number(skills[s].id.replace(skillID, ""));
                if ( id > 110 ) continue;
                if ( id < 107 && Number(skills[s].value) == 8 && forInputID == id ){
                    mw.notify( "Offensive combat skill capped at 8.", { autoHide: true,  type: 'error' } );
                    return;
                }
                if ( id >= 107 ){
                    if ( Number(skills[s].value) == 4 && forInputID == id ){
                        mw.notify( "Defensive combat skill capped at 4.", { autoHide: true,  type: 'error' } );
                        return;
                    }
                    defenseSkills_total += Number(skills[s].value);
                }
                combatSkills_total += Number(skills[s].value);
            }

            if ( defenseSkills_total == 4 && forInputID >= 107  ) {
                mw.notify( "Total defensive skills already capped at 4 points.", { autoHide: true,  type: 'error' } );
                return;
            }

            if ( combatSkills_total == 12  ) {
                mw.notify( "Total combat skills already capped at 12 points.", { autoHide: true,  type: 'error' } );
                return;
            }
        }
        else {

            for( let s = 0; s < skills.length; s++) {
                const id =  Number(skills[s].id.replace(skillID, ""));
                if ( id <= 110 ) continue;

                if ( Number(skills[s].value) == 8 && forInputID == id ){
                    mw.notify( "Magic skill capped at 8.", { autoHide: true,  type: 'error' } );
                    return;
                }
                magicSkills_total += Number(skills[s].value);
            }

            if ( magicSkills_total == 8 ) {
                mw.notify( "Total magic skills already capped at 8 points.", { autoHide: true,  type: 'error' } );
                return;
            }
        }

        forInput.value = Number(forInput.value) + val;
    }

}

function getCharName(){
    //if ( manualModeSelected() ) return null;
    const characterButtons = document.querySelectorAll('button[id*=FFXIPackageHelper_charButton_]');
    for ( const button of characterButtons){
    //Array.from(characterButtons).forEach((button) => {

        if ( button.classList.contains('FFXIPackageHelper_charButtonselected') ) {
            //console.log('FFXIPackageHelper_charButtonselected', button.innerHTML);
            return button.innerHTML;
        }
        //});
    }

    return null;
}


function toggleSelected(button){
    //console.log("toggleSelected:", button);
    button.classList.toggle('FFXIPackageHelper_charButtonselected');
}

function showCharButtonSelected(button, selected){
    if ( selected == true ) button.classList.add('FFXIPackageHelper_charButtonselected');
    else button.classList.remove('FFXIPackageHelper_charButtonselected');
}

// function ActionButtons.hideButton(button){ button.style.visibility = "hidden"; }
// function ActionButtons.showButton(button) { button.style.visibility = "visible"; }

function toggleDefaultSwitch(){
    if ( DEFAULT_SWITCH.disabled == true )  DEFAULT_SWITCH.disabled = false;
    else DEFAULT_SWITCH.disabled = true;
}

function toggleRaceDropDown(){
    if ( RACE_DROPDOWN.disabled == true )  RACE_DROPDOWN.disabled = false;
    else RACE_DROPDOWN.disabled = true;
}

function toggleNewButton() {
    NEWCHAR_BUTTON.classList.toggle('FFXIPackageHelper_newCharButton_Grayed');
    const newchar_buttonText = document.getElementById("FFXIPackageHelper_newCharButton-text");
    if ( NEWCHAR_BUTTON.classList.contains('FFXIPackageHelper_newCharButton_Grayed')) {
        newchar_buttonText.innerText = "Cancel";
    }
    else newchar_buttonText.innerText = "New";

    //unhide character name block
    if ( hiddenDiv.style.display != "none" ) {
        //enable all char buttons
        setDisabledState_AllCharacterButtons(false);
        EDIT_BUTTON.disabled = false;
        if ( manualModeSelected() == true ) ActionButtons.hideButton(REMOVE_BUTTON);//hide remove button
        
        //ActionButtons.showButton(EDIT_BUTTON);//show edit button
        hiddenDiv.style.display = "none";

        ActionButtons.hideButton(SAVE_BUTTON);//hide save button

        //console.log(currentCharacterName);
        // selectCharClicked(currentCharacterName);
        // currentCharacterName = null;
    }
    else  {
        // const selectedChar = document.getElementsByClassName("FFXIPackageHelper_charButton FFXIPackageHelper_charButtonselected")[0].innerHTML;
        // if ( selectedChar ) currentCharacterName = selectedChar;
        //console.log(currentCharacterName);
        setNewCharDefaults();

        //disable all char buttons
        setDisabledState_AllCharacterButtons(true);
        EDIT_BUTTON.disabled = true;

        ActionButtons.hideButton(REMOVE_BUTTON);//hide remove button
        //ActionButtons.hideButton(EDIT_BUTTON);//hide edit button
        hiddenDiv.style.display = "block";

        ActionButtons.showButton(SAVE_BUTTON);//show save button
        DEFAULT_SWITCH.checked = false; //set default switch to off
    }

    toggleDefaultSwitch();//toggle default switch to enabled/disabled
    toggleRaceDropDown();
    toggleMeritEditButtons(counterbox);    //unlock editing for all merits

}

function setNewCharDefaults(){
    document.getElementById("FFXIPackageHelper_dynamiccontent_charNameInput").value = "";
    Data.resetMeritsToDefault();
}

function setDisabledState_AllCharacterButtons(state){
    const characterDIV = document.getElementById("FFXIPackageHelper_equipsets_charSelect");
    const characterButtons = characterDIV.querySelectorAll('button[id*=FFXIPackageHelper_charButton_]');
    //console.log(characterButtons);
    for ( const button of characterButtons ){
        button.disabled = state;
    }

    let manualMode = document.getElementById("FFXIPackageHelper_charButtonNone");
    manualMode.disabled = state;

    //EDIT_BUTTON.disabled = state;
}

function manualModeSelected(clickedButton){
    if ( clickedButton ) {
        if ( clickedButton.id == "FFXIPackageHelper_charButtonNone" ) return true;
        else return false;
    }

    let manualMode = document.getElementById("FFXIPackageHelper_charButtonNone");
    //console.log("manualModeSelected", manualMode.classList);
    if ( manualMode.classList.contains("FFXIPackageHelper_charButtonselected") ) return true;
    else return false;
}





