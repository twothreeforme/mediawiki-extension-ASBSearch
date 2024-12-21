
const  _main = `<h2>Main Slot</h2>`;
const  _sub = `<h2>Sub Slot</h2>`;
const  _range = `<h2>Range Slot</h2>`;
const  _ammo = `<h2>Ammo Slot</h2>`;
const  _head = `<h2>Head Slot</h2>`;
const  _neck = `<h2>Neck Slot</h2>`;
const  _ear1 = `<h2>Ear1 Slot</h2>`;
const  _ear2 = `<h2>Ear2 Slot</h2>`;
const  _body = `<h2>Body Slot</h2>`;
const  _hands = `<h2>Body Slot</h2>`;
const  _ring1 = `<h2>Ring1 Slot</h2>`;
const  _ring2 = `<h2>Ring2 Slot</h2>`;
const  _back = `<h2>Back Slot</h2>`;
const  _waist = `<h2>Waist Slot</h2>`;
const  _legs = `<h2>Legs Slot</h2>`;
const  _feet = `<h2>Feet Slot</h2>`;

let _default = `
    <p>Search for item...</p>`;
    //<input id="FFXIPackageHelper_equipsets_searchInput" class=\"FFXIPackageHelper_dynamiccontent_textinput\" size=\"25\">`;

    //<button id="FFXIPackageHelper_equipsets_search" >Search</button>
let _default2 = `
    <button class="close-modal">Use</button>
    <br><br>
    <button class="close-modal">Cancel</button>`;

function searchInput(slot){
    return  "<input id=\"FFXIPackageHelper_equipsets_searchInput" + slot + "\" class=\"FFXIPackageHelper_dynamiccontent_textinput\" size=\"20\">";
}

function searchButton(slot){
    return "<button id=\"FFXIPackageHelper_equipsets_search" + slot + "\">Search</button>";
}

function searchResults(slot){
    return "<br><div id=\"FFXIPackageHelper_equipsets_searchResults_div\" style=\"max-height: 350px;overflow-y: auto;\"><dl id=\"FFXIPackageHelper_equipsets_searchResults" + slot + "\" ></dl></div>";
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


class ModalWindow {
    //searchCallback = null;

    constructor(content, options = {}) {
        this.slot = content;
        let def = _default + searchInput(this.slot) + searchButton(this.slot) + searchResults(this.slot) + _default2;

        if ( this.slot == 0 ) this.content = _main + def;
        if ( this.slot == 1 ) this.content = _sub + def;
        if ( this.slot == 2 ) this.content = _range + def;
        if ( this.slot == 3 ) this.content = _ammo + def;
        if ( this.slot == 4 ) this.content = _head + def;
        if ( this.slot == 5 ) this.content = _neck + def;
        if ( this.slot == 6 ) this.content = _ear1 + def;
        if ( this.slot == 7 ) this.content = _ear2 + def;
        if ( this.slot == 8 ) this.content = _body + def;
        if ( this.slot == 9 ) this.content = _hands + def;
        if ( this.slot == 10 ) this.content = _ring1 + def;
        if ( this.slot == 11 ) this.content = _ring2 + def;
        if ( this.slot == 12 ) this.content = _back + def;
        if ( this.slot == 13 ) this.content = _waist + def;
        if ( this.slot == 14 ) this.content = _legs + def;
        if ( this.slot == 15 ) this.content = _feet + def;

        this.options = {
            overlay: true,
            closeOnOverlayClick: true,
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
      contentWrapper.classList.add('modal-content');
      contentWrapper.innerHTML = this.content;
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
            button.addEventListener('click', () => {
                this.close();
            });
        });

        const searchButton = document.getElementById("FFXIPackageHelper_equipsets_search" + this.slot);
        searchButton.addEventListener('click', (e) =>  {
            //console.log("search clicked: " + this.slot);
            this.options.searchCallback(searchEquip(this.slot), "equipsets_search", null, returnCallback())
        });
        //console.log(searchButton.data("events"));

    }
  
    returnCallback(results){
        const slot = results[1];
        const arr = results[0];
        const idname = "FFXIPackageHelper_equipsets_searchResults" + slot;
        var dl = document.getElementById(idname);
        dl.innerHTML = "";

        for ( let i = 0; i < arr.length; i++ ){
            //console.log(arr[i]["name"]);

            var dt = document.createElement("dt");
            dt.onmouseover = function() { this.style="background-color:#00c4ff45;"; };
            dt.onmouseout = function() { this.style="background-color:none;"; };

            var t = document.createTextNode(arr[i]["name"]);
            var iconurl = "/index.php/Special:Filepath/itemid_" + arr[i]["id"] + ".png";

            var img = document.createElement("img");
            img.src=iconurl;
            img.width=12;
            img.height=12;

            dt.addEventListener('click', () => {
                // need item id
                console.log("clicked: " + arr[i]["id"]);
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

    open() {
        this.modal.classList.add('open');
    }
  
    close() {
        //this.searchButton.removeEventListener('click', this.searchClicked());
        this.modal.classList.remove('open');
    }
}

module.exports = ModalWindow;