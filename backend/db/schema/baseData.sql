-- user roles
INSERT INTO user_role (user_role_id, role_name) VALUES
(1, 'admin'),
(2, 'normal');

-- like types
INSERT INTO like_type (like_type_id, like_type_name) VALUES
(1, 'like'),
(2, 'dislike');

-- admin user
INSERT INTO _user (user_id, email, username, password_hash, user_role_id) VALUES
(1, "admin@forum.com", "admin", "2a4a27b4685d2977cd45e9cc3fdea31d7430727f96f56828a227e3ad63f038ea", 1);