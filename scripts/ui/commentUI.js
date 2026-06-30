import Comment from "../components/Comment.js";

export function renderComments(comments, container)
{
    if(!comments.length) return;

    container.innerHTML = "";

    comments.forEach(comment => {
        const commentElem = Comment(comment);
        container.appendChild(commentElem);
        const replyContainer = commentElem.querySelector(".js-replies");
        renderComments(comment.replies, replyContainer);
    });

    if(!comments.length)
        container.innerHTML = "No comments yet";
}

export function updateComment(postId, newData)
{
    const old = document.querySelector(`[data-post-id="${postId}"]`);
    if (!old) return;
    const newElem = Comment({...newData});   
    old.replaceWith(newElem);
}