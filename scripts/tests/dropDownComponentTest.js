import DropDown from "../components/DropDown.js";

const dropDown = DropDown({
    options: [
        {
            id: 1,
            value: "programing"
        },
        {
            id: 2,
            value: "sjdflk"
        },
        {
            id: 3,
            value: "krzeslo"
        }
    ],
    selectedOptionId: 2,
    onSelect: (id, value) => {
        console.log(id, value);
    }
});
const container = document.querySelector(".filters .categories");
container.innerHTML = "";
container.replaceWith(dropDown);