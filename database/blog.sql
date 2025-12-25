Use ZenBlogs;
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,  
    username VARCHAR(50) NOT NULL,     
    email VARCHAR(150) NOT NULL UNIQUE, 
    password VARCHAR(255) NOT NULL,     
    avatar VARCHAR(255) DEFAULT NULL,   
    role ENUM('user','writer','admin','superadmin') DEFAULT 'user', 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO user (
    first_name,
    last_name,
    username,
    email,
    password,
    avatar,
    role
) VALUES (
    'Super',
    'Admin',
    'superadmin',
    'superadmin@example.com',
    '$2y$10$L6wAKK27eWRqcIxGYYThqu/b3Rp7Fb2ozg93isAWXQMGIEFLVjEMq',---Password IS Asdf@#1234
    NULL, ---Change this name to superadmin.png and Keep photo of this name in your images folder
    'superadmin'
);

-- ==========================================
-- 2. Post Categories
-- ==========================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
--Insert Some Values 
INSERT INTO categories (title) VALUES 
('Trending'),
('Sports'),
('AITechnology');
-- ==========================================
-- 3. Post 
-- ==========================================
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    status ENUM('pending', 'approved') NOT NULL DEFAULT 'pending',
    para_1 TEXT NOT NULL, 
    para_2 TEXT NOT NULL, 
    para_3 TEXT NOT NULL, 
    para_4 TEXT, 
    para_5 TEXT, 
    para_6 TEXT,    
    thumbnail VARCHAR(255) NOT NULL,
    category_id INT,
    author_id INT,   
    likes_count INT DEFAULT 0, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES user(id) ON DELETE CASCADE
);
SELECT * FROM posts;
-- ==========================================
-- 4. Post Comments
-- ==========================================
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    body TEXT NOT NULL,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);
SELECT * FROM comments;
-- ==========================================
-- 5. Post Likes
-- ==========================================
CREATE TABLE post_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);


