-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2020 at 07:01 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET
  time_zone = "+00:00";
  /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
  /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
  /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
  /*!40101 SET NAMES utf8mb4 */;
--
  -- Database: `todosdb`
  --
  -- --------------------------------------------------------
  --
  -- Table structure for table `todos`
  --
  CREATE TABLE `todos` (
    `id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `td_title` varchar(255) DEFAULT NULL,
    `td_content` text NOT NULL,
    `created_at` datetime DEFAULT current_timestamp(),
    `last_updated` datetime DEFAULT current_timestamp()
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
--
  -- Dumping data for table `todos`
  --
INSERT INTO
  `todos` (
    `id`,
    `user_id`,
    `td_title`,
    `td_content`,
    `created_at`,
    `last_updated`
  )
VALUES
  (
    2,
    10,
    'qezrfqerq',
    'eqervqerv\neqvev\ne\nv\nesbvsebsebgsezb\nsebzeb',
    '2020-11-26 13:51:59',
    '2020-11-26 13:51:59'
  ),
  (
    3,
    10,
    'qzdf',
    'qzfazr',
    '2020-11-26 13:58:47',
    '2020-11-26 14:53:11'
  );
-- --------------------------------------------------------
  --
  -- Table structure for table `users`
  --
  CREATE TABLE `users` (
    `id` int(11) NOT NULL,
    `full_name` varchar(255) NOT NULL,
    `email` varchar(320) NOT NULL,
    `passwd_hash` varchar(64) NOT NULL,
    `last_login` datetime DEFAULT current_timestamp(),
    `profile_img_url` text DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
--
  -- Dumping data for table `users`
  --
  /*
    INSERT INTO `users` (`id`, `full_name`, `email`, `passwd_hash`, `last_login`, `profile_img_url`) VALUES
    (10, 'Mourad EL CADI', 'mourad@mail.com', '2ec3slf934ad4d53719c88fjndc62e35da777763aba0d6ca87ad9e4b4a73f9a0', '2020-11-28 11:46:06', '\\usersProfiles\\1606559834_10.png');
    */
  --
  -- Indexes for dumped tables
  --
  --
  -- Indexes for table `todos`
  --
ALTER TABLE
  `todos`
ADD
  PRIMARY KEY (`id`),
ADD
  UNIQUE KEY `id` (`id`),
ADD
  KEY `user_id` (`user_id`);
--
  -- Indexes for table `users`
  --
ALTER TABLE
  `users`
ADD
  PRIMARY KEY (`id`),
ADD
  UNIQUE KEY `id` (`id`);
--
  -- AUTO_INCREMENT for dumped tables
  --
  --
  -- AUTO_INCREMENT for table `todos`
  --
ALTER TABLE
  `todos`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 4;
--
  -- AUTO_INCREMENT for table `users`
  --
ALTER TABLE
  `users`
MODIFY
  `id` int(11) NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 13;
--
  -- Constraints for dumped tables
  --
  --
  -- Constraints for table `todos`
  --
ALTER TABLE
  `todos`
ADD
  CONSTRAINT `todos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;
  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
  /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;