var API = require("./FFXIPackageHelper_ActionAPI.js");

module.exports.setLinks = function (){

    const importButton = document.getElementById("FFXIPackageHelper_importluabutton");
    importButton.addEventListener("click", function () {
        var textarea = document.getElementById("form_importlua");
        if ( textarea.value == "" ) console.log("nothing here");
        else console.log(textarea.value);
    });

}
