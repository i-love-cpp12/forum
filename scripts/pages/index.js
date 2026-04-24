// import setGlobalEvents from "../core/events.js"
// import { getMe } from "../services/user.js";
// setGlobalEvents();
// // console.log(getMe());
import setGlobalEvents from "../core/events.js";

import { getToken } from "../auth/auth.js";
import { getMe } from "../services/user.js";
import { getPosts } from "../services/post.js";

import { renderPosts } from "../ui/post.js";

async function init() {
    setGlobalEvents();

    const postsContainer = document.querySelector(".posts");

    let user = null;

    const token = getToken();

    if(token)
    {
        try
        {
            user = await getMe();
        }
        catch(e) {
            console.warn("Invalid token or session expired");
        }
    }

    if(user)
    {
        console.log("Logged as:", user.username);
        document.body.classList.add("logged-in");
    }
    else
    {
        document.body.classList.add("guest");
    }

    try
    {
        const data = await getPosts({
            page: 1,
            limit: 20,
            sort: "latest"
        });
        console.log(data);
        console.log("dsfsdf");
        renderPosts(data, postsContainer);
    }
    catch(e)
    {
        console.error("Failed to load posts:", e);
        postsContainer.innerHTML = `
            <div class="error">
                Failed to load posts.
            </div>
        `;
    }
}

init();