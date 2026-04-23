import { Post } from "../services/post.js";

export const actions = {
    "like-post": Post.handleLike,
    "dislike-post": Post.handleDislike
};