
-- docs/mysql/schema.sql
DROP TABLE IF EXISTS commentaire;
DROP TABLE IF EXISTS favoris;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(100),
  lastname VARCHAR(100),
  email VARCHAR(190) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE favoris (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  element_id INT NOT NULL,
  element_type ENUM('film','serie') NOT NULL,
  title VARCHAR(255) NOT NULL,
  poster_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_element (user_id, element_id, element_type),
  INDEX idx_user (user_id),
  CONSTRAINT fk_favoris_user FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE commentaire (
  id_commentaire INT AUTO_INCREMENT PRIMARY KEY,
  content TEXT NOT NULL,
  id_user INT NOT NULL,
  id_media INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_comment_user (id_user),
  INDEX idx_comment_media (id_media),
  CONSTRAINT fk_comment_user FOREIGN KEY (id_user) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
