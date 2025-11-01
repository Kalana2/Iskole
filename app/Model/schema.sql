-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed user (email: admin@example.com, password: password123)
INSERT INTO users (email, password_hash) VALUES
('admin@example.com', '$2y$12$X3MO6s0PJxPxVsNsmkaFtueqVQmDvmtvqDP.xndNw6gSo8Lm2WHt6')
ON DUPLICATE KEY UPDATE email = VALUES(email);
