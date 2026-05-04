import { request } from "../api/request.js";
import { mapPost, mapPosts } from "../mappers/postMapper.js";
import { getUser } from "./userService.js";
import { getPostLikeState } from "./reactionsService.js"
import { getMeContext } from "../auth/authContext.js";

export async function getPosts(params = {})
{
    const me = getMeContext();

    const query = new URLSearchParams(params).toString();

    const res = await request(`posts?${query}`);
    const posts = res.posts;

    const usersMap = new Map();
    const likesMap = new Map();

    await Promise.all(
        posts.map(async post => {
            if(!usersMap.has(post.userId))
            {
                const user = await getUser(post.userId);
                usersMap.set(post.userId, user);
            }

            const likeState = await getPostLikeState(post.id);
            likesMap.set(post.id, {
                like: likeState?.type === "like",
                dislike: likeState?.type === "dislike"
            });

        })
    );

    return mapPosts(posts, usersMap, me, likesMap);
}

export async function getPost(id)
{
    const me = getMeContext();
    
    const res = await request(`posts/${id}`);
    const post = res.post;

    const user = await getUser(post.userId);

    const likeState = await getPostLikeState(post.id);

    const likes = {
        like: likeState?.type === "like",
        dislike: likeState?.type === "dislike"
    };

    return mapPost(post, user, me, likes);
}

export function createPost(data)
{
    return request("posts", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

export function updatePost(id, data)
{
    return request(`posts/${id}`, {
        method: "PUT",
        body: JSON.stringify(data)
    });
}

export function deletePost(id)
{
    return request(`posts/${id}`, {
        method: "DELETE"
    });
}