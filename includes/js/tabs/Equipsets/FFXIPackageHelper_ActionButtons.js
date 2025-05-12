


function hideButton(button){ 
    button.style.visibility = "hidden"; 
    //console.log("hideButton", button);
}
function showButton(button) { 
    button.style.visibility = "visible"; 
    //console.log("showButton", button);
}

module.exports = { hideButton, showButton }
