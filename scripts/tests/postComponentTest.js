import Post from "/scripts/components/Post.js";

const post = Post({
    postId: 1,
    username: "admin",
    postTimeStamp: Date.now() - (1000 * 44),
    categories: ["programing", "linux", "admin", "oliwier"],
    title: "Test post",
    content: "Lorem iprusdfds sdjf hksdjhf jksdhfjkd shkjds",
    likeStatus: {
        like: false,
        dislike: true
    },
    reactionsCount:{
        likeCount: 20,
        dislikeCount: 1,
        commentCount: 2348
    },
    isAdmin: true
})
const container = document.querySelector(".posts");
container.innerHTML = "";
container.appendChild(post);