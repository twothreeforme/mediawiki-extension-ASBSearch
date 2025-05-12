
var Tooltip = require("./FFXIPackageHelper_Tooltips.js");
var LuaSets = require("./FFXIPackageHelper_LuaSets.js");

function actionAPI(params, forTab, currentButton, callback) {
  //console.log(params);
  var api = new mw.Api();

  let mainDiv = document.getElementById("FFXIPackageHelper_tabs_equipsets_shown");
  if ( mainDiv) mainDiv.classList.toggle('tabcontent-loading');

  api.get( params ).done( function ( d ) {

    if ( mainDiv) mainDiv.classList.toggle('tabcontent-loading');

    const result = d[forTab];
      //console.log(forTab);
      if ( forTab == "dropratesearch" ) updateDropRatesFromQuery(result["droprates"]);
      else if ( forTab == "recipesearch" ) updateRecipesFromQuery(result["recipes"]);
      else if ( forTab == "equipmentsearch" ) updateEquipmentFromQuery(result["equipment"]);
      else if ( forTab.includes("equipsets") ){
        //console.log(result);
        if ( forTab.includes("search") )  {
          callback.returnCallback(result['search']);
        }
        else if ( forTab.includes("change")) {
          const stats_base64 = decodeURIComponent(result['stats']);
          const stats_ = JSON.parse(atob(stats_base64));
          updateEquipsets(stats_);

          const grid_base64 = decodeURIComponent(result['grid']);
          const grid_ = JSON.parse(atob(grid_base64));
          changeGrid(grid_, result['equipLabels']);

          const luas_base64 = decodeURIComponent(result['luaNames']);
          const luas_ = JSON.parse(atob(luas_base64));
          LuaSets.adjustLuaSet(luas_);
        }
        else if ( forTab.includes("savechar")) {
          if /*ERROR*/( result['status'][0] == "ERROR" ) mw.notify( result['status'][1], { autoHide: true,  type: 'error' } );
          else { /*PASS*/
            //updateCharsList(result['status'][1], callback);
            callback(result['status']);
            mw.notify( "Character Saved", { autoHide: true,  type: 'success' } );
          }
        }
        else if ( forTab.includes("removechar")) {
          //updateCharsList(result['userchars'], callback);
          callback(result['userchars']);
          mw.notify( "Character Removed", { autoHide: true,  type: 'success' } );
        }
        else if ( forTab.includes("selectchar")) {
          //console.log(result);
          callback.updateCharacter(result['selectchar']);
          callback.updateStats();
          //callback.setHeaderCharacterDetails();

        }
        else if ( forTab.includes("updatechar")) {
          if /*ERROR*/( result['status'][0] == "ERROR" ) mw.notify( result['status'][1], { autoHide: true,  type: 'error' } );
          else { /*PASS*/
            //updateCharsList(result['status'][1], callback);
            //console.log(result['status']);
            callback(result['status']);
            mw.notify( "Character Updated", { autoHide: true,  type: 'success' } );
          }
        }
        else if ( forTab.includes("saveset")) {
          // console.log(result);
          if /*ERROR*/( "status" in result && result['status'][0] == "ERROR" ) mw.notify( result['status'][1], { autoHide: true,  type: 'error' } );
          else {
            mw.notify( "Set Saved", { autoHide: true,  type: 'success' } );

            callback(result['getsets']);
          }
        }
        else if ( forTab.includes("getsets")) {
          //console.log(result);
          callback(result['getsets']);
        }
        else if ( forTab.includes("selectset")) {
          //console.log(result);

          const stats_base64 = decodeURIComponent(result['stats']);
          const stats_ = JSON.parse(atob(stats_base64));
          updateEquipsets(stats_);

          const grid_base64 = decodeURIComponent(result['grid']);
          const grid_ = JSON.parse(atob(grid_base64));
          changeGrid(grid_, result['equipLabels']);

          const luas_base64 = decodeURIComponent(result['luaNames']);
          const luas_ = JSON.parse(atob(luas_base64));
          LuaSets.adjustLuaSet(luas_);

          document.getElementById('FFXIPackageHelper_equipsets_selectMJob').selectedIndex = result['selectset']['mjob'];
          document.getElementById('FFXIPackageHelper_equipsets_selectSJob').selectedIndex = result['selectset']['sjob'];
          document.getElementById('FFXIPackageHelper_equipsets_selectMLevel').selectedIndex = result['selectset']['mlvl'];
          document.getElementById('FFXIPackageHelper_equipsets_selectSLevel').selectedIndex = result['selectset']['slvl'];

          //callback.loadSet(result['selectset']);
        }
        else if ( forTab.includes("removeset")) {
          if ( "status" in result )
            {
              if ( result['status'][0] == "ERROR" ) mw.notify( result['status'][1], { autoHide: true,  type: 'error' } );
              else mw.notify( result['status'][0], { autoHide: true,  type: 'success' } );
            }
            if ( "getsets" in result ) {
              //console.log(result['getsets']);
              callback(result['getsets']);
            }
        }
        else {
          updateEquipsets(result['stats']);
        }
      }
      else if ( forTab.includes("fishingsearch") )updateFishingFromQuery(result["fishing"]);

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
    slot_longname = updatedGrid[1][4];
    slot_tooltip = updatedGrid[2];

    for (let v = 0; v <= 15; v++) {
      /**
       * Change Grid
       */
      if ( slotChanged != v ) continue;
      if ( slot_Flag[2] == 0 ) continue;

      let str = "grid" + v;
      let slot = document.getElementById(str);

      slot.innerHTML = slot_HTML;
      slot.dataset.value = slot_ID;

      //let tempName = slot_name + "\n" + slot_name + "\n" + slot_name + "\n" + slot_name;
      /**
       * Handle Tooltip
       */
      if( slot_ID == 0 ) Tooltip.handleTooltip(slot);
      else {
        Tooltip.handleTooltip(slot, slot_tooltip);
      }

      if ( equipLabels != null ) updateEquipmentList(v, equipLabels[v]);
      break;
    }
  }

}

function updateEquipsets(updatedStats){
  //console.log("updateEquipsets:", updatedStats);

  //let tempTooltip = `<span class="myTooltip" data-options="background:#fff;animation:fade;">Hello</span>`;
  const _id = "FFXIPackageHelper_Equipsets_stat";
  let stat = document.getElementById(_id + "HP"); stat.innerHTML = updatedStats[0];
  stat = document.getElementById(_id + "MP"); stat.innerHTML = updatedStats[1];

  stat = document.getElementById(_id + "STR"); stat.innerHTML = updatedStats[2];
  formatStatMod(_id + "STRMod", updatedStats[3]);
  
  //formatStatMod(_id + "STRMod", updatedStats[3]);

  stat = document.getElementById(_id + "DEX"); stat.innerHTML = updatedStats[4];
  formatStatMod(_id + "DEXMod", updatedStats[5]);

  stat = document.getElementById(_id + "VIT"); stat.innerHTML = updatedStats[6];
  formatStatMod(_id + "VITMod", updatedStats[7]);

  stat = document.getElementById(_id + "AGI"); stat.innerHTML = updatedStats[8];
  formatStatMod(_id + "AGIMod", updatedStats[9]);

  stat = document.getElementById(_id + "INT"); stat.innerHTML = updatedStats[10];
  formatStatMod(_id + "INTMod", updatedStats[11]);

  stat = document.getElementById(_id + "MND"); stat.innerHTML = updatedStats[12];
  formatStatMod(_id + "MNDMod", updatedStats[13]);

  stat = document.getElementById(_id + "CHR"); stat.innerHTML = updatedStats[14];
  formatStatMod(_id + "CHRMod", updatedStats[15]);

  stat = document.getElementById(_id + "DEF"); stat.innerHTML = updatedStats[16];
  stat = document.getElementById(_id + "ATT"); stat.innerHTML = updatedStats[17];

  for ( var r = 18; r <= 25; r++){
    stat = document.getElementById(_id + "Res" + (r - 18)); stat.innerHTML = updatedStats[r];
  }

  stat = document.getElementById(_id + "ACC"); stat.innerHTML = updatedStats[26];
  stat = document.getElementById(_id + "EVA"); stat.innerHTML = updatedStats[27];
}

function formatStatMod(classname, modValue){
  let statElement = document.getElementById(classname);
  if ( modValue == 0 ){ statElement.innerHTML = ""; }
  else {
    if ( Number(modValue) > 0 ) {
      statElement.style.color = "green";
      statElement.innerHTML = "&nbsp;&nbsp;+" + modValue;
    }
    else if ( Number(modValue) < 0) {
      statElement.style.color = "red";
      statElement.innerHTML = "&nbsp;&nbsp;-" + modValue;
    }
  }
}


function updateEquipmentList(slotNumber, updatedName){
  let linkID = "FFXIPackageHelper_Equipsets_gridLabel" + slotNumber;
  let labelLink = document.getElementById(linkID);
  labelLink.innerHTML = updatedName;
}



module.exports = { actionAPI }


