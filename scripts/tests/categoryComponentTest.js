import Category from "../components/Category.js";

const categories = [
    Category({
        categoryId: 1,
        title: "programing",
        isAdmin: true,
        isInEditStage: false
    }),
    Category({
        categoryId: 2,
        title: "linux",
        isAdmin: false,
        isInEditStage: false
    }),
    Category({
        categoryId: 2,
        title: "hacking",
        isAdmin: true,
        isInEditStage: true
    })
];
const container = document.querySelector(".categories");
container.innerHTML = "";
categories.forEach((category) => {
    container.appendChild(category);
});