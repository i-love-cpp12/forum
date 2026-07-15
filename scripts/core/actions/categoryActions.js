import { addCategory, getCategories } from "../../services/categoryService.js";
import { renderCategories } from "../../ui/categoryUI.js";

const categoryActions = {
    "add-category": async (e) => {
        e.preventDefault();
        console.log("category added");
        const form = e.target;
        const inputElem = form.querySelector("input");
        const categoryName = inputElem.value.trim();

        try
        {
            await addCategory(categoryName);
        }
        catch
        {
            const errElem = form.querySelector(".error");
            errElem.innerText = "Something went wrong";
            errElem.classList.add("active");
            inputElem.classList.add("error");
            return;
        }

        const categories = await getCategories();
        console.log(categories);
        renderCategories(document.querySelector(".js-categories"), categories.map((category) => {
                return {categoryId: category.id, title: category.name, isAdmin: true, isInEditStage: false}
            }));
        form.reset();
    }
};

export default categoryActions;