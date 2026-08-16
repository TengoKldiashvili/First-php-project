-- Cleaned Tech World demo database
-- Generated for bachelor presentation

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `tech_world`

-- --------------------------------------------------------

-- Table structure for table `navs`
-- (categories)
CREATE TABLE `navs` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `navs_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `navs` (`id`, `name`, `navs_description`) VALUES
(1, 'ჰაკათონები', 'ჰაკათონები და გუნდური ტექნოლოგიური გამოწვევები'),
(2, 'კონფერენციები', 'ტექნოლოგიური კონფერენციები და ფორუმები'),
(3, 'ვორქშოფები', 'პრაქტიკული ტექნოლოგიური ვორქშოფები');

-- --------------------------------------------------------

-- Table structure for table `posts`
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

-- Demo events (12 items)
INSERT INTO `posts` (`id`, `name`, `small_description`, `description`, `imgs`, `created_at`, `count`, `navs_id`, `video`, `event_date`, `registration_deadline`, `location`, `registration_url`, `organizer`, `is_approved`) VALUES
(1, 'Tech Innovators Hackathon — Tbilisi', '48-საათიანი ჰაკათონი ურბანული სერვისებისთვის.', 'გაერთიანებული გუნდები შექმნიან პროტოტიპებს ქალაქის გამოწვევებისთვის. ფოკუსი: საჯარო მონაცემები და სერვისების ინტეგრაცია.', 'src/imgs/placeholder-event.png', '2026-06-01 09:00:00', 0, 1, NULL, '2026-09-12 10:00:00', '2026-09-08 23:59:00', 'თბილისი, ტექნოპარკი', 'https://example.com/register/tech-innovators', 'Tech Innovators', 1),
(2, 'AI Practical Workshop — Tbilisi', 'პრაქტიკული ვორქშოფი AI(tooling).', 'ვორქშოფზე მონაწილეები სამუშაო ჯგუფების მეშვეობით შეისწავლიან მახასიათებლებს და შექმნიან მცირე პროექტებს.', 'src/imgs/placeholder-event.png', '2026-06-02 10:30:00', 0, 3, NULL, '2026-09-20 12:00:00', '2026-09-18 18:00:00', 'თბილისი, ინოვაციების ცენტრი', 'https://example.com/register/ai-practical', 'AI Community Georgia', 1),
(3, 'Batumi Dev Conference 2026', 'ერთდღიანი კონფერენცია დეველოპერებისთვის.', 'შეხვედრა სესიებით, პანელებით და ნეტვორქინგით. თემები: ვებ, ინფრასტრუქტურა და მონაცემები.', 'src/imgs/placeholder-event.png', '2026-06-03 09:15:00', 0, 2, NULL, '2026-09-26 18:30:00', '2026-09-25 20:00:00', 'ბათუმი, ტექნოლოგიური ჰაბი', 'https://example.com/register/batumi-dev', 'Batumi Tech Community', 1),
(4, 'Online Coding Workshop: JS Essentials', '2-დღიანიონლაინ ვორქშოფი JavaScript-ზე.', 'ინტერვენციური პრაქტიკული სესიები, კოდურ სავარჯიშოებთან ერთად.', 'src/imgs/placeholder-event.png', '2026-06-04 11:00:00', 0, 3, NULL, '2026-08-30 12:00:00', '2026-08-29 23:59:00', 'Online', 'https://example.com/register/js-essentials', 'CodeLab', 1),
(5, 'Kutaisi Startup Hack', 'მინი ჰაკათონი ადგილობრივი სტარტაპებისთვის.', 'დღე-ღამის ინტენსიური სამუშაო და მენტორინგი პატარა გუნდებისთვის.', 'src/imgs/placeholder-event.png', '2026-06-05 14:00:00', 0, 1, NULL, '2026-10-03 10:00:00', '2026-09-28 23:59:00', 'ქუთაისი, უნივერსიტეტი', 'https://example.com/register/kutaisi-hack', 'Kutaisi Startup Hub', 1),
(6, 'CyberSec Meetup — Tbilisi', 'პანელის და პრეზენტაციების სერია კიბერუსაფრთხოების თემებზე.', 'ლოკალ ჟურნალისტები და ექსპერტები ივხილავენ პრაქტიკულ უსაფრთხოების შემთხვევებს.', 'src/imgs/placeholder-event.png', '2026-06-06 09:00:00', 0, 2, NULL, '2026-09-15 11:00:00', '2026-09-10 23:59:00', 'თბილისი, ტექნოლოგიური ცენტრი', 'https://example.com/register/cybersec-meet', 'CyberSec Georgia', 1),
(7, 'Students Hack — Campus Build', 'უნივერსიტეტური სტუდენტური ჰაკათონი.', 'სტუდენტები ქმნიან ზუსტად ორი დღის განმავლობაში სპეციალურ პროდუქტებს და პრეზენტაციას.', 'src/imgs/placeholder-event.png', '2026-06-07 16:00:00', 0, 1, NULL, '2026-10-10 11:00:00', '2026-10-01 18:00:00', 'თბილისი, უნივერსიტეტის ბიბლიოთეკა', 'https://example.com/register/students-hack', 'University Innovation Lab', 1),
(8, 'Frontend Masters Workshop', 'ინტენსიური ვორქშოფი თანამედროვე ფრონტენდ ტექნოლოგიებზე.', 'ხანმოკლე ლექციები და პრაქტიკული სამუშაოები.', 'src/imgs/placeholder-event.png', '2026-06-08 10:00:00', 0, 3, NULL, '2026-09-05 17:00:00', '2026-09-01 23:59:00', 'თბილისი, დიზაინ ლაბი', 'https://example.com/register/frontend-masters', 'Frontend Masters', 1),
(9, 'Online ML Bootcamp', 'კვირისა და ნახევარზე განაგრძობუებული ონლაინ ბუთკამპი.', 'გაფართოებული მსჯელობები და პროექტი.', 'src/imgs/placeholder-event.png', '2026-06-09 09:00:00', 0, 3, NULL, '2026-11-01 12:00:00', '2026-10-20 23:59:00', 'Online', 'https://example.com/register/ml-bootcamp', 'ML Academy', 1),
(10, 'Georgia IoT Summit', 'კონფერენცია ინტერნეტ რამისებისა და ინტეგრაციის შესახებ.', 'საკითხები: ქსელი, მოწყობილობები და მონაცემთა რეპრეზენტაცია.', 'src/imgs/placeholder-event.png', '2026-06-10 09:30:00', 0, 2, NULL, '2026-10-20 10:00:00', '2026-10-10 23:59:00', 'ბათუმი, კონფერენციის დარბაზი', 'https://example.com/register/iot-summit', 'IoT Georgia', 1),
(11, 'Intro to Cloud Workshop', 'მოკლე პრაქტიკული სამსახური კლაუდ ინფრაზე.', 'განხილვა და მცირე პრაქტიკა: კონტეინერები და CI/CD.', 'src/imgs/placeholder-event.png', '2026-06-11 14:00:00', 0, 3, NULL, '2026-09-18 12:00:00', '2026-09-15 23:59:00', 'თბილისი, ინოვაციების ცენტრი', 'https://example.com/register/cloud-workshop', 'Cloud Labs', 1),
(12, 'Product Design Thinking', 'ვორქშოფი პროდუქტის დიზაინ და UX მეთოდებზე.', 'ინგრედიენტები: მომხმარებლის კვლევა და პროტოტიპირება.', 'src/imgs/placeholder-event.png', '2026-06-12 11:00:00', 0, 3, NULL, '2026-08-25 12:00:00', '2026-08-20 23:59:00', 'თბილისი, დიზაინ ლაბი', 'https://example.com/register/design-thinking', 'Design Hub', 1);

-- --------------------------------------------------------

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- demo admin user placeholder (password hash must be set locally using provided script)
INSERT INTO `users` (`id`,`email`,`username`,`password`,`reg_date`,`is_admin`) VALUES
(1, 'admin@tlaab.com', 'admin', '$2y$12$oJrWK4FmS/0y0I5WXiTRRuiZuGJFh6GCQ0xXwVVvjuTxfpBgqMCJ6', '2026-06-01 08:00:00', 1);

-- --------------------------------------------------------

-- Table structure for table `user_event_views`
CREATE TABLE `user_event_views` (
  `id` int(11) NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `user_post` (`user_id`,`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Indexes and primary keys
ALTER TABLE `navs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `navs_id` (`navs_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

ALTER TABLE `user_event_views`
  ADD PRIMARY KEY (`id`);

-- AUTO_INCREMENT
ALTER TABLE `navs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `user_event_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Foreign keys
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`navs_id`) REFERENCES `navs` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_event_views`
  ADD CONSTRAINT `uev_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `uev_post_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

COMMIT;
