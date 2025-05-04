
let _default = `<br><br><button style="float: left;" class="close-modal FFXIPackageHelper_dynamiccontent_customButton customButton_cancel">Cancel</button>`;


// function searchInput(slot){
//     return  "<input id=\"FFXIPackageHelper_equipsets_searchInput" + slot + "\" class=\"FFXIPackageHelper_dynamiccontent_textinput\" size=\"20\">";
// }

// function searchButton(slot){
//     return "<button id=\"FFXIPackageHelper_equipsets_search" + slot + "\" class=\"FFXIPackageHelper_dynamiccontent_customButton\">Search</button>";
// }

// function searchResults(slot){
//     return "<br><br><div class=\"FFXIPackageHelper_equipsets_searchResults_div\" style=\"max-height: 350px;overflow-y: auto;\"><p></p><dl id=\"FFXIPackageHelper_equipsets_searchResults" + slot + "\" ></dl></div>";
// }

class ModalSetsWindow {
    //searchCallback = null;

    constructor(options = {}) {
        this.content = `<h2>Permanently remove this set?</h2>` + _default ;

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
        contentWrapper.id = `FFXIPackageHelper_equipsets_contentWrapperUserSets`;
        contentWrapper.classList.add('modal-content');
        contentWrapper.innerHTML = this.content;
        //contentWrapper.appendChild(removeItemButton(this.slot));
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

                //this.close();
            });
        });

        // const searchButton = document.getElementById("FFXIPackageHelper_equipsets_search" + this.slot);
        // searchButton.addEventListener('click', (e) =>  {
        //     this.options.searchCallback(searchEquip(this.slot), "equipsets_search", null, this);
        // });
    }
  
    // returnCallback(results){

    //     const slot = Number(results[1]);
    //     const arr = results[0];
    //     const idname = "FFXIPackageHelper_equipsets_searchResults" + slot;

    //     let commentNode = document.querySelectorAll(".FFXIPackageHelper_equipsets_searchResults_div")[this.slot].getElementsByTagName('p')[0];

    //     //remove all list items and start over
    //     var dl = document.getElementById(idname);
    //     dl.innerHTML = '';

    //     if ( results[0].length == 0 ) {
    //         commentNode.innerText = "No results found";
    //         return;
    //     }


    //     commentNode.innerText = "Click item to add to set...\n";

    //     for ( let i = 0; i < arr.length; i++ ){
    //         //console.log(arr[i]["name"]);

    //         var dt = document.createElement("dt");
    //         dt.onmouseover = function() { this.style="background-color:#00c4ff45;"; };
    //         dt.onmouseout = function() { this.style="background-color:none;"; };

    //         var t = document.createTextNode(arr[i]["name"]);

    //         var iconurl = mw.config.get( 'wgScript' ) + "/Special:Filepath/itemid_" + arr[i]["id"] + ".png";

    //         var img = document.createElement("img");
    //         img.src=iconurl;
    //         img.width=20;
    //         img.height=20;

    //         dt.addEventListener('click', () => {
    //             // need item id
    //             //console.log("clicked: " + arr[i]["id"]);
    //             document.getElementById(idname).innerHTML = "";
    //             this.options.returnCallback(arr[i]["id"], slot, this);
    //         });

    //         dt.appendChild(img);
    //         dt.appendChild(t);
    //         dl.appendChild(dt);
    //     }
    // }

    open() {
        this.modal.classList.add('open');
    }
  
    close() {
        this.modal.classList.remove('open');
    }
}


module.exports = ModalSetsWindow;