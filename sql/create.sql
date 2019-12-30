CREATE TABLE `Media` (
  `id` int(11) NOT NULL,
  `description` longtext NOT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `fileLocation` varchar(255) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `form` varchar(20) NOT NULL,
  `subject` varchar(40) NOT NULL,
  `title` varchar(255) NOT NULL,
  `uploadedOn` bigint(20) NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
