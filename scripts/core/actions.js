import { getPost, deletePost, createPost } from "../services/postService.js";
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
import { ROOT_DIR } from "../config/config.js";

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
        location.href = `${ROOT_DIR}/index.html`;
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

            location.href = `${ROOT_DIR}/index.html`;
        }
        catch
        {
            form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => errorElem.textContent = "Invalid email or password");
            form.querySelectorAll(".js-form-field .text-input")
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

            location.href = `${ROOT_DIR}/index.html`;
        }
        catch
        {
            form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => errorElem.textContent = "Something went wrong");
            form.querySelectorAll(".js-form-field .text-input")
                .forEach(inputElem => inputElem.classList.add("error"));
        }
    },

    "add-new-post": async (e) => {
        const form = e.target;
        const title = form.querySelector('.js-title input')?.value;
        const content = form.querySelector('.js-content textarea')?.value;
        const categories = [...form.querySelectorAll('.js-form-field .js-categories > div')]
            .reduce((acc, optionElem) => {
                if(optionElem.querySelector("input[type='checkbox']").checked)
                    acc.push(parseInt(optionElem.dataset.categoryId));

                return acc;
            }, []);

        console.log(title, content, categories);
        try
        {
            await createPost({ parentPostId: null, header: title, content, categories });

            location.href = `${ROOT_DIR}/index.html`;
        }
        catch
        {
            form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => errorElem.textContent = "Something went wrong");
            form.querySelectorAll(".js-form-field .text-input")
                .forEach(inputElem => inputElem.classList.add("error"));
        }
    }
};

function getPostElem(elem)
{
    return elem.closest("[data-post-id]");
}

function getPostId(elem)
{
    return getPostElem(elem).dataset.postId;
}