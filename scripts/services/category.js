import { request } from "../api/request.js";

const categoryCache = new Map();

export async function getCategories()
{
    return request("categories")
        .then(res => res.categories);
}

export function addCategory(categoryName)
{
    return request("categories", {
        method: "POST",
        body: JSON.stringify({
            categoryName
        })
    });
}

export function updateCategory(id, categoryName)
{
    categoryCache.delete(id);

    return request(`categories/${id}`, {
        method: "PUT",
        body: JSON.stringify({
            categoryName
        })
    });
}

export function deleteCategory(id)
{
    categoryCache.delete(id);

    return request(`categories/${id}`, {
        method: "DELETE"
    });
}