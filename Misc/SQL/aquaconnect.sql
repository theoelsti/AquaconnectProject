CREATE DATABASE IF NOT EXISTS aquaconnect;
USE aquaconnect;
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin int not null DEFAULT 0
)
CREATE TABLE offer_types (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    price VARCHAR(10) NOT NULL
)
INSERT INTO `offer_types` (`id`, `name`, `price`) VALUES ('0', 'Fish Starter', '25');
INSERT INTO `offer_types` (`id`, `name`, `price`) VALUES ('1', 'Fish Comfort', '50');
INSERT INTO `offer_types` (`id`, `name`, `price`) VALUES ('2', 'Fish Premium', '75');

CREATE TABLE current_contracts (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    label VARCHAR(50) DEFAULT null,
    contract_id INT,
    contract_end DATETIME DEFAULT null,
    hwid int not null
)
CREATE TABLE results (
    datetag DATETIME PRIMARY KEY not NULL,
    hwid int,
    ph decimal(4,1) NOT NULL,
    temp decimal(4,1) NOT NULL,
    lum decimal(4,1) NOT NULL   
)
CREATE TABLE changeValues (
    id int not null AUTO_INCREMENT PRIMARY KEY,
    hwid int,
    lum int,
    temp int 
)