import { ROOT_DIR } from "../config/config.js";
import { capitalize } from "../utils/strHelper.js";

export default function Header(
    {
        username,
        email,
        activePage
    } = props)
{
    const header = document.createElement("header");
    const activePageClasses = "active border";
    header.innerHTML =
    `
        <div class="logo" translate="no">
            <img src="${ROOT_DIR}/assets/img/icons/favicon/favicon.png" alt="logo">
            <span>Forum romanum</span>
        </div>
        <nav>
            <a href="${ROOT_DIR}/index.html">
                <button class="button button--blue button--gray-active ${activePage?.toLowerCase() === "forum" ? activePageClasses : ""}">Forum</button>
            </a>
            <a href="${ROOT_DIR}/pages/categories.html">
                <button class="button button--blue button--gray-active ${activePage?.toLowerCase() === "categories" ? activePageClasses : ""}">Categories</button>
            </a>
        </nav>
        <div class="right">
            ${username && email ? loggedTemplate(username, email) : unloggedTemplate()}
        </div>
    `;
    bindHeaderEvents(header);
    return header;
}

function unloggedTemplate()
{
    return `
        <div class="unlogged">
            <a href="${ROOT_DIR}/pages/login.html">
                <button class="button border button--hover-fill-blue">Log in</button>
            </a>
            <a href="${ROOT_DIR}/pages/signup.html">
                <button class="button border button--white-inverse">Sign up</button>
            </a>
        </div>
    `;
}
function loggedTemplate(username, email)
{
    return `
        <div class="logged">
            <a href="${ROOT_DIR}/pages/new-post.html">
                <button class="button button--white-inverse border new-post-btn">
                    <svg>
                        <use href="${ROOT_DIR}/assets/img/icons/icons.svg#edit_square"></use>
                    </svg>
                    <span>New post</span>
                </button>
            </a>
            <div class="js-profile profile">
                <button class="button profile-picture" data-action="toggle-expand" translate="no">
                    ${username.substring(0, 2).toUpperCase()}
                </button>
                <div class="js-profile-options profile-options tile-container grid">
                    <div class="profile-info profile-options-group" translate="no">
                        <span class="username">${capitalize(username)}</span>
                        <span class="email">${email.toLowerCase()}</span>
                    </div>
                    <div class="profile-options-group">
                        <a href="pages/profile.html">
                            <button class="button button--blue">
                                <svg>
                                    <use href="${ROOT_DIR}/assets/img/icons/icons.svg#profile"></use>
                                </svg>
                                <span>Profile</span>
                            </button>
                        </a>
                        <a href="${ROOT_DIR}/pages/categories.html">
                            <button class="button button--blue">
                                <svg>
                                    <use href="${ROOT_DIR}/assets/img/icons/icons.svg#category"></use>
                                </svg>
                                <span>Categories</span>
                            </button>
                        </a>
                    </div>
                    <div class="profile-options-group">
                        <button class="button button--red" data-action="logout">
                            <svg>
                                <use href="${ROOT_DIR}/assets/img/icons/icons.svg#logout"></use>
                            </svg>
                            <span>Log out</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function bindHeaderEvents(headerElem)
{
    headerElem.addEventListener("click", (e) => {
        const actionElem = e.target.closest("button[data-action='toggle-expand']");
        if(!actionElem)
            return;
        const profileOptionsElem = actionElem.closest(".js-profile").querySelector(".js-profile-options");

        profileOptionsElem.classList.toggle("expanded");
    });
}