import { ROOT_DIR } from "../config/config.js";

export default function Category(
    {
        categoryId,
        title,
        isAdmin,
        isInEditStage
    } = props)
{
    if(isAdmin !== isInEditStage && isAdmin === false)
        throw new Error("To be in edit stage you need to be the admin");

    const category = document.createElement("div");
    category.classList.add("category");
    category.setAttribute("data-category-id", categoryId);
    category.innerHTML = 
    `
        ${isInEditStage ? editStageTemplate(title) : categoryTemplate(isAdmin)}
    `;
    if(!isInEditStage)
        category.querySelector(".title").textContent = title;
    return category;
}
function categoryTemplate(isAdmin)
{
    return `
        <div class="title"></div>
        ${isAdmin ? optionsTemplate() : ""}
    `;
}
function optionsTemplate()
{
    return `
        <div class="options">
            <button class="button button--blue" data-action="category-edit">
                <svg>
                    <use href="${ROOT_DIR}/assets/img/icons/icons.svg#edit"></use>
                </svg>
            </button>
            <button class="button button--trash" data-action="category-delete">
                <svg>
                    <use href="${ROOT_DIR}/assets/img/icons/icons.svg#delete"></use>
                </svg>
            </button>
        </div>
    `;
}

function editStageTemplate(title)
{
    return `
        <form class="form" data-action="edit-category">
            <input type="text" class="text-input" value="${title}">
            <button class="button button--hover-fill-blue border" data-action="close-edit-category">
                <svg>
                    <use href="../assets/img/icons/icons.svg#close"></use>
                </svg>
            </button>
            <button class="button button--white-inverse border" data-action="accept-edit-category">
                <svg>
                    <use href="../assets/img/icons/icons.svg#check"></use>
                </svg>
            </button>
        </form>
    `;
}