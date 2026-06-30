import { getPost, deletePost, createPost } from "../services/postService.js";
import { updatePost } from "../ui/postUI.js";
import {
    likePost,
    dislikePost,
    removeLike,
    removeDislike,
    getPostLikeState
} from "../services/reactionsService.js";
import { logoutUser, loginUser, registerUser, updateUser, getMe } from "../services/userService.js";
import { getToken, setToken, logout } from "../auth/auth.js";
import { getMeContext, setMe } from "../auth/authContext.js";
import { EMPTY_LIST_TEXT, ROOT_DIR } from "../config/config.js";
import { updateHeader } from "../ui/headerUI.js";
import renderProfileForm from "../ui/profileUI.js";
import { addComment, getComments } from "../services/commentService.js";
import { renderComments } from "../ui/commentUI.js";

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

        const postContainer = actionElem.closest(".js-posts") || actionElem.closest(".js-comments");
        
        getPostElem(actionElem).remove();
        
        if(!postContainer.innerText.trim())
            postContainer.innerText = EMPTY_LIST_TEXT;

        await deletePost(postId);
        // if(document.location.href != ROOT_DIR && document.location.href != ROOT_DIR + "/index.html")
        //     document.location.href = `${ROOT_DIR}/index.html`;
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
        e.preventDefault();
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
        e.preventDefault();
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
        e.preventDefault();
        if(e.submitter.dataset.btnType != "submit")
            location.href = `${ROOT_DIR}/index.html`;
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
        catch(err)
        {
            console.error(err);
            form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => errorElem.textContent = "Something went wrong");
            form.querySelectorAll(".js-form-field .text-input")
                .forEach(inputElem => inputElem.classList.add("error"));
        }
    },

    "edit-profile": async (e) => {
        e.preventDefault();
            
        console.log("edited")
        const form = e.target;
        
        form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => errorElem.textContent = "");
        form.querySelectorAll(".js-form-field .text-input")
            .forEach(inputElem => inputElem.classList.remove("error"));

        const username = form.querySelector('.js-form-field input[type="text"]')?.value?.trim();
        const password = form.querySelector('.js-form-field input[type="password"]')?.value?.trim();
       
        try
        {
            let user = getMeContext();
            const newData = { username };

            if(password.length > 0)
                newData.password = password;

            console.log(newData);
            await updateUser(user.id, newData);

            user = await getMe();
            setMe(user);

            updateHeader({
                username: user?.username,
                email: user?.email
            });

            renderProfileForm(user, form);
        }
        catch
        {
            form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => errorElem.textContent = "Something went wrong");
            form.querySelectorAll(".js-form-field .text-input")
                .forEach(inputElem => inputElem.classList.add("error"));
        }
    },

    "make-comment-post": async (e) => {
            
        const postId = getPostId(e.target);
        location.href = `${ROOT_DIR}/pages/post.html?post-id=${postId}`;
    },

    "comment-post": async (e) => {
        e.preventDefault();
        const form = e.target;
        const postId = parseInt(new URL(document.URL).searchParams.get("post-id"));
        const inputElem = form.querySelector("textarea");
        const commentContent = inputElem.value;
        console.log(commentContent, postId);
        
        try
        {
            await addComment(postId, commentContent);
        }
        catch
        {
            form.querySelector(".error").innerText = "Something went wrong";
            inputElem.classList.add("error");
        }

        renderComments(await getComments(postId), document.querySelector(".js-comment"));

        form.reset();
    },

    "make-reply-post": async (e) => {
        getPostElem(e.target).querySelector(".add-reply").classList.add("active");
    },

    "reply-post": async (e) => {
        if(e.submitter.dataset.btnType != "submit")
            getPostElem(e.target).querySelector(".add-reply").classList.remove("active");
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