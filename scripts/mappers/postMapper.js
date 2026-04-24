export function mapPost(post, user, me, likeStatus)
{
    return {
        postId: post.id,
        username: user.username,
        postTimeStamp: post.createdAtTimeStamp * 1000,
        categories: post.categories.map(c => c.name),
        title: post.header,
        content: post.content,
        likeStatus: likeStatus,
        reactionsCount: {
            likeCount: post.likeCount,
            dislikeCount: post.dislikeCount,
            commentCount: post.commentCount
        },
        isEditor: me?.id === post.userId || me?.role === "admin"
    };
}

export function mapPosts(posts, usersMap, me, likesMap)
{
    console.log(me);
    return posts
        .filter(post => post.parentPostId === null)
        .map(post => {return mapPost(post, usersMap.get(post.userId), me, likesMap.get(post.id))});
}