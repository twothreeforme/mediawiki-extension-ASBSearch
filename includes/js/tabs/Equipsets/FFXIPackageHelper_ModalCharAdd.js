
class ModalCharAddWindow {
    constructor(options = {}) {

        this.options = {
            overlay: true,
            ...options
        };
        this.createModal();
    }
  
    createModal() {
        this.modal = document.createElement('div');
        this.modal.classList.add('modal');

        const contentWrapper = document.createElement('div');
        //contentWrapper.id = `FFXIPackageHelper_equipsets_contentWrapperUserChars`;
        contentWrapper.classList.add('modal-content');

        const br = document.createElement("br");
        const titleText = document.createElement("h2");
        titleText.textContent = 'Save Character';
        contentWrapper.appendChild(titleText);
        contentWrapper.appendChild(br);

        const descText = document.createElement("span");
        descText.textContent = 'Saves the current character RACE and MERITS.';
        descText.setAttribute('style', 'font-size: .80em;font-style: italic;whiteSpace:pre-line;');
        contentWrapper.appendChild(descText);

        contentWrapper.appendChild(br);

        const inputWrapper = document.createElement('div');
        inputWrapper.setAttribute('style', 'display: inline-flex; gap: 20px;');

            const closeButton = document.createElement('button');
            closeButton.id = 'FFXIPackageHelper_dynamiccontent_closeCharsAdd';
            closeButton.classList.add("close-modal", "FFXIPackageHelper_dynamiccontent_customButton", "customButton_cancel");
            closeButton.textContent = 'Cancel';

            const inputElement = document.createElement('input');
            inputElement.type = 'text';
            inputElement.id = 'FFXIPackageHelper_dynamiccontent_addCharInput';
            //inputElement.setAttribute('style', 'margin-right: auto;margin-left: auto;display: block;');
            inputElement.placeholder = 'Character Name';
            inputElement.maxLength = 25;

            const saveButton = document.createElement('button');
            saveButton.id = 'FFXIPackageHelper_dynamiccontent_saveChar';
            saveButton.classList.add("FFXIPackageHelper_dynamiccontent_customButton");
            saveButton.textContent = 'Save';

            inputWrapper.appendChild(inputElement);
            inputWrapper.appendChild(saveButton);
            inputWrapper.appendChild(closeButton);

        contentWrapper.appendChild(inputWrapper);
        contentWrapper.appendChild(br);

        const descText2 = document.createElement("span");
        descText2.setAttribute('style', 'font-size: .80em;font-style: italic;');
        descText2.textContent = 'Maximum of 25 characters.Alpha-numeric chars only.';

        contentWrapper.appendChild(descText2);

        this.modal.appendChild(contentWrapper);

        document.body.appendChild(this.modal);

        this.addEventListeners();
    }
  
    addEventListeners() {
        const closeButton =  document.getElementById("FFXIPackageHelper_dynamiccontent_closeCharsAdd" );
        closeButton.addEventListener('click', (e) => {
                this.close();
            });

        const saveButton = document.getElementById("FFXIPackageHelper_dynamiccontent_saveChar");
        saveButton.addEventListener('click', (e) =>  {
            const inputElement = document.getElementById("FFXIPackageHelper_dynamiccontent_addCharInput");
            this.options.saveCallback(inputElement.value);
            this.close();
        });

        const inputElement = document.getElementById("FFXIPackageHelper_dynamiccontent_addCharInput");
        inputElement.addEventListener('input', function(event) {
            const value = event.target.value;
            const sanitizedValue = value.replace(/[^a-zA-Z0-9]/g, '');
            event.target.value = sanitizedValue;
          });
    }
  

    open() {
        const inputElement = document.getElementById("FFXIPackageHelper_dynamiccontent_addCharInput");
        inputElement.value = "";

        this.modal.classList.add('open');
        inputElement.focus();

    }
  
    close() {
        this.modal.classList.remove('open');
    }
}


module.exports = ModalCharAddWindow;