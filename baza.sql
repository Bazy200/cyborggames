SET NAMES utf8mb4;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `cyborggames`
    DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cyborggames`;

DROP TABLE IF EXISTS `proizvod`;
DROP TABLE IF EXISTS `korisnik`;
DROP TABLE IF EXISTS `uloga`;
DROP TABLE IF EXISTS `proizvodjac`;
DROP TABLE IF EXISTS `platforma`;
DROP TABLE IF EXISTS `zanr`;
DROP TABLE IF EXISTS `nav`;

CREATE TABLE `uloga` (
    `id`     INT(11) NOT NULL AUTO_INCREMENT,
    `naziv`  VARCHAR(30) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `uloga` (`id`, `naziv`) VALUES
    (1, 'admin'),
    (2, 'user');

CREATE TABLE `korisnik` (
    `id`        INT(11) NOT NULL AUTO_INCREMENT,
    `ime`       VARCHAR(30) NOT NULL,
    `prezime`   VARCHAR(30) NOT NULL,
    `email`     VARCHAR(100) NOT NULL,
    `lozinka`   VARCHAR(32) NOT NULL,
    `uloga_id`  INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    KEY `uloga_id` (`uloga_id`),
    CONSTRAINT `korisnik_uloga_fk` FOREIGN KEY (`uloga_id`) REFERENCES `uloga` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `korisnik` (`ime`, `prezime`, `email`, `lozinka`, `uloga_id`) VALUES
    ('Admin', 'Cyborg', 'admin@cyborg.rs', MD5('admin123'), 1),
    ('Veljko', 'Veljkovic', 'user@cyborg.rs',  MD5('user123'),  2);

CREATE TABLE `proizvodjac` (
    `id`     INT(11) NOT NULL AUTO_INCREMENT,
    `naziv`  VARCHAR(50) NOT NULL,
    `href`   VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `proizvodjac` (`id`, `naziv`, `href`) VALUES
    (1, 'Cd projekt red', 'https://www.cdprojektred.com/en'),
    (2, 'EA',             'https://www.2k.com/en-US/'),
    (3, 'Bethesda',       'https://www.activision.com/'),
    (4, 'Konami',         'https://www.2k.com/en-US/');

CREATE TABLE `platforma` (
    `id`     INT(11) NOT NULL AUTO_INCREMENT,
    `naziv`  VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `platforma` (`id`, `naziv`) VALUES
    (1, 'XBOX'),
    (2, 'PC'),
    (3, 'PLAYSTATION');

CREATE TABLE `zanr` (
    `id`     INT(11) NOT NULL AUTO_INCREMENT,
    `naziv`  VARCHAR(50) NOT NULL,
    `href`   VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `zanr` (`id`, `naziv`, `href`) VALUES
    (1, 'RPG',   'https://www.cdprojektred.com/en'),
    (2, 'Sport', 'https://www.2k.com/en-US/'),
    (3, 'FPS',   'https://www.activision.com/');

CREATE TABLE `nav` (
    `id`        INT(11) NOT NULL AUTO_INCREMENT,
    `href`      VARCHAR(50) NOT NULL,
    `label`     VARCHAR(50) NOT NULL,
    `redosled`  INT(11) DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `nav` (`href`, `label`, `redosled`) VALUES
    ('index.php', 'Home',  1),
    ('shop.php',  'Shop',  2),
    ('autor.php', 'Autor', 3);

CREATE TABLE `proizvod` (
    `id`             INT(11) NOT NULL AUTO_INCREMENT,
    `naziv`          VARCHAR(100) NOT NULL,
    `proizvodjac_id` INT(11) NOT NULL,
    `platforma_id`   INT(11) NOT NULL,
    `zanr_id`        INT(11) NOT NULL,
    `slika_src`      VARCHAR(255) NOT NULL,
    `slika_alt`      VARCHAR(100) DEFAULT NULL,
    `cena_stara`     INT(11) DEFAULT NULL,
    `cena_nova`      INT(11) DEFAULT NULL,
    `new_arrival`    TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `proizvodjac_id` (`proizvodjac_id`),
    KEY `platforma_id` (`platforma_id`),
    KEY `zanr_id` (`zanr_id`),
    CONSTRAINT `proizvod_proizvodjac_fk` FOREIGN KEY (`proizvodjac_id`) REFERENCES `proizvodjac` (`id`),
    CONSTRAINT `proizvod_platforma_fk`   FOREIGN KEY (`platforma_id`)   REFERENCES `platforma` (`id`),
    CONSTRAINT `proizvod_zanr_fk`        FOREIGN KEY (`zanr_id`)        REFERENCES `zanr` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `proizvod`
    (`id`, `naziv`, `proizvodjac_id`, `platforma_id`, `zanr_id`, `slika_src`, `slika_alt`, `cena_stara`, `cena_nova`, `new_arrival`) VALUES
    (1, 'Metal Gear Solid V',        4, 2, 3, 'assets/images/mgsv.jpg',          'mgsv',       15999, NULL, 0),
    (2, 'Skyrim',                    3, 2, 1, 'assets/images/skyrim.jpg',        'Skyrim',      NULL, 6999, 1),
    (3, 'Crysis 3',                  2, 2, 3, 'assets/images/crysis3.jpg',       'Crysis 3',    7999, NULL, 0),
    (4, 'Kingdom come: Deliverance', 2, 1, 1, 'assets/images/kcd.jpg',           'KCD',         8999, NULL, 1),
    (5, 'Fifa 24',                   2, 3, 2, 'assets/images/fifa24.jpg',        'Fifa 24',     8999, NULL, 0),
    (6, 'The Witcher 3: Wild Hunt',  1, 2, 1, 'assets/images/witcher3.jpg',      'Witcher 3',   8999, NULL, 0),
    (7, 'CyberPunk 2077',            1, 1, 3, 'assets/images/2077.jpg',          'CyberPunk',   8999, NULL, 0),
    (8, 'Fifa 24',                   2, 2, 1, 'assets/images/fifa24.jpg',        'Fifa 24',     8999, NULL, 0),
    (9, 'Death Stranding',           4, 3, 1, 'assets/images/deathstranding.jpg','Death Stranding', 8999, NULL, 0);

CREATE TABLE `porudzbina` (
    `id`             INT(11) NOT NULL AUTO_INCREMENT,
    `ime`            VARCHAR(50) NOT NULL,
    `prezime`        VARCHAR(50) NOT NULL,
    `email`          VARCHAR(255) NOT NULL,
    `nacin_placanja` VARCHAR(20) NOT NULL,
    `korisnik_id`    INT(11) DEFAULT NULL,
    `ukupno`         INT(11) NOT NULL,
    `datum`          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `korisnik_id` (`korisnik_id`),
    CONSTRAINT `porudzbina_korisnik_fk` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `stavka_porudzbine` (
    `id`            INT(11) NOT NULL AUTO_INCREMENT,
    `porudzbina_id` INT(11) NOT NULL,
    `proizvod_id`   INT(11) NOT NULL,
    `kolicina`      INT(11) NOT NULL,
    `cena`          INT(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `porudzbina_id` (`porudzbina_id`),
    KEY `proizvod_id` (`proizvod_id`),
    CONSTRAINT `stavka_porudzbina_fk` FOREIGN KEY (`porudzbina_id`) REFERENCES `porudzbina` (`id`) ON DELETE CASCADE,
    CONSTRAINT `stavka_proizvod_fk`   FOREIGN KEY (`proizvod_id`)   REFERENCES `proizvod` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
