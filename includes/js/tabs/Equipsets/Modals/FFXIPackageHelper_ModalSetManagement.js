
class ModalSetManagement {
    //searchCallback = null;

    constructor(options = {}) {

        this.options = {
            overlay: true,
            //closeOnOverlayClick: false,
            setID: null,
            setName: null,
            removeCallback: null,
            returnCallback: null,
            ...options
        };

        //console.log(this.options);
    }
  
    createModal() {
        this.modal = document.createElement('div');
        this.modal.classList.add('modal','modal-remove');

        if (this.options.overlay) {
            this.overlay = document.createElement('div');
            this.overlay.classList.add('overlay');
            this.modal.appendChild(this.overlay);
        }

        this.content = `<h3>Permanently remove this set?</h3>`;
        this.content += `<h3>` + this.options.setName + `</h3><i>This action cannot be undone</i><br><br>`;

        const contentWrapper = document.createElement('div');
        contentWrapper.id = `FFXIPackageHelper_equipsets_contentWrapperUserSets`;
        contentWrapper.classList.add('modal-content');
        contentWrapper.innerHTML = this.content;
        //contentWrapper.appendChild(removeItemButton(this.slot));

            const closeButton = document.createElement('button');
            closeButton.id = 'FFXIPackageHelper_dynamiccontent_closeCharsRemove';
            closeButton.classList.add("close-modal", "FFXIPackageHelper_dynamiccontent_customButton", "customButton_cancel");
            closeButton.textContent = 'Cancel';

            const removeButton = document.createElement('button');
            //removeButton.id = 'FFXIPackageHelper_dynamiccontent_removeChar';
            removeButton.classList.add("FFXIPackageHelper_dynamiccontent_customButton", "customButton_removeItem");
            removeButton.textContent = 'Remove';

            contentWrapper.appendChild(removeButton);
            contentWrapper.appendChild(closeButton);

        this.modal.appendChild(contentWrapper);

        document.body.appendChild(this.modal);

        //Show Remove button
        // const removeButton = this.modal.querySelector('#FFXIPackageHelper_deleteSetButton');
        // removeButton.style.visibility = "visible";
        // console.log(removeButton);

        this.addEventListeners();
    }
  
    addEventListeners() {
        // if (this.options.closeOnOverlayClick) {
        //     this.overlay.addEventListener('click', () => this.close());
        // }

        const closeButtons = this.getCloseButtons();
            closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // if ( button.id == `FFXIPackageHelper_equipsets_removeButton${this.slot}` ){
                //     //console.log(button);
                //     this.options.returnCallback(0, this.slot, this);
                // }

                this.close();
            });
        });

        const removeButtons = this.getRemoveButton();
            removeButtons.forEach(button => {
                button.addEventListener('click', (e) =>  {
                const data = {
                    action: "equipsets_removeset",
                    usersetid: this.options.setID,
                }

                this.options.removeCallback(data, data.action, null, this.options.returnCallback);
                this.close();
            });
        });
    }
  
    destroy(){
        const oldModals = this.modal.querySelectorAll('.modal-content');
        oldModals.forEach(modal => {
            const deadModal = modal.cloneNode(true);
            modal.parentNode.replaceChild(deadModal, modal);
            //console.log(modal);
        });
    }

    open(setID, setName) {
        this.options.setID = setID;
        this.options.setName = setName;

        this.createModal();
        this.modal.classList.add('open');
    }
  
    close() {
        this.modal.classList.remove('open');
        this.destroy();

        const removeModal = document.querySelectorAll('.modal-remove');
        removeModal.forEach(modal => { modal.remove(); });
    }

    getCloseButtons(){ return this.modal.querySelectorAll('.close-modal'); }
    getRemoveButton(){ return this.modal.querySelectorAll('.customButton_removeItem'); }
}


module.exports = ModalSetManagement;