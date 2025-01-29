
function handleTooltip(element, itemName){
    //console.log("handleTooltip");

    const parentElement = element.parentElement;
    if ( parentElement.classList.contains('hint--bottom') ){
        removeTooltip(parentElement);
    }
    if ( itemName == null ) return;

    //console.log("adding Tooltip")
    parentElement.classList.add('hint--bottom');
    parentElement.setAttribute("aria-label", itemName);


}

function removeTooltip(parElement){
    //console.log("remove Tooltip");

    parElement.classList.remove("hint--bottom");
    parElement.removeAttribute("aria-label");
}

function setupPageTooltips(){
    //console.log("here");
}

module.exports = { handleTooltip, removeTooltip, setupPageTooltips };