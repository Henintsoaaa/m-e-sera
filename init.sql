mysql -u admin -Teny123!

CREATE DATABASE IF NOT EXISTS reseaux_sociaux;
USE reseaux_sociaux;

CREATE TABLE IF NOT EXISTS compte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    prenom VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS publication (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_compte INT NOT NULL,
    contenu TEXT NOT NULL,
    date_pub TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_compte) REFERENCES compte(id)
);

CREATE TABLE IF NOT EXISTS comment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_publication INT NOT NULL,
    id_compte INT NOT NULL,
    contenu TEXT NOT NULL,
    date_coms TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_publication) REFERENCES publication(id),
    FOREIGN KEY (id_compte) REFERENCES compte(id)
);

CREATE TABLE IF NOT EXISTS publication_reaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_publication INT NOT NULL,
    id_compte INT NOT NULL,
    type ENUM('like', 'love', 'haha', 'wow', 'sad', 'angry') NOT NULL,
    FOREIGN KEY (id_publication) REFERENCES publication(id),
    FOREIGN KEY (id_compte) REFERENCES compte(id)
);

CREATE TABLE IF NOT EXISTS comment_reaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_comment INT NOT NULL,
    id_compte INT NOT NULL,
    type ENUM('like', 'love', 'haha', 'wow', 'sad', 'angry') NOT NULL,
    FOREIGN KEY (id_comment) REFERENCES comment(id),
    FOREIGN KEY (id_compte) REFERENCES compte(id)
);