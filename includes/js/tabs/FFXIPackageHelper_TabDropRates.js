var API = require("./Equipsets/FFXIPackageHelper_ActionAPI.js");

module.exports.setLinks = function (){
    const searchDropRatesSubmit = document.getElementById("FFXIPackageHelper_dynamiccontent_searchDropRatesSubmit");
    searchDropRatesSubmit.addEventListener("click", function (e) {
        submitDropRatesRequest();
    });

    const mobName_enterKeysearchDropRatesSubmit = document.querySelectorAll('input[name=mobNameSearch]')[0];
    mobName_enterKeysearchDropRatesSubmit.addEventListener("keypress", (e) =>  {
        if (e.key === "Enter") {
        submitDropRatesRequest();
        }
    });

    const itemName_enterKeysearchDropRatesSubmit = document.querySelectorAll('input[name=itemNameSearch]')[0];
    itemName_enterKeysearchDropRatesSubmit.addEventListener("keypress", (e) =>  {
        if (e.key === "Enter") {
        submitDropRatesRequest();
        }
    });

    const shareDropRateQuery = document.getElementById("FFXIPackageHelper_dynamiccontent_shareDropRateQuery");
    shareDropRateQuery.addEventListener("click", function (e) {
        shareQueryClicked("FFXIPackageHelper_dynamiccontent_shareDropRateQuery", getDropRateQueryParams());
    });
}

function validDropRateQuery(params){
    if( params['mobname'] == "" && params['itemname'] == "" && params['zonename'] == "searchallzones" )return false;
    else return true;
  }

function submitDropRatesRequest(){
  const params = getDropRateQueryParams();

  if( validDropRateQuery(params) == false ){
      //document.getElementById("FFXIPackageHelper_tabs_droprates_queryresult").innerHTML = "<i>*Please use the fields above to query a search.</i>";
      mw.notify( 'Please complete the fields to query a search', { autoHide: true,  type: 'error' } );
      return;
    }

  const currentButton = document.getElementById("FFXIPackageHelper_dynamiccontent_searchDropRatesSubmit");
  currentButton.disabled = true;
  document.getElementById("FFXIPackageHelper_tabs_droprates_queryresult").innerHTML = "Loading query...";

  API.actionAPI(params, "dropratesearch", "FFXIPackageHelper_dynamiccontent_searchDropRatesSubmit");
}

function getDropRateQueryParams(){
    return {
      action: "dropratesearch",
      mobname: document.querySelectorAll('input[name=mobNameSearch]')[0].value,
      itemname: document.querySelectorAll('input[name=itemNameSearch]')[0].value,
      zonename: document.getElementById("FFXIPackageHelper_dynamiccontent_selectZoneName").value,
      lvlmin: document.getElementById("FFXIPackageHelper_dynamiccontent_selectLvlMIN").value,
      lvlmax: document.getElementById("FFXIPackageHelper_dynamiccontent_selectLvlMAX").value,
      showth: ( document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxShowTH").checked ) ? 1 : 0,
      bcnm: ( document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxBCNM").checked  ) ? 1 : 0,
      excludenm: ( document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxExcludeNM").checked  ) ? 1 : 0,
      includesteal: ( document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxIncludeSteal").checked  ) ? 1 : 0,
      includefished: ( document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxIncludeFished").checked  ) ? 1 : 0
    };
  }

function shareQueryClicked(shareID, params) {
    var GETparams = "";
    if ( shareID == "FFXIPackageHelper_dynamiccontent_shareDropRateQuery" && validDropRateQuery(params) == true ){
        GETparams = "mobNameSearch=" + params['mobname'] + "&itemNameSearch=" + params['itemname'] + "&zoneNameDropDown=" + params['zonename'] + "&levelRangeMIN=" + params['lvlmin'] + "&levelRangeMAX=" + params['lvlmax'] + "&thRatesCheck=" + params['showth'] + "&showBCNMdrops=" + params['bcnm'] + "&excludeNMs=" + params['excludenm'] + "&includeSteal=" + params['includesteal'];
    }
    else {
      mw.notify( 'Your query is not complete. Please complete and try again.', { autoHide: true,  type: 'error' } );
      return;
    }
    var url = window.location.href.split('?')[0] + "?" + GETparams;
    //console.log(url);
    navigator.clipboard.writeText(url).then(function() {
        //console.log('copyURLToClipboard(): Copied!');
        mw.notify( 'Copied to Clipboard !', { autoHide: true,  type: 'warn' } );
    }, function() {
      mw.notify( 'Error copying to clipboard. Please report on our Discord.', { autoHide: true,  type: 'error' } );
      //console.log('Clipboard error');
    });
    };

