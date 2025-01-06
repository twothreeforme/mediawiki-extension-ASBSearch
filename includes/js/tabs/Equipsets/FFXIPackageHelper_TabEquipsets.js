var API = require("./FFXIPackageHelper_ActionAPI.js");
var ModalWindow = require("./FFXIPackageHelper_ModalWindow.js");

var raceDropdown = null;
var mJobDropdown = null;
var sJobDropdown = null;
var mlvlDropdown = null;
var slvlDropdown = null;

// const blankSlotIMG = new Image();
// blankSlotIMG.src = "/index.php/Special:Filepath/Blank.jpg";

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

function getEquipIDs(){
    let equipIDs = [];
    //const flag = ( updateSpecificItem != null) ? 1 : 0;
    for (let v = 0; v <= 15; v++) {
        let str = "grid" + v;
        let slot = document.getElementById(str);
        equipIDs[v] = [ slot.dataset.value, 0 ]; // 0 is default flag id
    }
    return equipIDs;
}

function getStatsData(equipIDString){
    if ( equipIDString == null ) equipIDString = getEquipIDs(); //getEquipIDs().join(",");
    //if ( updateSpecificItem != null) equipIDString =  getEquipIDs(updateSpecificItem).join(",");
    //console.log("getStatsData: " + equipIDString);
    return {
        action: "equipsets",
        race:document.getElementById("FFXIPackageHelper_equipsets_selectRace").value,
        mlvl:document.getElementById("FFXIPackageHelper_equipsets_selectMLevel").value,
        slvl:document.getElementById("FFXIPackageHelper_equipsets_selectSLevel").value,
        mjob:document.getElementById("FFXIPackageHelper_equipsets_selectMJob").value,
        sjob:document.getElementById("FFXIPackageHelper_equipsets_selectSJob").value,
        equipment: equipIDString,
    };
  }



module.exports.setLinks = function (){
    for (let v = 0; v <= 15; v++) {
        const modal = new ModalWindow(v, { searchCallback: API.actionAPI, returnCallback: updateEquipmentGrid});

        let str = "grid" + v;
        let slot = document.getElementById(str);

        console.log(v);
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


    /**
     * DEV ONLY
     */
    mlvlDropdown.value=75;
    slvlDropdown.value=37;
    mJobDropdown.value=15;
    sJobDropdown.value=3;
    raceDropdown.value=3;

    /**
     * On page load
     */
    updateStats();

    //updateEquipmentGrid(18270, 0, null);
}