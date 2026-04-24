let me = null;

export function setMe(user)
{
    me = user;
}

export function getMeContext()
{
    return me;
}