var API = require("./FFXIPackageHelper_ActionAPI.js");
var ModalWindow = require("./FFXIPackageHelper_ModalWindow.js");

// const slot = {
//     MAIN: 0,
//     SUB: 1,
//     RANGE: 2,
//     AMMO: 3,
//     HEAD: 4,
//     NECK: 5,
//     EAR1: 6,
//     EAR2: 7,
//     BODY: 8,
//     HANDS: 9,
//     RING1: 10,
//     RING2: 11,
//     BACK: 12,
//     WAIST: 13,
//     LEGS: 14,
//     FEET: 15
// };

var raceDropdown = null;
var mJobDropdown = null;
var sJobDropdown = null;
var mlvlDropdown = null;
var slvlDropdown = null;

const blankSlotIMG = new Image();
blankSlotIMG.src = "/index.php/Special:Filepath/Blank.jpg";

// function replaceImageWithCanvas(imageId) {
//     imageId = "grid0";
//     const image = document.getElementById(imageId);

//     const canvas = document.createElement('canvas');
//     const ctx = canvas.getContext('2d');

//     // Set canvas dimensions to match the image
//     canvas.width = 64;
//     canvas.height = 64;

//     // Draw the image onto the canvas
//     ctx.drawImage(image, 0, 0);

//     // Replace the image with the canvas
//     image.parentNode.replaceChild(canvas, image);
//   }

function updateEquipmentGrid(id, slot, sender){
    console.log("clicked: " + id + ", " + slot);
    const ids = getEquipIDs();
    ids[slot] = id; // updated equip
    //console.log(ids);
    var all = getStatsData(ids);
    all.action = "equipsets_change";

    API.actionAPI(all, "equipsets_change", null, null);
    // close modal window
    sender.close();
}

function getEquipIDs(){
    let equipIDs = [];
    for (let v = 0; v <= 15; v++) {
        let str = "grid" + v;
        let slot = document.getElementById(str);
        equipIDs[v] = slot.dataset.value;
    }
    return equipIDs;
}


function getStatsData(equipIDString){
    if ( equipIDString == null ) equipIDString =  getEquipIDs().join(",");

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

function updateStats(data){
    if ( data == null ) data = getStatsData();
    //console.log(getStatData());
    API.actionAPI(data, "equipsets", null);
}

module.exports.setLinks = function (){
    for (let v = 0; v <= 15; v++) {
        const modal = new ModalWindow(v, { searchCallback: API.actionAPI, returnCallback: updateEquipmentGrid});

        let str = "grid" + v;

        let slot = document.getElementById(str);
        slot.addEventListener("click", function (e) {
            modal.open();
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
    });

    mlvlDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectMLevel");
    mlvlDropdown.addEventListener("change", (e) =>  {
        //console.log(e.target.value);
        if ( document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxMaxSub").checked == 1 ){
            slvlDropdown.value = (e.target.value > 1) ? Math.floor(e.target.value / 2) : 1;
        }
    });

    raceDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectRace");
    raceDropdown.addEventListener("change", (e) =>  {
        //console.log(e.target.value);
    });

    /**
     * Main and Sub job elements
     */
    mJobDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectMJob");
    mJobDropdown.addEventListener("change", (e) => {
        //console.log(e.target.value);
    });

    sJobDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectSJob");
    sJobDropdown.addEventListener("change", (e) => {
        //console.log(e.target.value);
    });

    sJobMaxCheckbox = document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxMaxSub");
    sJobMaxCheckbox.addEventListener("change", (e) => {
        if ( e.target.checked == 1 ){
            slvlDropdown.value = (mlvlDropdown.value > 1) ? Math.floor(mlvlDropdown.value / 2) : 1;
        }
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
}