CREATE DATABASE IF NOT EXISTS DKS DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE DKS;

CREATE DATABASE IF NOT EXISTS DKS DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE DKS;

-- Table passwords
DROP TABLE IF EXISTS `passwords`;
CREATE TABLE IF NOT EXISTS `passwords` (
  `id` int NOT NULL AUTO_INCREMENT,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,  -- Ajout du champ email
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pseudo` (`pseudo`),
  UNIQUE KEY `email` (`email`) -- Email unique
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `nom`, `prenom`, `pseudo`, `email`, `password`, `created_at`) VALUES
(7, 'su', 'sophie', 'sophie', 'susophie16@gmail.com', '$2y$10$gw90F9q9RynQVai06NrGm.HTZa5esDNKIxNUgw4Gae1oZyijlUoJ2', '2025-10-29 18:08:21');
COMMIT;

-- Table abonnements
DROP TABLE IF EXISTS `abonnements`;
CREATE TABLE IF NOT EXISTS `abonnements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `storage_size` int NOT NULL,  -- Taille en Go
  `price` decimal(10, 2) NOT NULL,  -- Prix en €
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
