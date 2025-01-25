
const  _main = `<h2>Main Slot</h2>`;
const  _sub = `<h2>Sub Slot</h2>`;
const  _range = `<h2>Range Slot</h2>`;
const  _ammo = `<h2>Ammo Slot</h2>`;
const  _head = `<h2>Head Slot</h2>`;
const  _neck = `<h2>Neck Slot</h2>`;
const  _ear1 = `<h2>Ear1 Slot</h2>`;
const  _ear2 = `<h2>Ear2 Slot</h2>`;
const  _body = `<h2>Body Slot</h2>`;
const  _hands = `<h2>Hands Slot</h2>`;
const  _ring1 = `<h2>Ring1 Slot</h2>`;
const  _ring2 = `<h2>Ring2 Slot</h2>`;
const  _back = `<h2>Back Slot</h2>`;
const  _waist = `<h2>Waist Slot</h2>`;
const  _legs = `<h2>Legs Slot</h2>`;
const  _feet = `<h2>Feet Slot</h2>`;

let _default = `<p>Search for item...</p>`;
let _default2 = `<br><br><button style="float: left;" class="close-modal FFXIPackageHelper_dynamiccontent_customButton customButton_cancel">Cancel</button>`;


function searchInput(slot){
    return  "<input id=\"FFXIPackageHelper_equipsets_searchInput" + slot + "\" class=\"FFXIPackageHelper_dynamiccontent_textinput\" size=\"20\">";
}

function searchButton(slot){
    return "<button id=\"FFXIPackageHelper_equipsets_search" + slot + "\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button>";
}

function searchResults(slot){
    return "<br><br><div class=\"FFXIPackageHelper_equipsets_searchResults_div\" style=\"max-height: 350px;overflow-y: auto;\"><p></p><dl id=\"FFXIPackageHelper_equipsets_searchResults" + slot + "\" ></dl></div>";
}

function removeItemButton(slot){
    const newElement = document.createElement("button");
    newElement.id = `FFXIPackageHelper_equipsets_removeButton${slot}`;
    newElement.innerText = "Remove Item";
    newElement.classList.add('close-modal'); // FFXIPackageHelper_dynamiccontent_customButton customButton_removeItem
    newElement.classList.add('FFXIPackageHelper_dynamiccontent_customButton');
    newElement.classList.add('customButton_removeItem');
    //newElement.setAttribute("style", "display: none; float: right; background-color:rgba(244, 67, 54, 0.50);");
    return newElement;
}

function searchEquip(slot){
    return {
        action: "equipsets_search",
        search: document.getElementById("FFXIPackageHelper_equipsets_searchInput" + slot).value,
        mlvl: document.getElementById("FFXIPackageHelper_equipsets_selectMLevel").value,
        mjob: document.getElementById("FFXIPackageHelper_equipsets_selectMJob").value,
        slot: slot
    };
  }

//  function changeEquip(stats, equipment){
//     return {
//         action: "equipsets_change",
//         equipment: "asdf"
//     };
//   }

class ModalWindow {
    //searchCallback = null;

    constructor(content, options = {}) {
        this.slot = content;
        let def = _default + searchInput(this.slot) + searchButton(this.slot) + searchResults(this.slot) + _default2;

        if ( this.slot == 0 ) this.content = _main + def;
        else if ( this.slot == 1 ) this.content = _sub + def;
        else if ( this.slot == 2 ) this.content = _range + def;
        else if ( this.slot == 3 ) this.content = _ammo + def;
        else if ( this.slot == 4 ) this.content = _head + def;
        else if ( this.slot == 5 ) this.content = _neck + def;
        else if ( this.slot == 6 ) this.content = _ear1 + def;
        else if ( this.slot == 7 ) this.content = _ear2 + def;
        else if ( this.slot == 8 ) this.content = _body + def;
        else if ( this.slot == 9 ) this.content = _hands + def;
        else if ( this.slot == 10 ) this.content = _ring1 + def;
        else if ( this.slot == 11 ) this.content = _ring2 + def;
        else if ( this.slot == 12 ) this.content = _back + def;
        else if ( this.slot == 13 ) this.content = _waist + def;
        else if ( this.slot == 14 ) this.content = _legs + def;
        else if ( this.slot == 15 ) this.content = _feet + def;

        this.options = {
            overlay: true,
            closeOnOverlayClick: false,
            ...options
        };
        this.createModal();
    }
  
    createModal() {
        this.modal = document.createElement('div');
        this.modal.classList.add('modal');


        if (this.options.overlay) {
            this.overlay = document.createElement('div');
            this.overlay.classList.add('overlay');
            this.modal.appendChild(this.overlay);
        }

        const contentWrapper = document.createElement('div');
        contentWrapper.id = `FFXIPackageHelper_equipsets_contentWrapper${this.slot}`;
        contentWrapper.classList.add('modal-content');
        contentWrapper.innerHTML = this.content;
        contentWrapper.appendChild(removeItemButton(this.slot));
        this.modal.appendChild(contentWrapper);

        document.body.appendChild(this.modal);

        this.addEventListeners();
    }
  
    addEventListeners() {
        if (this.options.closeOnOverlayClick) {
            this.overlay.addEventListener('click', () => this.close());
        }
  
        const closeButtons = this.modal.querySelectorAll('.close-modal');
            closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                if ( button.id == `FFXIPackageHelper_equipsets_removeButton${this.slot}` ){
                    //console.log(button);
                    this.options.returnCallback(0, this.slot, this);
                }

                this.close();
            });
        });

        const searchButton = document.getElementById("FFXIPackageHelper_equipsets_search" + this.slot);
        searchButton.addEventListener('click', (e) =>  {
            this.options.searchCallback(searchEquip(this.slot), "equipsets_search", null, this);
        });
    }
  
    returnCallback(results){

        const slot = Number(results[1]);
        const arr = results[0];
        const idname = "FFXIPackageHelper_equipsets_searchResults" + slot;

        let commentNode = document.querySelectorAll(".FFXIPackageHelper_equipsets_searchResults_div")[this.slot].getElementsByTagName('p')[0];

        //remove all list items and start over
        var dl = document.getElementById(idname);
        dl.innerHTML = '';

        if ( results[0].length == 0 ) {
            commentNode.innerText = "No results found";
            return;
        }


        commentNode.innerText = "Click item to add to set...\n";

        for ( let i = 0; i < arr.length; i++ ){
            //console.log(arr[i]["name"]);

            var dt = document.createElement("dt");
            dt.onmouseover = function() { this.style="background-color:#00c4ff45;"; };
            dt.onmouseout = function() { this.style="background-color:none;"; };

            var t = document.createTextNode(arr[i]["name"]);

            var iconurl = mw.config.get( 'wgScript' ) + "/Special:Filepath/itemid_" + arr[i]["id"] + ".png";

            var img = document.createElement("img");
            img.src=iconurl;
            img.width=12;
            img.height=12;

            dt.addEventListener('click', () => {
                // need item id
                //console.log("clicked: " + arr[i]["id"]);
                document.getElementById(idname).innerHTML = "";
                this.options.returnCallback(arr[i]["id"], slot, this);
            });

            dt.appendChild(img);
            dt.appendChild(t);
            dl.appendChild(dt);
        }
    }

    // searchClicked() {
    //     console.log("search clicked: " + this.slot);
    //     this.options.searchCallback(searchEquip(), "equipsets_search", null)
    // }

    open(itemid) {
        let rButton = document.getElementById(`FFXIPackageHelper_equipsets_removeButton${this.slot}`);
        if ( itemid != 0 ) rButton.style.display = "block";
        else  rButton.style.display = "none";

        this.modal.classList.add('open');
    }
  
    close() {
        document.getElementById("FFXIPackageHelper_equipsets_searchResults" + this.slot).innerHTML = "";

        let commentNode = document.querySelectorAll(".FFXIPackageHelper_equipsets_searchResults_div")[this.slot].getElementsByTagName('p')[0];
        commentNode.innerText = "";

        document.getElementById("FFXIPackageHelper_equipsets_searchInput" + this.slot).value = "";

        this.modal.classList.remove('open');
    }
}


module.exports = ModalWindow;