CREATE DATABASE IF NOT EXISTS daily_drifts;

USE daily_drifts;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_username VARCHAR(255) UNIQUE NOT NULL,
    user_email VARCHAR(255) UNIQUE NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS blogs (
    blog_id INT AUTO_INCREMENT PRIMARY KEY,
    blog_title VARCHAR(255) NOT NULL,
    blog_body TEXT NOT NULL,
    blog_user_id INT NOT NULL,
    blog_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    blog_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (blog_user_id) REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS author_posts (
    author_posts_id INT AUTO_INCREMENT PRIMARY KEY,
    author_posts_user_id INT NOT NULL,
    author_posts_blog_id INT NOT NULL,
    author_posts_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    author_posts_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (author_posts_user_id) REFERENCES users(user_id),
    FOREIGN KEY (author_posts_blog_id) REFERENCES blogs(blog_id)
);

CREATE TABLE IF NOT EXISTS comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    comment_body TEXT NOT NULL,
    comment_user_id INT NOT NULL,
    comment_blog_id INT NOT NULL,
    comment_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    comment_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (comment_user_id) REFERENCES users(user_id),
    FOREIGN KEY (comment_blog_id) REFERENCES blogs(blog_id)
);