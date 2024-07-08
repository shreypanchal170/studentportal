-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2017 at 08:22 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `istudent_db`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAllDegrees` (IN `inst_id` INT(11))  BEGIN
 select * 
 from degree 
 where institute_id=inst_id;
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetContactList` (IN `login_user` INT(11))  BEGIN
 SELECT * 
 FROM user
 JOIN contact
 ON contact_id = id
 WHERE user_id = login_user;
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetMsgList` (IN `from_id` INT(11), IN `login_user` INT(11))  BEGIN
 SELECT * 
 FROM chat
 WHERE (sent_from = from_id and sent_to = login_user) or (sent_from = login_user and sent_to = from_id);
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Login` (IN `email` TEXT, IN `passwd` VARCHAR(32))  BEGIN
 SELECT * 
 FROM user
 WHERE email_id = email and password = passwd;
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `msg_counter` (IN `id` INT(11))  BEGIN
 SELECT * 
 FROM chat
 WHERE sent_to = id;
 END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(10) UNSIGNED NOT NULL,
  `sent_to` int(11) NOT NULL,
  `sent_from` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`, `sent_to`, `sent_from`, `message`, `sent_date`) VALUES
(111, 2, 1, 'hello', '2017-06-04 04:07:35'),
(112, 5, 1, 'Excuse me?', '2017-06-04 21:46:56'),
(113, 1, 5, 'yes?', '2017-06-05 03:40:24'),
(114, 5, 1, 'Hello???', '2017-06-05 03:41:39'),
(115, 1, 5, 'yupp??? -_-', '2017-06-05 03:42:36'),
(120, 6, 1, 'hello', '2017-06-05 08:20:34'),
(121, 7, 1, 'Hello', '2017-06-05 11:36:37');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `user_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`user_id`, `contact_id`) VALUES
(1, 5),
(1, 6),
(5, 1),
(5, 6),
(5, 7),
(5, 8),
(5, 2),
(1, 2),
(1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `degree`
--

CREATE TABLE `degree` (
  `degree_id` int(11) NOT NULL,
  `degree_name` varchar(50) NOT NULL,
  `institute_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `degree`
--

INSERT INTO `degree` (`degree_id`, `degree_name`, `institute_id`) VALUES
(1, 'Bachelors in Computer Science', 7),
(2, 'Software Engineering', 7),
(3, 'Chemical Engineering', 7),
(4, 'Masters in Business Administration (MBA)', 7),
(5, 'Software Engineering', 8),
(6, 'MSc. Mining Engineering', 8),
(7, 'M.Phil. Applied Mathematics', 8);

-- --------------------------------------------------------

--
-- Table structure for table `has_degree`
--

CREATE TABLE `has_degree` (
  `student_id` int(11) NOT NULL,
  `degree_id` int(11) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `from_year` int(11) NOT NULL,
  `to_year` int(11) NOT NULL,
  `institute_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `has_degree`
--

INSERT INTO `has_degree` (`student_id`, `degree_id`, `verified`, `from_year`, `to_year`, `institute_id`) VALUES
(1, 1, 0, 2015, 2019, 7),
(1, 6, 0, 2017, 2021, 8),
(1, 3, 0, 1999, 2015, 7),
(5, 1, 0, 2015, 2019, 7);

-- --------------------------------------------------------

--
-- Table structure for table `has_document`
--

CREATE TABLE `has_document` (
  `student_id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `doc_name` varchar(30) NOT NULL,
  `file` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `has_interest`
--

CREATE TABLE `has_interest` (
  `student_id` int(11) NOT NULL,
  `interest` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `has_interest`
--

INSERT INTO `has_interest` (`student_id`, `interest`) VALUES
(1, 'Blogging'),
(1, 'Reading');

-- --------------------------------------------------------

--
-- Table structure for table `has_job`
--

CREATE TABLE `has_job` (
  `student_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `has_skill`
--

CREATE TABLE `has_skill` (
  `student_id` int(11) NOT NULL,
  `skill` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `has_skill`
--

INSERT INTO `has_skill` (`student_id`, `skill`) VALUES
(5, 'Data analyst'),
(1, 'Front End Developer'),
(1, 'Python Expert'),
(1, 'Sleeping');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `job_id` int(11) NOT NULL,
  `job_title` varchar(50) NOT NULL,
  `location` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `salary` int(11) NOT NULL,
  `job_type` varchar(20) NOT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `email_id` text NOT NULL,
  `password` varchar(32) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `user_type` enum('student','institute','company') NOT NULL,
  `img_url` varchar(255) NOT NULL DEFAULT 'images/dp.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `user_name`, `email_id`, `password`, `contact_no`, `user_type`, `img_url`) VALUES
(1, 'Asad Ali', 'Asad', 'asad@gmail.com', 'asad', '03025855267', 'student', 'https://2.bp.blogspot.com/-oPwiFzGzO_o/V8lWeLItEiI/AAAAAAAADb4/tFg849jD-T0mCsPYvr8KrEEmTu3YZLMJACLcB/s1600/best-whatsapp-dp-quotes.jpg'),
(2, 'mujeeb', 'mujeeb', 'mujeeb@gmail.com', 'asad', '0300-2222111', 'student', 'https://www.iconfinder.com/data/icons/freeline/32/account_friend_human_man_member_person_profile_user_users-256.png'),
(5, 'Ajwad', 'ajwad.striker', 'ajwad@gmail.com', 'ajwad', '0301-1111111', 'company', 'images/dp.png'),
(6, 'Random user', 'random', 'company@gmail.com', 'company', '0', 'company', 'images/dp.png'),
(7, 'National University of Science and Technology, H-12, Islamabad', 'nust', 'admissions@nust.edu.pk', 'nust', '+92-51-90856878', 'institute', 'https://upload.wikimedia.org/wikipedia/en/thumb/2/22/NUST_Vector.svg/1026px-NUST_Vector.svg.png'),
(8, 'University of Engineering and Technology, Lahore', 'uet.lahore', 'admissions@uet.edu.pk', 'uetlahore', '042-99029245', 'institute', 'https://upload.wikimedia.org/wikipedia/en/thumb/b/b0/University_of_Engineering_and_Technology_Lahore_logo.svg/1018px-University_of_Engineering_and_Technology_Lahore_logo.svg.png'),
(9, 'Usman', 'baou.usman', 'usman@gmail.com', 'baou', '', 'student', 'images/dp.png'),
(10, 'Mubeen Butt', 'mubeen', 'mubeen@gmail.com', 'mubeen', '', 'student', 'images/dp.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sent_to` (`sent_to`),
  ADD KEY `sent_from` (`sent_from`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `contact_id` (`contact_id`);

--
-- Indexes for table `degree`
--
ALTER TABLE `degree`
  ADD PRIMARY KEY (`degree_id`),
  ADD KEY `institute_id` (`institute_id`);

--
-- Indexes for table `has_degree`
--
ALTER TABLE `has_degree`
  ADD KEY `has_degree_ibfk_1` (`student_id`),
  ADD KEY `has_degree_ibfk_2` (`institute_id`),
  ADD KEY `has_degree_ibfk_3` (`degree_id`);

--
-- Indexes for table `has_document`
--
ALTER TABLE `has_document`
  ADD UNIQUE KEY `doc_id` (`doc_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `has_interest`
--
ALTER TABLE `has_interest`
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `has_job`
--
ALTER TABLE `has_job`
  ADD KEY `student_id` (`student_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `has_skill`
--
ALTER TABLE `has_skill`
  ADD KEY `has_skill_ibfk_1` (`student_id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
--
-- AUTO_INCREMENT for table `degree`
--
ALTER TABLE `degree`
  MODIFY `degree_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`sent_to`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `chat_ibfk_2` FOREIGN KEY (`sent_from`) REFERENCES `user` (`id`);

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `degree`
--
ALTER TABLE `degree`
  ADD CONSTRAINT `degree_ibfk_1` FOREIGN KEY (`institute_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `has_degree`
--
ALTER TABLE `has_degree`
  ADD CONSTRAINT `has_degree_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `has_degree_ibfk_2` FOREIGN KEY (`institute_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `has_document`
--
ALTER TABLE `has_document`
  ADD CONSTRAINT `has_document_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `has_interest`
--
ALTER TABLE `has_interest`
  ADD CONSTRAINT `has_interest_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `has_job`
--
ALTER TABLE `has_job`
  ADD CONSTRAINT `has_job_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `has_job_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `job` (`job_id`);

--
-- Constraints for table `has_skill`
--
ALTER TABLE `has_skill`
  ADD CONSTRAINT `has_skill_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `job_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
