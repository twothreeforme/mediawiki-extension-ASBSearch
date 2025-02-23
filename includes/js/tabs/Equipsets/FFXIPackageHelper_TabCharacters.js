var API = require("./FFXIPackageHelper_ActionAPI.js");
var Data = require("./FFXIPackageHelper_DataManager.js");

var ModalCharAddWindow = require("./FFXIPackageHelper_ModalCharAdd.js");
var ModalCharRemoveWindow = require("./FFXIPackageHelper_ModalCharRemove.js");

module.exports.setLinks = function (){

    const modalCharAdd = new ModalCharAddWindow({ saveCallback: saveCharacterClicked});
    const newCharButton = document.getElementById("FFXIPackageHelper_newCharButton");
    newCharButton.addEventListener("click", function (e) {
        modalCharAdd.open();
    });


    const charButtons = document.getElementsByClassName("FFXIPackageHelper_charButton");

    const refreshButton = document.getElementById("FFXIPackageHelper_refreshStatsButton");
    refreshButton.addEventListener("click", function () {

        Array.from(charButtons).forEach((button) => {
            if ( button.classList.contains('FFXIPackageHelper_charButtonselected') ) button.classList.toggle('FFXIPackageHelper_charButtonselected');
          });

        Data.resetStats();
        scrollToTop();
        Data.setHeaderCharacterDetails();

    });

    const removeChar = document.getElementById("FFXIPackageHelper_deleteCharButton");
    removeChar.addEventListener("click", function () {
        let charname = null;
        Array.from(charButtons).forEach((button) => {
            if ( button.classList.contains('FFXIPackageHelper_charButtonselected') ) charname = button.innerHTML;
          });
          //console.log(charname);
        if ( charname ) removeCharacter(charname);
    });

    if ( charButtons ) { addCharButtonEvents(charButtons); }

    const raceDropdown = document.getElementById("FFXIPackageHelper_equipsets_selectRace");
    raceDropdown.addEventListener("change", () =>  {
        //console.log(e.target.value);
        Data.updateStats();
        Data.setHeaderCharacterDetails();
        resetCharSelection();
    });

     Data.setHeaderCharacterDetails();
}

function selectCharClicked(charname){
    //console.log("selectCharClicked");

    const data = {
        action: "equipsets_selectchar",
        charname: charname,
    }

    show_RemoveCharacterButton();

    API.actionAPI(data, data.action, null, Data);
    scrollToTop();
}

function saveCharacterClicked(charName){
    //console.log(charName);

    const data = {
        action: "equipsets_savechar",
        race: document.getElementById("FFXIPackageHelper_equipsets_selectRace").value,
        merits: encodeURIComponent(btoa(Data.getMeritsData())),
        charname: charName
    }

    API.actionAPI(data, data.action, null, characterSaved);
    scrollToTop();
}

function removeCharacter(charname){
    //console.log("should remove " + charname);

    const data = {
        action: "equipsets_removechar",
        charname: charname
    }

    API.actionAPI(data, data.action, null, resetCharList);
    Data.resetStats();
    scrollToTop();
    Data.setHeaderCharacterDetails();

}

function addCharButtonEvents(charButtons){
    Array.from(charButtons).forEach((button) => {
        button.addEventListener("click", function () {

           selectCharClicked(button.innerHTML);

            Array.from(charButtons).forEach((btn) => {
                if ( btn.classList.contains('FFXIPackageHelper_charButtonselected') ) btn.classList.toggle('FFXIPackageHelper_charButtonselected');
            });

            button.classList.toggle('FFXIPackageHelper_charButtonselected');
        });
    });
}

function resetCharList(incCharsList){
    //remove all old list items
    clearCharList();

    //add new list items
    if (incCharsList){
        var charSelectDIV = document.getElementById("FFXIPackageHelper_equipsets_charSelect");
        incCharsList.map((details) => {
            var btn = document.createElement('button')
            btn.appendChild(document.createTextNode(details["charname"]));
            btn.id = 'FFXIPackageHelper_charButton_' + details["charname"];
            //btn.id = 'FFXIPackageHelper_charButton';
            btn.classList.add("FFXIPackageHelper_charButton");
            charSelectDIV.appendChild(btn);

            btn.addEventListener("click", function (){
                selectCharClicked(details["charname"]);
            });
        });
    }

    //add event listeners to new items
    const charButtons = document.getElementsByClassName("FFXIPackageHelper_charButton");
    if ( charButtons ) addCharButtonEvents(charButtons);
}

function characterSaved(results){
    const recentCharSaved = results[0];
    const newUserList = results[1];

    resetCharList(newUserList);

    const charButton = document.getElementById('FFXIPackageHelper_charButton_' + recentCharSaved);
    charButton.classList.toggle('FFXIPackageHelper_charButtonselected');

    show_RemoveCharacterButton();
    scrollToTop();
    Data.setHeaderCharacterDetails();
}

function clearCharList(){
    var charSelectDIV = document.getElementById("FFXIPackageHelper_equipsets_charSelect");
    const buttons = charSelectDIV.querySelectorAll('button');
    Array.from(buttons).forEach((button) => {
      if ( button.classList.contains("FFXIPackageHelper_charButton") ) charSelectDIV.removeChild(button);
    });
}

function show_RemoveCharacterButton(){
    const removeButton = document.getElementById("FFXIPackageHelper_deleteCharButton");
    removeButton.style.display = "inline-block";
}

// function remove_RemoveCharacterButton(){
//     const removeButton = document.getElementById("FFXIPackageHelper_deleteCharButton");
//     removeButton.style.display = "none";
// }

function resetCharSelection(){
    const removeButton = document.getElementById("FFXIPackageHelper_deleteCharButton");
    removeButton.style.display = "none";

    const selectedChars = document.getElementsByClassName("FFXIPackageHelper_charButtonselected");
    Array.from(selectedChars).forEach((btn) => {
        btn.classList.toggle('FFXIPackageHelper_charButtonselected');
    });
}

function scrollToTop() {
    //const top = document.getElementById("FFXIPackageHelper_dynamiccontent_changeMerits_top");
    window.scrollIntoView({ behavior: "smooth", block: "start" });
}






