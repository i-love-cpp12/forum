import { addCategory, getCategories, updateCategory } from "../../services/categoryService.js";
import { renderCategories, updateCategoryUI } from "../../ui/categoryUI.js";
import { getCategoryElem, getCategoryId } from "./actions.js";

const categoryActions = {
    "add-category": async (e) => {
        e.preventDefault();
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
        renderCategories(document.querySelector(".js-categories"), categories.map((category) => {
                return {categoryId: category.id, title: category.name, isAdmin: true, isInEditStage: false}
            }));
        form.reset();
    },

    "category-make-edit": (e, actionElem) => {
        const categoryId = getCategoryId(actionElem);
        const categoryElem = getCategoryElem(actionElem);
        const categoryName = categoryElem.querySelector(".title").innerText;

        updateCategoryUI(categoryId, {categoryId, title: categoryName, isAdmin: true, isInEditStage: true});

        const inputElem = document.querySelector(`[data-category-id="${categoryId}"] input`);
        const len = inputElem.value.length;

        inputElem.focus();
        inputElem.setSelectionRange(0, len);
    },

    "edit-category": async (e) => {
        e.preventDefault();

        const form = e.target;
        const submitter = e.submitter;
        const categoryId = getCategoryId(form);
        const inputElem = form.querySelector("input");
        const newCategoryName = inputElem?.value?.trim();


        if(submitter.dataset.btnType === "submit")
        {
            try
            {
                await updateCategory(categoryId, newCategoryName);
            }
            catch
            {
                const errDiv = form.querySelector("div.error");
                errDiv.innerText = "Something went wrong";
                errDiv.classList.add("active");
                inputElem.classList.add("error");
                return;
            }
        }

        const categories = await getCategories();
        const container = form.closest(".js-categories");
        renderCategories(container, categories.map((category) => {
            return {categoryId: category.id, title: category.name, isAdmin: true, isInEditStage: false};
        }));
    }
};

export default categoryActions;