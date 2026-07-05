import { getTimeAgo } from "../utils/time.js";
import { ROOT_DIR } from "../config/config.js";

/**
 * @param {Object} props
 * @param {Number} props.postId
 * @param {String} props.username
 * @param {Number} props.postTimeStamp
 * @param {String[]} props.categories
 * @param {String} props.content
 * @param {{ like: boolean, dislike: boolean }} props.likeStatus
 * @param {{ likeCount: number, dislikeCount: number, commentCount: number }} props.reactionsCount
 * @param {Element[]} props.replies
 * @param {Boolean} props.repliesVisible
 * @param {boolean} props.isEditor
 * @return {Element}
 */

export default function Comment(
    {
        postId,
        username,
        postTimeStamp,
        content,
        likeStatus,
        reactionsCount,
        replies,
        repliesVisible,
        isEditor
    } = props)
{
    const comment = document.createElement("div");
    comment.classList.add("comment");
    comment.classList.add("js-comment");
    if(repliesVisible)
        comment.classList.add("replies-visible");
    comment.setAttribute("data-post-id", postId);
    comment.innerHTML = 
    `
        <div class="header">
            <div class="reply-icon">
                <svg>
                    <use href="../assets/img/icons/icons.svg#reply2"></use>
                </svg>
            </div>
            <div class="username js-username"></div>
            <div>&middot;</div>
            <div class="js-time"></div>
        </div>
        <div class="js-post-content comment-content">
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. In, modi tenetur. Vel cupiditate ipsam at! Illo facere, aliquam quod veritatis, commodi, quam saepe et aperiam eos fugiat rerum porro beatae.
        </div>
        <div class="comment-options">
            <button class="js-like button button--blue" data-action="like-post">
                <svg>
                    <use href="../assets/img/icons/icons.svg#thumb_up"></use>
                </svg>
                <span></span>
            </button>
            <button class="js-dislike button button--red" data-action="dislike-post">
                <svg>
                    <use href="../assets/img/icons/icons.svg#thumb_down"></use>
                </svg>
                <span></span>
            </button>
            <button class="button button--neutral" data-action="make-reply-post">
                <svg>
                    <use href="../assets/img/icons/icons.svg#reply1"></use>
                </svg>
                <span>Reply</span>
            </button>
            <button class="js-show-replies show-replies button button--neutral" data-action="show-replies-post">
                <svg>
                    <use href="../assets/img/icons/icons.svg#arrow_down"></use>
                </svg>
                <span>Show replies <span class="js-replies-count"></span></span>
            </button>
            <button class="js-trash button button--trash" data-action="delete-post">
                <svg>
                    <use href="../assets/img/icons/icons.svg#delete"></use>
                </svg>
            </button>
        </div>
        <div class="add-reply">
            <form data-action="reply-post" class="form">
                <textarea class="text-input" placeholder="Write a reply"></textarea>
                <div class="error"></div>
                <div class="buttons">
                    <button class="button button--hover-fill-blue border" data-btn-type="submit">Reply</button>
                    <button class="button button--white-inverse border">Cancel</button>
                </div>
            </form>
        </div>
        <div class="js-replies replies">
            
        </div>
    `;
    comment.querySelector(".js-username").textContent = "@" + username;
    comment.querySelector(".js-time").textContent = getTimeAgo(postTimeStamp);

    comment.querySelector(".js-post-content").textContent = content;

    const likeElem = comment.querySelector(".js-like");
    if(likeStatus.like)
    {
        likeElem.classList.add("active");
        likeElem.querySelector("svg use").setAttribute("href", `${ROOT_DIR}/assets/img/icons/icons.svg#thumb_up_filled`);
    }
    likeElem.querySelector("span").textContent = reactionsCount.likeCount;

    const dislikeElem = comment.querySelector(".js-dislike");
    if(likeStatus.dislike)
    {
        dislikeElem.classList.add("active");
        dislikeElem.querySelector("svg use").setAttribute("href", `${ROOT_DIR}/assets/img/icons/icons.svg#thumb_down_filled`);
    }
    dislikeElem.querySelector("span").textContent = reactionsCount.dislikeCount;

    const showRepliesBtn = comment.querySelector(".comment-options .js-show-replies");

    if(!reactionsCount.commentCount)
        showRepliesBtn.remove();
    else
        showRepliesBtn.querySelector(".js-replies-count").innerText = reactionsCount.commentCount;

    showRepliesBtn.addEventListener("click", (e) => {
        e.target.closest("[data-post-id]").classList.toggle("replies-visible");
    })

    if(!isEditor)
        comment.querySelector(".js-trash").remove();

    const replyContainer = comment.querySelector(".js-replies");
    replies.forEach((reply) => {
        replyContainer.appendChild(reply);
    });

    return comment;
}

