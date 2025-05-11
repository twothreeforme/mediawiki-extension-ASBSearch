var API = require("./FFXIPackageHelper_ActionAPI.js");

function updateEquipmentGrid(id, slot, sender){
    //console.log("clicked: " + id + ", " + slot);
    const equipment = getEquipIDs();
    equipment[slot] = [ id, 1 ]; // updated equip flagged with 1 to trigger update

    var all = getStatsData(equipment);
    all.action = "equipsets_change";

    //console.log("updateEquipmentGrid", all);
    API.actionAPI(all, "equipsets_change", null, null);

    // close modal window
    if( sender !== null) sender.close();
}

function getEquipID(forSlot){
    let slot = document.getElementById("grid" + forSlot);
    return slot.dataset.value;
}

function getEquipIDs(updateAll){
    let equipIDs = [];
    let shareEquipIDs = [];

    for (let v = 0; v <= 15; v++) {
        let str = "grid" + v;
        let slot = document.getElementById(str);
        //console.log(v, slot.dataset.value);
        if ( updateAll == true ) {
            // if ( slot.dataset.value != 0) shareEquipIDs[v] = [ slot.dataset.value, 1 ];
            // else shareEquipIDs[v] = [ 0, 0 ];
            shareEquipIDs[v] = [ slot.dataset.value, 1 ];
        }
        else  {
            equipIDs[v] = [ slot.dataset.value, 0 ]; // 0 is default flag id
        }
    }

    if ( updateAll == true ) { return shareEquipIDs;
    }
    else return equipIDs;
}

function updateStats(data){
    if ( data == null ) data = getStatsData();
    //console.log("updateStats: ", data);
    if ( data.mjob == 0 || data.sjob == 0 ) return;

    API.actionAPI(data, "equipsets", null);
}

function getMeritsData(){
    let meritStats = {};
    let meritSkills = {};

    const _id = "FFXIPackageHelper_equipsets_merits_";
    const allMerits = document.querySelectorAll("[id*='" + _id + "']");
    //console.log(allMerits);
    var meritsArray = [...allMerits];
    meritsArray.forEach(merit => {
        //console.log(merit);
        if ( merit.value != 0 ){
            if ( merit.id.includes("skill") ) {
                var skillid = merit.id.replace(_id + "skill",'');
                meritSkills[Number(skillid)] = merit.value;
            }
            else if ( merit.id.includes("stat") ) {
                var statid = merit.id.replace(_id + "stats",'');
                meritStats[Number(statid)] = merit.value;
            }
        }
    });

    //console.log(JSON.stringify([meritStats, meritSkills]));
    return JSON.stringify([meritStats, meritSkills]);
}

function areMeritsSet(){
    const _id = "FFXIPackageHelper_equipsets_merits_";
    const allMerits = document.querySelectorAll("[id*='" + _id + "']");
    //console.log(allMerits);
    var meritsArray = [...allMerits];
    for (const mer of meritsArray) {
        if ( mer.value > 0){
            return true;
        }
    }
    return false;
}

function getStatsData(equipIDString){
    if ( equipIDString == null ) equipIDString = getEquipIDs(); //getEquipIDs().join(",");
    else if ( equipIDString == true ) {
        equipIDString = getEquipIDs(true);
    }
    equipIDString = equipIDString.join("|");

    //console.log("getStatsData: ", equipIDString);
    //console.log("getMeritsData: ", getMeritsData(), encodeURIComponent(btoa(getMeritsData())));
    return {
        action: "equipsets",
        race:document.getElementById("FFXIPackageHelper_equipsets_selectRace").value,
        mlvl:document.getElementById("FFXIPackageHelper_equipsets_selectMLevel").value,
        slvl:document.getElementById("FFXIPackageHelper_equipsets_selectSLevel").value,
        mjob:document.getElementById("FFXIPackageHelper_equipsets_selectMJob").value,
        sjob:document.getElementById("FFXIPackageHelper_equipsets_selectSJob").value,
        merits: encodeURIComponent(btoa(getMeritsData())),
        equipment: encodeURIComponent(btoa(equipIDString)),
    };
}

function getSetData(){
    equipIDString = getEquipIDs(true); //getEquipIDs().join(",");
    equipIDString = equipIDString.join("|");

    //console.log("getStatsData: ", equipIDString);
    //console.log("getMeritsData: ", getMeritsData(), encodeURIComponent(btoa(getMeritsData())));
    return {
        action: "equipsets",
        mlvl:document.getElementById("FFXIPackageHelper_equipsets_selectMLevel").value,
        slvl:document.getElementById("FFXIPackageHelper_equipsets_selectSLevel").value,
        mjob:document.getElementById("FFXIPackageHelper_equipsets_selectMJob").value,
        sjob:document.getElementById("FFXIPackageHelper_equipsets_selectSJob").value,
        equipment: encodeURIComponent(btoa(equipIDString)),
        setname:document.getElementById("FFXIPackageHelper_dynamiccontent_setNameInput").value, 
    };
}

function resetStats(){
    document.getElementById("FFXIPackageHelper_equipsets_selectRace").value = 0;

    const removeButton = document.getElementById("FFXIPackageHelper_deleteCharButton");
    removeButton.style.visibility = "hidden";

    resetMeritsToDefault();

    updateStats();
    setHeaderCharacterDetails();
}

function resetMeritsToDefault(){
    const _id = "FFXIPackageHelper_equipsets_merits_";
    const allMerits = document.querySelectorAll("[id*='" + _id + "']");
    //console.log(allMerits);
    var meritsArray = [...allMerits];
    meritsArray.forEach(merit => {
        merit.value = 0;
    });
}

function setMeritsData(merits_){

    let meritStats = merits_[0];
    let meritSkills = merits_[1];

    const stats = document.querySelectorAll('[id*="FFXIPackageHelper_equipsets_merits_stats"]');
    stats.forEach(stat => {
        const id =  Number(stat.id.replace("FFXIPackageHelper_equipsets_merits_stats", ""));
        if ( meritStats.hasOwnProperty(id) == true ) stat.value = meritStats[id];
        else  stat.value = 0;
    });

    const skills = document.querySelectorAll('[id*="FFXIPackageHelper_equipsets_merits_skill"]');
    skills.forEach(skill => {
        const id =  Number(skill.id.replace("FFXIPackageHelper_equipsets_merits_skill", ""));
        if ( meritSkills.hasOwnProperty(id) == true ) skill.value = meritSkills[id];
        else  skill.value = 0;
    });
}


function setHeaderCharacterDetails(){
    console.log("setHeaderCharacterDetails");
    const charSelectDIV = document.getElementById("FFXIPackageHelper_equipsets_charSelect");
    const buttons = charSelectDIV.querySelectorAll('button');
    let currentCharacterName = document.getElementById("FFXIPackageHelper_characterHeader_name");

    let namechanged = false;
    Array.from(buttons).forEach((button) => {

        if ( button.classList.contains('FFXIPackageHelper_charButtonselected') ) {
            currentCharacterName.innerText = button.innerHTML;
            namechanged = true;
            //console.log("changed", namechanged, button.innerHTML, currentCharacterName.innerText);
        }
    });
    if (namechanged == false ) {
        currentCharacterName.innerText = "No character selected";
        return;
    }

    const selectRace = document.getElementById("FFXIPackageHelper_equipsets_selectRace");
    if (selectRace) {
        const race = selectRace.selectedOptions[0].innerHTML;
        if ( areMeritsSet() == true ) document.getElementById("FFXIPackageHelper_characterHeader_details").innerText = "  -  " + race + "  -  Merits set";
        else document.getElementById("FFXIPackageHelper_characterHeader_details").innerText = "  -  " + race + "  -  No merits set";
        // console.log("setHeaderCharacterDetails");
    }
}

function updateCharacter(char){
    console.log("updateCharacter:", char);
    document.getElementById("FFXIPackageHelper_equipsets_selectRace").value = char.race;

    if ( char.def == 0 ) document.getElementById("FFXIPackageHelper_dynamiccontent_defaultChar").checked = false;
    else document.getElementById("FFXIPackageHelper_dynamiccontent_defaultChar").checked = true;

    if ( char.charname.length > 0 ) {
        const merits_base64 = decodeURIComponent(char.merits);
        const merits_ = JSON.parse(atob(merits_base64));

        setMeritsData(merits_);
        setHeaderCharacterDetails();
    }
}

function loadSet(fromFetechedResult){

    // const data = {
    //     action: "equipsets_change",
    //     race:document.getElementById("FFXIPackageHelper_equipsets_selectRace").value,
    //     mlvl:fromFetechedResult.mlvl,
    //     slvl:fromFetechedResult.slvl,
    //     mjob:fromFetechedResult.mjob,
    //     sjob:fromFetechedResult.sjob,
    //     merits: encodeURIComponent(btoa(getMeritsData())),
    //     equipment: fromFetechedResult.equipment,
    // };
    // API.actionAPI(data, "equipsets_change", null, null);

    updateStats();
}

module.exports = {
    updateEquipmentGrid, getEquipID, getEquipIDs, updateStats, getMeritsData, getStatsData, resetStats,
    setMeritsData, setHeaderCharacterDetails, updateCharacter, resetMeritsToDefault, getSetData, loadSet
}