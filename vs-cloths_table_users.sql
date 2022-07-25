
-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `us_id` int(11) NOT NULL,
  `us_nom` varchar(20) NOT NULL,
  `us_prenom` varchar(20) NOT NULL,
  `us_email` varchar(60) NOT NULL,
  `us_password` varchar(100) NOT NULL,
  `us_adresse` tinytext DEFAULT NULL,
  `us_phone` varchar(20) DEFAULT NULL,
  `us_pays` varchar(20) DEFAULT current_timestamp(),
  `us_ville` varchar(20) DEFAULT NULL,
  `us_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table contenant les clients (pas les pme)';

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`us_id`, `us_nom`, `us_prenom`, `us_email`, `us_password`, `us_adresse`, `us_phone`, `us_pays`, `us_ville`, `us_date`) VALUES
(1, 'Darius', 'Joto', 'joto@gmail.com', '$2y$10$oH0hKF.QNghVLlwwJ8NUl.9xO/0seIvOwixwU4YppL1TF9UAC7CF.', 'mon adresse de livraison', '06584578', '2021-05-09 01:02:20', NULL, '2021-05-09 01:02:20'),
(2, 'Le n  o m', 'Joto 2 lol', 'joto2@gmail.com', '$2y$10$znGBr8wzCQcdoKMxAwCanec2OopERDVI27vvgWyzFHgsG5n.SNpya', '', '', '2021-05-29 15:10:29', NULL, '2021-05-29 14:10:29');
