
-- --------------------------------------------------------

--
-- Structure de la table `pme`
--

CREATE TABLE `pme` (
  `pme_id` int(11) NOT NULL,
  `pme_nom` varchar(60) NOT NULL,
  `pme_email` varchar(60) NOT NULL,
  `pme_password` varchar(100) NOT NULL,
  `pme_phone` varchar(15) NOT NULL,
  `pme_logo` varchar(20) NOT NULL DEFAULT 'pme_default.png',
  `pme_banner` varchar(20) NOT NULL DEFAULT 'banner_default.png',
  `pme_description` tinytext NOT NULL DEFAULT 'Aucune description pour le moment',
  `pme_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Compte validé ou pas',
  `pme_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `pme`
--

INSERT INTO `pme` (`pme_id`, `pme_nom`, `pme_email`, `pme_password`, `pme_phone`, `pme_logo`, `pme_banner`, `pme_description`, `pme_active`, `pme_date`) VALUES
(1, 'PME fictive', 'joto@gmail.com', '$2y$10$ousrO0JaSGHUVffQvMmtuewftJTWBPZ3RIT3UAtyFktLwLgBrvqYG', '+212633937528', 'pme_default.png', 'banner_default.png', 'aLorem, ipsum dolor sit amet consectetur adipisicing elit. Nihil expedita, error dicta ipsa voluptate deserunt, voluptatibus fuga laborum dolore odio, hic animi nesciunt similique fugit assumenda totam eligendi deleniti ipsam.', 1, '2021-05-29 14:26:39'),
(2, 'dsd sds', 'joto@gmail.coma', '$2y$10$aQwTmOm5SeS3KmxceAnLa.b5kbuI3om8yvUWLhnswBiJWm2hdvzYS', '+212633937528', 'pme_default.png', 'banner_default.png', '\'Aucune description pour le moment\'', 0, '2021-05-29 14:28:30'),
(3, 'dsd sds', 'joto@gmail.comz', '$2y$10$mrtjTd80Wxb1s2USSWn5meUiQ/fH4e2Hswf1EfQCDSk9lOzmRtyIu', '06584578', 'pme_default.png', 'banner_default.png', '\'Aucune description pour le moment\'', 0, '2021-05-29 14:29:49');
