var API = require("./FFXIPackageHelper_ActionAPI.js");
var Data = require("./FFXIPackageHelper_DataManager.js");
var ModalWindow = require("./FFXIPackageHelper_ModalWindow.js");
var ActionButtons = require("./FFXIPackageHelper_ActionButtons.js");

//var ModalSetManagement = require("./FFXIPackageHelper_ModalSetManagement.js");
//var ModalCharManagement = require("./FFXIPackageHelper_ModalCharManagement.js");

var Tooltip = require("./FFXIPackageHelper_Tooltips.js");

const NEWSET_BUTTON = document.getElementById("FFXIPackageHelper_newSetButton");
const SAVE_BUTTON = document.getElementById("FFXIPackageHelper_dynamiccontent_saveSet");
const REMOVE_BUTTON = document.getElementById("FFXIPackageHelper_deleteSetButton");
//const //SELECTSET_DROPDOWN = document.getElementById("FFXIPackageHelper_equipsets_selectSet");

const hiddenDiv = document.getElementById("FFXIPackageHelper_dynamiccontent_newSetSection");
hiddenDiv.offsetTop;

var raceDropdown = null;
var mJobDropdown = null;
var sJobDropdown = null;
var mlvlDropdown = null;
var slvlDropdown = null;

let currentSetName = null;

module.exports.setLinks = function (){

    // const setButtons = document.getElementsByClassName("FFXIPackageHelper_setButton");
    // if ( setButtons ) { addSetButtonEvents(setButtons); }

    NEWSET_BUTTON.addEventListener("click", function () {
        if ( mJobDropdown.value == 0  || sJobDropdown.value == 0){
            mw.notify( "Main job and Sub job must be selected to save a set.", { autoHide: true,  type: 'error' } );
            return;
        }

        // if ( hiddenDiv.style.display != "none" ) {
        //     if ( currentSetName != null ) selectSetClicked(currentSetName);
        // }
        // else  {
        //     const selectedSet = document.getElementsByClassName("FFXIPackageHelper_setButton FFXIPackageHelper_setButtonselected");
        //     if ( selectedSet.length > 0 ) currentSetName = selectedSet[0].innerHTML;
        // }

        //toggle New button
        toggleNewButton();
    });

    SAVE_BUTTON.addEventListener('click', (e) =>  {
        saveSetClicked();
    });

    // //SELECTSET_DROPDOWN.addEventListener('change', (e) =>  {
    //     const selectedSet = //SELECTSET_DROPDOWN.options[//SELECTSET_DROPDOWN.selectedIndex];
    //     //console.log('selected:', selectedSet.value);
    //     fetchSet(selectedSet.value);
    // });

    /**
     * Set up available sets list events
     */

    let setListItems = document.getElementById("FFXIPackageHelper_Equipsets_setManagement_setsList").querySelectorAll("li");
    if ( setListItems.length > 0 ){
        // setListItems.forEach(node => {
        //     node.addEventListener('click', () => {
        //         selectSetClicked(node.dataset.value)
        //     });
        //     //console.log(node.dataset.value);
        // });
        addSetButtonEvents(setListItems);
    }
    //.getElementsByTagName('li');


    /**
     * Modal Windows
     * All equip slots
     */
    for (let v = 0; v <= 15; v++) {
        const modal = new ModalWindow(v, { searchCallback: API.actionAPI, returnCallback: Data.updateEquipmentGrid});

        let str = "grid" + v;
        let slot = document.getElementById(str);

        slot.addEventListener("click", function (e) {
            modal.open(Data.getEquipID(v));
        });
    }

    /**
     * Level range elements for both maind and sub jobs
     */
    slvlDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectSLevel");
    slvlDropdown.addEventListener("change", (e) =>  {
        //console.log(e.target.value);
        sJobMaxCheckbox.checked = 0;
        Data.updateStats();
    });

    mlvlDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectMLevel");
    mlvlDropdown.addEventListener("change", (e) =>  {
        //console.log(e.target.value);
        if ( document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxMaxSub").checked == 1 ){
            slvlDropdown.value = (e.target.value > 1) ? Math.floor(e.target.value / 2) : 1;
        }
        Data.updateStats();
    });

    raceDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectRace");
    // raceDropdown.addEventListener("change", (e) =>  {
    //     //console.log(e.target.value);
    //     Data.updateStats();
    //     Data.setHeaderCharacterDetails();
    // });

    /**
     * Main and Sub job elements
     */
    mJobDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectMJob");
    mJobDropdown.addEventListener("change", (e) => {
        //console.log(e.target.value);
        Data.updateStats();
        resetSetList();
    });

    sJobDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectSJob");
    sJobDropdown.addEventListener("change", (e) => {
        //console.log(e.target.value);
        Data.updateStats();
    });

    sJobMaxCheckbox = document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxMaxSub");
    sJobMaxCheckbox.addEventListener("change", (e) => {
        if ( e.target.checked == 1 ){
            slvlDropdown.value = (mlvlDropdown.value > 1) ? Math.floor(mlvlDropdown.value / 2) : 1;
        }
        Data.updateStats();
    });

    const shareEquipset = document.getElementById("FFXIPackageHelper_dynamiccontent_shareEquipset");
    shareEquipset.addEventListener("click", function (e) {
        shareQueryClicked("FFXIPackageHelper_dynamiccontent_shareEquipset", Data.getStatsData(true));
    });

     // Load Merit Edits section
    // MeritEdits.setLinks(Data.updateStats);
    
    const menuIcon = document.getElementById("FFXIPackageHelper_menuIcon");
    menuIcon.addEventListener("click", function (e) {
        adjustMenuIconButtonCSS(this);
    });

    const setsListRemoves = document.getElementsByClassName("FFXIPackageHelper_Equipsets_setManagement_setsListTable_Remove");
    if ( setsListRemoves.length > 0 ){
        for (r = 0; r < setsListRemoves.length; r++) {
            setsListRemoves[r].addEventListener("click", function (e) {
                //console.log(e.target.dataset.value);
                const rowDetails = e.target.parentNode.querySelector("td");
                const userSetID = rowDetails.dataset.value;
                const setName = rowDetails.innerText;

                console.log(userSetID, setName);

            });
        }
    }


    /**
     * DEV ONLY
     */
    mlvlDropdown.value=75;
    slvlDropdown.value=37;
    //mJobDropdown.value=1;
    //sJobDropdown.value=2;
    // raceDropdown.value=3;

    /**
     * On page load
     */
    const url = window.location.href;
    if ( url.includes("action=equipsets_share")) {
        loadSharedLink(url);
        //console.log("fired");
    }
    else Data.updateStats();

    Data.getMeritsData();
    Tooltip.setupPageTooltips();
}

function addSetButtonEvents(setListItems){
    setListItems.forEach(node => {
        node.addEventListener('click', () => {
            selectSetClicked(node.dataset.value)
        });
        //console.log(node.dataset.value);
    });
}

function selectSetClicked(usersetid){
    if ( usersetid == null ) return;

    const data = {
        action: "equipsets_selectset",
        usersetid: usersetid,
    }

    ActionButtons.showButton(REMOVE_BUTTON);
    // ActionButtons.showButton(EDIT_BUTTON);

    // const selectedSetButton = document.getElementById('FFXIPackageHelper_setButton_' + setname);
    // if ( selectedSetButton ) showSetButtonSelected(selectedSetButton, true);

    API.actionAPI(data, data.action, null, Data);
    //scrollToTop();
}


function saveSetClicked(){
    const data = Data.getSetData();
    //console.log(data);
    if ( data.setname.length == 0 ){
        mw.notify( "Set name must be filled.", { autoHide: true,  type: 'error' } );
        return;
    }

    data.action = "equipsets_saveset";
    API.actionAPI(data, data.action, null, setSaved);
    // //scrollToTop();

    // // make New button green again
    toggleNewButton();

    // //hide Save button
    // ActionButtons.hideButton(SAVE_BUTTON);
}

function setSaved(results){
    //results[0] = set name that was saved
    //results[1] = the new set list

    //const recentSave = results[0];

    //const newSetList = results;
    //resetSetList(newSetList);

    // const setButton = document.getElementById('FFXIPackageHelper_setButton_' + recentSave);
    // setButton.classList.toggle('FFXIPackageHelper_setButtonselected');

    //toggleButtonVisibility(REMOVE_BUTTON);
    //Data.updateStats();
    //scrollToTop();
    //Data.setHeaderCharacterDetails();
    resetSetList(results);
}

function resetSetList(results){

    clearSetList();
    if (results){
        currentSetName = results[0];
        buildSetslist(results[1]);
        ////SELECTSET_DROPDOWN.value = currentSetName;
    }
    else {
        const data = {
            action: "equipsets_getsets",
            mjob:document.getElementById("FFXIPackageHelper_equipsets_selectMJob").value,
            };

        API.actionAPI(data, data.action, null, buildSetslist);
    }
}

function buildSetslist(results){
    //console.log(results);

    if (results.length <= 0 ){
        //SELECTSET_DROPDOWN.disabled = true;

        const optgroupElement = document.createElement("optgroup");
        optgroupElement.label = "None";
        //SELECTSET_DROPDOWN.appendChild(optgroupElement);
        // const firstOption = document.createElement('option');
        // firstOption.value = 0;
        // firstOption.text = "None";
        // //SELECTSET_DROPDOWN.appendChild(firstOption);

        //SELECTSET_DROPDOWN.selectedIndex = 0;
        return;
    }

    //console.log(results instanceof Array);

    if ( results instanceof Array ){
        //SELECTSET_DROPDOWN.disabled = false;

        results.forEach((r)=> {
            const optionElement = document.createElement('option');
            optionElement.value = r.usersetid;
            optionElement.text = r.setname;
            //SELECTSET_DROPDOWN.appendChild(optionElement);
        });
    }
    else {
        //SELECTSET_DROPDOWN.disabled = false;
        for (const obj of Object.entries(results)) {
            //console.log(`${key}: ${value}`);

            const optgroupElement = document.createElement("optgroup");
            optgroupElement.label = obj[0];

            Object.values(obj[1]).forEach(value => {
                //console.log(value.setname);
                const option = document.createElement("option");
                option.value = value.usersetid;
                option.text = value.setname;
                optgroupElement.appendChild(option);
            });

            //SELECTSET_DROPDOWN.appendChild(optgroupElement);

          }
    }



}

function clearSetList(){
    /*
    var setSelectDIV = document.getElementById("FFXIPackageHelper_equipsets_setSelect");
    const buttons = setSelectDIV.querySelectorAll('button');
    Array.from(buttons).forEach((button) => {
      if ( button.classList.contains("FFXIPackageHelper_setButton") )  {
        let new_element = button.cloneNode(true);
        button.parentNode.replaceChild(new_element, button);
        setSelectDIV.removeChild(new_element);
      }
    });
    */

    // var i, L = SELECTSET_DROPDOWN.options.length - 1;
    // for(i = L; i >= 0; i--) {
    //     //SELECTSET_DROPDOWN.remove(i);
    // }

    // while (SELECTSET_DROPDOWN.querySelector('optgroup')) {
    //     //SELECTSET_DROPDOWN.removeChild(//SELECTSET_DROPDOWN.querySelector('optgroup'));
    // }

   ////SELECTSET_DROPDOWN.children().remove();

}

function fetchSet(usersetid){
    const data = {
        action:"equipsets_selectset",
        usersetid:usersetid
    };
    API.actionAPI(data, data.action, null, Data);
}

function shareQueryClicked(shareID, params) {
    var GETparams = "";
    if ( shareID == "FFXIPackageHelper_dynamiccontent_shareEquipset" ){
        GETparams = "&race=" + params['race'] +
                    "&mlvl=" + params['mlvl'] +
                    "&slvl=" + params['slvl'] +
                    "&mjob=" + params['mjob'] +
                    "&sjob=" + params['sjob'] +
                    "&merits=" + params['merits'] +
                    "&equipment=" + params['equipment'];
    }
    else {
        mw.notify( 'Your query is not complete. Please complete and try again.', { autoHide: true,  type: 'error' } );
        return;
    }
    //const encodedParams = encodeURIComponent(GETparams);
    var url = window.location.href.split('?')[0] + "?action=equipsets_share" + GETparams;
    //console.log(url);
    //return;

    navigator.clipboard.writeText(url).then(function() {
        //console.log('copyURLToClipboard(): Copied!');
        mw.notify( 'Copied to Clipboard !', { autoHide: true,  type: 'warn' } );
    }, function() {
      mw.notify( 'Error copying to clipboard. Please report on our Discord.', { autoHide: true,  type: 'error' } );
      //console.log('Clipboard error');
    });
};

function loadSharedLink(url){
    let paramString = url.split('?')[1];
    let params_arr = paramString.split('&');
    var race, mlvl, slvl, mjob, sjob, merits, equipment;

    for(let i = 0; i < params_arr.length; i++) {
        let pair = params_arr[i].split('=');
        // console.log("Key is:" + pair[0]);
        // console.log("Value is:" + pair[1]);
        switch(pair[0]){
            case "race":
                race = pair[1];
                break;
            case "mlvl":
                mlvl = pair[1];
                break;
            case "slvl":
                slvl = pair[1];
                break;
            case "mjob":
                mjob = pair[1];
                break;
            case "sjob":
                sjob = pair[1];
                break;
            case "equipment":
                equipment = pair[1];
                break;
            case "merits":
                merits = pair[1];
                break;
        }
    }

    const data = {
        action: "equipsets_change",
        race:race,
        mlvl:mlvl,
        slvl:slvl,
        mjob:mjob,
        sjob:sjob,
        merits: merits,
        equipment: equipment,
    }

    API.actionAPI(data, data.action, null, this);

    mlvlDropdown.value=data.mlvl;
    slvlDropdown.value=data.slvl;
    mJobDropdown.value=data.mjob;
    sJobDropdown.value=data.sjob;
    raceDropdown.value=data.race;

    //encodeURIComponent(btoa(Data.getMeritsData())),
    merits = atob(decodeURIComponent(data.merits));
    const meritObj = JSON.parse(merits);

    // Set merit stats in table
    Object.keys(meritObj[0]).forEach(key => {
            let _id = "FFXIPackageHelper_equipsets_merits_stats"
            const e = document.getElementById(_id + key);
            e.value = meritObj[0][key];
      });

    // Set merit skills in table
    Object.keys(meritObj[1]).forEach(key => {
            let _id = "FFXIPackageHelper_equipsets_merits_skill"
            const e = document.getElementById(_id + key);
            e.value = meritObj[1][key];
    });

    const tabsButton_equipsets = document.getElementById("FFXIPackageHelper_tabs_equipsets");
    tabsButton_equipsets.click();
}

function showSetButtonSelected(button, selected){
    if ( selected == true ) button.classList.add('FFXIPackageHelper_setButtonselected');
    else button.classList.remove('FFXIPackageHelper_setButtonselected');
}

function toggleNewButton() {

    NEWSET_BUTTON.classList.toggle('FFXIPackageHelper_newSetButton_Grayed');
    const newset_buttonText = document.getElementById("FFXIPackageHelper_newSetButton-text");
    if ( NEWSET_BUTTON.classList.contains('FFXIPackageHelper_newSetButton_Grayed')) {
        newset_buttonText.innerText = "Cancel";
    }
    else newset_buttonText.innerText = "Save this set";

    if ( hiddenDiv.style.display != "none" ) {
        hiddenDiv.style.display = "none";
        //ActionButtons.showButton(REMOVE_BUTTON);//show remove button
        
        ////SELECTSET_DROPDOWN.disabled = false;
        mJobDropdown.disabled = false;
        sJobDropdown.disabled = false;
        mlvlDropdown.disabled = false;
        slvlDropdown.disabled = false;

        //setDisabledState_AllSavedSetButtons(false);
        ActionButtons.hideButton(SAVE_BUTTON);//show save button
    }
    else  {
        //setDisabledState_AllSavedSetButtons(true);
        //ActionButtons.hideButton(REMOVE_BUTTON);//show remove button

        ////SELECTSET_DROPDOWN.disabled = true;
        mJobDropdown.disabled = true;
        sJobDropdown.disabled = true;
        mlvlDropdown.disabled = true;
        slvlDropdown.disabled = true;

        hiddenDiv.style.display = "inline-block";
        ActionButtons.showButton(SAVE_BUTTON);//show save button
    }
}

function setDisabledState_AllSavedSetButtons(state){
    const setsDIV = document.getElementById("FFXIPackageHelper_equipsets_setSelect");
    const setButtons = setsDIV.querySelectorAll('button[id*=FFXIPackageHelper_setButton_]');
    //console.log(setButtons);
    for ( const button of setButtons ){
        button.disabled = state;

        // if ( state == true ) {
        //     hideButton(REMOVE_BUTTON);//hide remove button
        //     hideButton(EDIT_BUTTON);//hide edit button
        // }
        // else {
        //     ActionButtons.showButton(REMOVE_BUTTON);//show remove button
        //     ActionButtons.showButton(EDIT_BUTTON);//show edit button
        // }
    }
}

function adjustMenuIconButtonCSS(i) {
    i.classList.toggle("FFXIPackageHelper_menuIcon_change");
    const availableSets = document.getElementById("FFXIPackageHelper_Equipsets_setManagement");
    availableSets.classList.toggle("FFXIPackageHelper_Equipsets_setManagement_expanded");    
  }
