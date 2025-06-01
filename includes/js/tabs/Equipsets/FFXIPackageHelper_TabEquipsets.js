var API = require("./FFXIPackageHelper_ActionAPI.js");
var Data = require("./FFXIPackageHelper_DataManager.js");
var ModalWindow = require("./FFXIPackageHelper_ModalWindow.js");
var ActionButtons = require("./FFXIPackageHelper_ActionButtons.js");

var ModalSetManagement = require("./FFXIPackageHelper_ModalSetManagement.js");
//var ModalCharManagement = require("./FFXIPackageHelper_ModalCharManagement.js");

var Tooltip = require("./FFXIPackageHelper_Tooltips.js");

const NEWSET_BUTTON = document.getElementById("FFXIPackageHelper_newSetButton");
const SAVE_BUTTON = document.getElementById("FFXIPackageHelper_dynamiccontent_saveSet");
// const REMOVE_BUTTON = document.getElementById("FFXIPackageHelper_deleteSetButton");
//const //SELECTSET_DROPDOWN = document.getElementById("FFXIPackageHelper_equipsets_selectSet");

const hiddenDiv = document.getElementById("FFXIPackageHelper_dynamiccontent_newSetSection");
hiddenDiv.offsetTop;

var raceDropdown = null;
var mJobDropdown = null;
var sJobDropdown = null;
var mlvlDropdown = null;
var slvlDropdown = null;

// let currentSetName = null;
let setsModal = null;

module.exports.setLinks = function (){
    cleanAllTables();
    // const setButtons = document.getElementsByClassName("FFXIPackageHelper_setButton");
    // if ( setButtons ) { addSetButtonEvents(setButtons); }

    NEWSET_BUTTON.addEventListener("click", function () {
        if ( mJobDropdown.value == 0  || sJobDropdown.value == 0){
            mw.notify( "Main job and Sub job must be selected to save a set.", { autoHide: true,  type: 'error' } );
            return;
        }

        let inputElement = document.getElementById('FFXIPackageHelper_dynamiccontent_setNameInput');
        inputElement.value = '';

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

    // const setListItems = document.getElementById("FFXIPackageHelper_Equipsets_setManagement_setsListTable").querySelectorAll("td");
    // if ( setListItems.length > 0 ){
    //     // setListItems.forEach(node => {
    //     //     node.addEventListener('click', () => {
    //     //         selectSetClicked(node.dataset.value)
    //     //     });
    //     //     //console.log(node.dataset.value);
    //     // });
    //     //console.log(setListItems);
    //     addSetButtonEvents(setListItems);
    //     setsModal = new ModalSetManagement({ removeCallback: API.actionAPI, returnCallback: setRemoved });
    // }
    addEventListersToSetsTable();

    // const setsListRemoves = document.getElementsByClassName("FFXIPackageHelper_Equipsets_setManagement_setsListTable_Remove");
    // if ( setsListRemoves.length > 0 ){
    //     for (r = 0; r < setsListRemoves.length; r++) {
    //         setsListRemoves[r].addEventListener("click", function (e) {
    //             //console.log(e.target.dataset.value);
    //             const rowDetails = e.target.parentNode.querySelector("td");
    //             const userSetID = rowDetails.dataset.value;
    //             const setName = rowDetails.innerText;
    //             //console.log(userSetID, setName);
    //         });
    //     }
    // }

    /**
     * Modal Windows
     * All equip slots
     */
    for (let v = 0; v <= 15; v++) {
        let modal = new ModalWindow(v, { searchCallback: API.actionAPI, returnCallback: Data.updateEquipmentGrid});

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

    let shareEquipset = document.getElementById("FFXIPackageHelper_dynamiccontent_shareEquipset");
    shareEquipset.addEventListener("click", function (e) {
        shareQueryClicked("FFXIPackageHelper_dynamiccontent_shareEquipset", Data.getStatsData(true));
    });

    let shareDiscordEquipset = document.getElementById("FFXIPackageHelper_dynamiccontent_shareDiscordEquipset");
    shareDiscordEquipset.addEventListener("click", function (e) {
        shareQueryClicked("FFXIPackageHelper_dynamiccontent_shareDiscordEquipset", Data.getStatsData(true));
    });
    

     // Load Merit Edits section
    // MeritEdits.setLinks(Data.updateStats);
    
    const menuIcon = document.getElementById("FFXIPackageHelper_menuIcon");
    menuIcon.addEventListener("click", function (e) {
        adjustMenuIconButtonCSS(this);
    });


    /**
     * DEV ONLY
     */
    // mlvlDropdown.value=75;
    // slvlDropdown.value=37;
    //mJobDropdown.value=1;
    //sJobDropdown.value=2;
    // raceDropdown.value=3;


    Data.getMeritsData();
    Tooltip.setupPageTooltips();
}

function addSetButtonEvents(setListItems){
    setListItems.forEach(node => {
        //console.log(node.classList);
        node.addEventListener('click', () => {
            if ( node.classList.length < 1 )  selectSetClicked(node.dataset.value);
            else if (node.classList.contains("FFXIPackageHelper_Equipsets_setManagement_setsListTable_Remove") ) {
                let setInRow = node.parentNode.querySelectorAll('td');
                if ( setInRow.length > 0 ){
                    //console.log(setInRow[0].dataset.value,setInRow[0].innerText );
                    removeSetClicked(setInRow[0].dataset.value, setInRow[0].innerText);
                }
                else console.log("no data for set in row");
            }
        });
        //console.log(node.dataset.value);
    });
}

function selectSetClicked(usersetid){
    if ( usersetid == null ) return;

    const data = Data.getCharData();
    data.action = "equipsets_selectset",
    data.usersetid = usersetid;

    API.actionAPI(data, data.action, null, Data);
}

function removeSetClicked(setID, setName){
    setsModal.open(setID, setName);
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

    toggleNewButton();
}

function setSaved(results){
    resetSetList(results);
}

function setRemoved(results){
    resetSetList(results);
}


function resetSetList(results){

    clearSetList();
    if (results){
        //console.log(results);
        buildSetslist(results);
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
    if ( Array.isArray(results) ) {

    }
    else {
        const tableDiv = document.getElementById("FFXIPackageHelper_Equipsets_setManagement_setsList");
        const tableElement = document.getElementById("FFXIPackageHelper_Equipsets_setManagement_setsListTable");
        if ( tableElement ) tableElement.remove();
        tableDiv.innerHTML = results;
    }
    addEventListersToSetsTable();
}

function clearSetList(){

}


function shareQueryClicked(shareID, params) {
    let GETparams = "";
    GETparams = "&race=" + params['race'] +
            "&mlvl=" + params['mlvl'] +
            "&slvl=" + params['slvl'] +
            "&mjob=" + params['mjob'] +
            "&sjob=" + params['sjob'] +
            "&merits=" + params['merits'] +
            "&equipment=" + params['equipment'];

    //const encodedParams = encodeURIComponent(GETparams);
    let wgServer = mw.config.get( 'wgServer' );
    let wgScriptPath = mw.config.get( 'wgScriptPath' );
    let url = wgServer +  wgScriptPath + "/index.php?title=Special:Equipsets" + GETparams;

    //console.log(url);

    if ( shareID == "FFXIPackageHelper_dynamiccontent_shareDiscordEquipset" ){
        let mjob = document.getElementById("FFXIPackageHelper_equipsets_selectMJob");

        mjob = mjob.options[mjob.selectedIndex].text;
        let sjob = document.getElementById("FFXIPackageHelper_equipsets_selectSJob");
        sjob = sjob.options[sjob.selectedIndex].text;
        url = `[ ${mjob}/${sjob} - Wiki Equipset](` + url + `)`;
        //console.log(url);
    }

    navigator.clipboard.writeText(url).then(function() {
        //console.log('copyURLToClipboard(): Copied!');
        mw.notify( 'Copied to Clipboard !', { autoHide: true,  type: 'warn' } );
    }, function() {
      mw.notify( 'Error copying to clipboard. Please report on our Discord.', { autoHide: true,  type: 'error' } );
      //console.log('Clipboard error');
    });
};



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
    }
}

function adjustMenuIconButtonCSS(i) {
    i.classList.toggle("FFXIPackageHelper_menuIcon_change");
    const availableSets = document.getElementById("FFXIPackageHelper_Equipsets_setManagement");
    availableSets.classList.toggle("FFXIPackageHelper_Equipsets_setManagement_expanded");    
  }

function addEventListersToSetsTable(){
    const setListTable = document.getElementById("FFXIPackageHelper_Equipsets_setManagement_setsListTable");
    if ( setListTable ) {
        const setListItems = setListTable.querySelectorAll("td");

        if ( setListItems.length > 0 ){
            // setListItems.forEach(node => {
            //     node.addEventListener('click', () => {
            //         selectSetClicked(node.dataset.value)
            //     });
            //     //console.log(node.dataset.value);
            // });
            //console.log(setListItems);
            addSetButtonEvents(setListItems);
            setsModal = new ModalSetManagement({ removeCallback: API.actionAPI, returnCallback: setRemoved });
        }
    }
}

function cleanAllTables(){
    let allDivsWithTables = document.getElementsByClassName("content-table-wrapper overflowed scroll-right");
    if ( allDivsWithTables.length > 0 ){
        for ( const div of allDivsWithTables ){
            div.classList.toggle("overflowed");
            div.classList.toggle("scroll-right");
        }
    }
    //console.log(allDivsWithTables) ;
}
