import Comment from "../components/Comment.js";
import { EMPTY_LIST_TEXT } from "../config/config.js";

export function renderComments(comments, container)
{
    container.innerHTML = "";

    comments.forEach(comment => {
        const commentElem = Comment(comment);
        container.appendChild(commentElem);
        // const replyContainer = commentElem.querySelector(".js-replies");
        // renderComments(comment.replies, replyContainer);
    });

    if(!comments.length)
        container.innerHTML = EMPTY_LIST_TEXT;
}

export function updateComment(postId, newData)
{
    const old = document.querySelector(`[data-post-id="${postId}"]`);
    if (!old) return;
    const repliesElems = document.querySelectorAll(`[data-post-id="${postId}"] > .js-replies > .comment`);
    console.log(old);
    console.log("replies aftr udate: ", repliesElems);
    const newElem = Comment({...newData, replies: repliesElems, repliesVisible: old.classList.contains("replies-visible")});
    old.replaceWith(newElem);
}