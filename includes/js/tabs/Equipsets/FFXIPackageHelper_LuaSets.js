

module.exports.adjustLuaSet = function (set) {
  if ( !set ) return;

  let luashitacast = "<h2>Luashitacast</h2><pre>SetNameHere = {\n";
  let ashitacast = "<h2>AshitaCast (LegacyAC)</h2><pre>&ltset name=\"SetNameHere\"&gt\n";


  for (let e = 0; e <= 15; e++) {
    console.log(set[e]);
    if ( set[e] != 0 ){
      let item = set[e].replace("\'", "\\\'");
      luashitacast += `\t${LAC_slotName(e)}\'${item}\',\n`;
      ashitacast += AC_slotName(e, item) + `\n`;
    }
  }
  console.log(ashitacast);
  luashitacast += "},</pre>";
  ashitacast += "&lt/set&gt";


  document.getElementById("FFXIPackageHelper_Equipsets_showLuaSets").innerHTML = luashitacast + ashitacast;
}

function LAC_slotName(slot){
  switch(slot){
    case 0: return "Main = ";
    case 1: return "Sub = ";
    case 2: return "Range = ";
    case 3: return  "Ammo = ";
    case 4: return  "Head = ";
    case 5: return  "Neck = ";
    case 6: return  "Ear1 = ";
    case 7: return  "Ear2 = ";
    case 8: return  "Body = ";
    case 9: return  "Hands = ";
    case 10: return "Ring1 = ";
    case 11: return "Ring2 = ";
    case 12: return "Back = ";
    case 13: return "Waist = ";
    case 14: return "Legs = ";
    case 15: return "Feet = ";
  }
}

function AC_slotName(slot,item){
  switch(slot){
    case 0: return `\t&ltmain&gt${item}&lt/main&gt`;
    case 1: return `\t&ltsub&gt${item}&lt/sub&gt`;
    case 2: return `\t&ltrange&gt${item}&lt/range&gt`;
    case 3: return  `\t&ltammo&gt${item}&lt/main&gt`;
    case 4: return  `\t&lthead&gt${item}&lt/head&gt`;
    case 5: return  `\t&ltneck&gt${item}&lt/neck&gt`;
    case 6: return  `\t&ltear1&gt${item}&lt/ear1&gt`;
    case 7: return  `\t&ltear2&gt${item}&lt/ear2&gt`;
    case 8: return  `\t&ltbody&gt${item}&lt/body&gt`;
    case 9: return  `\t&lthands&gt${item}&lt/hands&gt`;
    case 10: return `\t&ltring1&gt${item}&lt/ring1&gt`;
    case 11: return `\t&ltring2&gt${item}&lt/ring2&gt`;
    case 12: return `\t&ltback&gt${item}&lt/back&gt`;
    case 13: return `\t&ltwaist&gt${item}&lt/waist&gt`;
    case 14: return `\t&ltlegs&gt${item}&lt/legs&gt`;
    case 15: return `\t&ltfeet&gt${item}&lt/feet&gt`;
  }
}