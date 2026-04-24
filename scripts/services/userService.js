import { request } from "../api/request.js";

const userCache = new Map();

export function registerUser(data)
{
    return request("users/register", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

export async function loginUser(data)
{
    const token = await request("users/login", {
        method: "POST",
        body: JSON.stringify(data)
    }).then(res => res.token);

    return token;
}

export function logoutUser()
{
    return request("users/logout", {
        method: "POST"
    });
}

export async function getUser(id)
{
    if(userCache.has(id)) return userCache.get(id);

    const user = await request(`users/${id}`).then(res => res.user);

    userCache.set(id, user);
    return user;
}

export async function getAllUsers()
{
    return request("users").then(res => res.users);
}

export async function getMe()
{
    return request("me").then(res => res.user);
}

export function updateUser(id, data)
{
    return request(`users/${id}`, {
        method: "PUT",
        body: JSON.stringify(data)
    });
}

export function deleteUser(id)
{
    return request(`users/${id}`, {
        method: "DELETE"
    });
}