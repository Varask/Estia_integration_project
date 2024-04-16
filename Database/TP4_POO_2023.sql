CREATE TABLE `employee` (
  `id` integer PRIMARY KEY,
  `name` varchar(255),
  `firstname` varchar(255),
  `id_role` varchar(255),
  `SommeTravailPasse` DECIMAL(10,2),
  `SommeTravailAVenir` DECIMAL(10,2),
  `created_at` timestamp
);

CREATE TABLE `Roles` (
  `id` integer PRIMARY KEY,
  `name` varchar(255),
  `price` decimal
);

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
  `created_at` timestamp
);

CREATE TABLE `Types` (
  `id` integer PRIMARY KEY,
  `name` varchar(255)
);

CREATE TABLE `States` (
  `id` integer PRIMARY KEY,
  `name` varchar(255)
);

CREATE TABLE `Assigned_Tasks` (
  `id_task` integer,
  `id_employee` integer
);

CREATE TABLE `Security` (
  `id_employee` int,
  `password` varchar(255)
);
