-- docs/mysql/seed.sql

-- utilisateur d'exemple avec un mot de passe hashé
INSERT INTO user (firstname, lastname, email, password)
VALUES
('John','Doe','john@example.com',
'$2y$10$abcdefghijkABCDEFGHIJK1234567890lmnopqrstuVWXYZabcd'); -- dummy hash

-- favoris d'exemple
INSERT INTO favoris (user_id, element_id, element_type, title, poster_path)
VALUES
(1, 777, 'film', 'Seven', '/p.jpg');

-- commentaires d'exemple
INSERT INTO commentaire (content, id_user, id_media)
VALUES
('super film', 1, 1234);
