import bootstrap from "../core/bootstrap.js";
import { renderCategoriesNewPost } from "../ui/categoryUI.js";
import { getCategories } from "../services/categoryService.js";

async function loadCategories(container)
{
    const categories = await getCategories();
    console.log(categories);

    renderCategoriesNewPost(container, categories.map((category) => {
        return {categoryId: category.id, title: category.name};
    }));
}

async function init()
{
    await bootstrap();

    const container = document.querySelector(".js-categories");

    await loadCategories(container);
}

init();