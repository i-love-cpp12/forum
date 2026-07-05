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
    const repliesElems = old.querySelectorAll(".replies > div");
    const newElem = Comment({...newData, replies: repliesElems});
    old.replaceWith(newElem);
}