import { request } from "../api/request.js";
import { getMeContext } from "../auth/authContext.js";

export async function getPostLikeState(postId)
{
    if(getMeContext())
        return request(`posts/${postId}/like`)
            .then(res =>  res.like);
    return {like: false, dislike: false};
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