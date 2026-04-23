let token = null;

export function setToken(t)
{
    token = t;
    localStorage.setItem("token", t);
}

export function getToken()
{
    if (!token) {
        token = localStorage.getItem("token");
    }
    return token;
}

export function logout()
{
    token = null;
    localStorage.removeItem("token");
}