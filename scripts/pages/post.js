import bootstrap from "../core/bootstrap.js";
import { renderPosts } from "../ui/postUI.js";
import { getPost } from "../services/postService.js";
import { ROOT_DIR } from "../config/config.js";
import { getComments } from "../services/commentService.js";
import { renderComments } from "../ui/commentUI.js";

async function loadPost(container, postId)
{
    if(!postId)
        location.href = `${ROOT_DIR}/index.html`;

    const post = await getPost(postId);
    if(!post.title)
        location.href = `${ROOT_DIR}/index.html`;

    console.log(post);
    renderPosts([post], container, false);
    // const categories = await getCategories();
    // console.log(categories);

    // renderCategoriesNewPost(container, categories.map((category) => {
    //     return {categoryId: category.id, title: category.name};
    // }));
}
async function loadComments(container, postId)
{
    // container.innerHTML = "";
    // container.appendChild(Comment({postId: 1, username: "admin1",
    //     postTimeStamp: 123123,
    //     content: "sdfsdf asda dasd asd asd asdasd asd asd asd asd asd asd asdasda dasd asd asd asdasd asd asd asd asd asd asd asd asd asdasdasdasd hagsdjhgasjhdg asdjhgasd jhgs asd asdasdasdasd hagsdjhgasjhdg asdjhgasd jhgs",
    //     likeStatus: {like: true, dislike: false},
    //     reactionsCount: {likeCount: 1, dislikeCount: 2},
    //     replies: [],
    //     isEditor: false}));
        
    // const replies1 = [Comment({postId: 2, username: "admin2",
    //     postTimeStamp: 1283791823,
    //     content: "asda dasd asd ",
    //     likeStatus: {like: false, dislike: true},
    //     reactionsCount: {likeCount: 1, dislikeCount: 20},
    //     replies: [],
    //     isEditor: true}), Comment({postId: 2, username: "admin2",
    //     postTimeStamp: 1283791823,
    //     content: "asda dasd asd asd asdasd asd asd asd asd asd asd asd asd asdasdasdasd hagsdjhgasjhdg asdjhgasd jhgsad jhgasdj hgasd jhasd",
    //     likeStatus: {like: false, dislike: true},
    //     reactionsCount: {likeCount: 1, dislikeCount: 20},
    //     replies: [],
    //     isEditor: false})];

    // const replies2 = [Comment({postId: 2, username: "admin2",
    //     postTimeStamp: 1283791823,
    //     content: "asda dasd asd ",
    //     likeStatus: {like: false, dislike: true},
    //     reactionsCount: {likeCount: 1, dislikeCount: 20},
    //     replies: [],
    //     isEditor: true}), Comment({postId: 2, username: "admin2",
    //     postTimeStamp: 1283791823,
    //     content: "asda dasd asd asd asdasd asd asd asd asd asd asd asd asd asdasdasdasd hagsdjhgasjhdg asdjhgasd jhgsad jhgasdj hgasd jhasd",
    //     likeStatus: {like: false, dislike: true},
    //     reactionsCount: {likeCount: 1, dislikeCount: 20},
    //     replies: [...replies1],
    //     isEditor: false})];

    // container.appendChild(Comment({postId: 2, username: "admin2",
    //     postTimeStamp: 1283791823,
    //     content: "asda dasd asd ",
    //     likeStatus: {like: false, dislike: true},
    //     reactionsCount: {likeCount: 1, dislikeCount: 20},
    //     replies: replies2,
    //     isEditor: true}));
    const comments = await getComments(postId);
    renderComments(comments, container);
}

async function init()
{
    await bootstrap();

    const postContainer = document.querySelector(".js-posts");
    const postId = parseInt(new URL(document.URL).searchParams.get("post-id"));

    await loadPost(postContainer, postId);
    await loadComments(document.querySelector(".comments"), postId);
}

init();