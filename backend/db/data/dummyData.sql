-- =========================
-- SEED DATA - FORUM
-- =========================

-- user roles
INSERT INTO user_role (user_role_id, role_name) VALUES
(1, 'admin'),
(2, 'normal');

-- like types
INSERT INTO like_type (like_type_id, like_type_name) VALUES
(1, 'like'),
(2, 'dislike');

-- users
INSERT INTO _user (user_id, email, username, password_hash, user_role_id) VALUES
(1, 'admin@forum.com', 'admin', '2a4a27b4685d2977cd45e9cc3fdea31d7430727f96f56828a227e3ad63f038ea', 1),
(2, 'user1@forum.com', 'user1', '0a041b9462caa4a31bac3567e0b6e6fd9100787db2ab433d96f6d178cabfce90', 2),
(3, 'user2@forum.com', 'user2', '9f3c7a1e5b8d4c2a6e7f0a1b2c3d4e5f6a7b8c9d0e1f2233445566778899abcd', 2);

-- categories
INSERT INTO post_category (post_category_id, post_category_name) VALUES
(1, 'programming'),
(2, 'gaming'),
(3, 'life'),
(4, 'tech');

-- posts
INSERT INTO post (post_id, parent_post_id, user_id, header, content, like_count, dislike_count, comment_count) VALUES
(1, NULL, 1, 'Pierwszy post', 'To jest pierwszy post admina', 2, 1, 2),
(2, NULL, 2, 'JS problem', 'Mam problem z fetch', 1, 0, 1),
(3, NULL, 3, 'Gaming setup', 'Mój setup RTX 3060 Ti', 0, 0, 0);

-- comments
INSERT INTO post (post_id, parent_post_id, user_id, header, content, like_count, dislike_count, comment_count) VALUES
(4, 1, 2, NULL, 'Fajny post', 0, 0, 0),
(5, 1, 3, NULL, 'Zgadzam się', 0, 0, 0),
(6, 2, 1, NULL, 'Pokaż kod', 0, 0, 0);

-- post <-> category
INSERT INTO post_post_category (post_id, post_category_id) VALUES
(1, 3),
(1, 4),
(2, 1),
(2, 4),
(3, 2);

-- likes / dislikes
INSERT INTO _like (post_id, user_id, like_type_id) VALUES
(1, 2, 1),
(1, 3, 1),
(1, 3, 2),
(2, 1, 1);

-- tokens (KAŻDY UNIQUE)
INSERT INTO user_token (user_id, value, is_active, expire_at) VALUES
(1, '2a4a27b4685d2977cd45e9cc3fdea31d7430727f96f56828a227e3ad63f038ea', 1, NOW() + INTERVAL 1 DAY),
(2, '0a041b9462caa4a31bac3567e0b6e6fd9100787db2ab433d96f6d178cabfce90', 1, NOW() + INTERVAL 1 DAY),
(3, 'b7e1c2d3f4a5968776655443322110ffeeddccbbaa99887766554433221100aa', 1, NOW() + INTERVAL 1 DAY);

-- =========================
-- END
-- =========================