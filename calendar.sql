CREATE DATABASE calendar;

CREATE TABLE users (
username VARCHAR(50) NOT NULL,
password VARCHAR(50) NOT NULL,
UNIQUE key username (username),
PRIMARY KEY (username)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE events (
id INT UNSIGNED NOT NULL AUTO_INCREMENT,
username VARCHAR(50) NOT NULL,
title VARCHAR(100) NOT NULL,
time VARCHAR(10) NOT NULL,
day INT UNSIGNED NOT NULL,
month INT UNSIGNED NOT NULL,
year INT UNSIGNED NOT NULL,
sharedWith VARCHAR(50),
category VARCHAR(50),
PRIMARY KEY(id),
FOREIGN KEY(username) REFERENCES users(username)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;


CREATE user 'mod5admin'@'localhost' identified BY 'mod5admin';
GRANT SELECT,INSERT,UPDATE,DELETE on calendar.* to mod5admin@'localhost';