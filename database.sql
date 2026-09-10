-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 10, 2026 at 12:26 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bankmini`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounting_journals`
--

CREATE TABLE `accounting_journals` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` bigint UNSIGNED NOT NULL,
  `account_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` enum('Debit','Credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounting_journals`
--

INSERT INTO `accounting_journals` (`id`, `transaction_id`, `account_code`, `position`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, '101', 'Debit', 120000.00, '2026-08-13 07:42:13', '2026-08-13 07:42:13'),
(2, 1, '201', 'Credit', 120000.00, '2026-08-13 07:42:13', '2026-08-13 07:42:13'),
(3, 2, '201', 'Debit', 12000.00, '2026-08-13 07:42:58', '2026-08-13 07:42:58'),
(4, 2, '101', 'Credit', 12000.00, '2026-08-13 07:42:58', '2026-08-13 07:42:58'),
(5, 3, '101', 'Debit', 1000000.00, '2026-08-13 17:37:02', '2026-08-13 17:37:02'),
(6, 3, '201', 'Credit', 1000000.00, '2026-08-13 17:37:02', '2026-08-13 17:37:02');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_accounts`
--

CREATE TABLE `customer_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `nasabah_id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_accounts`
--

INSERT INTO `customer_accounts` (`id`, `nasabah_id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 1, 'renocahya', '$2y$12$J2eh5xCPps9TwY45A8Il5.CUwE1LfbCAlBBJ5Didb4OGNFY.bWMwu', '2026-08-13 07:33:23', '2026-08-13 07:33:23'),
(2, 2, 'afnaan', '$2y$12$sI.hn6I1pculQN7DpSAgx.DvFwTCm25Bez9PILNpCI1uKR1j0dKiG', '2026-08-13 17:35:16', '2026-08-13 17:35:16'),
(3, 3, 'akmal', '$2y$12$LlHzo4m9Vn5mOKn5QH.geuCpyMgiDk5Vwe.jOAf61ZwmbXhSmC.M2', '2026-09-08 23:04:02', '2026-09-08 23:04:02');

-- --------------------------------------------------------

--
-- Table structure for table `daily_reports`
--

CREATE TABLE `daily_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `report_date` date NOT NULL,
  `teller_id` bigint UNSIGNED NOT NULL,
  `supervisor_id` bigint UNSIGNED DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL,
  `total_deposit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_withdrawal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `closing_balance` decimal(15,2) NOT NULL,
  `status` enum('Draft','Submitted','Rejected','Approved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_reports`
--

INSERT INTO `daily_reports` (`id`, `report_date`, `teller_id`, `supervisor_id`, `opening_balance`, `total_deposit`, `total_withdrawal`, `closing_balance`, `status`, `submitted_at`, `approved_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, '2026-08-13', 3, 2, 0.00, 0.00, 0.00, 0.00, 'Approved', '2026-08-13 07:26:45', '2026-08-13 07:30:20', NULL, '2026-08-13 07:26:40', '2026-08-13 07:30:20'),
(2, '2026-08-14', 3, 2, 0.00, 1000000.00, 0.00, 1000000.00, 'Submitted', '2026-09-01 00:13:31', NULL, NULL, '2026-08-13 17:37:44', '2026-09-01 00:13:31');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_08_13_033917_create_users_table', 1),
(2, '2026_08_13_034135_create_nasabahs_table', 2),
(3, '2026_08_13_034150_create_customer_accounts_table', 2),
(4, '2026_08_13_034201_create_transactions_table', 2),
(5, '2026_08_13_034209_create_accounting_journals_table', 2),
(6, '2026_08_13_034218_create_daily_reports_table', 2),
(7, '2026_08_13_034552_create_sessions_table', 3),
(8, '0001_01_01_000000_create_users_table', 1),
(9, '0001_01_01_000001_create_cache_table', 4),
(10, '0001_01_01_000002_create_jobs_table', 4),
(11, '2026_09_09_051418_add_jurusan_to_nasabahs_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `nasabahs`
--

CREATE TABLE `nasabahs` (
  `id` bigint UNSIGNED NOT NULL,
  `account_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurusan` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `security_pin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('Aktif','Nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nasabahs`
--

INSERT INTO `nasabahs` (`id`, `account_number`, `student_number`, `student_name`, `class`, `jurusan`, `gender`, `phone_number`, `security_pin`, `balance`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BM-2026-0001', '17782', 'reno cahya hutama', '12 rpl 1', NULL, 'L', '08231', '$2y$12$ON935DvvAZyZdUsIni91peNfxLxaCtuCaqawMLDYJmC4yB641x4IO', 1108000.00, 'Aktif', '2026-08-13 07:33:22', '2026-08-13 17:37:02'),
(2, 'BM-2026-0002', '17762', 'afnaan', '12 rpl 1', NULL, 'P', '08922', '$2y$12$p2KA3A0/tXjb1SeOeGyvsuRJjyM9g4hxBh6XtHJ3xX/N6sPudVpsu', 0.00, 'Aktif', '2026-08-13 17:35:16', '2026-08-31 23:59:18'),
(3, 'RPL-2026-0001', '17788', 'akmal', '12 rpl 1', 'RPL', 'L', '0831', '$2y$12$UUBhc9Kokdb5s.zTsnOOXuNttjSLfWAX/K85gMR.U8cH/Nc6KAfZ.', 0.00, 'Aktif', '2026-09-08 23:04:02', '2026-09-08 23:04:02');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('mJ1G81WLvuARzJoAKBhFmJo5D9dJQL7HjJG0VgXX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJlRmd5ZU5YWWxvS2x4bmlTUlJxVWQzWXI0VW1YcDBLM0lTd1ZvNEpsIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==', 1788934374),
('OQO3i9l0sAKK9baTusfpCqXSelkBppUDUdoT00KN', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJHY1B6V3ZaSmc1dVY1N2R5WkllQzR6NWhGaWYzM3hPTEhxNklGR2R1IiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL3RlbGxlclwvZGVwb3NpdCIsInJvdXRlIjoidGVsbGVyLmRlcG9zaXQuY3JlYXRlIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjozfQ==', 1788932600);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `nasabah_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `transaction_type` enum('Deposit','Withdrawal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance_before` decimal(15,2) NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `nasabah_id`, `user_id`, `transaction_type`, `amount`, `balance_before`, `balance_after`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Deposit', 120000.00, 0.00, 120000.00, NULL, '2026-08-13 07:42:13', '2026-08-13 07:42:13'),
(2, 1, 3, 'Withdrawal', 12000.00, 120000.00, 108000.00, NULL, '2026-08-13 07:42:58', '2026-08-13 07:42:58'),
(3, 1, 3, 'Deposit', 1000000.00, 108000.00, 1108000.00, NULL, '2026-08-13 17:37:02', '2026-08-13 17:37:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Administrator','Supervisor','Teller') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Aktif','Nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `password`, `role`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', '$2y$12$Zn2rAOEj.fEtv7/D.Kc3ouc9erUfLyKEKAjIQ48ni1tx4/eT.Wpa.', 'Administrator', 'Aktif', NULL, '2026-08-12 21:06:31', '2026-08-12 21:06:31'),
(2, 'supervisor', 'Supervisor', '$2y$12$hArNfuSNwhevLgISy.2pyeuXaa8GL7OEQ/cyiGcRXnrl9fLTvA3be', 'Supervisor', 'Aktif', NULL, '2026-08-12 21:06:31', '2026-08-12 21:06:31'),
(3, 'teller01', 'rehan', '$2y$12$uv0Fp8mP1uamfczAQczAAe9OvxhfA/ws67PVLaZtBIspjYwzvBnfa', 'Teller', 'Aktif', NULL, '2026-08-12 21:06:31', '2026-08-12 21:06:31'),
(4, 'nagja', 'najgha', '$2y$12$0pO.uTL0LISI898dymYQs.q0dSXbpLkZk7Ce9mjdVhiU6j5GQUZzO', 'Teller', 'Aktif', NULL, '2026-09-06 22:58:09', '2026-09-06 22:58:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounting_journals`
--
ALTER TABLE `accounting_journals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounting_journals_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_accounts_nasabah_id_unique` (`nasabah_id`),
  ADD UNIQUE KEY `customer_accounts_username_unique` (`username`);

--
-- Indexes for table `daily_reports`
--
ALTER TABLE `daily_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_reports_teller_id_foreign` (`teller_id`),
  ADD KEY `daily_reports_supervisor_id_foreign` (`supervisor_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nasabahs`
--
ALTER TABLE `nasabahs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nasabahs_account_number_unique` (`account_number`),
  ADD UNIQUE KEY `nasabahs_student_number_unique` (`student_number`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_nasabah_id_foreign` (`nasabah_id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounting_journals`
--
ALTER TABLE `accounting_journals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `daily_reports`
--
ALTER TABLE `daily_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `nasabahs`
--
ALTER TABLE `nasabahs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounting_journals`
--
ALTER TABLE `accounting_journals`
  ADD CONSTRAINT `accounting_journals_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_accounts`
--
ALTER TABLE `customer_accounts`
  ADD CONSTRAINT `customer_accounts_nasabah_id_foreign` FOREIGN KEY (`nasabah_id`) REFERENCES `nasabahs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_reports`
--
ALTER TABLE `daily_reports`
  ADD CONSTRAINT `daily_reports_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `daily_reports_teller_id_foreign` FOREIGN KEY (`teller_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_nasabah_id_foreign` FOREIGN KEY (`nasabah_id`) REFERENCES `nasabahs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
