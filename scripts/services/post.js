import { request } from "../api/request.js";

export function getPosts(params = {})
{
    const query = new URLSearchParams(params).toString();
    return request(`posts?${query}`);
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