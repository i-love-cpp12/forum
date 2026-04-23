import { request } from "../api/request.js";

export function getComments(postId)
{
    return request(`posts/${postId}/comments`);
}

export function addComment(postId, content)
{
    return request(`posts/${postId}/comments`, {
        method: "POST",
        body: JSON.stringify({
            parentPostId: postId,
            content
        })
    });
}

export function updateComment(id, content)
{
    return request(`comments/${id}`, {
        method: "PUT",
        body: JSON.stringify({ content })
    });
}

export function deleteComment(id)
{
    return request(`comments/${id}`, {
        method: "DELETE"
    });
}