import { actions } from "./actions.js"

export default function setGlobalEvents()
{
    document.addEventListener("click", (e) => {
        const actionElem = e.target.closest("[data-action]");
        if (!actionElem) return;
        const action = actionElem.dataset.action;
    
        actions[action]?.(e, actionElem);
    })

    document.addEventListener("submit", (e) => {
        const formElem = e.target.closest("[data-action]");
        if (!formElem) return;
        console.log(formElem);

        e.preventDefault();
    
        const submitter = e.submitter;
        const action =
            submitter?.dataset.action || formElem.dataset.action;

        actions[action]?.(e);
    })
}