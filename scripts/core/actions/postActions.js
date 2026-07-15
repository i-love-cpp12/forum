import { getPostId, getPostElem, authorize, isComment } from "./actions.js";
import {
    likePost,
    dislikePost,
    removeLike,
    removeDislike,
    getPostLikeState
} from "../../services/reactionsService.js";
import { updatePost } from "../../ui/postUI.js";
import { EMPTY_LIST_TEXT, ROOT_DIR } from "../../config/config.js";
import { getPost, deletePost, createPost } from "../../services/postService.js";
import { getMeContext } from "../../auth/authContext.js";
import { updateComment } from "../../ui/commentUI.js";


const postActions = {
    "like-post": async (e, actionElem) => {
        if(!await authorize())
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
        isComment(actionElem) ? updateComment(postId, updatedPost) : updatePost(postId, updatedPost);
    },

    "dislike-post": async (e, actionElem) => {
        if(!await authorize())
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

        isComment(actionElem) ? updateComment(postId, updatedPost) : updatePost(postId, updatedPost);
    },

    "delete-post": async (e, actionElem) => {
        if(!await authorize())
        {
            console.warn("User is not logged");
            return;
        }

        const postElem = getPostElem(actionElem);
        const postId = getPostId(postElem);

        if(!(await getPost(postId, getMeContext())).isEditor)
        {
            console.warn("User is not authenticated");
            return;
        }

        const postContainer = actionElem.closest(".js-posts") || actionElem.closest(".js-comments");
        

        const parentElem = getPostElem(postElem.parentElement);

        postElem.remove();

        if(postContainer.children.legnht === 0)
            postContainer.innerText = EMPTY_LIST_TEXT;
        
        await deletePost(postId);

        if(parentElem)
        {
            if(!parentElem.querySelector(".replies").children.length)
                parentElem.classList.remove("replies-visible");

            const parentPostId = getPostId(parentElem);

            const updatedComment = await getPost(parentPostId);
            // console.log(updatedComment);
            updateComment(parentPostId, updatedComment);
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
            form.querySelectorAll(".js-form-field .error")
                .forEach(errorElem => {
                    errorElem.textContent = "Something went wrong";
                    errorElem.classList.add("active");
                });
            form.querySelectorAll(".js-form-field .text-input")
                .forEach(inputElem => inputElem.classList.add("error"));
        }
    },
};

export default postActions;