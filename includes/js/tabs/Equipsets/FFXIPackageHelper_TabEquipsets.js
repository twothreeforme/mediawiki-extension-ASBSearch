var API = require("./FFXIPackageHelper_ActionAPI.js");
var ModalWindow = require("./FFXIPackageHelper_ModalWindow.js");

var raceDropdown = null;
var mJobDropdown = null;
var sJobDropdown = null;
var mlvlDropdown = null;
var slvlDropdown = null;

function updateEquipmentGrid(id, slot, sender){
    //console.log("clicked: " + id + ", " + slot);
    const equipment = getEquipIDs();
    equipment[slot] = [ id, 1 ]; // updated equip flagged with 1 to trigger update

    //console.log(ids);
    var all = getStatsData(equipment);
    all.action = "equipsets_change";

    API.actionAPI(all, "equipsets_change", null, null);
    // close modal window
    if( sender !== null) sender.close();
}

function getEquipID(forSlot){
    let slot = document.getElementById("grid" + forSlot);
    return slot.dataset.value;
}

function updateStats(data){
    if ( data == null ) data = getStatsData();
    //console.log(getStatData());
    API.actionAPI(data, "equipsets", null);
}

function getEquipIDs(updateAll){
    let equipIDs = [];
    //const flag = ( updateSpecificItem != null) ? 1 : 0;
    let shareEquipIDs = "";
    for (let v = 0; v <= 15; v++) {
        let str = "grid" + v;
        let slot = document.getElementById(str);
        if ( updateAll == true ) {
            if ( slot.dataset.value != 0) shareEquipIDs += slot.dataset.value + ",1|";
            else shareEquipIDs += "0,0|";
        }
        else  equipIDs[v] = [ slot.dataset.value, 0 ]; // 0 is default flag id
    }
    if ( updateAll == true ) {
        shareEquipIDs = shareEquipIDs.slice(0, -1);
        return shareEquipIDs;
    }
    else return equipIDs;
}

function getStatsData(equipIDString){
    if ( equipIDString == null ) equipIDString = getEquipIDs(); //getEquipIDs().join(",");
    else if ( equipIDString == true ) {
        equipIDString = getEquipIDs(true);
        //console.log(equipIDString);
    }

    //if ( updateSpecificItem != null) equipIDString =  getEquipIDs(updateSpecificItem).join(",");
    //console.log("getStatsData: " + equipIDString);
    return {
        action: "equipsets",
        race:document.getElementById("FFXIPackageHelper_equipsets_selectRace").value,
        mlvl:document.getElementById("FFXIPackageHelper_equipsets_selectMLevel").value,
        slvl:document.getElementById("FFXIPackageHelper_equipsets_selectSLevel").value,
        mjob:document.getElementById("FFXIPackageHelper_equipsets_selectMJob").value,
        sjob:document.getElementById("FFXIPackageHelper_equipsets_selectSJob").value,
        equipment: btoa(equipIDString),
    };
  }


module.exports.setLinks = function (){
    for (let v = 0; v <= 15; v++) {
        const modal = new ModalWindow(v, { searchCallback: API.actionAPI, returnCallback: updateEquipmentGrid});

        let str = "grid" + v;
        let slot = document.getElementById(str);

        //console.log(v);
        slot.addEventListener("click", function (e) {
            modal.open(getEquipID(v));
            // DEV ONLY
            //updateStats();
        });
    }

    /**
     * Level range elements for both maind and sub jobs
     */
    slvlDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectSLevel");
    slvlDropdown.addEventListener("change", (e) =>  {
        //console.log(e.target.value);
        sJobMaxCheckbox.checked = 0;
        updateStats();
    });

    mlvlDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectMLevel");
    mlvlDropdown.addEventListener("change", (e) =>  {
        //console.log(e.target.value);
        if ( document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxMaxSub").checked == 1 ){
            slvlDropdown.value = (e.target.value > 1) ? Math.floor(e.target.value / 2) : 1;
        }
        updateStats();
    });

    raceDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectRace");
    raceDropdown.addEventListener("change", (e) =>  {
        //console.log(e.target.value);
        updateStats();
    });

    /**
     * Main and Sub job elements
     */
    mJobDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectMJob");
    mJobDropdown.addEventListener("change", (e) => {
        //console.log(e.target.value);
        updateStats();
    });

    sJobDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectSJob");
    sJobDropdown.addEventListener("change", (e) => {
        //console.log(e.target.value);
        updateStats();
    });

    sJobMaxCheckbox = document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxMaxSub");
    sJobMaxCheckbox.addEventListener("change", (e) => {
        if ( e.target.checked == 1 ){
            slvlDropdown.value = (mlvlDropdown.value > 1) ? Math.floor(mlvlDropdown.value / 2) : 1;
        }
        updateStats();
    });

    const shareEquipset = document.getElementById("FFXIPackageHelper_dynamiccontent_shareEquipset");
    shareEquipset.addEventListener("click", function (e) {
        shareQueryClicked("FFXIPackageHelper_dynamiccontent_shareEquipset", getStatsData(true));
    });

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
        console.log("fired");
    }
    else updateStats();

    //console.log(window.location.href);
    //updateEquipmentGrid(18270, 0, null);
}

function shareQueryClicked(shareID, params) {
    var GETparams = "";
    if ( shareID == "FFXIPackageHelper_dynamiccontent_shareEquipset" ){
        GETparams = "&race=" + params['race'] +
                    "&mlvl=" + params['mlvl'] +
                    "&slvl=" + params['slvl'] +
                    "&mjob=" + params['mjob'] +
                    "&sjob=" + params['sjob'] +
                    "&equipment=" + btoa(params['equipment']);
    }
    else {
    //   mw.notify( 'Your query is not complete. Please complete and try again.', { autoHide: true,  type: 'error' } );
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
    var race, mlvl, slvl, mjob, sjob, equipment;

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
        }
    }

    const data = {
        action: "equipsets_change",
        race:race,
        mlvl:mlvl,
        slvl:slvl,
        mjob:mjob,
        sjob:sjob,
        equipment: equipment,
    }
    //console.log(url, data);
    API.actionAPI(data, data.action, null, this);

    const tabsButton_equipsets = document.getElementById("FFXIPackageHelper_tabs_equipsets");
    tabsButton_equipsets.click();
}
