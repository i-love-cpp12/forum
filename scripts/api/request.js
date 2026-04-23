import { getToken } from "../auth/auth.js";
import { ROOT_DIR } from "../config/config.js";

const API_PATH = ROOT_DIR + "/backend/api/";

export async function request(path, options = {})
{
    const token = getToken();

    const headers = {
        "Content-Type": "application/json",
        ...options.headers
    };

    if(token)
    {
        headers["Authorization"] = `Bearer ${token}`;
    }

    const res = await fetch(API_PATH + path, {
        ...options,
        headers
    });

    const json = await res.json();

    if(json.error)
        throw new Error(json.error);

    return json.data;
}