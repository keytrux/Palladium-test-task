CREATE DATABASE palladium;

USE palladium;

CREATE TABLE type_groups(
    id_group INT PRIMARY KEY,
    name VARCHAR(100)
);

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE group_user (
    id_user INT,
    id_group INT,
    PRIMARY KEY (id_user, id_group),
    FOREIGN KEY (id_group) REFERENCES type_groups(id_group) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
);