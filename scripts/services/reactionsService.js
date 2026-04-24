import { request } from "../api/request.js";

export async function getPostLikeState(postId)
{
    return request(`posts/${postId}/like`)
        .then(res =>  res.like);
}

export function likePost(postId)
{
    return request(`posts/${postId}/like`, {
        method: "POST"
    });
}

export function removeLike(postId)
{
    return request(`posts/${postId}/like`, {
        method: "DELETE"
    });
}

export function dislikePost(postId)
{
    return request(`posts/${postId}/dislike`, {
        method: "POST"
    });
}

export function removeDislike(postId)
{
    return request(`posts/${postId}/dislike`, {
        method: "DELETE"
    });
}