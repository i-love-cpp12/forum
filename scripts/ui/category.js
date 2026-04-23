import Category from "../components/Category.js";

export function renderCategories(container, categories)
{
    container.innerHTML = "";

    categories.forEach(category => {
        container.appendChild(Category(category));
    });
}

export function updateCategoryUI(categoryId, newData)
{
    const old = document.querySelector(`[data-category-id="${categoryId}"]`);
    if (!old) return;

    const newEl = Category(newData);
    old.replaceWith(newEl);
}