import { getMe } from "../../services/userService.js";
import { setMe } from "../../auth/authContext.js";

import postActions from "./postActions.js";
import authActions from "./authActions.js";
import commentActions from "./commentActions.js";
import userActions from "./userActions.js";
import categoryActions from "./categoryActions.js";

export const actions = {
    ...postActions,
    ...authActions,
    ...commentActions,
    ...userActions,
    ...categoryActions
};

export function getPostElem(elem)
{
    return elem.closest("[data-post-id]");
}

export function getPostId(elem)
{
    return getPostElem(elem).dataset.postId;
}

export function isComment(elem)
{
    return getPostElem(elem).classList.contains("js-comment");
}

export async function authorize()
{
    try
    {
        const user = await getMe();
        setMe(user);
    }
    catch
    {
        return false;
    }
    return true;
}

export function getCategoryElem(elem)
{
    return elem.closest("[data-category-id]");
}

export function getCategoryId(elem)
{
    return getCategoryElem(elem).dataset.categoryId;
}