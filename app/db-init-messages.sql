-- Add messages table for chat
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    time DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
