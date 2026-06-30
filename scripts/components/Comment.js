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
 * @param {{ likeCount: number, dislikeCount: number }} props.reactionsCount
 * @param {Element[]} props.replies
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
        isEditor
    } = props)
{
    const comment = document.createElement("div");
    comment.classList.add("comment");
    comment.setAttribute("data-post-id", postId);
    comment.innerHTML = 
    `
        <div class="comment">
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
                <button class="js-like button button--blue">
                    <svg>
                        <use href="../assets/img/icons/icons.svg#thumb_up"></use>
                    </svg>
                    <span></span>
                </button>
                <button class="js-dislike button button--red">
                    <svg>
                        <use href="../assets/img/icons/icons.svg#thumb_down"></use>
                    </svg>
                    <span></span>
                </button>
                <button class="button button--neutral">
                    <svg>
                        <use href="../assets/img/icons/icons.svg#reply1"></use>
                    </svg>
                    <span>Reply</span>
                </button>
                <button class="js-trash button button--trash" data-action="delete-post">
                    <svg>
                        <use href="../assets/img/icons/icons.svg#delete"></use>
                    </svg>
                </button>
            </div>
            <div class="add-reply">
                <textarea class="text-input" placeholder="Write a reply"></textarea>
                <div class="buttons">
                    <button class="button button--hover-fill-blue border">Reply</button>
                    <button class="button button--white-inverse border">Cancel</button>
                </div>
            </div>
            <div class="replies active">
                
            </div>
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

    if(!isEditor)
        comment.querySelector(".js-trash").remove();

    const replyContainer = comment.querySelector(".replies");
    replies.forEach((reply) => {
        replyContainer.appendChild(reply);
    });

    return comment;
}
