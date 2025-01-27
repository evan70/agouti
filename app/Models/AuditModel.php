<?php

namespace App\Models;

use DB;

class AuditModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Add an entry to the audit table (audit or flag)
    // Добавим запись в таблицу аудита (аудит или флаг)
    public static function add($params)
    {
        $sql = "INSERT INTO audits(action_type, type_belonging, user_id, content_id, read_flag) 
                    VALUES(:action_type, :type_belonging, :user_id, :content_id, 0)";

        return DB::run($sql, $params);
    }

    // Let's limit How many complaints are filed today (for frequency limitation)
    // Сколько жалоб подано сегодня (для ограничение частоты)
    public static function getSpeedReport($uid)
    {
        $sql = "SELECT id FROM audits
                    WHERE user_id = :uid AND type_belonging = 'report'
                        AND add_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['uid' => $uid])->rowCount();
    }
    
    // Get a list of forbidden stop words
    // Получим список запрещенных стоп-слов
    public static function getStopWords()
    {
        $sql = "SELECT stop_id, stop_word FROM stop_words";

        return DB::run($sql)->fetchAll();
    }
    
    // Member information (id, slug) 
    // Информация по участнику (id, slug)
    public static function getUsers($params, $type)
    {
        $sort = "id = :params";
        if ($type == 'slug') {
            $sort = "login = :params";
        }

        $sql = "SELECT 
                    id,
                    login,
                    activated,
                    is_deleted 
                        FROM users WHERE $sort AND activated = 1 AND is_deleted = 0";

        return DB::run($sql, ['params' => $params])->fetch();
    }
}
