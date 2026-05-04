import Header from "../components/Header.js";

export function updateHeader(newData)
{
    const old = document.querySelector("header");
    if (!old) return;

    const newElem = Header(newData);
    old.replaceWith(newElem);
}