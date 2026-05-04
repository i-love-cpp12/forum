import setGlobalEvents from "../core/events.js";
import { getToken } from "../auth/auth.js";
import { getMe } from "../services/userService.js";
import { getPosts } from "../services/postService.js";
import { getCategories } from "../services/categoryService.js";
import { renderPosts } from "../ui/postUI.js";
import DropDown from "../components/DropDown.js";
import Header from "../components/Header.js";
import { capitalize } from "../utils/strHelper.js";
import { setMe } from "../auth/authContext.js";
import { me } from "./init.js";

let state =
{
    sort: "latest",
    category: null,
    search: null,
    author: null
}


async function loadPosts(container)
{
    const params =
    {
        sort: state.sort
    };

    if(state.category)
        params.category = state.category;

    if(state.search)
        params.search = state.search;

    if(state.author)
        params.author = state.author;

    const data = await getPosts(params, me);

    renderPosts(data, container);
}

async function loadFilters(postsContainer)
{
    const sortingElem = document.querySelector(".sorting");
    const categoriesElem = document.querySelector(".categories");

    sortingElem.replaceWith(
        DropDown({
            selectedOptionId: "latest",
            options:
            [
                { id: "latest", value: "Latest" },
                { id: "eldest", value: "Eldest" },
                { id: "mostLiked", value: "Most liked" },
                { id: "leastLiked", value: "Least liked" },
                { id: "mostDisliked", value: "Most disliked" },
                { id: "mostCommented", value: "Most commented" },
                { id: "leastCommented", value: "Least commented" }
            ],
            onSelect: async (id) =>
            {
                state.sort = id;
                await loadPosts(postsContainer);
            }
        })
    );

    const categories = await getCategories();

    categoriesElem.replaceWith(
        DropDown({
            selectedOptionId: "all",
            options:
            [
                { id:"all", value: "All categories"},
                ...categories.map((category) => {
                    return { id: category.name.toLowerCase(), value: capitalize(category.name) }
                })
            ],
            onSelect: async (id) =>
            {
                if(id === "all")
                    state.category = null;
                else
                    state.category = id;

                await loadPosts(postsContainer);
            }
        })
    );

    document.querySelector(".filters .search input").addEventListener("input", async (e) => {
        state.search = e.target.value;
        await loadPosts(postsContainer);
    });

    document.querySelector(".filters .author-search input").addEventListener("input", async (e) => {
        state.author = e.target.value;
        await loadPosts(postsContainer);
    });
}

async function init()
{
    const postsContainer = document.querySelector(".posts");

    await loadFilters(postsContainer);
    await loadPosts(postsContainer);
}

init();