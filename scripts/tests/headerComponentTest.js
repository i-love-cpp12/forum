import Header from "../components/Header.js";

const header = Header({
    isLoggedIn: true,
    username: "oliwier",
    email: "Oliwer@gmail.com"
});
const container = document.querySelector("header");
container.replaceWith(header);