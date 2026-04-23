import { ROOT_DIR } from "../config/config.js";
import { capitalize } from "../utils/strHelper.js";

export default function Header(
    {
        isLoggedIn,
        username,
        email
    } = props)
{
    const header = document.createElement("header");
    header.innerHTML =
    `
        <div class="logo">
            <img src="${ROOT_DIR}/assets/img/icons/favicon/favicon.png" alt="logo">
            <span>Forum romanum</span>
        </div>
        <nav>
            <a href="${ROOT_DIR}/index.html">
                <button class="button button--blue button--gray-active active border">Forum</button>
            </a>
            <a href="${ROOT_DIR}/pages/categories.html">
                <button class="button button--blue button--gray-active">Categories</button>
            </a>
        </nav>
        <div class="right ${isLoggedIn ? "logged-in" : ""}">
            ${isLoggedIn ? loggedTemplate(username, email) : unloggedTemplate()}
        </div>
    `;
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
            <div class="profile">
                <button class="button profile-picture" translate="no">
                    ${username.substring(0, 2).toUpperCase()}
                </button>
                <div class="profile-options tile-container grid expanded">
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