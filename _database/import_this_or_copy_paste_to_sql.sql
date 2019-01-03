CREATE DATABASE `ci_contact_book`;
USE `ci_contact_book`;

CREATE TABLE `contact_list` (
  `id` int(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `phone_number` char(15) NOT NULL,
  `name` varchar(30) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `contact_list` (`id`, `phone_number`, `name`, `gender`, `email`, `address`) VALUES
(1, '0861239123611', 'SimpleCodz', 'female', 'SimpleCodz@gmail.com', 'Indonesia'),
(2, '0812671657112', 'Chitanda Eru', 'female', 'eru.chitanda@gmail.co.jp', 'Okinawa');