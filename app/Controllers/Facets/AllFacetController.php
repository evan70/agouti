<?php

namespace App\Controllers\Facets;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\FacetModel;
use Base, Config, Translate;

class AllFacetController extends MainController
{
    private $uid;

    public function __construct()
    {
        $this->uid  = Base::getUid();
    }

    public function index($sheet, $type)
    {
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;
 
        $limit = 40;
        $pagesCount = FacetModel::getFacetsAllCount($this->uid['user_id'], $sheet);
        $facets     = FacetModel::getFacetsAll($page, $limit, $this->uid['user_id'], $sheet);

        $num = ' ';
        if ($page > 1) {
            $num = sprintf(Translate::get('page-number'), $page);
        }

        $url = self::names($sheet);

        if ($sheet == 'blogs.all') {
            $count_blog     = FacetModel::countFacetsUser($this->uid['user_id'], 'blog');
            $count_add_blog = $this->uid['user_trust_level'] == 5 ? 999 : Config::get('trust-levels.count_add_blog');
            $in_total       = $count_add_blog - $count_blog;
        }

        $m = [
            'og'         => false,
            'twitter'    => false,
            'imgurl'     => false,
            'url'        => getUrlByName($url),
        ];

        return view(
            '/facets/all',
            [
                'meta'  => meta($m, Translate::get($sheet) . $num, Translate::get($sheet . '-desc') . $num),
                'uid'   => $this->uid,
                'data'  => [
                    'sheet'         => $sheet,
                    'type'          => $type,
                    'facets'        => $facets,
                    'pagesCount'    => ceil($pagesCount / $limit),
                    'pNum'          => $page,
                    'count_blog'    => $in_total ?? 0,
                ]
            ]
        );
    }

    public static function names($sheet)
    {
        switch ($sheet) {
            case 'blogs.all':
                $url = 'blogs.all';
                break;
            case 'blogs.new':
                $url = 'blogs.new';
                break;
            case 'blogs.my':
                $url = 'blogs.my';
                break;
            case 'topics.new':
                $url = 'topics.new';
                break;
            case 'topics.my':
                $url = 'topics.my';
                break;
            default:
                $url = 'topics.all';
                break;
        }

        return $url;
    }
}
