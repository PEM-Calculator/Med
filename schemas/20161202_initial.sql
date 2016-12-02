-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.7.15-log - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица med.arc_exception
CREATE TABLE IF NOT EXISTS `arc_exception` (
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_user` int(10) unsigned DEFAULT NULL,
  `class` varchar(25) NOT NULL COMMENT 'Класс исключения',
  `code` int(11) NOT NULL COMMENT 'Код ошибки',
  `message` text NOT NULL,
  `trace` text NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.arc_exception: 0 rows
/*!40000 ALTER TABLE `arc_exception` DISABLE KEYS */;
/*!40000 ALTER TABLE `arc_exception` ENABLE KEYS */;

-- Дамп структуры для таблица med.arc_history
CREATE TABLE IF NOT EXISTS `arc_history` (
  `table` varchar(25) NOT NULL,
  `id_row` int(10) unsigned NOT NULL,
  `action_type` int(11) NOT NULL COMMENT '1-Ins, 2-Upd, 3-Del',
  `id_user` int(11) unsigned DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_new` json DEFAULT NULL COMMENT '[I, U]',
  `data_old` json DEFAULT NULL COMMENT '[U, D]'
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.arc_history: 0 rows
/*!40000 ALTER TABLE `arc_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `arc_history` ENABLE KEYS */;

-- Дамп структуры для таблица med.arc_log
CREATE TABLE IF NOT EXISTS `arc_log` (
  `type` char(1) NOT NULL COMMENT '[Error, Warning, Notify, Debug]',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_user` int(10) unsigned DEFAULT NULL,
  `class` varchar(25) NOT NULL COMMENT 'Класс сообщения',
  `action` varchar(50) NOT NULL COMMENT 'Действие',
  `message` text
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.arc_log: 0 rows
/*!40000 ALTER TABLE `arc_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `arc_log` ENABLE KEYS */;

-- Дамп структуры для таблица med.cube_period
CREATE TABLE IF NOT EXISTS `cube_period` (
  `id_period__from` int(11) unsigned NOT NULL,
  `id_period__to` int(11) unsigned NOT NULL,
  `sum_plan_length` int(11) NOT NULL COMMENT 'Сум. план. длительность',
  `sum_plan_expense` double NOT NULL COMMENT 'Сум. план. бюджет',
  `sum_plan_result` double NOT NULL COMMENT 'Сум. план. результат',
  `sum_fact_length` int(11) NOT NULL COMMENT 'Сум. факт. длительность',
  `sum_fact_expense` double NOT NULL COMMENT 'Сум. факт. расход',
  `sum_fact_result` double NOT NULL COMMENT 'Сум. факт. результат',
  `prfz` double NOT NULL COMMENT 'ПРФЗ',
  `ks` double NOT NULL COMMENT 'Кс',
  `kd` double NOT NULL COMMENT 'Кд',
  `kr` double NOT NULL COMMENT 'Кр',
  `eff` double NOT NULL COMMENT 'Эффективность',
  `frc_length` double NOT NULL COMMENT 'Прогноз длительности',
  `frc_expense` double NOT NULL COMMENT 'Прогноз расхода',
  `frc_profit` double DEFAULT NULL COMMENT 'Прогноз прибыли',
  `frc_eff_profit` double DEFAULT NULL COMMENT 'Прогноз эффективности прибыли',
  `dev_abs_length` double NOT NULL COMMENT 'Абс. отклон. длительности',
  `dev_abs_expense` double NOT NULL COMMENT 'Абс. отклон. расходов',
  `dev_abs_result` double NOT NULL COMMENT 'Абс. отклон. результата',
  `dev_rel_length` double NOT NULL COMMENT 'Относ. отклон. длительности',
  `dev_rel_expense` double NOT NULL COMMENT 'Относ. отклон. расходов',
  `dev_rel_result` double NOT NULL COMMENT 'Относ. отклон. результата'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.cube_period: 0 rows
/*!40000 ALTER TABLE `cube_period` DISABLE KEYS */;
/*!40000 ALTER TABLE `cube_period` ENABLE KEYS */;

-- Дамп структуры для таблица med.cube_task
CREATE TABLE IF NOT EXISTS `cube_task` (
  `id_task` int(10) unsigned NOT NULL,
  `prfz` double NOT NULL,
  `kd` double NOT NULL,
  `ks` double NOT NULL,
  `kr` double NOT NULL,
  `eff` double NOT NULL,
  `kpr_prfz` double DEFAULT NULL,
  `kpr_kd` double DEFAULT NULL,
  `kpr_ks` double DEFAULT NULL,
  `kpr_kr` double DEFAULT NULL,
  `kpr_eff` double DEFAULT NULL,
  `path_prfz` double DEFAULT NULL,
  `path_kd` double DEFAULT NULL,
  `path_ks` double DEFAULT NULL,
  `path_kr` double DEFAULT NULL,
  `path_eff` double DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.cube_task: 0 rows
/*!40000 ALTER TABLE `cube_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `cube_task` ENABLE KEYS */;

-- Дамп структуры для таблица med.group
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `id_organization` int(10) unsigned NOT NULL COMMENT 'Организация',
  `ids_user` json NOT NULL COMMENT 'Список пользователей в группе',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.group: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `group` DISABLE KEYS */;
/*!40000 ALTER TABLE `group` ENABLE KEYS */;

-- Дамп структуры для таблица med.kpr
CREATE TABLE IF NOT EXISTS `kpr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_task` int(10) unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `date__plan_begin` date NOT NULL COMMENT 'План. начало задачи',
  `date__plan_end` date NOT NULL COMMENT 'План. окончание задачи',
  `plan_length` int(11) NOT NULL COMMENT 'План. длина задачи',
  `plan_expense` double NOT NULL COMMENT 'План. бюджет',
  `plan_value` double NOT NULL COMMENT 'Плн. результат',
  `ids_period` json NOT NULL COMMENT 'Периоды',
  `data_options` json NOT NULL COMMENT 'Настройки',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='data_options = {id_unit__result}';

-- Дамп данных таблицы med.kpr: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `kpr` DISABLE KEYS */;
/*!40000 ALTER TABLE `kpr` ENABLE KEYS */;

-- Дамп структуры для таблица med.organization
CREATE TABLE IF NOT EXISTS `organization` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `id_user__creator` int(10) unsigned NOT NULL,
  `time__create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time__update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.organization: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `organization` DISABLE KEYS */;
/*!40000 ALTER TABLE `organization` ENABLE KEYS */;

-- Дамп структуры для таблица med.period
CREATE TABLE IF NOT EXISTS `period` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date__plan_begin` date NOT NULL COMMENT 'План. начало',
  `date__plan_end` date NOT NULL COMMENT 'План. конец',
  `plan_length` int(11) NOT NULL COMMENT 'План. длительность',
  `plan_expense` double NOT NULL COMMENT 'Бюджет',
  `plan_result` double NOT NULL COMMENT 'Показатель',
  `date__fact_begin` date DEFAULT NULL COMMENT 'Факт. начало',
  `date__fact_end` date DEFAULT NULL COMMENT 'Факт. конец',
  `fact_length` int(11) DEFAULT NULL COMMENT 'Факт. длительность',
  `fact_expense` double DEFAULT NULL COMMENT 'Расходы',
  `fact_result` double DEFAULT NULL COMMENT 'Результат',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.period: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `period` DISABLE KEYS */;
/*!40000 ALTER TABLE `period` ENABLE KEYS */;

-- Дамп структуры для таблица med.task
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(10) unsigned DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `date__plan_begin` date NOT NULL COMMENT 'План. начало',
  `date__plan_end` date NOT NULL COMMENT 'План. конец',
  `plan_length` int(11) NOT NULL COMMENT 'План. длительность в днях',
  `plan_preiod_count` int(11) NOT NULL COMMENT 'План. длительность в периодах',
  `plan_expense` double NOT NULL COMMENT 'План. бюджет',
  `date__over_end` date NOT NULL COMMENT 'Сверхплан. конец',
  `over_length` int(11) NOT NULL COMMENT 'Сверхплан. длительность в днях',
  `over_period_count` int(11) NOT NULL COMMENT 'Сверхплан. длительность в периодах',
  `over_expense` double NOT NULL COMMENT 'Сверхплан. бюджет',
  `id_user__creator` int(11) NOT NULL COMMENT 'Автор',
  `time__create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Время создания',
  `id_user__editor` int(11) DEFAULT NULL COMMENT 'Редактор',
  `time__update` timestamp NULL DEFAULT NULL COMMENT 'Последняя редакция',
  `ids_user__member` json NOT NULL COMMENT 'Члены, имеющие доступ на чтение',
  `id_user__manager` int(11) NOT NULL COMMENT 'Менеджер',
  `id_user__executor` int(11) NOT NULL COMMENT 'Исполнитель',
  `ids_period` json NOT NULL COMMENT 'Набор периодов задачи',
  `data_options` json NOT NULL COMMENT 'Настройки',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='data_options = {period_width, expense_unit, plan_method, result_method, eff_method, eff_min, eff_max}\r\n';

-- Дамп данных таблицы med.task: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `task` DISABLE KEYS */;
/*!40000 ALTER TABLE `task` ENABLE KEYS */;

-- Дамп структуры для таблица med.unit
CREATE TABLE IF NOT EXISTS `unit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `one` json NOT NULL COMMENT 'Полное название в 6 падежах',
  `many` json NOT NULL,
  `short` json NOT NULL COMMENT 'Краткое название в 6 падежах',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.unit: 2 rows
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
INSERT INTO `unit` (`id`, `one`, `many`, `short`) VALUES
	(1, '["километр", "климетра", "километру", "километр", "километром", "километре"]', '["километры", "километров", "километрам", "километры", "километрами", "километрах"]', '["км"]'),
	(2, '["литр", "литра", "литру", "литр", "литром", "литре"]', '["литры", "литров", "литрам", "литры", "литрами", "литрах"]', '["л."]');
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;

-- Дамп структуры для таблица med.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `hash_password` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `midname` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `data_contacts` json DEFAULT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `time_logined` timestamp NULL DEFAULT NULL,
  `time_last_logined` timestamp NULL DEFAULT NULL,
  `is_admin` int(11) unsigned NOT NULL DEFAULT '0',
  `is_locked` int(11) unsigned NOT NULL DEFAULT '0',
  `time_locked` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.user: ~29 rows (приблизительно)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `username`, `hash_password`, `name`, `midname`, `surname`, `date_birth`, `data_contacts`, `time_created`, `time_updated`, `time_logined`, `time_last_logined`, `is_admin`, `is_locked`, `time_locked`) VALUES
	(1, 'system', '', NULL, NULL, NULL, '2016-11-28', NULL, '2016-11-28 20:34:50', '2016-11-30 23:41:38', NULL, NULL, 1, 0, NULL),
	(5, 'Vasya_Pupkin5', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', NULL, NULL, '2016-12-01 01:43:48', '2016-12-01 02:15:08', NULL, NULL, 0, 0, NULL),
	(6, 'Vasya_Pupkin6', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', NULL, NULL, '2016-12-01 01:44:52', '2016-12-01 02:15:00', NULL, NULL, 0, 0, NULL),
	(7, 'Vasya_Pupkin7', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', NULL, NULL, '2016-12-01 01:47:15', '2016-12-01 02:15:00', NULL, NULL, 0, 0, NULL),
	(8, 'Vasya_Pupkin8', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:51:54', '2016-12-01 02:15:00', NULL, NULL, 0, 0, NULL),
	(9, 'Vasya_Pupkin9', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:54:07', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(10, 'Vasya_Pupkin10', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:54:15', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(11, 'Vasya_Pupkin11', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:54:25', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(12, 'Vasya_Pupkin12', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:54:48', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(13, 'Vasya_Pupkin13', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:55:41', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(14, 'Vasya_Pupkin14', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:55:48', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(15, 'Vasya_Pupkin15', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:55:58', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(16, 'Vasya_Pupkin16', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:56:25', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(17, 'Vasya_Pupkin17', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:56:48', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(18, 'Vasya_Pupkin18', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:57:24', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(19, 'Vasya_Pupkin19', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '2016-12-01', NULL, '2016-12-01 01:58:33', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(20, 'Vasya_Pupkin20', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1970-01-01', NULL, '2016-12-01 01:59:02', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(21, 'Vasya_Pupkin21', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 01:59:20', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(22, 'Vasya_Pupkin22', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 01:59:42', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(23, 'Vasya_Pupkin23', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 02:03:34', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(24, 'Vasya_Pupkin24', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 02:04:16', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(25, 'Vasya_Pupkin25', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 02:04:34', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(26, 'Vasya_Pupkin26', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 02:05:03', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(27, 'Vasya_Pupkin27', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 02:05:35', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(28, 'Vasya_Pupkin28', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 02:05:54', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(29, 'Vasya_Pupkin29', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 02:06:14', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(30, 'Vasya_Pupkin30', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 02:06:44', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(31, 'Vasya_Pupkin31', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', NULL, '2016-12-01 02:07:23', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(32, 'Vasya_Pupkin32', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', '{"email": "vasya@pupkin.ru", "phone": 89870123456, "skype": "vasya_pupkin"}', '2016-12-01 02:07:54', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(33, 'Vasya_Pupkin33', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', '{"email": "vasya@pupkin.ru", "phone": 89870123456, "skype": "vasya_pupkin"}', '2016-12-01 02:08:08', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(34, 'Vasya_Pupkin34', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', '{"email": "vasya@pupkin.ru", "phone": 89870123456, "skype": "vasya_pupkin"}', '2016-12-01 02:10:37', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(35, 'Vasya_Pupkin35', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', '{"email": "vasya@pupkin.ru", "phone": 89870123456, "skype": "vasya_pupkin"}', '2016-12-01 02:10:48', '2016-12-01 02:15:00', NULL, NULL, 0, 1, NULL),
	(36, 'Vasya_Pupkin36', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', '{"email": "vasya@pupkin.ru", "phone": 89870123456, "skype": "vasya_pupkin"}', '2016-12-01 02:14:19', '2016-12-01 02:15:00', NULL, NULL, 0, 0, NULL),
	(37, 'Vasya_Pupkin', 'de9d5a9592a4582eac80030ace638ae4', 'Василий', 'Иванович', 'Пупкин', '1999-11-25', '{"email": "vasya@pupkin.ru", "phone": 89870123456, "skype": "vasya_pupkin"}', '2016-12-01 02:15:20', NULL, NULL, NULL, 0, 0, NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Дамп структуры для таблица med.user_log
CREATE TABLE IF NOT EXISTS `user_log` (
  `id_user` int(10) unsigned NOT NULL,
  `action` int(10) unsigned NOT NULL COMMENT '11-Create, 12-Delete, 21-Login, 22-Logout, 23-Locked, 24-Login wrong, 31-Add to group, 32-Del from group',
  `id_group` int(10) unsigned DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` json DEFAULT NULL COMMENT 'Информация о входе или выходе, причине блокировки и прочее'
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8;

-- Дамп данных таблицы med.user_log: 0 rows
/*!40000 ALTER TABLE `user_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_log` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
