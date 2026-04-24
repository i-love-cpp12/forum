import { request } from "../api/request.js";

export function registerUser(data)
{
    return request("users/register", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

export function loginUser(data)
{
    return request("users/login", {
        method: "POST",
        body: JSON.stringify(data)
    });
}

export function logoutUser()
{
    return request("users/logout", {
        method: "POST"
    });
}

const userCache = new Map();

export async function getUser(id)
{
    if(userCache.has(id)) return userCache.get(id);

    const res = await request(`users/${id}`);
    const user = res.user;

    userCache.set(id, user);
    return user;
}

export function getAllUsers()
{
    return request("users");
}

export function getMe()
{
    return request("me");
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