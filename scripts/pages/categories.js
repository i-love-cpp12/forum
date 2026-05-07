import { getMeContext } from "../auth/authContext.js";
import bootstrap from "../core/bootstrap.js";
import { getCategories } from "../services/categoryService.js";
import { renderCategories } from "../ui/categoryUI.js";

async function loadCategories(container, me)
{
    const categories = await getCategories();
    console.log(categories);
    const isAdmin = me?.role === "admin";
    renderCategories(container, categories.map((category) => {
        return {categoryId: category.id, title: category.name, isAdmin, isInEditStage: false};
    }));
}
function loadAddCategory(me)
{
    if(!me || me.role !== "admin")
    {
        document.querySelector(".js-add-category").remove();
        document.querySelector(".js-subtitle").textContent = "Browse categories";
    }
}


async function init()
{
    await bootstrap("categories");

    const categoriesContainer = document.querySelector(".js-categories");
    const me = getMeContext();
    await loadCategories(categoriesContainer, me);
    loadAddCategory(me);
}

init();