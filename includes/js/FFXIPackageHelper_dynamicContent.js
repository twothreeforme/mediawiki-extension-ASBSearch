

function runMe(){

    const tabsButton_droprates = document.getElementById("FFXIPackageHelper_tabs_droprates");
    tabsButton_droprates.addEventListener("click", function (e) {
        showTab(e,tabsButton_droprates.id);
      });
    tabsButton_droprates.click(); 

    const tabsButton_recipes = document.getElementById("FFXIPackageHelper_tabs_recipes");
    tabsButton_recipes.addEventListener("click", function (e) {
        showTab(e,tabsButton_recipes.id);
    });

    const tabsButton_equipsets = document.getElementById("FFXIPackageHelper_tabs_equipsets");
    tabsButton_equipsets.addEventListener("click", function (e) {
        showTab(e,tabsButton_equipsets.id);
    });

    // https://stackoverflow.com/questions/12725265/is-it-possible-to-use-a-for-loop-in-select-in-html-and-how
    (function() { 
        var df = document.createDocumentFragment(); 
            
        for (var i = 0; i <= 85; i++) { 
            var option = document.createElement('option'); 
            option.value = i; 
            
            if( i == 0 ) option.appendChild(document.createTextNode("None"));
            else option.appendChild(document.createTextNode(i)); 
            
            document.getElementById('FFXIPackageHelper_dynamiccontent_selectLvlMIN').appendChild(option.cloneNode(true)); 
            document.getElementById('FFXIPackageHelper_dynamiccontent_selectLvlMAX').appendChild(option);
        }
    }());

    //document.getElementById("initialHide").style.display = "block";
}



//mw.hook('wikipage.content').add(runMe());
$( document ).ready( function () {
    runMe();
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
    console.log(cityName.concat("_shown"));
    evt.currentTarget.className += " active";
  } 

