import { ROOT_DIR } from "../config/config.js";

export default function DropDown(
    {
        options,
        selectedOptionId,
        onSelect
    } = props)
{
    const dropDown = document.createElement("div");
    dropDown.classList.add("drop-down-input");
    
    let selectedOptionValue = "";
    const optionsHTML = options.reduce((acc, option) => {
        if(option.id === selectedOptionId)
            selectedOptionValue = option.value;
        return acc += optionTemplate(option.id, option.value, option.id === selectedOptionId);
    }, "");

    dropDown.innerHTML =
    `
        <button class="js-header text-input" data-option-id="${selectedOptionId}" data-action="drop-down-toggle-expand">
            <span>${selectedOptionValue}</span>
            <svg>
                <use href="${ROOT_DIR}/assets/img/icons/icons.svg#arrow_down"></use>
            </svg>
        </button>
        <div class="options tile-container">
            ${optionsHTML}
        </div>
    `;

    bindDropDownEvents(dropDown, onSelect);

    return dropDown;
}

function optionTemplate(id, value, isChecked)
{
    return `
        <button class="${isChecked ? "checked" : ""}" data-option-id="${id}" data-action="drop-down-select-option">
            <span>${value}</span>
            <svg>
                <use href="${ROOT_DIR}/assets/img/icons/icons.svg#check"></use>
            </svg>
        </button>
    `;
}

function bindDropDownEvents(dropDown, onSelect)
{
    dropDown.addEventListener("click", (e) => {
        const actionElem = e.target.closest("[data-action]");

        if(!actionElem)
            return;

        const action = actionElem.dataset.action;
        switch(action)
        {
            case "drop-down-select-option":
                const headerBtn = dropDown.querySelector(".js-header");

                const oldClickedOptionId = headerBtn.dataset.optionId;
                const clickedOptionId = actionElem.dataset.optionId;

                dropDown.querySelector(`.options button[data-option-id="${oldClickedOptionId}"]`).classList.remove("checked");
                dropDown.querySelector(`.options button[data-option-id="${clickedOptionId}"]`).classList.add("checked");

                const clickedOptionValue = actionElem.querySelector("span").textContent;

                headerBtn.dataset.optionId = clickedOptionId;
                headerBtn.querySelector("span").textContent = clickedOptionValue;

                onSelect(clickedOptionId, clickedOptionValue);
            break;
            
            case "drop-down-toggle-expand":
                actionElem.parentElement.classList.toggle("expanded");
            break;
        }
        

    })
}