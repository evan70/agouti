<?php

namespace App\Models\User;

use DB;

class BadgeModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Все награды
    public static function getAll()
    {
        $sql = "SELECT 
                    badge_id,
                    badge_icon,
                    badge_tl,
                    badge_score,
                    badge_title,
                    badge_description
                        FROM badges";

        return DB::run($sql)->fetchAll();
    }

    // Получим информацию по награде
    public static function getId($badge_id)
    {
        $sql = "SELECT 
                    badge_id,
                    badge_icon,
                    badge_tl,
                    badge_score,
                    badge_title,
                    badge_description
                        FROM badges 
                        WHERE badge_id = :badge_id";

        return DB::run($sql, ['badge_id' => $badge_id])->fetch();
    }

    // Редактирование награды
    public static function edit($params)
    {
        $sql = "UPDATE badges 
                    SET badge_title     = :badge_title,  
                    badge_description   = :badge_description, 
                    badge_icon          = :badge_icon 
                        WHERE badge_id  = :badge_id";

        return  DB::run($sql, $params);
    }

    // Добавить награды
    public static function add($params)
    {
        $sql = "INSERT INTO badges(badge_tl, 
                        badge_score, 
                        badge_title, 
                        badge_description, 
                        badge_icon) 
                            VALUES(:badge_tl, 
                                :badge_score, 
                                :badge_title, 
                                :badge_description, 
                                :badge_icon)";

        return DB::run($sql, $params);
    }

    // Reward the participant 
    // Наградить участника
    public static function badgeUserAdd($params)
    {
        $sql = "INSERT INTO badges_user(bu_user_id, bu_badge_id) VALUES(:user_id, :badge_id)";

        return DB::run($sql, $params);
    }

    // All participant awards
    // Все награды участника
    public static function getBadgeUserAll($uid)
    {
        $sql = "SELECT 
                    bu_id,
                    bu_badge_id,
                    bu_user_id,
                    badge_id,
                    badge_tl,
                    badge_score,
                    badge_title,
                    badge_icon,
                    badge_description
                        FROM badges_user
                        LEFT JOIN badges ON badge_id = bu_badge_id
                            WHERE bu_user_id = :uid";

        return DB::run($sql, ['uid' => $uid])->fetchAll();
    }

    // Remove member award
    // Удалить награду участника
    public static function remove($params)
    {
        $sql = "DELETE FROM badges_user WHERE bu_id = :bu_id AND bu_user_id = :bu_user_id";

        return DB::run($sql, $params);
    }
}
