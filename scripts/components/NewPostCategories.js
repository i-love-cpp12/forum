export default function NewPostCategory(
    {
        categoryId,
        title
    } = props)
{
    const category = document.createElement("div");
    category.classList.add("checkbox-fill-button", "checkbox--fill-accent");
    category.setAttribute("data-category-id", categoryId);
    category.innerHTML = 
    `
        <label>
            <input type="checkbox">
            <div class="label">${title}</div>
        </label>
    `;
    return category;
}
