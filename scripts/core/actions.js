import { getPost, deletePost } from "../services/postService.js";
import { updatePost } from "../ui/postUI.js";
import { updateHeader } from "../ui/headerUI.js";
import {
    likePost,
    dislikePost,
    removeLike,
    removeDislike,
    getPostLikeState
} from "../services/reactionsService.js";
import { logoutUser } from "../services/userService.js";
import { getToken, logout } from "../auth/auth.js";
import { getMeContext } from "../auth/authContext.js";

export const actions = {
    "like-post": async (e, actionElem) => {
        if(!getToken())
        {
            console.warn("User is not logged");
            return;
        }

        const postId = getPostId(actionElem);

        const state = await getPostLikeState(postId);

        if(state?.type === "like")
        {
            await removeLike(postId);
        }
        else
        {
            await likePost(postId);
        }

        const updatedPost = await getPost(postId, getMeContext());
        updatePost(postId, updatedPost);
    },

    "dislike-post": async (e, actionElem) => {
        if(!getToken())
        {
            console.warn("User is not logged");
            return;
        }

        const postId = getPostId(actionElem);

        const state = await getPostLikeState(postId);

        if(state?.type === "dislike")
        {
            await removeDislike(postId);
        }
        else
        {
            await dislikePost(postId);
        }

        const updatedPost = await getPost(postId, getMeContext());

        updatePost(postId, updatedPost);
    },

    "delete-post": async (e, actionElem) => {
        if(!getToken())
        {
            console.warn("User is not logged");
            return;
        }

        const postId = getPostId(actionElem);
        if(!(await getPost(postId, getMeContext())).isEditor)
        {
            console.warn("User is not authenticated");
            return;
        }

        await deletePost(postId);

        getPostElem(actionElem).remove();
    },

    "logout": async (e, actionElem) => {
        if(!getToken())
        {
            console.warn("User is not logged");
            return;
        }

        await logoutUser();
        logout();
        document.location.reload();
    }
};

function getPostId(elem)
{
    return elem.closest("[data-post-id]").dataset.postId;
}
function getPostElem(elem)
{
    return elem.closest("[data-post-id]");
}