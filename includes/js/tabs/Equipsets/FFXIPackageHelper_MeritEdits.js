module.exports.setLinks = function (callback){
    
    // good test
    // callback("blah");

    const counterbox = document.querySelectorAll('#FFXIPackageHelper_dynamiccontent_counterbox');
    for (var c = 0; c < counterbox.length; c++) {
        //console.log(counterbox[c].querySelectorAll(".FFXIPackageHelper_dynamiccontent_incrementButton"));
        const buttons = counterbox[c].querySelectorAll(".FFXIPackageHelper_dynamiccontent_incrementButton");
        const input = counterbox[c].querySelector(".FFXIPackageHelper_dynamiccontent_incrementInput");
        buttons[0].addEventListener("click", function (e) {
            changeValue(input, -1);
        });
        buttons[1].addEventListener("click", function (e) {
            changeValue(input, 1);
        });
    }

    // Set all associated layouts for the Edit button and associated events
    const editMerits = document.getElementById("FFXIPackageHelper_dynamiccontent_changeMerits");
    editMerits.addEventListener("click", function (e) {
        // Adjust Edit button
        if ( editMerits.innerText == "Edit") {
            editMerits.innerText = "Apply";
            editMerits.className = "FFXIPackageHelper_dynamiccontent_customButton";
        }
        else {
            editMerits.innerText = "Edit";
            editMerits.className = "FFXIPackageHelper_dynamiccontent_shareButton";
        }

        // Adjust merit edit buttons for all stats
        for (var c = 0; c < counterbox.length; c++) {
      
            let meritschildren = counterbox[c].children;
            for (var i = 0; i < meritschildren.length; i++) {
                //console.log(meritschildren[i].tagName);
                if (meritschildren[i].tagName == "BUTTON" ){
                    //console.log(meritschildren[i]);
                    meritschildren[i].classList.toggle('incrementButton_show');
                }
            }
        }
    });
}

function changeValue(forInput, val){
    //console.log(forStat, val);
    if ( Number(forInput.value) + val < 0 ) forInput.value = 0;
    else forInput.value = Number(forInput.value) + val;
}
