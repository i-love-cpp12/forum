import Post from "../components/Post.js";
import { EMPTY_LIST_TEXT } from "../config/config.js";

export function renderPosts(posts, container, renderCommentBtn = true)
{
    container.innerHTML = "";

    posts.forEach(post => {
        container.appendChild(Post({...post, renderCommentBtn}));
    });

    if(!posts.length)
        container.innerHTML = EMPTY_LIST_TEXT;
}

export function updatePost(postId, newData)
{
    const old = document.querySelector(`[data-post-id="${postId}"]`);
    if (!old) return;
    const renderCommentBtn = new Boolean(old.querySelector(`[data-action="make-comment-post"]`)).valueOf();
    const newElem = Post({...newData, renderCommentBtn});   
    old.replaceWith(newElem);
}