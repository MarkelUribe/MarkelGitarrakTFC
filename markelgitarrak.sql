-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-05-2023 a las 20:10:43
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `markelgitarrak`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chats`
--

CREATE TABLE `chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `eskaintzaId` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `erabiltzaile_chats`
--

CREATE TABLE `erabiltzaile_chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `chatId` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eskaintzak`
--

CREATE TABLE `eskaintzak` (
  `id` int(10) UNSIGNED NOT NULL,
  `izena` varchar(255) NOT NULL,
  `azalpena` varchar(500) NOT NULL,
  `prezioa` int(11) NOT NULL,
  `argazkiak` varchar(2550) NOT NULL,
  `kokapena` varchar(255) NOT NULL DEFAULT '0',
  `estatua` varchar(255) NOT NULL,
  `motaId` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `erosleId` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `eskaintzak`
--

INSERT INTO `eskaintzak` (`id`, `izena`, `azalpena`, `prezioa`, `argazkiak`, `kokapena`, `estatua`, `motaId`, `userId`, `erosleId`, `created_at`, `updated_at`) VALUES
(7, 'Stratocaster Rockjam beltza', 'RockJam - Zorroarekin. Gitarra oso egoera onean. Oso gutxitan erabiltzen da. Gutxi gorabehera neurriak (98 cm luze, 32 cm zabaleran, 4 cm zabal zabalenean eta 2 cm zabal estuenean). Onartzen dut 2 gitarren ordez bidai gitarra akustiko bat erabiltzea.', 62, ', productimages/vl28EzEvTI1eLkbhdgFhDwqFelUO2GPasCWTgdge.webp, productimages/EvywPOj07BrErllDTO9pgRjvP57fk7aZ1bE7kelu.webp, productimages/YmuXMQ9g1KyfS0TQCAjz6JIpurREimOiCdQ2mqYt.webp, productimages/Sjs12UC1TOuJ7CKLfdcaWF7WNSlOcquD62GUu2zr.webp', 'Tolosa kalea, 1, 20720 Azkoitia, Spain', 'erabilia', 1, 2, NULL, '2023-05-08 13:54:50', '2023-05-18 15:17:12'),
(8, 'Kitarra klasikoa', 'El Corte Inglés - Duquesa (Estudio eredua) C-3901. Gutxi gorabeherako neurriak (37 cm gitarraren zabaleran, 1 m luze gutxi gorabehera, eta ia 10 cm zabal). Gitarra oso egoera onean, oso gutxitan erabilia. Ez du zorrorik.', 80, ', productimages/kNpWRCtvb5hugPWtvyOksBiU2TTXdPOymFyPA0AH.webp, productimages/OnTjSSDxuaWekdwZkwrnSSH8WBIXRGEq9tyUHWlW.webp', 'San Andres pasealekua, 1, 20600 Eibar, Spain', 'erabilia', 3, 2, NULL, '2023-05-08 14:00:01', '2023-05-18 15:39:55'),
(9, 'Ibanez Baxua', 'Seymour Duncan SSB4 pilulak - Aldez aurretik Aktibo Seymour Duncan STC-3P - Schaller Zubia 2000 4S - Wilkinson larakoak engranajea bistan dutela. - Kobrean pantailaratutako elektronika guztia Osagaien balioa 450 EURO baino gehiago da.', 500, ', productimages/eEROSMd6nlYELXb2jzSso3H4tAKnvfGjiFcaZzux.webp, productimages/ebsCSSRnLxUixSAFPUx0i2cSVyF8Mvcq2uOjfrkU.webp, productimages/uXFEC5BmWNCga5OvhKYJFuNRvpTQhvxAlU6YHCz4.webp, productimages/QphOhtdQ1gUDabsVgG5ScdNUj7MPbyO7e3enczgB.webp', 'Calle Bizkaia / Bizkaia kalea, 36, 48901 Barakaldo, Spain', 'berria', 2, 2, NULL, '2023-05-08 14:05:51', '2023-05-08 14:05:51'),
(10, 'Kitarra Strandberg Boden Standard NX 6 2022', 'Ongi! Strandberg Boden Standard NX 6 gitarra saltzen dut, berria 2022ko irailean erosia. Denbora honetan etxean bakarrik erabilia.', 1400, ', productimages/xo8d4PV2t7YcgelVCB7MxJgjfRaClAFtSJqWzW3v.webp, productimages/UoSrSi9casPbt1ULmOryzITZqaD2WUM2ZQVU413H.webp, productimages/aCV4DIpSogRiEi7pyaZMsTVISyso0HbNxonmmEhb.webp, productimages/Eh48AAwAsDgMMWr1zBm73bB7Ry2JMna9iP6lYHeL.webp', 'Eliz Kalea, 36, 20730 Azpeitia, Spain', 'ia_berria', 1, 1, NULL, '2023-05-08 14:15:19', '2023-05-08 14:15:19'),
(11, 'Bateria akustikoa', 'Bateria akustiko osoa eta oso egoera onean  lesgo', 230, ', productimages/3r6T5LIgWfzHvfYe6R0rLlPDIttEcVs1oT8NoDFU.webp, productimages/AXaV3SqfDi3uHzWtW15yNpU1d7T4Ha5y6FWkftbx.webp,', 'Portalekoa, San Roke kalea, 6, 20820 Deba, Spain', 'erabilia', 5, 1, NULL, '2023-05-08 14:18:26', '2023-05-15 13:44:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eskaintzamotak`
--

CREATE TABLE `eskaintzamotak` (
  `id` int(10) UNSIGNED NOT NULL,
  `mota` varchar(255) NOT NULL,
  `azalpena` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `eskaintzamotak`
--

INSERT INTO `eskaintzamotak` (`id`, `mota`, `azalpena`, `created_at`, `updated_at`) VALUES
(1, 'Gitarra elektrikoa', 'Instrumentu elektrikoa', NULL, NULL),
(2, 'Baxu elektrikoa', 'instrumentu elektrikoa', NULL, NULL),
(3, 'Gitarra akustikoa', 'instrumentu akustikoa', NULL, NULL),
(4, 'Baxu akustikoa', 'Instrumentu akustikoa', NULL, NULL),
(5, 'Bateria osagaia', 'Bateria batek izan ditzaken osagaia', NULL, NULL),
(6, 'Sintetizadorea', 'Sintetizadore elektrikoa, teklatua', NULL, NULL),
(7, 'Beste bat', 'Beste edozein instrumentu', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE `likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `eskaintzaId` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `likes`
--

INSERT INTO `likes` (`id`, `userId`, `eskaintzaId`, `created_at`, `updated_at`) VALUES
(8, 2, 10, '2023-05-12 10:33:25', '2023-05-12 10:33:25'),
(11, 1, 7, '2023-05-16 10:07:28', '2023-05-16 10:07:28'),
(12, 1, 8, '2023-05-16 19:14:24', '2023-05-16 19:14:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mezuak`
--

CREATE TABLE `mezuak` (
  `id` int(10) UNSIGNED NOT NULL,
  `textua` varchar(255) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `irakurrita` int(11) DEFAULT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `chatId` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_03_29_104709_create_eskaintza_motas_table', 1),
(6, '2023_03_29_104730_create_eskaintzas_table', 1),
(7, '2023_03_29_104745_create_likes_table', 1),
(8, '2023_03_29_104757_create_chats_table', 1),
(9, '2023_03_29_104808_create_mezuas_table', 1),
(10, '2023_03_29_104833_create_erabiltzaile_chats_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `kokapena` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `argazkia` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `email_verified_at`, `kokapena`, `password`, `remember_token`, `argazkia`, `created_at`, `updated_at`) VALUES
(1, 'Markel', 'Uribe', 'uribemarkel@gmail.com', NULL, 'Euskalerria Auzunea, 34, 20730 Azpeitia, Spain', '$2y$10$2wIHz4RY7EV18r6ZYgTyZO9el.pZunRx5031CoID.oQbPEm58kAXW', NULL, 'profilepics/kNIG42rj5eDuJDuX4jwcPIik6MTfMUlkUht9A9v1.jpg', '2023-03-30 09:57:41', '2023-05-09 09:07:39'),
(2, 'Aimar', 'Martínez', 'aimar@aimar.com', NULL, 'Isasi kalea, 21, 20600 Eibar, Spain', '$2y$10$MbrZq7Wj82LkCwESjVDa5ef.LDYwmK4W6BbxQ4bkCKjnEhhaGR9ye', NULL, 'profilepics/wIpC6N2avfXAi7NW4TQf0o0K8cNVhV9P9eaMU5ey.jpg', '2023-04-04 10:31:07', '2023-05-10 11:22:50');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_eskaintza_foreign` (`eskaintzaId`);

--
-- Indices de la tabla `erabiltzaile_chats`
--
ALTER TABLE `erabiltzaile_chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `erabiltzaile_chats_userid_foreign` (`userId`),
  ADD KEY `erabiltzaile_chats_chatid_foreign` (`chatId`);

--
-- Indices de la tabla `eskaintzak`
--
ALTER TABLE `eskaintzak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eskaintzak_userid_index` (`userId`),
  ADD KEY `eskaintzak_motaid_index` (`motaId`),
  ADD KEY `erosleid_userid_foreign` (`erosleId`);

--
-- Indices de la tabla `eskaintzamotak`
--
ALTER TABLE `eskaintzamotak`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `likes_userid_foreign` (`userId`),
  ADD KEY `likes_eskaintzaid_foreign` (`eskaintzaId`);

--
-- Indices de la tabla `mezuak`
--
ALTER TABLE `mezuak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mezuak_userid_foreign` (`userId`),
  ADD KEY `mezuak_chatid_foreign` (`chatId`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `erabiltzaile_chats`
--
ALTER TABLE `erabiltzaile_chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `eskaintzak`
--
ALTER TABLE `eskaintzak`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `eskaintzamotak`
--
ALTER TABLE `eskaintzamotak`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `mezuak`
--
ALTER TABLE `mezuak`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chat_eskaintza_foreign` FOREIGN KEY (`eskaintzaId`) REFERENCES `eskaintzak` (`id`);

--
-- Filtros para la tabla `erabiltzaile_chats`
--
ALTER TABLE `erabiltzaile_chats`
  ADD CONSTRAINT `erabiltzaile_chats_chatid_foreign` FOREIGN KEY (`chatId`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `erabiltzaile_chats_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `eskaintzak`
--
ALTER TABLE `eskaintzak`
  ADD CONSTRAINT `erosleid_userid_foreign` FOREIGN KEY (`erosleId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `eskaintzak_motaid_foreign` FOREIGN KEY (`motaId`) REFERENCES `eskaintzamotak` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `eskaintzak_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_eskaintzaid_foreign` FOREIGN KEY (`eskaintzaId`) REFERENCES `eskaintzak` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mezuak`
--
ALTER TABLE `mezuak`
  ADD CONSTRAINT `mezuak_chatid_foreign` FOREIGN KEY (`chatId`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mezuak_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
