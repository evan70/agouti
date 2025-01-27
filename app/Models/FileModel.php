<?php

namespace App\Models;

use DB;

class FileModel extends \Hleb\Scheme\App\Models\MainModel
{
    public static function set($params)
    {
        $sql = "INSERT INTO files(
                    file_path, 
                    file_type,
                    file_content_id, 
                    file_user_id, 
                    file_is_deleted) 
                       VALUES(
                       :file_path, 
                       :file_type,
                       :file_content_id,
                       :file_user_id,
                       :file_is_deleted)";

        return DB::run($sql, $params);
    }

    public static function get($file_id, $uid, $type)
    {
        $sql = "SELECT 
                    file_id, 
                    file_path, 
                    file_type,
                    file_content_id, 
                    file_user_id, 
                    file_date, 
                    file_is_deleted
                        FROM files 
                        WHERE file_id = :file_id AND 
                            file_user_id = :uid AND file_type = :type";

        return  DB::run($sql, ['file_id' => $file_id, 'uid' => $uid, 'type' => $type])->fetch();
    }

    public static function removal($file_path, $uid)
    {
        $sql = "UPDATE files SET file_is_deleted = 1 WHERE file_path = :file_path AND file_user_id = :uid";

        return DB::run($sql, ['file_path' => $file_path, 'uid' => $uid]);
    }
}
