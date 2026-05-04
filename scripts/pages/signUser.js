import setGlobalEvents from "../core/events.js";
import { getMe } from "../services/userService.js";
import { getToken } from "../auth/auth.js";
import { setMe } from "../auth/authContext.js";
import Header from "../components/Header.js";

async function init()
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
    
    const header = document.querySelector("header");
    header.replaceWith(Header({
        username: user?.username,
        email: user?.email
    }));

}

init();