var API = require("./FFXIPackageHelper_ActionAPI.js");
var Data = require("./FFXIPackageHelper_DataManager.js");

const importButton = document.getElementById("FFXIPackageHelper_importLuaButton");

module.exports.setLinks = function (){

    const verifyButton = document.getElementById("FFXIPackageHelper_verifyluabutton");
    verifyButton.addEventListener("click", function () {
        const textarea = document.getElementById("form_importlua");

        if ( textarea.value == "" ) {
             mw.notify( 'No input text found', { autoHide: true,  type: 'error' } );
        }
        else {
            var jsonString = allSets(textarea.value);
            //console.log(jsonString);
            if ( jsonString == null ) mw.notify( 'Lua not recognized', { autoHide: true,  type: 'error' } );
            else {
                API.actionAPI( verifyLuaData(jsonString), "importlua_verify", "FFXIPackageHelper_verifyluabutton", verifyResult);
            }
        }
    });

    importButton.addEventListener("click", function () {
        if ( !isCharSet() ) {
            mw.notify( 'Import failed - Set up character job/level in \'Gear Sets\' tab first', { autoHide: true,  type: 'error' } );
            return;
        }

        const luaImportReady = document.getElementById("FFXIPackageHelper_importlua_importReady");
        if ( luaImportReady.innerText.trim() == '' ) {
            mw.notify( 'Import failed - Missing base64 encoded luaImportReady text', { autoHide: true,  type: 'error' } );
            return;
        }

        importLuaData(luaImportReady.innerText);
    });
}

function verifyLuaData(luaText){
    luaText = JSON.stringify(luaText); // must convert to string before encoding

    return {
        action: "importlua_verify",
        importlua: encodeURIComponent(btoa(luaText))
    }
}

function importLuaData(luaImportEncodedStr){
    const data = Data.getStatsData(luaImportEncodedStr);
    data.action = "equipsets_change",

    API.actionAPI(data, data.action, null, importResult);
}

function convertSet(luaObjectStr) {
    // Replace `=` with `:` and format quotes
    let jsonStr = luaObjectStr
        //.replace(/local\s+\w+\s*=\s*/, '') // Remove "local sets ="
        .replace(/\n+\t+\s*/g, '') // Remove spaces, new lines, and tabs
        .replace(/(\w+)\s*=\s*/g, '"$1": ') // Replace key=value with "key":
        .replace(/"(\w+)":\s*'([\w\s*-_`']+)'/g, '"$1":"$2"') // Ensure string values are quoted
        .replace(/\\'/g, '') // Handle backslash w/apostrophe - for JSON.parse and SQL query
        .replace(/(\w+)\s*=\s*\{/, '"$1": {') // Handle object start
        .replace(/,?\s*}\s*[;,]*/g, '}') // Properly handle object end with braces, comma, and semicolon

    //console.log(jsonStr);
    return jsonStr;
}

function allSets(luaObjectStr){
    let jsonStr = luaObjectStr.replace(/local\s+\w+\s*=\s*/, ''); // Remove "local sets ="

    function handleSet(luaObjectStr){
        const index = luaObjectStr.indexOf("="); // Separate title from set details
        const setTitle = luaObjectStr.slice(0, index).replace(/\s*/, '').replace(/\s+$/, '');  // Remove spaces at beginning and end of the title

        var setDetails = luaObjectStr.slice(index + 1).replace(/;/g, ''); // Remove semi colon at the end
        setDetails = JSON.parse( convertSet(setDetails) ); // Convert the set string into JSON object

        if ( isValidJSONSet(setDetails) ) return [ setTitle, setDetails];
        else return null;
    }

    if ( jsonStr == luaObjectStr ) { // Lua string is only 1 set
        var setJSON = handleSet(luaObjectStr);
        var json = {}
        if ( setJSON ) { json[setJSON[0]] = setJSON[1]; }
        return json;
    }
    else { // Lua string has multiple sets
        mw.notify( 'Importing multiple sets is not available yet', { autoHide: true,  type: 'error' } );
        return null;
    }
}

function isValidJSONSet(jsonDetails){
    const validList = [ "Main", "Sub", "Range", "Ammo", "Head", "Neck", "Ear1", "Ear2", "Body", "Hands", "Ring1", "Ring2", "Back", "Waist", "Legs", "Feet" ];

    Object.keys(jsonDetails).forEach(key => {
        //console.log(key);
        if ( !validList.includes( key.charAt(0).toUpperCase()) ) { return false; } });
    return true;
}

function verifyResult(result){
    const resultsDIV = document.getElementById("FFXIPackageHelper_importlua_verificationResults");
    resultsDIV.innerHTML = "";
    resultsDIV.innerHTML += result['verifyresults'];

    const importComment = document.getElementById("FFXIPackageHelper_importLuaComment");
    if ( isCharSet() ){
        importComment.innerHTML = "";
        importButton.disabled = false; }
    else {
        importComment.innerHTML = "<i>Requires job and level selected on Gear Sets tab.</i>";
    }
    const importReady = document.getElementById("FFXIPackageHelper_importlua_importReady");
    importReady.innerText += result['luaImportReady'];
}

function importResult(){
    const tabsButton_equipsets = document.getElementById("FFXIPackageHelper_tabs_equipsets");
    tabsButton_equipsets.click();

    resetPage();
}

function resetPage(){
    const resultsDIV = document.getElementById("FFXIPackageHelper_importlua_verificationResults");
    resultsDIV.innerHTML = "";

    const importReady = document.getElementById("FFXIPackageHelper_importlua_importReady");
    importReady.innerText = "";

    const textarea = document.getElementById("form_importlua");
    textarea.value = "";

    importButton.disabled = true;
}

function isCharSet(){
    if ( document.getElementById("FFXIPackageHelper_equipsets_selectMLevel").value == 0 ||
            document.getElementById("FFXIPackageHelper_equipsets_selectMJob").value == 0 ||
            document.getElementById("FFXIPackageHelper_equipsets_selectSJob").value == 0 )
            return false;
    else return true;
}



