var API = require("./Equipsets/FFXIPackageHelper_ActionAPI.js");

module.exports.setLinks = function (){
    const searchFishingSubmit = document.getElementById("FFXIPackageHelper_dynamiccontent_searchFishingSubmit");
    searchFishingSubmit.addEventListener("click", function (e) {
        submitFishingRequest();
    });

    const baitName_enterKeysearchFishingSubmit = document.querySelectorAll('input[name=baitSearch]')[0];
    baitName_enterKeysearchFishingSubmit.addEventListener("keyup", function(e) {
        e.preventDefault();
        if (e.keyCode === 13) {
        submitFishingRequest();
        }
    });

    const fishName_enterKeysearchFishingSubmit = document.querySelectorAll('input[name=fishNameSearch]')[0];
    fishName_enterKeysearchFishingSubmit.addEventListener("keyup", function(e) {
        e.preventDefault();
        if (e.keyCode === 13) {
        submitFishingRequest();
        }
    });

    // const shareDropRateQuery = document.getElementById("FFXIPackageHelper_dynamiccontent_shareDropRateQuery");
    // shareDropRateQuery.addEventListener("click", function (e) {
    //     shareQueryClicked("FFXIPackageHelper_dynamiccontent_shareDropRateQuery", getDropRateQueryParams());
    // });
}

function validFishingQuery(params){
    if( params['baitname'] == "" && params['fishname'] == "" && params['zonename'] == "searchallzones" )return false;
    else return true;
  }

function submitFishingRequest(){
  const params = getFishingQueryParams();

  if( validFishingQuery(params) == false ){
      //document.getElementById("FFXIPackageHelper_tabs_droprates_queryresult").innerHTML = "<i>*Please use the fields above to query a search.</i>";
      mw.notify( 'Please complete the fields to query a search', { autoHide: true,  type: 'error' } );
      return;
    }

  const currentButton = document.getElementById("FFXIPackageHelper_dynamiccontent_searchFishingSubmit");
  currentButton.disabled = true;
  document.getElementById("FFXIPackageHelper_tabs_fishing_queryresult").innerHTML = "Loading query...";

  API.actionAPI(params, "fishingsearch", "FFXIPackageHelper_dynamiccontent_searchFishingSubmit");
}

function getFishingQueryParams(){
    return {
      action: "fishingsearch",
      baitname: document.querySelectorAll('input[name=baitSearch]')[0].value,
      fishname: document.querySelectorAll('input[name=fishNameSearch]')[0].value,
      zonename: document.getElementById("FFXIPackageHelper_dynamiccontent_selectFishingZone").value
    };
  }

// function shareQueryClicked(shareID, params) {
//     var GETparams = "";
//     if ( shareID == "FFXIPackageHelper_dynamiccontent_shareDropRateQuery" && validDropRateQuery(params) == true ){
//         GETparams = "mobNameSearch=" + params['mobname'] + "&itemNameSearch=" + params['itemname'] + "&zoneNameDropDown=" + params['zonename'] + "&levelRangeMIN=" + params['lvlmin'] + "&levelRangeMAX=" + params['lvlmax'] + "&thRatesCheck=" + params['showth'] + "&showBCNMdrops=" + params['bcnm'] + "&excludeNMs=" + params['excludenm'] + "&includeSteal=" + params['includesteal'];
//     }
//     else {
//       mw.notify( 'Your query is not complete. Please complete and try again.', { autoHide: true,  type: 'error' } );
//       return;
//     }
//     var url = window.location.href.split('?')[0] + "?" + GETparams;
//     //console.log(url);
//     navigator.clipboard.writeText(url).then(function() {
//         //console.log('copyURLToClipboard(): Copied!');
//         mw.notify( 'Copied to Clipboard !', { autoHide: true,  type: 'warn' } );
//     }, function() {
//       mw.notify( 'Error copying to clipboard. Please report on our Discord.', { autoHide: true,  type: 'error' } );
//       //console.log('Clipboard error');
//     });
//     };

