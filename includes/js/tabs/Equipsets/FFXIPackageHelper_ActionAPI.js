
var Tooltip = require("./FFXIPackageHelper_Tooltips.js");


module.exports.actionAPI = function (params, forTab, currentButton, sender) {
  //console.log(params);
  var api = new mw.Api();
  api.get( params ).done( function ( d ) {
    //console.log(d);
    const result = d[forTab];
      //console.log(forTab);
      if ( forTab == "dropratesearch" ) updateDropRatesFromQuery(result);
      else if ( forTab == "recipesearch" ) updateRecipesFromQuery(result);
      else if ( forTab == "equipmentsearch" ) updateEquipmentFromQuery(result);
      else if ( forTab.includes("equipsets") ){
        if ( forTab.includes("search") )  {
          //console.log(result);
          sender.returnCallback(result['search']);
        }
        else if ( forTab.includes("change")) {
          
          // updateEquipsets(result['stats']);
          // changeGrid(result['grid']);

          const stats_base64 = decodeURIComponent(result['stats']);
          const stats_ = JSON.parse(atob(stats_base64));
          //console.log("api: equipsets_change: ", stats_);
          updateEquipsets(stats_);

          const grid_base64 = decodeURIComponent(result['grid']);
          const grid_ = JSON.parse(atob(grid_base64));
          //console.log("api: equipsets_change: ", grid_);
          changeGrid(grid_, result['equipLabels']);

          //console.log(result['equipLabels']);
        }
        else if ( forTab.includes("savechar")) {
          mw.notify( result['savecharERROR'], { autoHide: true,  type: 'error' } );
        }
        else {
          updateEquipsets(result['stats']);
        }
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

function changeGrid(incomingGridData, equipLabels){
  //console.log("changeGrid: " ,updatedGrid);

  for (const updatedGrid of incomingGridData){
      if ( updatedGrid == null ) return;

    //var gridArray = updatedGrid.split(",");
    slotChanged = updatedGrid[0];
    slot_ID = updatedGrid[1][0];
    slot_HTML = updatedGrid[1][1];
    slot_Flag = updatedGrid[1][2];
    slot_name = updatedGrid[1][3];
    slot_tooltip = updatedGrid[2];

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

      if ( equipLabels != null ) updateEquipmentList(v, equipLabels[v]);
      break;
    }
  }

  //mw.hook( 'wikipage.content' ).fire($('.FFXIPackageHelper_Equipsets_equipList'));
}


function updateEquipsets(updatedStats){
  //console.log(updatedStats);

  //let tempTooltip = `<span class="myTooltip" data-options="background:#fff;animation:fade;">Hello</span>`;
  const _id = "FFXIPackageHelper_Equipsets_stat";
  let stat = document.getElementById(_id + "HP"); stat.innerHTML = updatedStats[0];
  stat = document.getElementById(_id + "MP"); stat.innerHTML = updatedStats[1];

  stat = document.getElementById(_id + "STR"); stat.innerHTML = updatedStats[2];
  adjustStatColor(_id + "STRMod", updatedStats[3]);

  stat = document.getElementById(_id + "DEX"); stat.innerHTML = updatedStats[4];
  adjustStatColor(_id + "DEXMod", updatedStats[5]);

  stat = document.getElementById(_id + "VIT"); stat.innerHTML = updatedStats[6];
  adjustStatColor(_id + "VITMod", updatedStats[7]);

  stat = document.getElementById(_id + "AGI"); stat.innerHTML = updatedStats[8];
  adjustStatColor(_id + "AGIMod", updatedStats[9]);

  stat = document.getElementById(_id + "INT"); stat.innerHTML = updatedStats[10];
  adjustStatColor(_id + "INTMod", updatedStats[11]);

  stat = document.getElementById(_id + "MND"); stat.innerHTML = updatedStats[12];
  adjustStatColor(_id + "MNDMod", updatedStats[13]);

  stat = document.getElementById(_id + "CHR"); stat.innerHTML = updatedStats[14];
  adjustStatColor(_id + "CHRMod", updatedStats[15]);

  stat = document.getElementById(_id + "DEF"); stat.innerHTML = updatedStats[16];
  stat = document.getElementById(_id + "ATT"); stat.innerHTML = updatedStats[17];

  for ( var r = 18; r <= 25; r++){
    stat = document.getElementById(_id + "Res" + (r - 18)); stat.innerHTML = updatedStats[r];
  }

  stat = document.getElementById(_id + "ACC"); stat.innerHTML = updatedStats[26];
  stat = document.getElementById(_id + "EVA"); stat.innerHTML = updatedStats[27];
}

function adjustStatColor(classname, modValue){
  let stat = document.getElementById(classname);

  if ( Number(modValue) > 0 ) {
    stat.style.color = "green";
    stat.innerHTML = "&nbsp;&nbsp;+" + modValue;
  }
  else if ( Number(modValue) > 0) {
    stat.style.color = "red";
    stat.innerHTML = "&nbsp;&nbsp;-" + modValue;
  }
}

function updateEquipmentList(slotNumber, updatedName){

  let linkID = "FFXIPackageHelper_Equipsets_gridLabel" + slotNumber;
  let labelLink = document.getElementById(linkID);
  labelLink.innerHTML = updatedName;

}

