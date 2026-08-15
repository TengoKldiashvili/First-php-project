-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 20, 2025 at 05:01 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ambioni`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `post_id` int(99) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `name`, `comment`, `email`, `post_id`, `created_at`) VALUES
(1, '22222', 'wnfsdifn', 'hello@gmail.com', 1, '2025-01-18 13:39:26'),
(2, 'idjfnsjdfn', 'hello', 'hoo@gmail.com', 1, '2025-01-18 13:50:48'),
(3, 'idjfnsjdfn', 'hello', 'hoo@gmail.com', 1, '2025-01-18 13:50:54'),
(4, 'idjfnsjdfn', 'hello', 'hoo@gmail.com', 1, '2025-01-18 13:50:58'),
(5, '123', 'ტენგო', 'tengo@mgial.com', 10, '2025-01-18 13:53:01'),
(7, 'giorgi tevdoradze', 'kargia', 'giorgitevdoradze@gmail.com', 7, '2025-01-18 17:17:11');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `message_text` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_name`, `sender_email`, `message_text`, `sent_at`) VALUES
(1, 'Tengo', 'tengo@gmail.com', 'kargi websitia', '2025-01-20 03:33:18'),
(2, 'Tengo', 'tengo@gmail.com', 'kargi websitia', '2025-01-20 03:34:54'),
(3, 'Tengo', 'tengo@gmail.com', 'kargi websitia', '2025-01-20 03:35:39'),
(4, 'Tengo', 'tengo@gmail.com', 'kargi websitia', '2025-01-20 03:35:47'),
(5, 'Tengo', 'tengo@gmail.com', 'kargi websitia', '2025-01-20 03:37:27'),
(6, 'Tengo', 'tengo@gmail.com', 'kargi websitia', '2025-01-20 03:38:15'),
(7, 'Tengo', 'tengo@gmail.com', 'kargi websitia', '2025-01-20 03:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `navs`
--

CREATE TABLE `navs` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `navs_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `navs`
--

INSERT INTO `navs` (`id`, `name`, `navs_description`) VALUES
(1, 'ჰაკათონები', 'ჰაკათონები და გუნდური ტექნოლოგიური გამოწვევები'),
(2, 'კონფერენციები', 'ტექნოლოგიური კონფერენციები და ფორუმები'),
(3, 'ვორქშოფები', 'პრაქტიკული ტექნოლოგიური ვორქშოფები');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `small_description` text NOT NULL,
  `description` text NOT NULL,
  `imgs` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `count` int(11) NOT NULL DEFAULT 0,
  `navs_id` int(11) NOT NULL,
  `video` text DEFAULT NULL,
  `event_date` datetime DEFAULT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `registration_url` text DEFAULT NULL,
  `organizer` varchar(255) DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `name`, `small_description`, `description`, `imgs`, `created_at`, `count`, `navs_id`, `video`, `event_date`, `registration_deadline`, `location`, `registration_url`, `organizer`) VALUES
(1, 'ჰაკათონი თბილისში — Smart City Challenge', '48-საათიანი ჰაკათონი ქალაქის ციფრული სერვისებისთვის.', 'მონაწილეები გუნდებად იმუშავებენ თბილისის ურბანული გამოწვევების ტექნოლოგიურ გადაწყვეტებზე. პროგრამა მოიცავს მენტორების სესიებს, პროტოტიპის შექმნას და ფინალურ პრეზენტაციებს.', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1200&q=80', '2026-08-01 10:00:00', 24, 1, NULL, '2026-09-12 10:00:00', '2026-09-08 23:59:00', 'თბილისი, ტექნოპარკი', 'https://example.com/register/tbilisi-hackathon', 'საქართველოს ინოვაციებისა და ტექნოლოგიების სააგენტო'),
(2, 'AI Workshop — პრაქტიკული გენერაციული ხელოვნური ინტელექტი', 'პრაქტიკული ვორქშოფი AI ხელსაწყოებისა და მოდელების გამოყენებაზე.', 'ვორქშოფზე მონაწილეები გაეცნობიან გენერაციული ხელოვნური ინტელექტის ძირითად შესაძლებლობებს და მცირე პრაქტიკულ პროექტს შექმნიან.', 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80', '2026-08-02 11:30:00', 18, 3, NULL, '2026-09-20 12:00:00', '2026-09-18 18:00:00', 'თბილისი, ინოვაციების ცენტრი', 'https://example.com/register/ai-workshop', 'AI Community Georgia'),
(3, 'Batumi Tech Talks 2026', 'ერთდღიანი შეხვედრა დეველოპერებისთვის, დიზაინერებისა და სტარტაპებისთვის.', 'ტექნოლოგიური კონფერენცია აერთიანებს სფეროს წარმომადგენლებს მოკლე გამოსვლების, პრაქტიკული გამოცდილების გაზიარებისა და ახალი პროფესიული კავშირებისთვის.', 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80', '2026-08-03 09:15:00', 31, 2, NULL, '2026-09-26 18:30:00', '2026-09-25 20:00:00', 'ბათუმი, ტექნოლოგიური ჰაბი', 'https://example.com/register/batumi-tech-talks', 'Batumi Tech Community'),
(7, 'საქართველოს ტექნოლოგიური კონფერენცია 2026', 'ერთდღიანი კონფერენცია პროგრამული უზრუნველყოფის, მონაცემებისა და კიბერუსაფრთხოების შესახებ.', 'ქართველი და საერთაშორისო სპიკერები ისაუბრებენ თანამედროვე პროგრამულ არქიტექტურაზე, მონაცემთა პროდუქტებსა და კიბერუსაფრთხოების პრაქტიკულ გამოცდილებაზე.', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80', '2026-08-04 14:00:00', 42, 2, NULL, '2026-10-03 10:00:00', '2026-09-28 23:59:00', 'თბილისი, ექსპო ჯორჯია', 'https://example.com/register/georgia-tech-conference', 'Tech World Georgia'),
(10, 'სტუდენტური ჰაკათონი — Campus Build', 'სტუდენტური გუნდების ჰაკათონი ინოვაციური ციფრული პროდუქტებისთვის.', 'უნივერსიტეტის სტუდენტები გუნდებად შექმნიან ვებ, მობილურ და მონაცემებზე დაფუძნებულ პროტოტიპებს. ფინალისტები საკუთარ ნამუშევრებს ჟიურის წინაშე წარადგენენ.', 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1200&q=80', '2026-08-05 16:20:00', 15, 1, NULL, '2026-10-10 11:00:00', '2026-10-01 18:00:00', 'თბილისი, უნივერსიტეტის ბიბლიოთეკა', 'https://example.com/register/campus-build', 'უნივერსიტეტის ინოვაციების ლაბორატორია');

-- --------------------------------------------------------

--
-- Table structure for table `reklama`
--

CREATE TABLE `reklama` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `imgs` text NOT NULL,
  `link` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reklama`
--

INSERT INTO `reklama` (`id`, `name`, `imgs`, `link`, `created_at`) VALUES
(1, 'reklama', 'src/imgs/reklama.png', '?contact', '2025-01-20 03:07:12'),
(2, 'reklama2', 'src/imgs/reklama.png', '?contact', '2025-01-20 02:44:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `reg_date`, `is_admin`) VALUES
(1, 'admin@example.com', 'admin', '$2y$12$oJrWK4FmS/0y0I5WXiTRRuiZuGJFh6GCQ0xXwVVvjuTxfpBgqMCJ6', '2025-01-20 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_event_views`
--

CREATE TABLE `user_event_views` (
  `id` int(11) NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `navs`
--
ALTER TABLE `navs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `navs_id` (`navs_id`);

--
-- Indexes for table `reklama`
--
ALTER TABLE `reklama`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_event_views`
--
ALTER TABLE `user_event_views`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_post` (`user_id`,`post_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `navs`
--
ALTER TABLE `navs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reklama`
--
ALTER TABLE `reklama`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_event_views`
--
ALTER TABLE `user_event_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`navs_id`) REFERENCES `navs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
