-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: mysql
-- Час створення: Жов 16 2021 р., 16:14
-- Версія сервера: 8.0.16
-- Версія PHP: 7.2.19

--
-- База даних: `kafedra1`
--

--
-- Дамп даних таблиці `admin_menu`
--

INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES
(2, 0, 14, 'Управління системою', 'fa-tasks', '', NULL, NULL, '2021-10-16 15:03:23'),
(3, 2, 15, 'Користувачі', 'fa-users', 'auth/users', NULL, NULL, '2021-10-16 15:03:23'),
(4, 2, 16, 'Ролі', 'fa-user', 'auth/roles', NULL, NULL, '2021-10-16 15:03:23'),
(5, 2, 17, 'Доступ', 'fa-ban', 'auth/permissions', NULL, NULL, '2021-10-16 15:03:23'),
(6, 2, 18, 'Меню', 'fa-bars', 'auth/menu', NULL, NULL, '2021-10-16 15:03:23'),
(7, 2, 19, 'Журнал подій', 'fa-history', 'auth/logs', NULL, NULL, '2021-10-16 15:03:23'),
(8, 0, 6, 'Працівники', 'fa-users', 'employees', 'employees', '2021-09-19 13:01:13', '2021-10-16 16:45:01'),
(10, 11, 8, 'Список', 'fa-bars', 'leaves', NULL, '2021-10-09 12:56:58', '2021-10-16 10:37:21'),
(11, 0, 7, 'Вихідні', 'fa-bars', NULL, 'leaves', '2021-10-09 14:15:05', '2021-10-16 16:45:28'),
(12, 11, 9, 'Календар', 'fa-bars', 'leaves-calendar', NULL, '2021-10-09 14:15:49', '2021-10-16 10:37:21'),
(13, 0, 1, 'Dashboard', 'fa-bar-chart', 'dashboard', 'dashboard', '2021-10-09 15:22:25', '2021-10-16 16:44:22'),
(14, 0, 2, 'Довідник', 'fa-bars', NULL, 'labels', '2021-10-16 10:04:32', '2021-10-16 16:44:46'),
(15, 14, 3, 'Наукові ступені', 'fa-bars', 'degrees', NULL, '2021-10-16 10:05:03', '2021-10-16 10:37:21'),
(16, 14, 4, 'Вчені звання', 'fa-bars', 'titles', NULL, '2021-10-16 10:11:19', '2021-10-16 10:37:21'),
(17, 14, 5, 'Посади', 'fa-bars', 'positions', NULL, '2021-10-16 10:13:08', '2021-10-16 10:37:21'),
(18, 0, 13, 'Студенти', 'fa-users', 'students', 'students', '2021-10-16 10:49:57', '2021-10-16 16:45:10'),
(19, 0, 10, 'Наука', 'fa-bars', NULL, 'science', '2021-10-16 14:28:53', '2021-10-16 16:45:19'),
(20, 19, 11, 'Наукові роботи', 'fa-bars', 'works', NULL, '2021-10-16 14:29:38', '2021-10-16 15:03:23'),
(21, 19, 12, 'Підвищення кваліфікації', 'fa-bars', 'certification', NULL, '2021-10-16 15:03:15', '2021-10-16 15:03:23');

--
-- Дамп даних таблиці `admin_permissions`
--

INSERT INTO `admin_permissions` (`id`, `name`, `slug`, `http_method`, `http_path`, `created_at`, `updated_at`) VALUES
(1, 'All permission', '*', '', '*', NULL, NULL),
(2, 'Service dashboard', 'service-dashboard', 'GET', '/', NULL, '2021-10-16 16:34:29'),
(3, 'Login', 'auth.login', '', '/auth/login\r\n/auth/logout', NULL, NULL),
(4, 'User setting', 'auth.setting', 'GET,PUT', '/auth/setting', NULL, NULL),
(5, 'Auth management', 'auth.management', '', '/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs', NULL, NULL),
(8, 'Dashboard', 'dashboard', 'GET', '/dashboard', '2021-10-16 16:34:10', '2021-10-16 16:35:04'),
(9, 'Наукові ступені, ...', 'labels', '', '/degrees\r\n/titles\r\n/positions', '2021-10-16 16:36:22', '2021-10-16 16:36:22'),
(10, 'Працівники', 'employees', '', '/employees', '2021-10-16 16:37:17', '2021-10-16 16:37:17'),
(11, 'Студенти', 'students', '', '/students*', '2021-10-16 16:37:37', '2021-10-16 16:37:37'),
(12, 'Наука. Роботи, Підвищення кваліфікації', 'science', '', '/works*\r\n/certification*', '2021-10-16 16:38:31', '2021-10-16 16:38:31'),
(13, 'Вихідні', 'leaves', '', '/leaves*\r\n/leaves-calendar', '2021-10-16 16:38:59', '2021-10-16 16:38:59');

--
-- Дамп даних таблиці `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'administrator', '2021-09-19 12:59:00', '2021-09-19 12:59:00'),
(2, 'User', 'user', '2021-10-10 10:08:28', '2021-10-10 10:08:28');

--
-- Дамп даних таблиці `admin_role_menu`
--

INSERT INTO `admin_role_menu` (`role_id`, `menu_id`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL);

--
-- Дамп даних таблиці `admin_role_permissions`
--

INSERT INTO `admin_role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(2, 3, NULL, NULL),
(2, 4, NULL, NULL),
(2, 5, NULL, NULL),
(2, 11, NULL, NULL),
(2, 12, NULL, NULL),
(2, 13, NULL, NULL);

--
-- Дамп даних таблиці `admin_role_users`
--

INSERT INTO `admin_role_users` (`role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(2, 2, NULL, NULL);

--
-- Дамп даних таблиці `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$c6ANZXyaN2SGjrKXlbpIzuiSEL0H5nUbt47Ht651gOJqeElrHqLr2', 'Administrator', NULL, '1zFbykysTa6LhAeHHzMz0eiCuYKeEdaCIiIr9Zm4OD5pXre7oLDh4zq4v5wD', '2021-09-19 12:59:00', '2021-09-19 12:59:00'),
(2, 'Victor', '$2y$10$c6ANZXyaN2SGjrKXlbpIzuiSEL0H5nUbt47Ht651gOJqeElrHqLr2', 'Victor', NULL, 'KUeXrkfUhiZVE3oHlpNTAiL386s1f3Pvb3y0kKfuWKCEGLedeat2S7wB9FrH', '2021-10-10 10:22:30', '2021-10-10 10:22:30');
COMMIT;
