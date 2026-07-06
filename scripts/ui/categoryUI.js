import Category from "../components/Category.js";
import NewPostCategory from "../components/NewPostCategories.js";
import { EMPTY_LIST_TEXT } from "../config/config.js";

export function renderCategories(container, categories)
{
    container.innerHTML = "";

    categories.forEach(category => {
        container.appendChild(Category(category));
    });
    if(!categories.length)
        container.innerText = EMPTY_LIST_TEXT;
}

export function renderCategoriesNewPost(container, categories)
{
    container.innerHTML = "";
    console.log(categories);
    categories.forEach(category => {
        container.appendChild(NewPostCategory(category));
    });
}

export function updateCategoryUI(categoryId, newData)
{
    const old = document.querySelector(`[data-category-id="${categoryId}"]`);
    if (!old) return;

    const newEl = Category(newData);
    old.replaceWith(newEl);
}