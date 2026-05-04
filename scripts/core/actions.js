import { getPost, deletePost } from "../services/postService.js";
import { updatePost } from "../ui/postUI.js";
import {
    likePost,
    dislikePost,
    removeLike,
    removeDislike,
    getPostLikeState
} from "../services/reactionsService.js";
import { logoutUser, loginUser, registerUser } from "../services/userService.js";
import { getToken, setToken, logout } from "../auth/auth.js";
import { getMeContext, setMe } from "../auth/authContext.js";

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
    },

    "login": async (e) => {
        const form = e.target;

        const email = form.querySelector('.js-form-field input[type="email"]')?.value;
        const password = form.querySelector('.js-form-field input[type="password"]')?.value;

        console.log(email, password);
        try
        {
            const token = (await loginUser({ email, password })).value;
            console.log(token);
            setToken(token);

            const user = await getMeContext();
            setMe(user);

            location.href = "../index.html";
        }
        catch
        {
            form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => errorElem.textContent = "Invalid email or password");
            form.querySelectorAll(".js-form-field input")
                .forEach(inputElem => inputElem.classList.add("error"));
        }
    },

    "signup": async (e) => {
        const form = e.target;

        const username = form.querySelector('.js-form-field input[type="text"]')?.value;
        const email = form.querySelector('.js-form-field input[type="email"]')?.value;
        const password = form.querySelector('.js-form-field input[type="password"]')?.value;

        console.log(email, password, username);
        try
        {
            await registerUser({ username, email, password});

            location.href = "./login.html";
        }
        catch
        {
            form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => errorElem.textContent = "Something went wrong");
            form.querySelectorAll(".js-form-field input")
                .forEach(inputElem => inputElem.classList.add("error"));
        }
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