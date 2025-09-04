var TabEquipsets = require("./FFXIPackageHelper_TabEquipsets.js");
var TabCharacters = require("./FFXIPackageHelper_TabCharacters.js");
var TabCombatSim = require("../FFXIPackageHelper_TabMobSearch.js");
var Tabs = require("../FFXIPackageHelper_ShowTabs.js");

function onPageLoad(){
//console.log("onReady")

  const tabsButton_equipsets = document.getElementById("FFXIPackageHelper_tabs_equipsets");
  if ( tabsButton_equipsets == null ) {
    return ;
  }
  tabsButton_equipsets.addEventListener("click", function (e) {
    Tabs.showTab(e,tabsButton_equipsets.id);
  });
  tabsButton_equipsets.click();

  const tabsButton_characters = document.getElementById("FFXIPackageHelper_tabs_characters");
  if ( tabsButton_characters == null ) {
    return ;
  }
  tabsButton_characters.addEventListener("click", function (e) {
    Tabs.showTab(e,tabsButton_characters.id);
  });

  const tabsButton_combatsim = document.getElementById("FFXIPackageHelper_tabs_combatsim");
  if ( tabsButton_combatsim == null ) {
    return ;
  }
  tabsButton_combatsim.addEventListener("click", function (e) {
    Tabs.showTab(e,tabsButton_combatsim.id);
  });

  return 0;
}

var initiallyLoaded = false;
mw.hook('wikipage.content').add( function () {
  //console.log('wikipage.content: fired');
  if ( initiallyLoaded == true) return;

  if ( onPageLoad() == null) {
    console.log("Equipsets - Tab Controller: Tabs not found. ");
    return;
  }

  document.getElementById("initialHide").style.display = "block";

  TabEquipsets.setLinks();
  TabCharacters.setLinks();
  TabCombatSim.setLinks();

  initiallyLoaded = true;
  console.log("Equipsets - Tab Controller: initiallyLoaded");
  });

