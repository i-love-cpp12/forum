import { getPosts } from "./postService.js";
import { request } from "../api/request.js";


export async function getComments(postId)
{
    return getPosts({}, true, postId);
}

export function addComment(postId, content)
{
    return request(`posts/${postId}/comments`, {
        method: "POST",
        body: JSON.stringify({
            content
        })
    });
}

export function updateComment(id, content)
{
    return request(`comments/${id}`, {
        method: "PUT",
        body: JSON.stringify({
            content
        })
    });
}

export function deleteComment(id)
{
    return request(`comments/${id}`, {
        method: "DELETE"
    });
}