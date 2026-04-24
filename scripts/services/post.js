import { request } from "../api/request.js";
import { getUser } from "./user.js";

export async function getPosts(params = {})
{
    const query = new URLSearchParams(params).toString();

    const responce = await request(`posts?${query}`);
    const posts = responce.posts;

    const enrichedPosts = await Promise.all(
        posts
            .filter(post => post.parentPostId === null)
            .map(async (post) => {
                const user = await getUser(post.userId);

                return {
                    postId: post.id,
                    username: user.username,
                    postTimeStamp: post.createdAtTimeStamp * 1000,
                    categories: post.categories.map(c => c.name),
                    title: post.header,
                    content: post.content,
                    likeStatus: {
                        like: false,
                        dislike: false
                    },
                    reactionsCount: {
                        likeCount: post.likeCount,
                        dislikeCount: post.dislikeCount,
                        commentCount: post.commentCount
                    },
                    isEditor: false
                };
            })
    );

    return enrichedPosts;
}

export function getPost(id)
{
    return request(`posts/${id}`);
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