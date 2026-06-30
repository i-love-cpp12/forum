import { mapPosts } from "../mappers/postMapper.js";
import { request } from "../api/request.js";
import { getUser } from "./userService.js";
import { getPostLikeState } from "./reactionsService.js"
import { getMeContext } from "../auth/authContext.js";

export async function getComments(postId)
{
    // return request(`posts/${postId}/comments`)
    //     .then(res => res.comments);
    const me = getMeContext();
    
    const res = await request(`posts/${postId}/comments`);
    const comments = res.comments;
    // console.log("res:", res);
    // console.log("comments: ", res.comments);

    const usersMap = new Map();
    const likesMap = new Map();

    await Promise.all(
        comments.map(async comment => {
            if(!usersMap.has(comment.userId))
            {
                const user = await getUser(comment.userId);
                usersMap.set(comment.userId, user);
            }

            const likeState = await getPostLikeState(comment.id);
            likesMap.set(comment.id, {
                like: likeState?.type === "like",
                dislike: likeState?.type === "dislike"
            });

        })
    );

    return mapPosts(comments, usersMap, me, likesMap);
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