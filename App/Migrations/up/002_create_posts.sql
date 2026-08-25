CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100),
    description VARCHAR(1000),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
