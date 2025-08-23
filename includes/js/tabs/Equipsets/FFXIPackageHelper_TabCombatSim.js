var API = require("./FFXIPackageHelper_ActionAPI.js");
//var Data = require("./FFXIPackageHelper_DataManager.js");

module.exports.setLinks = function (){

    let mobName_enterKeySubmit = document.querySelectorAll('input[name=mobNameSearch]')[0];
    if ( mobName_enterKeySubmit ) mobName_enterKeySubmit.addEventListener("keypress", (e) =>  {
        if (e.key === "Enter") {
            submitMobSearchRequest();
        }
    });

    let searchMobNameSubmit = document.getElementById("FFXIPackageHelper_dynamiccontent_searchForMobAndZone");
    if ( searchMobNameSubmit ) searchMobNameSubmit.addEventListener("click", function (e) {
        submitMobSearchRequest();
    });

}

function validMobSearchQuery(params){
    if( params['mobname'] == "" && params['zonename'] == "searchallzones" ) return false;
    else return true;
}

function getQueryParams(){
    return {
      action: "combatsim_mobsearch",
      mobname: document.querySelectorAll('input[name=mobNameSearch]')[0].value,
      zonename: document.getElementById("FFXIPackageHelper_dynamiccontent_selectZoneName").value,
    };
}

function submitMobSearchRequest(){
  let params = getQueryParams();

  if( validMobSearchQuery(params) == false ){
      //document.getElementById("FFXIPackageHelper_tabs_droprates_queryresult").innerHTML = "<i>*Please use the fields above to query a search.</i>";
      mw.notify( 'Mob name or zone are required.', { autoHide: true,  type: 'error' } );
      return;
    }

  let currentButton = document.getElementById("FFXIPackageHelper_dynamiccontent_searchForMobAndZone");
  currentButton.disabled = true;
  document.getElementById("FFXIPackageHelper_tabs_combatsim_queryresult").innerHTML = "Loading query...";

  API.actionAPI(params, "combatsim_mobsearch", "FFXIPackageHelper_dynamiccontent_searchForMobAndZone");
}
