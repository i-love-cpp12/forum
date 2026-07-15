import { ROOT_DIR } from "../../config/config.js";
import { addComment, getComments } from "../../services/commentService.js";
import { renderComments } from "../../ui/commentUI.js";
import { updateComment } from "../../ui/commentUI.js";
import { getPost } from "../../services/postService.js";
import { getPostId, getPostElem, authorize } from "./actions.js";



const commentActions = {
    "make-comment-post": async (e, actionElem) => {
        if(!await authorize())
        {
            console.warn("User is not logged");
            return;
        }
        const postId = getPostId(actionElem);
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
            const errDiv = form.querySelector("div.error");
            errDiv.innerText = "Something went wrong";
            errDiv.classList.add("active");
            inputElem.classList.add("error");
        }
    
        renderComments(await getComments(postId), document.querySelector(".js-comments"));

        form.reset();
    },

    "make-reply-post": async (e, actionElem) => {
        getPostElem(actionElem).querySelector(".add-reply").classList.add("active");
    },

    "reply-post": async (e) => {

        const form = e.target;
        console.log(form);
        if(e.submitter.dataset.btnType != "submit")
            getPostElem(form).querySelector(".add-reply").classList.remove("active");

        const commentElem = getPostElem(form)
        const postId = getPostId(commentElem);
        console.log(postId);
        const inputElem = form.querySelector("textarea");
        const replyContent = inputElem.value.trim();
        try
        {
            await addComment(postId, replyContent);
            const updatedReplies = await getComments(postId);
            const updatedComment = await getPost(postId);
    
            commentElem.classList.add("replies-visible");

            renderComments(updatedReplies, commentElem.querySelector(".js-replies"));
            updateComment(postId, updatedComment);
        }
        catch
        {
            const errDiv = form.querySelector("div.error");
            errDiv.innerText = "Something went wrong";
            errDiv.classList.add("active");
            inputElem.classList.add("error");
        }
    },

    "show-replies-post": async (e, actionElem) => {
        const commentElem = getPostElem(actionElem);
        const repliesContainer = commentElem.querySelector(".js-replies");
        if(repliesContainer.children.length)
            return;
        const postId = getPostId(commentElem);
        const comments = await getComments(postId);
        renderComments(comments, repliesContainer);
    }
};

export default commentActions;