
var Tooltip = require("./FFXIPackageHelper_Tooltips.js");


module.exports.actionAPI = function (params, forTab, currentButton, sender) {
  //console.log(params["action"]);
  var api = new mw.Api();
  api.get( params ).done( function ( d ) {
      const result = d[forTab];
      console.log(result);
      if ( forTab == "dropratesearch" ) updateDropRatesFromQuery(result);
      else if ( forTab == "recipesearch" ) updateRecipesFromQuery(result);
      else if ( forTab == "equipmentsearch" ) updateEquipmentFromQuery(result);
      else if ( forTab.includes("equipsets") ){
        if ( forTab.includes("search") )  {
          //console.log(result);
          sender.returnCallback(result['search']);
        }
        else if ( forTab.includes("change")) {
          //console.log("api: equipsets_change: fired");
          updateEquipsets(result['stats']);
          changeGrid(result['changeGrid']);
        }
        else updateEquipsets(result['stats']);
      }
      else if ( forTab.includes("fishingsearch") )updateFishingFromQuery(result);


      if ( currentButton != null){
        const button = document.getElementById(currentButton);
        button.disabled = false;
      }

      //console.log( d );
  } );
};

function updateDropRatesFromQuery(updatedHTML){
  document.getElementById("FFXIPackageHelper_tabs_droprates_queryresult").innerHTML = updatedHTML;
  mw.hook( 'wikipage.content' ).fire($('#FFXIPackageHelper_tabs_droprates_queryresult'));
}

function updateRecipesFromQuery(updatedHTML){
  document.getElementById("FFXIPackageHelper_tabs_recipeSearch_queryresult").innerHTML = updatedHTML;
  mw.hook( 'wikipage.content' ).fire($('#FFXIPackageHelper_tabs_recipeSearch_queryresult'));
}

function updateEquipmentFromQuery(updatedHTML){
  document.getElementById("FFXIPackageHelper_tabs_equipment_queryresult").innerHTML = updatedHTML;
  mw.hook( 'wikipage.content' ).fire($('#FFXIPackageHelper_tabs_equipment_queryresult'));
}

function updateFishingFromQuery(updatedHTML){
  document.getElementById("FFXIPackageHelper_tabs_fishing_queryresult").innerHTML = updatedHTML;
  mw.hook( 'wikipage.content' ).fire($('#FFXIPackageHelper_tabs_fishing_queryresult'));
}

function changeGrid(updatedGrid){
  console.log("changeGrid: " ,updatedGrid);
  if ( updatedGrid == null ) return;

  //var gridArray = updatedGrid.split(",");
  slotChanged = updatedGrid[0][0];
  slot_ID = updatedGrid[0][1][0];
  slot_HTML = updatedGrid[0][1][1];
  slot_Flag = updatedGrid[0][1][2];
  slot_name = updatedGrid[0][1][3];
  slot_tooltip = updatedGrid[0][2];

  for (let v = 0; v <= 15; v++) {
    if ( slotChanged != v ) continue;
    if ( slot_Flag[2] == 0 ) continue;

    let str = "grid" + v;
    let slot = document.getElementById(str);

    slot.innerHTML = slot_HTML;
    slot.dataset.value = slot_ID;

    //let tempName = slot_name + "\n" + slot_name + "\n" + slot_name + "\n" + slot_name;
    if( slot_ID == 0 ) Tooltip.handleTooltip(slot);
    else {
      Tooltip.handleTooltip(slot, slot_tooltip);
    }
    break;
  }
}


function updateEquipsets(updatedStats){
  //console.log(updatedStats);

  //let tempTooltip = `<span class="myTooltip" data-options="background:#fff;animation:fade;">Hello</span>`;

  let stat = document.getElementById("FFXIPackageHelper_Equipsets_statHP"); stat.innerHTML = updatedStats[0];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statMP"); stat.innerHTML = updatedStats[1];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statSTR"); stat.innerHTML = updatedStats[2];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statDEX"); stat.innerHTML = updatedStats[3];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statVIT"); stat.innerHTML = updatedStats[4];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statAGI"); stat.innerHTML = updatedStats[5];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statINT"); stat.innerHTML = updatedStats[6];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statMND"); stat.innerHTML = updatedStats[7];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statCHR"); stat.innerHTML = updatedStats[8];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statDEF"); stat.innerHTML = updatedStats[9];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statATT"); stat.innerHTML = updatedStats[10];

  for ( var r = 11; r <= 18; r++){
    stat = document.getElementById("FFXIPackageHelper_Equipsets_statRes" + (r - 11)); stat.innerHTML = updatedStats[r];
  }

  stat = document.getElementById("FFXIPackageHelper_Equipsets_statACC"); stat.innerHTML = updatedStats[19];
  stat = document.getElementById("FFXIPackageHelper_Equipsets_statEVA"); stat.innerHTML = updatedStats[20];

}



