-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema spravacd_databaze
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema spravacd_databaze
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `spravacd_databaze` DEFAULT CHARACTER SET utf8 ;
USE `spravacd_databaze` ;

-- -----------------------------------------------------
-- Table `spravacd_databaze`.`album`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spravacd_databaze`.`album` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nazev` VARCHAR(100) NOT NULL,
  `autor` VARCHAR(100) NOT NULL,
  `datum_vydani` DATE NOT NULL,
  `smazano` TINYINT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spravacd_databaze`.`stopa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spravacd_databaze`.`stopa` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `poradi_album` INT NOT NULL,
  `nazev` VARCHAR(100) NOT NULL,
  `delka` INT NOT NULL,
  `smazano` TINYINT NOT NULL,
  `album_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_stopa_album_idx` (`album_id` ASC),
  CONSTRAINT `fk_stopa_album`
    FOREIGN KEY (`album_id`)
    REFERENCES `spravacd_databaze`.`album` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `spravacd_databaze`.`uzivatel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `spravacd_databaze`.`uzivatel` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(100) NOT NULL,
  `heslo` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `spravacd_databaze`.`album`
-- -----------------------------------------------------
START TRANSACTION;
USE `spravacd_databaze`;
INSERT INTO `spravacd_databaze`.`album` (`id`, `nazev`, `autor`, `datum_vydani`, `smazano`) VALUES (1, 'Minutes to Midnight', 'Linkin Park', '2007-01-03', 0);
INSERT INTO `spravacd_databaze`.`album` (`id`, `nazev`, `autor`, `datum_vydani`, `smazano`) VALUES (2, 'Meteora', 'Linkin Park', '2003-05-10', 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `spravacd_databaze`.`stopa`
-- -----------------------------------------------------
START TRANSACTION;
USE `spravacd_databaze`;
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (1, 1, 'Wake', 100, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (2, 2, 'Given Up', 189, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (3, 3, 'Leave Out All the Rest', 209, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (4, 4, 'Bleed It Out', 164, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (5, 5, 'Shadow of the Day', 289, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (6, 6, 'What I\'ve Done', 205, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (7, 7, 'Hands Held High', 233, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (8, 8, 'No More Sorrow', 221, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (9, 9, 'Valentine\'s Day', 196, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (10, 10, 'In Between', 196, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (11, 11, 'In Pieces', 218, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (12, 12, 'The Little Things Give You Away', 383, 0, 1);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (13, 1, 'Foreword', 13, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (14, 2, 'Don\'t Stay', 187, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (15, 3, 'Somewhere I Belong', 213, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (16, 4, 'Lying from You', 175, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (17, 5, 'Hit the Floor', 164, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (18, 6, 'Easier to Run', 204, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (19, 7, 'Faint', 162, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (20, 8, 'Figure.09', 197, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (21, 9, 'Breaking the Habit', 196, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (22, 10, 'From the Inside', 175, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (23, 11, 'Nobody\'s Listening', 178, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (24, 12, 'Session', 144, 0, 2);
INSERT INTO `spravacd_databaze`.`stopa` (`id`, `poradi_album`, `nazev`, `delka`, `smazano`, `album_id`) VALUES (25, 13, 'Numb', 187, 0, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `spravacd_databaze`.`uzivatel`
-- -----------------------------------------------------
START TRANSACTION;
USE `spravacd_databaze`;
INSERT INTO `spravacd_databaze`.`uzivatel` (`id`, `login`, `heslo`) VALUES (1, 'admin', 'adminpass');
INSERT INTO `spravacd_databaze`.`uzivatel` (`id`, `login`, `heslo`) VALUES (2, 'user', 'userpass');

COMMIT;

