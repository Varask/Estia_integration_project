CREATE TABLE `employee` (
  `id` integer PRIMARY KEY,
  `name` varchar(255),
  `firstname` varchar(255),
  `id_role` varchar(255),
  `SommeTravailPasse` DECIMAL(10,2),
  `SommeTravailAVenir` DECIMAL(10,2),
  `created_at` timestamp,
  INDEX `idx_role` (`id_role`)
) ENGINE=InnoDB;

CREATE TABLE `Roles` (
  `id` integer PRIMARY KEY,
  `name` varchar(255),
  `price` decimal
) ENGINE=InnoDB;

CREATE TABLE `Tasks` (
  `id` integer PRIMARY KEY,
  `name` varchar(255),
  `id_type` integer,
  `id_state` integer,
  `is_validated` bool,
  `color` int,
  `date_debut` date,
  `date_fin` date,
  `estimated_time` int,
  `created_at` timestamp,
  INDEX `idx_type` (`id_type`),
  INDEX `idx_state` (`id_state`)
) ENGINE=InnoDB;

CREATE TABLE `Types` (
  `id` integer PRIMARY KEY,
  `name` varchar(255)
) ENGINE=InnoDB;

CREATE TABLE `States` (
  `id` integer PRIMARY KEY,
  `name` varchar(255)
) ENGINE=InnoDB;

CREATE TABLE `Assigned_Tasks` (
  `id_task` integer,
  `id_employee` integer,
  INDEX `idx_task` (`id_task`),
  INDEX `idx_employee` (`id_employee`),
  FOREIGN KEY (`id_task`) REFERENCES `Tasks` (`id`),
  FOREIGN KEY (`id_employee`) REFERENCES `employee` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `Security` (
  `id_employee` int,
  `password` varchar(255),
  PRIMARY KEY (`id_employee`),
  FOREIGN KEY (`id_employee`) REFERENCES `employee` (`id`)
) ENGINE=InnoDB;
