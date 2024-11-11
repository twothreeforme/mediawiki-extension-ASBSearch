
var currentButton;

function onReady(){

    const tabsButton_droprates = document.getElementById("FFXIPackageHelper_tabs_droprates");
    tabsButton_droprates.addEventListener("click", function (e) {
        showTab(e,tabsButton_droprates.id);
      });
    // set the current tab to be "Drop Rates"
    tabsButton_droprates.click(); 

    const tabsButton_recipes = document.getElementById("FFXIPackageHelper_tabs_recipes");
    tabsButton_recipes.addEventListener("click", function (e) {
        showTab(e,tabsButton_recipes.id);
    });

    const tabsButton_equipsets = document.getElementById("FFXIPackageHelper_tabs_equipsets");
    tabsButton_equipsets.addEventListener("click", function (e) {
        showTab(e,tabsButton_equipsets.id);
    });

    const searchDropRatesSubmit = document.getElementById("FFXIPackageHelper_dynamiccontent_searchDropRatesSubmit");
    searchDropRatesSubmit.addEventListener("click", function (e) {
      submitDropRatesRequest();
    });

    // const searchRecipeSubmit = document.getElementById("FFXIPackageHelper_dynamiccontent_searchRecipeSubmit");
    // searchRecipeSubmit.addEventListener("click", function (e) {
    //   submitRecipeRequest();
    // });

    const shareDropRateQuery = document.getElementById("FFXIPackageHelper_dynamiccontent_shareDropRateQuery");
    shareDropRateQuery.addEventListener("click", function (e) {
      shareQueryClicked("FFXIPackageHelper_dynamiccontent_shareDropRateQuery", getDropRateQueryParams());
    });


    const inputElement = document.getElementById("FFXIPackageHelper_dynamiccontent_selectCraft");
    inputElement.addEventListener("change", (event) => {
      // Code to execute when the input value changes
      if ( event.target.value !=  "none" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectSkillRank").disabled = false;
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").disabled = false;
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").disabled = false;
      }
      else {
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectSkillRank").disabled = true;
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectSkillRank").value = "0";

        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").disabled = true;
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "0";

        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").disabled = true;
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "0";

      }
      //console.log(event.target.value);
    });

    const skillRankSelect = document.getElementById("FFXIPackageHelper_dynamiccontent_selectSkillRank");
    skillRankSelect.addEventListener("change", (event) => {
      // Code to execute when the input value changes
      if ( event.target.value ==  "1" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "0";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "10";
      }
      else if ( event.target.value ==  "11" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "11";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "20";
      }
      else if ( event.target.value ==  "21" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "21";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "30";
      }
      else if ( event.target.value ==  "31" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "31";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "40";
      }
      else if ( event.target.value ==  "41" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "41";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "50";
      }
      else if ( event.target.value ==  "51" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "51";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "60";
      }
      else if ( event.target.value ==  "61" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "61";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "70";
      }
      else if ( event.target.value ==  "71" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "71";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "80";
      }
      else if ( event.target.value ==  "81" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "81";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "90";
      }
      else if ( event.target.value ==  "91" ){
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value = "91";
        document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value = "100";
      }

    });


    document.getElementById("initialHide").style.display = "block";
}



//mw.hook('wikipage.content').add(runMe());
$( document ).ready( function () {
    onReady();
} );



function showTab(evt, cityName) { //https://www.w3schools.com/howto/howto_js_tabs.asp
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
    document.getElementById(cityName.concat("_shown")).style.display = "block";
    //console.log(cityName.concat("_shown"));
    evt.currentTarget.className += " active";
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
    excludenm: ( document.getElementById("FFXIPackageHelper_dynamiccontent_checkboxExcludeNM").checked  ) ? 1 : 0
  };
}

function validDropRateQuery(params){
  if( params['mobname'] == "" && params['itemname'] == "" && params['zonename'] == "searchallzones" )return false;
  else return true;
}

function validRecipesQuery(params){
  if( params['recipename'] == "" && params['ingredient'] == "" && params['craft'] == "" )return false;
  else return true;
}

function submitDropRatesRequest(){
  const params = getDropRateQueryParams();

  if( validDropRateQuery(params) == false ){
      //document.getElementById("FFXIPackageHelper_tabs_droprates_queryresult").innerHTML = "<i>*Please use the fields above to query a search.</i>";
      mw.notify( 'Please complete the fields to query a search', { autoHide: true,  type: 'error' } );
      return;
    }

  currentButton = document.getElementById("FFXIPackageHelper_dynamiccontent_searchDropRatesSubmit");
  currentButton.disabled = true;
  document.getElementById("FFXIPackageHelper_tabs_droprates_queryresult").innerHTML = "Loading query...";

  actionAPI(params, "dropratesearch");
}

function getRecipesQueryParams(){
  return {
    action: "recipesearch",
    craft: document.getElementById("FFXIPackageHelper_dynamiccontent_selectCraft").value,
    recipename: document.querySelectorAll('input[name=recipeNameSearch]')[0].value,
    ingredient: document.querySelectorAll('input[name=ingredientSearch]')[0].value,
    crystal: document.getElementById("FFXIPackageHelper_dynamiccontent_selectCrystal").value,
    skillrank: document.getElementById("FFXIPackageHelper_dynamiccontent_selectSkillRank").value,
    mincraftlvl: document.getElementById("FFXIPackageHelper_dynamiccontent_selectMinCraftLvl").value,
    maxcraftlvl: document.getElementById("FFXIPackageHelper_dynamiccontent_selectMaxCraftLvl").value
  };
}

function submitRecipeRequest(){
  const params = getRecipesQueryParams();

  if ( validRecipesQuery(params) == false) {
    //document.getElementById("FFXIPackageHelper_tabs_recipeSearch_queryresult").innerHTML = "<p style=\"color:red;\">Either Recipe Name, Ingredient, or Craft are required to search.</p>";
    mw.notify( 'Either Recipe Name, Ingredient, or Craft are required to search', { autoHide: true,  type: 'error' } );
    return;
  }

  currentButton = document.getElementById("FFXIPackageHelper_dynamiccontent_searchRecipeSubmit");
  currentButton.disabled = true;
  document.getElementById("FFXIPackageHelper_tabs_recipeSearch_queryresult").innerHTML = "Loading query...";

  //console.log(params.crystal);
  actionAPI(params, "recipesearch");
}

function actionAPI(params, forTab) {
  console.log(params["action"]);
  var api = new mw.Api();
  api.get( params ).done( function ( d ) {
      if ( forTab ==  "dropratesearch" ) updateDropRatesFromQuery(d[forTab][0]);
      else if ( forTab ==  "recipesearch" ) updateRecipesFromQuery(d[forTab][0]);
      currentButton.disabled = false;
      //console.log( d );
  } );
};

function updateDropRatesFromQuery(updatedHTML){
  document.getElementById("FFXIPackageHelper_tabs_droprates_queryresult").innerHTML = updatedHTML;
}

function updateRecipesFromQuery(updatedHTML){
  document.getElementById("FFXIPackageHelper_tabs_recipeSearch_queryresult").innerHTML = updatedHTML;
}

function shareQueryClicked(shareID, params) {
  var GETparams = "";
  if ( shareID == "FFXIPackageHelper_dynamiccontent_shareDropRateQuery" && validDropRateQuery(params) == true ){
      GETparams = "mobNameSearch=" + params['mobname'] + "&itemNameSearch=" + params['itemname'] + "&zoneNameDropDown=" + params['zonename'] + "&levelRangeMIN=" + params['lvlmin'] + "&levelRangeMAX=" + params['lvlmax'] + "&thRatesCheck=" + params['showth'] + "&showBCNMdrops=" + params['bcnm'] + "&excludeNMs=" + + params['excludenm'];
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