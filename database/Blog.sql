CREATE DATABASE BlogXpert;
Use BlogXpert;
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

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (title) VALUES 
('Trending'),
('Sports'),
('AITechnology');


CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    
    -- Status Column Added Here
    status ENUM('pending', 'approved') NOT NULL DEFAULT 'pending',

    -- The 6 separate paragraphs
    para_1 TEXT NOT NULL, 
    para_2 TEXT, 
    para_3 TEXT, 
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
-- 3. COMMENTS TABLE
-- Only stores user_id (We fetch Name/Avatar via JOIN)
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
-- 4. POST_LIKES TABLE
-- Tracks WHO liked the post (so they can't like twice)
-- ==========================================
CREATE TABLE post_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

 SELECT posts.*, user.first_name, user.last_name, user.avatar 
        FROM posts 
        JOIN user ON posts.author_id = user.id 
        ORDER BY posts.created_at DESC;



