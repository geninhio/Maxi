-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema maxi
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `maxi` ;

-- -----------------------------------------------------
-- Schema maxi
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `maxi` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------

USE `maxi` ;

-- -----------------------------------------------------
-- Table `maxi`.`sexe`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`sexe` (
  `id_sexe` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_sexe`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`role` (
  `id_role` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_role`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`departement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`departement` (
  `id_departement` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_departement`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`poste`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`poste` (
  `id_poste` INT NOT NULL AUTO_INCREMENT,
  `id_departement` INT NOT NULL,
  `nom` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_poste`),
  INDEX `fk_id_poste_id_departement_idx` (`id_departement` ASC) VISIBLE,
  CONSTRAINT `fk_id_poste_id_departement`
    FOREIGN KEY (`id_departement`)
    REFERENCES `maxi`.`departement` (`id_departement`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`employes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`employes` (
  `id_employes` INT NOT NULL AUTO_INCREMENT,
  `id_sexe` INT NOT NULL,
  `id_role` INT NOT NULL,
  `id_poste` INT NOT NULL,
  `prenom` VARCHAR(150) NOT NULL,
  `nom` VARCHAR(150) NOT NULL,
  `date_naissance` DATE NOT NULL,
  `id_hash` VARCHAR(45) NOT NULL,
  `hash` TEXT NOT NULL,
  `actif` INT NOT NULL,
  PRIMARY KEY (`id_employes`),
  INDEX `fk_id_employe_id_sexe_idx` (`id_sexe` ASC) VISIBLE,
  INDEX `fk_id_employe_id_role_idx` (`id_role` ASC) VISIBLE,
  INDEX `fk_id_employe_id_poste_idx` (`id_poste` ASC) VISIBLE,
  CONSTRAINT `fk_id_employe_id_sexe`
    FOREIGN KEY (`id_sexe`)
    REFERENCES `maxi`.`sexe` (`id_sexe`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_id_employe_id_role`
    FOREIGN KEY (`id_role`)
    REFERENCES `maxi`.`role` (`id_role`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_id_employe_id_poste`
    FOREIGN KEY (`id_poste`)
    REFERENCES `maxi`.`poste` (`id_poste`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`marque`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`marque` (
  `id_marque` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_marque`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`produit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`produit` (
  `id_produit` INT NOT NULL AUTO_INCREMENT,
  `id_marque` INT NOT NULL,
  `nom` VARCHAR(150) NOT NULL,
  `prix` DECIMAL(10,2) NOT NULL,
  `quantite` INT NOT NULL,
  `actif` INT NOT NULL,
  PRIMARY KEY (`id_produit`),
  INDEX `fk_id_produit_id_marque_idx` (`id_marque` ASC) VISIBLE,
  CONSTRAINT `fk_id_produit_id_marque`
    FOREIGN KEY (`id_marque`)
    REFERENCES `maxi`.`marque` (`id_marque`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`panier`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`panier` (
  `id_panier` INT NOT NULL AUTO_INCREMENT,
  `id_employe` INT NOT NULL,
  `date_creation` DATE NOT NULL,
  `actif` INT NOT NULL,
  PRIMARY KEY (`id_panier`),
  INDEX `fk_id_panier_id_employe_idx` (`id_employe` ASC) VISIBLE,
  CONSTRAINT `fk_id_panier_id_employe`
    FOREIGN KEY (`id_employe`)
    REFERENCES `maxi`.`employes` (`id_employes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`panier_produit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`panier_produit` (
  `id_panier` INT NOT NULL,
  `id_produit` INT NOT NULL,
  `quantite` INT NOT NULL,
  PRIMARY KEY (`id_panier`, `id_produit`),
  INDEX `fk_panier_produit_id_produit_idx` (`id_produit` ASC) VISIBLE,
  CONSTRAINT `fk_panier_produit_id_panier`
    FOREIGN KEY (`id_panier`)
    REFERENCES `maxi`.`panier` (`id_panier`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_panier_produit_id_produit`
    FOREIGN KEY (`id_produit`)
    REFERENCES `maxi`.`produit` (`id_produit`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`action`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`action` (
  `id_action` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id_action`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `maxi`.`journalisation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `maxi`.`journalisation` (
  `id_journalisation` INT NOT NULL AUTO_INCREMENT,
  `id_action` INT NOT NULL,
  `id_employe` INT NOT NULL,
  `date` DATETIME NOT NULL,
  `message` TEXT NOT NULL,
  PRIMARY KEY (`id_journalisation`),
  INDEX `fk_id_audit_id_action_idx` (`id_action` ASC) VISIBLE,
  CONSTRAINT `fk_id_journalisation_id_action`
    FOREIGN KEY (`id_action`)
    REFERENCES `maxi`.`action` (`id_action`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Data for table `maxi`.`action`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`action` (`id_action`, `nom`) VALUES (1, 'Ajout');
INSERT INTO `maxi`.`action` (`id_action`, `nom`) VALUES (2, 'Suppression');
INSERT INTO `maxi`.`action` (`id_action`, `nom`) VALUES (3, 'Consultation');

COMMIT;

-- -----------------------------------------------------
-- Data for table `maxi`.`sexe`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`sexe` (`id_sexe`, `nom`) VALUES (1, 'masculin');
INSERT INTO `maxi`.`sexe` (`id_sexe`, `nom`) VALUES (2, 'féminin');

COMMIT;


-- -----------------------------------------------------
-- Data for table `maxi`.`role`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`role` (`id_role`, `nom`) VALUES (1, 'superviseur');
INSERT INTO `maxi`.`role` (`id_role`, `nom`) VALUES (2, 'commis');

COMMIT;


-- -----------------------------------------------------
-- Data for table `maxi`.`departement`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`departement` (`id_departement`, `nom`) VALUES (1, 'Viandes');
INSERT INTO `maxi`.`departement` (`id_departement`, `nom`) VALUES (2, 'Legumes');

COMMIT;


-- -----------------------------------------------------
-- Data for table `maxi`.`poste`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`poste` (`id_poste`, `id_departement`, `nom`) VALUES (1, 1, 'Boucher junior');
INSERT INTO `maxi`.`poste` (`id_poste`, `id_departement`, `nom`) VALUES (2, 1, 'Boucher sénior');

COMMIT;


-- -----------------------------------------------------
-- Data for table `maxi`.`employes`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`employes` (`id_employes`, `id_sexe`, `id_role`, `id_poste`, `prenom`, `nom`, `date_naissance`, `id_hash`, `hash`, `actif`) VALUES (1, 2, 2, 1, 'Francine', 'Tf', '2000-02-12', '4d2fe', '$2y$12$x01J2MhZlNSERYid9yXPyOmF02hyhH62ZMUZ5fPRQ7A7PlTv/SSKC', 1);
INSERT INTO `maxi`.`employes` (`id_employes`, `id_sexe`, `id_role`, `id_poste`, `prenom`, `nom`, `date_naissance`, `id_hash`, `hash`, `actif`) VALUES (2, 1, 1, 2, 'Jean-Michel', 'Laliberté', '1990-08-19', '7736e', '$2y$12$lbZVmDcvqLzV3KAcTlpHK.AHqbXrdEZGt1aKrzz/ia2/zek.I5h0W', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `maxi`.`marque`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`marque` (`id_marque`, `nom`) VALUES (1, 'Nestlé');
INSERT INTO `maxi`.`marque` (`id_marque`, `nom`) VALUES (2, 'BoucherHalal');

COMMIT;


-- -----------------------------------------------------
-- Data for table `maxi`.`produit`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`produit` (`id_produit`, `id_marque`, `nom`, `prix`, `quantite`, `actif`) VALUES (1, 2, 'steak', 23.65, 62, 1);
INSERT INTO `maxi`.`produit` (`id_produit`, `id_marque`, `nom`, `prix`, `quantite`, `actif`) VALUES (2, 1, 'lait', 14.25, 150, 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `maxi`.`panier`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`panier` (`id_panier`, `id_employe`, `date_creation`, `actif`) VALUES (1, 1, '2025-10-27', 1);
INSERT INTO `maxi`.`panier` (`id_panier`, `id_employe`, `date_creation`, `actif`) VALUES (2, 2, '2025-10-27', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `maxi`.`panier_produit`
-- -----------------------------------------------------
START TRANSACTION;
USE `maxi`;
INSERT INTO `maxi`.`panier_produit` (`id_panier`, `id_produit`, `quantite`) VALUES (1, 1, 5);
INSERT INTO `maxi`.`panier_produit` (`id_panier`, `id_produit`, `quantite`) VALUES (1, 2, 6);
INSERT INTO `maxi`.`panier_produit` (`id_panier`, `id_produit`, `quantite`) VALUES (2, 1, 4);

COMMIT;


CREATE USER 'lire_Maxi'@'%' IDENTIFIED BY '8%5VU6C6%bA3';
GRANT SELECT, EXECUTE ON maxi.* TO 'lire_Maxi'@'%';

CREATE USER 'ecrire_Maxi'@'%' IDENTIFIED BY '8%5VU6C6%bA3';
GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON maxi.* TO 'ecrire_Maxi'@'%';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
