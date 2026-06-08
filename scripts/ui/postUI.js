import Post from "../components/Post.js";

export function renderPosts(posts, container, renderCommentBtn = true)
{
    container.innerHTML = "";
    posts.forEach(post => {
        container.appendChild(Post({...post, renderCommentBtn}));
    });
}

export function updatePost(postId, newData)
{
    const old = document.querySelector(`[data-post-id="${postId}"]`);
    if (!old) return;

    const newElem = Post(newData);
    old.replaceWith(newElem);
}