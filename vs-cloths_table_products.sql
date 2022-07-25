
-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `pr_id` int(11) NOT NULL,
  `pme_id` int(11) NOT NULL COMMENT 'id pme',
  `pr_title` varchar(60) NOT NULL,
  `pr_description` text NOT NULL,
  `pr_prix` float NOT NULL,
  `pr_photo_1` varchar(20) NOT NULL,
  `pr_photo_2` varchar(20) DEFAULT NULL,
  `pr_photo_3` varchar(20) DEFAULT NULL,
  `pr_type` varchar(20) NOT NULL,
  `pr_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`pr_id`, `pme_id`, `pr_title`, `pr_description`, `pr_prix`, `pr_photo_1`, `pr_photo_2`, `pr_photo_3`, `pr_type`, `pr_date`) VALUES
(1, 1, 'Produit test 1', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid modi, blanditiis dolorum saepe inventore ab omnis dicta cupiditate earum laboriosam ipsa, et expedita delectus. Harum dolorem cupiditate a. Ratione, libero?', 200, '1.png', '2.png', NULL, 'laitier', '2021-05-07 20:38:41'),
(2, 1, 'Produit test 2', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid modi, blanditiis dolorum saepe inventore ab omnis dicta cupiditate earum laboriosam ipsa, et expedita delectus. Harum dolorem cupiditate a. Ratione, libero?', 299, '2.png', '4.png', '5.png', 'laitier', '2021-05-07 20:38:41'),
(3, 1, 'Produit test 3', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid modi, blanditiis dolorum saepe inventore ab omnis dicta cupiditate earum laboriosam ipsa, et expedita delectus. Harum dolorem cupiditate a. Ratione, libero?', 150, '3.png', '1.png', '7.png', 'legume', '2021-05-07 20:38:41'),
(4, 1, 'Produit test 4', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid modi, blanditiis dolorum saepe inventore ab omnis dicta cupiditate earum laboriosam ipsa, et expedita delectus. Harum dolorem cupiditate a. Ratione, libero?', 559, '4.png', '1.png', '6.png', 'argume', '2021-05-07 20:38:41'),
(5, 1, 'Produit test 5', '            Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid modi, blanditiis dolorum saepe inventore ab omnis dicta cupiditate earum laboriosam ipsa, et expedita delectus. Harum dolorem cupiditate a. Ratione, libero?<br />\r\nlaboriosam ipsa, et expedita delectus. Harum dolorem cupiditate a. Ratione, libero?        ', 150, '5.png', '', '', 'laitier', '2021-05-07 20:38:41'),
(6, 1, 'Produit test 6', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid modi, blanditiis dolorum saepe inventore ab omnis dicta cupiditate earum laboriosam ipsa, et expedita delectus. Harum dolorem cupiditate a. Ratione, libero?', 120, '7.png', '6.png', '1.png', 'legume', '2021-05-07 20:38:41'),
(7, 1, 'Produit test 8', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid modi, blanditiis dolorum saepe inventore ab omnis dicta cupiditate earum laboriosam ipsa, et expedita delectus. Harum dolorem cupiditate a. Ratione, libero?', 799, '8.png', '', '', 'legume', '2021-05-07 20:38:41'),
(8, 1, 'Produit test 9', '            Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid modi, blanditiis dolorum saepe inventore ab omnis dicta cupiditate earum laboriosam ipsa, et expedita delectus. Harum dolorem cupiditate a. Ratione, libero?                                ', 120, '1622413559.png', '', '', 'argume', '2021-05-07 20:38:41'),
(10, 1, 'test product', '            Some desc<br />\r\nAnd more        ', 14, '1622346265.png', '1622413921.png', '1622413921.png', 'laitier', '2021-05-30 03:44:25'),
(11, 1, 'test product again', '            SOme desc        ', 55, '1622346323.png', '1622496020.png', 'Erreur, format image', 'legume', '2021-05-30 03:45:23'),
(12, 1, 'test product for that', 'Again some desc', 45, '1622346384.png', NULL, NULL, 'argume', '2021-05-30 03:46:24'),
(17, 1, 'Quelque chose', 'Aucune desc ', 47, '1622414660.png', '1622414661.png', '1622414662.png', 'laitier', '2021-05-30 22:44:23'),
(18, 1, 'Test de produit', 'La description ici haha', 49.5, '1622414741.png', '1622414742.png', '1622414743.png', 'legume', '2021-05-30 22:45:44'),
(21, 1, 'Test produit 4', 'Test produit ', 45.99, '1622417018.png', NULL, NULL, 'laitier', '2021-05-30 23:23:41'),
(22, 1, 'Test produit 5', 'Test produit ', 85, '1622417040.png', NULL, NULL, 'laitier', '2021-05-30 23:24:04'),
(23, 1, 'Test produit 9', 'Test produit ', 96, '1622417061.png', NULL, NULL, 'laitier', '2021-05-30 23:24:24'),
(24, 1, 'Test produit 8', 'Test produit ', 48, '1622417095.png', NULL, NULL, 'argume', '2021-05-30 23:24:58'),
(25, 1, 'Test produit 6', 'Test produit ', 58.99, '1622417126.png', '1622417127.png', NULL, 'legume', '2021-05-30 23:25:29');
