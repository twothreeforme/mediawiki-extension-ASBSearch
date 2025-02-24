var API = require("./FFXIPackageHelper_ActionAPI.js");
var Data = require("./FFXIPackageHelper_DataManager.js");
var ModalWindow = require("./FFXIPackageHelper_ModalWindow.js");
//var ModalSetManagement = require("./FFXIPackageHelper_ModalSetManagement.js");
//var ModalCharManagement = require("./FFXIPackageHelper_ModalCharManagement.js");

var Tooltip = require("./FFXIPackageHelper_Tooltips.js");


var raceDropdown = null;
var mJobDropdown = null;
var sJobDropdown = null;
var mlvlDropdown = null;
var slvlDropdown = null;

module.exports.setLinks = function (){

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
        shareQueryClicked("FFXIPackageHelper_dynamiccontent_shareEquipset", getStatsData(true));
    });

     // Load Merit Edits section
    // MeritEdits.setLinks(Data.updateStats);
    
    /**
     * DEV ONLY
     */
    mlvlDropdown.value=75;
    slvlDropdown.value=37;
    mJobDropdown.value=7;
    sJobDropdown.value=4;
    raceDropdown.value=3;

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

// function selectCharClicked(){
//     const data = {
//         action: "equipsets_selectchar",
//         charname: document.getElementById("FFXIPackageHelper_equipsets_selectUserChar").value,
//     }

//     API.actionAPI(data, data.action, null, Data.updateStats);
// }

// function saveCharacterClicked(charName){
//     //console.log(charName);

//     const data = {
//         action: "equipsets_savechar",
//         race: document.getElementById("FFXIPackageHelper_equipsets_selectRace").value,
//         merits: encodeURIComponent(btoa(Data.getMeritsData())),
//         charname: charName
//     }

//     API.actionAPI(data, data.action, null, this);
// }

// function removeCharacter(charname){
//     //console.log("should remove " + charname);

//     const data = {
//         action: "equipsets_removechar",
//         charname: charname
//     }

//     API.actionAPI(data, data.action, null, this);
//     resetStats();
// }

// function resetStats(){
//     document.getElementById("FFXIPackageHelper_equipsets_selectUserChar").value = 0;
//     document.getElementById("FFXIPackageHelper_equipsets_selectRace").value = 0;

//     const removeButton = document.getElementById("FFXIPackageHelper_dynamiccontent_removeCharacter");
//     removeButton.style.display = "none";

//     const _id = "FFXIPackageHelper_equipsets_merits_";
//     const allMerits = document.querySelectorAll("[id*='" + _id + "']");
//     //console.log(allMerits);
//     var meritsArray = [...allMerits];
//     meritsArray.forEach(merit => {
//         merit.value = 0;
//     });

//     Data.updateStats();
// }
