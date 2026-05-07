import setGlobalEvents from "../core/events.js";
import { getMe } from "../services/userService.js";
import { getToken } from "../auth/auth.js";
import { setMe } from "../auth/authContext.js";
import { updateHeader } from "../ui/headerUI.js";

export default async function bootstrap(activePage = null)
{
    setGlobalEvents();
    let user = null;
    const token = getToken();

    if(token)
    {
        try
        {
            user = await getMe();
        }
        catch(e)
        {
            console.warn("Invalid token or session expired");
        }
    }

    if(user)
        document.body.classList.add("logged");
    else
        document.body.classList.add("guest");

    setMe(user);

    updateHeader({
        username: user?.username,
        email: user?.email,
        activePage
    });
}