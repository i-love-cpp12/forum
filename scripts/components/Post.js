import { getTimeAgo } from "../utils/time.js";
import { ROOT_DIR } from "../config/config.js";

/**
 * @param {Object} props
 * @param {Number} props.postId
 * @param {String} props.username
 * @param {Number} props.postTimeStamp
 * @param {String[]} props.categories
 * @param {String} props.title
 * @param {String} props.content
 * @param {{ like: boolean, dislike: boolean }} props.likeStatus
 * @param {{ likeCount: number, dislikeCount: number, commentCount: number }} props.reactionsCount
 * @param {boolean} props.isEditor
 * @return {Element}
 */
export default function Post(
    {
        postId,
        username,
        postTimeStamp,
        categories,
        title,
        content,
        likeStatus,
        reactionsCount,
        isEditor
    } = props)
{
    const post = document.createElement("div");
    post.classList.add("post");
    post.classList.add("tile-container");
    post.setAttribute("data-post-id", postId);
    post.innerHTML = 
    `
        <div class="post-header">
            <div class="js-username username"></div>
            <div class="dot">&middot;</div>
            <div class="js-time">2h ago</div>
            <div class="dot">&middot;</div>
            <div class="js-categories categories"></div>
        </div>
        <div class="js-post-title post-title"></div>
        <div class="js-post-content post-content"></div>
        <div class="post-options">
            <div>
                <button class="js-like button button--blue" data-action="like-post">
                    <svg>
                        <use href="${ROOT_DIR}/assets/img/icons/icons.svg#thumb_up"><use>
                    </svg>
                    <span></span>
                </button>
                <button class="js-dislike button button--red" data-action="dislike-post">
                    <svg>
                        <use href="${ROOT_DIR}/assets/img/icons/icons.svg#thumb_down"></use>
                    </svg>
                    <span></span>
                </button>
                <a href="${ROOT_DIR}/pages/post.html?postId=${postId}">
                    <button class="js-comment button button--neutral">
                        <svg>
                            <use href="${ROOT_DIR}/assets/img/icons/icons.svg#comment"><use>
                        </svg>
                        <span></span>
                    </button>
                </a>
            </div>
            <div class="js-trash">
                <button class="button button--trash" data-action="delete-post">
                    <svg>
                        <use href="${ROOT_DIR}/assets/img/icons/icons.svg#delete"><use>
                    </svg>
                </button>
            </div>
        </div>
    `;
    post.querySelector(".js-username").textContent = "@" + username;
    post.querySelector(".js-time").textContent = getTimeAgo(postTimeStamp);

    const categoriesElem = post.querySelector(".js-categories");
    categories.forEach((categoryName) => {
        const categoryElem = document.createElement("div");
        categoryElem.textContent = categoryName;
        categoriesElem.appendChild(categoryElem);
    });

    post.querySelector(".js-post-title").textContent = title;
    post.querySelector(".js-post-content").textContent = content;

    const likeElem = post.querySelector(".js-like");
    if(likeStatus.like)
    {
        likeElem.classList.add("active");
        likeElem.querySelector("svg use").setAttribute("href", `${ROOT_DIR}/assets/img/icons/icons.svg#thumb_down_filled`);
    }
    likeElem.querySelector("span").textContent = reactionsCount.likeCount;

    const dislikeElem = post.querySelector(".js-dislike");
    if(likeStatus.dislike)
    {
        dislikeElem.classList.add("active");
        dislikeElem.querySelector("svg use").setAttribute("href", `${ROOT_DIR}/assets/img/icons/icons.svg#thumb_down_filled`);
    }
    dislikeElem.querySelector("span").textContent = reactionsCount.dislikeCount;

    post.querySelector(".js-comment span").textContent = reactionsCount.commentCount;
    if(!isEditor)
        post.querySelector(".js-trash").remove();
    return post;
}
