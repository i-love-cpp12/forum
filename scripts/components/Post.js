import { getTimeAgo } from "/scripts/services/time.js";
import { icons } from "/scripts/ui/icons.js";

/**
 * @param {Number} postId
 * @param {String} username
 * @param {Number} postTimeStamp
 * @param {String[]} categories
 * @param {String} title
 * @param {String} content
 * @param {{ like: boolean, dislike: boolean }} likeStatus
 * @param {{ likeCount: number, dislikeCount: number, commentCount: number }} reactionsCount
 * @param {boolean} isAdmin
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
        isAdmin
    } = props)
{
    const post = document.createElement("div");
    post.classList.add("post");
    post.innerHTML = 
    `
        <div class="posts" data-post-id="${postId}">
           <div class="post tile-container">
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
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"></svg>
                            <span></span>
                        </button>
                        <button class="js-dislike button button--red" data-action="dislike-post">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M250.77-803.84h420.77v479.99L406.15-60l-33.84-33.85q-6.23-6.23-10.35-16.5-4.11-10.27-4.11-19.34v-10.16l42.46-184h-268q-28.54 0-50.42-21.88Q60-367.62 60-396.15v-64.62q0-6.23 1.62-13.46 1.61-7.23 3.61-13.46l114.62-270.46q8.61-19.23 28.84-32.46t42.08-13.23Zm360.77 60H250.77q-4.23 0-8.65 2.3-4.43 2.31-6.74 7.7L120-463.84v67.69q0 5.38 3.46 8.84 3.46 3.47 8.85 3.47h343.84L426-164.61l185.54-184.77v-394.46Zm0 394.46v-394.46 394.46Zm60 25.53v-59.99H800v-360H671.54v-60H860v479.99H671.54Z"/></svg>
                            <span></span>
                        </button>
                        <button class="js-comment button button--neutral" data-action="comment-post">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M250-410h460v-60H250v60Zm0-120h460v-60H250v60Zm0-120h460v-60H250v60Zm610 531.54L718.46-260H172.31Q142-260 121-281q-21-21-21-51.31v-455.38Q100-818 121-839q21-21 51.31-21h615.38Q818-860 839-839q21 21 21 51.31v669.23ZM172.31-320H744l56 55.39v-523.08q0-4.62-3.85-8.46-3.84-3.85-8.46-3.85H172.31q-4.62 0-8.46 3.85-3.85 3.84-3.85 8.46v455.38q0 4.62 3.85 8.46 3.84 3.85 8.46 3.85ZM160-320v-480 480Z"/></svg>
                            <span></span>
                        </button>
                    </div>
                    <div class="js-trash">
                        <button class="button button--trash">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M292.31-140q-29.92 0-51.12-21.19Q220-182.39 220-212.31V-720h-40v-60h180v-35.38h240V-780h180v60h-40v507.69Q740-182 719-161q-21 21-51.31 21H292.31ZM680-720H280v507.69q0 5.39 3.46 8.85t8.85 3.46h375.38q4.62 0 8.46-3.85 3.85-3.84 3.85-8.46V-720ZM376.16-280h59.99v-360h-59.99v360Zm147.69 0h59.99v-360h-59.99v360ZM280-720v520-520Z"/></svg>
                        </button>
                    </div>
                </div>
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
        likeElem.querySelector("svg").outerHTML = icons.like
    }
    likeElem.querySelector("span").textContent = reactionsCount.likeCount;

    const dislikeElem = post.querySelector(".js-dislike");
    if(likeStatus.dislike)
        dislikeElem.classList.add("active");
    dislikeElem.querySelector("span").textContent = reactionsCount.dislikeCount;

    post.querySelector(".js-comment span").textContent = reactionsCount.commentCount;

    return post;
}