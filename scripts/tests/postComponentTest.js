import Post from "../components/Post.js";

const posts = [
    Post({
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
        isEditor: true
    }),
    Post({
        postId: 2,
        username: "admin",
        postTimeStamp: Date.now() - (1000 * 44),
        categories: ["programing", "linux", "admin", "oliwier"],
        title: "Test post",
        content: "Lorem iprusdfds sdjf hksdjhf jksdhfjkd shkjds",
        likeStatus: {
            like: false,
            dislike: false
        },
        reactionsCount:{
            likeCount: 20,
            dislikeCount: 1,
            commentCount: 2348
        },
        isEditor: false
    }),
    Post({
        postId: 3,
        username: "oliwier",
        postTimeStamp: Date.now() - (1000 * 60 * 60 * 2),
        categories: [, "linux", "admin", "oliwier"],
        title: "Test post",
        content: "Lorem iprusdfds sdjf hksdjhf jksdhfjkd shkjds",
        likeStatus: {
            like: true,
            dislike: false
        },
        reactionsCount:{
            likeCount: 20,
            dislikeCount: 1,
            commentCount: 22
        },
        isEditor: false
    })
];
const container = document.querySelector(".posts");
container.innerHTML = "";
posts.forEach((post) => {
    container.appendChild(post);
});