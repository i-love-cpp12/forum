export class Post
{
    static handleReaction(e, likeType)
    {
        const reactionBtnElem = e.target;
        // console.log(reactionBtnElem.parentElement);

        // console.log(e, likeType);
    }
    static handleDislike(e, actionTarget)
    {
        // Post.handleReaction(e, "dislike");
        // document.createElement("div").previousElementSibling
        // console.log(e.target.closest("[data-action]"));
        // console.log(e.target.previousElementSibling);
        
    }
    static handleLike(e)
    {
        Post.handleReaction(e, "like");
    }
}