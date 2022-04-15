--
-- Структура таблицы `teams`
-- Всё будет менять. На данный момент нет смысла делать запрос. 
-- Данный функционал не поддерживается. В стадии разработки.
-- 
 
CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `content` text,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(32) NOT NULL COMMENT 'тип команды, к чему относится?',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 
 
CREATE TABLE `teams_content_relation` (
  `team_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `teams_users_relation` (
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 

--
-- Индексы
-- 

ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `teams_content_relation`
    ADD UNIQUE INDEX `tc_relation` (`team_id`, `content_id`);  
  
ALTER TABLE `teams_users_relation`
    ADD UNIQUE INDEX `tu_relation` (`team_id`, `user_id`);
    
ALTER TABLE `teams_content_relation` ADD INDEX(`content_id`); 
ALTER TABLE `teams_users_relation` ADD INDEX(`team_id`);   