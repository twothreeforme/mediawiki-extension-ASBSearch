var TabDropRates = require("./FFXIPackageHelper_TabDropRates.js");
var TabRecipes = require("./FFXIPackageHelper_TabRecipes.js");
var TabEquipment = require("./FFXIPackageHelper_TabEquipment.js");
var TabEquipsets = require("./Equipsets/FFXIPackageHelper_TabEquipsets.js");


function showTab(evt, tabName) { //https://www.w3schools.com/howto/howto_js_tabs.asp
    // Declare all variables
    var i, tabcontent, tablinks;
  
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
  
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
  
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName.concat("_shown")).style.display = "block";
    //console.log(tabName.concat("_shown"));
    evt.currentTarget.className += " active";
  } 

function onPageLoad(){
//console.log("onReady")

  const tabsButton_droprates = document.getElementById("FFXIPackageHelper_tabs_droprates");
  if ( tabsButton_droprates == null ) {
    //console.log("droprates null");
    return ;
  }
  tabsButton_droprates.addEventListener("click", function (e) {
      showTab(e,tabsButton_droprates.id);
    });
  // set the current tab to be "Drop Rates"
  tabsButton_droprates.click();

  const tabsButton_recipes = document.getElementById("FFXIPackageHelper_tabs_recipes");
  if ( tabsButton_recipes == null ) {
    //console.log("recipes null");
    return ;
  }
  tabsButton_recipes.addEventListener("click", function (e) {
      showTab(e,tabsButton_recipes.id);
  });

  const tabsButton_equipment = document.getElementById("FFXIPackageHelper_tabs_equipment");
  if ( tabsButton_equipment == null )  {
    //console.log("equipment null");
    return ;
  }
  tabsButton_equipment.addEventListener("click", function (e) {
      showTab(e,tabsButton_equipment.id);
  });

  const tabsButton_equipsets = document.getElementById("FFXIPackageHelper_tabs_equipsets");
  if ( tabsButton_equipsets == null )  {
    //console.log("equipsets null");
    return ;
  }
  tabsButton_equipsets.addEventListener("click", function (e) {
      showTab(e,tabsButton_equipsets.id);
  });
  //tabsButton_equipsets.click();

  return 0;
}

var initiallyLoaded = false;
mw.hook('wikipage.content').add( function () {
  if ( initiallyLoaded == true) return;

  if ( onPageLoad() == null) {
    console.log("Tab Controller: Tabs not found. ");
    return;
  }

  document.getElementById("initialHide").style.display = "block";

  TabDropRates.setLinks();
  TabRecipes.setLinks();
  TabEquipment.setLinks();
  TabEquipsets.setLinks();

  initiallyLoaded = true;
  console.log("Tab Controller: initiallyLoaded");

  });

// $( document ).ready( function () {

//     onPageLoad();
//     document.getElementById("initialHide").style.display = "block";

//     TabDropRates.setLinks();
//     TabRecipes.setLinks();
//     TabEquipment.setLinks();
//     TabEquipsets.setLinks();

//   } );
