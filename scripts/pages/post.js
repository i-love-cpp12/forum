import bootstrap from "../core/bootstrap.js";
import { renderPosts } from "../ui/postUI.js";
import { getPost } from "../services/postService.js";
import { ROOT_DIR } from "../config/config.js";

async function loadPost(container)
{
    const postId = parseInt(new URL(document.URL).searchParams.get("post-id"));
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

async function init()
{
    await bootstrap();

    const postContainer = document.querySelector(".js-posts");

    await loadPost(postContainer);
}

init();