

class ModalCombatSimMobSelect {
    content = {
        divID: "FFXIPackageHelper_equipsets_combatSimMobSelect",
        closeButtonID: "FFXIPackageHelper_dynamiccontent_closeCombatSimMobSelect"
    }

    constructor(options = {}) {
        
        this.options = {
            overlay: true,
            selectMobCallback: null,
            ...options
        };
    }
  
    createModal(moblist) {
        this.modal = document.createElement('div');
        this.modal.classList.add('modal');

        const contentWrapper = document.createElement('div');
        contentWrapper.id = this.content.divID;
        contentWrapper.classList.add('modal-content');

        //let closeHTML = `<br><br><button style="float:left;" id="FFXIPackageHelper_dynamiccontent_closeCombatSimMobSelect" class="close-modal FFXIPackageHelper_dynamiccontent_customButton customButton_cancel">Cancel</button>`;
        let closeHTML = `<br><br><button style="float:left;" id="${this.content.closeButtonID}" class="close-modal FFXIPackageHelper_dynamiccontent_customButton customButton_cancel">Cancel</button>`;
        // let saveCharHTML = `<br><button class="FFXIPackageHelper_dynamiccontent_customButton">Save Character</button>`;

        contentWrapper.innerHTML = "<h2>Select Mob</h2>" + moblist + "<br>" + closeHTML;

        this.modal.appendChild(contentWrapper);
        document.body.appendChild(this.modal);

        this.addEventListeners();
    }
  
    addEventListeners() {
        const closeButton =  document.getElementById(this.content.closeButtonID);
        closeButton.addEventListener('click', (e) => {
                this.close();
            });


        let resultsListDIV = document.getElementById("FFXIPH_equipsets_combatsim_mobandzonelist");
        let listItems = resultsListDIV.getElementsByTagName('tr');
        for (let i = 1; i < listItems.length; i++) {
            listItems[i].onmouseover = function() { this.style="background-color:#00c4ff45;"; };
            listItems[i].onmouseout = function() { this.style="background-color:none;"; };

            listItems[i].addEventListener('click', () => {
                let cells = listItems[i].getElementsByTagName('td');
                this.options.selectMobCallback(cells[0].innerText, cells[1].innerText); 
                this.close();
            });
        }
    }
  

    open(moblist) {
        this.createModal(moblist);
        this.modal.classList.add('open');
    }
  
    close() {
        this.modal.classList.remove('open');
    }
}


module.exports = ModalCombatSimMobSelect;