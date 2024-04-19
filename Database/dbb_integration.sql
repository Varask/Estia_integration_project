-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 19 avr. 2024 à 08:04
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dbb_integration`
--

-- --------------------------------------------------------

--
-- Structure de la table `assigned_tasks`
--

DROP TABLE IF EXISTS `assigned_tasks`;
CREATE TABLE IF NOT EXISTS `assigned_tasks` (
  `id_task` int DEFAULT NULL,
  `id_employee` int DEFAULT NULL,
  KEY `idx_task` (`id_task`),
  KEY `idx_employee` (`id_employee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `assigned_tasks`
--

INSERT INTO `assigned_tasks` (`id_task`, `id_employee`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `mail` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `id_role` int NOT NULL,
  `SommeTravailPasse` decimal(10,2) DEFAULT NULL,
  `SommeTravailAVenir` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_role` (`id_role`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `employee`
--

INSERT INTO `employee` (`id`, `name`, `firstname`, `mail`, `id_role`, `SommeTravailPasse`, `SommeTravailAVenir`, `created_at`) VALUES
(1, 'Fleuve', 'Guillaume', 'guillaume.fleuve@net.estia.fr', 3, '0.00', '0.00', '2024-04-19 07:02:11'),
(2, 'Ruisseau', 'Martin', 'martin.ruisseau@net.estia.fr', 4, '0.00', '0.00', '2024-04-19 07:02:34'),
(3, 'Musk', 'Matthieu', 'matthieu.musk@net.estia.fr', 2, '0.00', '0.00', '2024-04-19 07:02:34'),
(4, 'Gratlékouy', 'Sam', 'sam.gratlekouy@net.estia.fr', 1, '0.00', '0.00', '2024-04-19 07:05:53');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `price`) VALUES
(1, 'Project Manager', '100'),
(2, 'Project Owner', '95'),
(3, 'Developer', '50'),
(4, 'Intern', '15');

-- --------------------------------------------------------

--
-- Structure de la table `security`
--

DROP TABLE IF EXISTS `security`;
CREATE TABLE IF NOT EXISTS `security` (
  `id_employee` int NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_employee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `security`
--

INSERT INTO `security` (`id_employee`, `password`) VALUES
(1, 'fleuve'),
(2, 'ruisseau'),
(3, 'musk'),
(4, 'gratlekouy');

-- --------------------------------------------------------

--
-- Structure de la table `states`
--

DROP TABLE IF EXISTS `states`;
CREATE TABLE IF NOT EXISTS `states` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `states`
--

INSERT INTO `states` (`id`, `name`) VALUES
(1, 'Validated'),
(2, 'Current'),
(3, 'On hold'),
(4, 'Closed');

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `id_type` int DEFAULT NULL,
  `id_state` int DEFAULT NULL,
  `is_validated` tinyint(1) DEFAULT NULL,
  `color` int DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `estimated_time` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`id_type`),
  KEY `idx_state` (`id_state`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `id_type`, `id_state`, `is_validated`, `color`, `date_debut`, `date_fin`, `estimated_time`, `created_at`) VALUES
(1, 'Fix dev for release', 4, 2, 0, 0, '2024-04-18', '2024-08-08', 112, '2024-04-19 07:12:43');

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`id`, `name`) VALUES
(1, 'Graphism'),
(2, 'Documentation'),
(3, 'Requirement'),
(4, 'Development');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `assigned_tasks`
--
ALTER TABLE `assigned_tasks`
  ADD CONSTRAINT `assigned_tasks_ibfk_1` FOREIGN KEY (`id_task`) REFERENCES `tasks` (`id`),
  ADD CONSTRAINT `assigned_tasks_ibfk_2` FOREIGN KEY (`id_employee`) REFERENCES `employee` (`id`);

--
-- Contraintes pour la table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `security`
--
ALTER TABLE `security`
  ADD CONSTRAINT `security_ibfk_1` FOREIGN KEY (`id_employee`) REFERENCES `employee` (`id`);

--
-- Contraintes pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`id_state`) REFERENCES `states` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
