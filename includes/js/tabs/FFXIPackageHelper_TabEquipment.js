const API = require("./Equipsets/FFXIPackageHelper_ActionAPI.js");

module.exports.setLinks = function (){
    const searchEquipmentSubmit = document.getElementById("FFXIPackageHelper_dynamiccontent_searchEquipmentSubmit");
    searchEquipmentSubmit.addEventListener("click", function (e) {
    submitEquipmentRequest();
    });
}

function validEquipQuery(params){
    if( params['equipmentname'] == "" && params['job'] == "0" && params['minitemlvl'] == "0" )return false;
    else return true;
  }

function getEquipQueryParams(){
    return {
        action: "equipmentsearch",
        equipmentname: document.querySelectorAll('input[name=equipmentNameSearch]')[0].value,
        job: document.getElementById("FFXIPackageHelper_dynamiccontent_selectJob").value,
        minitemlvl: document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinItemLvl").value,
        slot: document.getElementById("FFXIPackageHelper_dynamiccontent_selectSlotType").value,
    };
  }


function submitEquipmentRequest(){
    const params = getEquipQueryParams();

    if( validEquipQuery(params) == false ){
        mw.notify( 'Please complete the fields to query a search', { autoHide: true,  type: 'error' } );
        return;
      }

    const currentButton = document.getElementById("FFXIPackageHelper_dynamiccontent_searchEquipmentSubmit");
    currentButton.disabled = true;
    document.getElementById("FFXIPackageHelper_tabs_equipment_queryresult").innerHTML = "Loading query...";

    API.actionAPI(params, "equipmentsearch", "FFXIPackageHelper_dynamiccontent_searchEquipmentSubmit");
  }

