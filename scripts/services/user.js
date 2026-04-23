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

export function getUser(id)
{
    return request(`users/${id}`);
}

export function getAllUsers()
{
    return request("users");
}

export function getMe()
{
    return request("users/me");
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