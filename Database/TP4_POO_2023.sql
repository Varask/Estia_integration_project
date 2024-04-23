CREATE TABLE `employee` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255),
  `firstname` VARCHAR(255),
  `mail` VARCHAR(255),
  `id_role` INTEGER,
  `SommeTravailPasse` DECIMAL(10,2),
  `SommeTravailAVenir` DECIMAL(10,2),
  `created_at` TIMESTAMP,
  INDEX `idx_role` (`id_role`)
) ENGINE=InnoDB;

CREATE TABLE `Roles` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255),
  `price` DECIMAL
) ENGINE=InnoDB;

CREATE TABLE `Tasks` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255),
  `id_type` INTEGER,
  `id_state` INTEGER,
  `is_validated` BOOLEAN,
  `color` INT,
  `date_debut` DATE,
  `date_fin` DATE,
  `estimated_time` INT,
  `created_at` TIMESTAMP,
  INDEX `idx_type` (`id_type`),
  INDEX `idx_state` (`id_state`)
) ENGINE=InnoDB;

CREATE TABLE `Types` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE `States` (
  `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE `Assigned_Tasks` (
  `id_task` INTEGER,
  `id_employee` INTEGER,
  INDEX `idx_task` (`id_task`),
  INDEX `idx_employee` (`id_employee`),
  FOREIGN KEY (`id_task`) REFERENCES `Tasks` (`id`),
  FOREIGN KEY (`id_employee`) REFERENCES `employee` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `Security` (
  `id_employee` INT AUTO_INCREMENT,
  `password` VARCHAR(255),
  PRIMARY KEY (`id_employee`),
  FOREIGN KEY (`id_employee`) REFERENCES `employee` (`id`)
) ENGINE=InnoDB;
