module.exports.actionAPI = function (params, forTab, currentButton) {
  //console.log(params["action"]);
  var api = new mw.Api();
  api.get( params ).done( function ( d ) {
      const result = d[forTab][0];
      if ( forTab == "dropratesearch" ) updateDropRatesFromQuery(result);
      else if ( forTab == "recipesearch" ) updateRecipesFromQuery(result);
      else if ( forTab == "equipmentsearch" ) updateEquipmentFromQuery(result);
      else if ( forTab.includes("equipsets") ){
        if ( forTab.includes("search") ) updateEquipsets_Search(result);
        else updateEquipsets(result);
      }
      //else if ( forTab == "equipsets_search") updateEquipsets_Search(result);

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

function updateEquipsets(updatedStats){
  console.log(updatedStats);

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
}

function updateEquipsets_Search(results){
  const slot = results[1];
  const arr = results[0];
  const classname = "FFXIPackageHelper_equipsets_searchResults" + slot;
  var ul = document.getElementById(classname);
  ul.innerHTML = "";


  for ( let i = 0; i < arr.length; i++ ){
    console.log(arr[i]["name"]);

    var li = document.createElement("li");
    // li.appendChild(document.createTextNode(arr[i]["name"]));

    // var iconurl = "[[File:itemid_" + arr["name"]
    // mw.hook( 'wikipage.content' ).fire( $content );

    var t = document.createTextNode(arr[i]["name"]);
    var iconurl = "/index.php/Special:Filepath/itemid_" + arr[i]["id"] + ".png";

    var img = document.createElement("img");
    img.src=iconurl;
    img.width=16;
    img.height=16;

    li.appendChild(img);
    li.appendChild(t);
    ul.appendChild(li);
  }
  //mw.hook( 'wikipage.content' ).fire($(classname));

}
