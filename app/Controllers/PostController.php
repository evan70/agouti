<?php

namespace App\Controllers;
use Hleb\Constructor\Handlers\Request;
use App\Models\PostModel;
use App\Models\UserModel;
use App\Models\SpaceModel;
use App\Models\AnswerModel;
use App\Models\CommentModel;
use App\Models\VotesPostModel;
use Phphleb\Imageresizer\SimpleImage;
use Lori\Config;
use Lori\Base;
use Parsedown;
use UrlRecord;

class PostController extends \MainController
{
    // Главная страница
    public function index($type) 
    {
        $pg     = \Request::getInt('page'); 
        $page   = (!$pg) ? 1 : $pg;
        $uid    = Base::getUid();
 
        $space_user  = SpaceModel::getSpaceUser($uid['id']);
        
        $pagesCount = PostModel::getPostFeedCount($space_user, $uid['id'], $type); 
        $posts      = PostModel::getPostFeed($page, $space_user, $uid['trust_level'], $uid['id'], $type);

        if (!$posts) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }

        $result = Array();
        foreach($posts as $ind => $row) {
            
            if(Base::getStrlen($row['post_url']) > 6) {
                $parse = parse_url($row['post_url']);
                $row['post_url'] = $parse['host'];  
            } 
            $row['post_content_preview']    = Base::cutWords($row['post_content'], 120);
            $row['lang_num_answers']        = Base::ru_num('answ', $row['post_answers_num']);
            $row['post_date']               = Base::ru_date($row['post_date']);
            $result[$ind]                   = $row;
         
        }  

        // Последние комментарии и отписанные пространства
        $latest_answers     = AnswerModel::latestAnswers($uid);
        $space_signed_bar   = SpaceModel::getSpaceUser($uid['id']);
 
        $result_comm = Array();
        foreach($latest_answers as $ind => $row) {
            $row['answer_content']      = Base::cutWords($row['answer_content'], 81);
            $row['answer_date']         = Base::ru_date($row['answer_date']);
            $result_comm[$ind]          = $row;
        }

        if($page > 1) { 
            $num = ' — ' . lang('Page') . ' ' . $page;
        } else {
            $num = '';
        }

        if($type == 'feed') {
            $meta_title = Config::get(Config::PARAM_HOME_TITLE) . $num;
            $meta_desc  = lang('home-desc') . $num;
        } else {
            $meta_title = lang('TOP') .'. '. Config::get(Config::PARAM_HOME_TITLE) . $num;
            $meta_desc  = lang('top-desc') . $num;   
        }

        $other = [
            'img'   => '/assets/images/areadev.jpg'
        ];
   
        // title, description
        Base::Meta($meta_title, $meta_desc, $other);  
        
        $data = [
            'latest_answers'    => $result_comm,
            'pagesCount'        => $pagesCount,
            'pNum'              => $page,
            'canonical'         => '/',
        ];

        return view(PR_VIEW_DIR . '/home', ['data' => $data, 'uid' => $uid, 'posts' => $result, 'space_bar' => $space_signed_bar, 'type' => $type]);
    }

    // Полный пост
    public function viewPost()
    {
        $uid        = Base::getUid();
        $slug       = \Request::get('slug');
        $post_id    = \Request::getInt('id');
        
        $post_new   = PostModel::getPostId($post_id); 

        // Проверим (id, slug)
        if (!$post_new) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        } else {
            if($slug != $post_new['post_slug']) {
                redirect('/post/' . $post_new['post_id'] . '/' . $post_new['post_slug']);
            }
        }
        
        $post = PostModel::getPostSlug($slug, $uid['id']); 
        
        // Рекомендованные посты
        $recommend = PostModel::PostsSimilar($post['post_id'], $post['post_space_id'], $uid['id']);
     
        // Выводить или нет? Что дает просмотр даты изменения?
        // Учитывать ли изменение в сортировки и в оповещение в будущем...
        if($post['post_date'] != $post['edit_date']) {
            $post['edit_date'] = $post['edit_date'];
        } else {
            $post['edit_date'] = null;
        }
        
        if(Base::getStrlen($post['post_url']) > 6) {
            $post['post_url_full'] = $post['post_url'];
            $parse = parse_url($post['post_url']);
            $post['post_url'] = $parse['host'];  
        } else {
            $post['post_url_full'] = null;
        }
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        // Обработает некоторые поля
        $post['post_content']   = $Parsedown->text($post['post_content']);
        $post['post_date']      = Base::ru_date($post['post_date']);
        $post['num_answers']    = Base::ru_num('answ', $post['post_answers_num']); 
        $post['num_comments']   = Base::ru_num('comm', $post['post_comments_num']); 
        $post['favorite_post']  = PostModel::getMyPostFavorite($post['post_id'], $uid['id']);
        
        // Получим ответы
        // post_type: 0 - дискуссия, 1 - Q&A
        $post_answers = AnswerModel::getAnswersPost($post['post_id'], $uid['id'], $post['post_type']);
  
        // Получим ЛО (временно)
        // Возможно нам стоит просто поднять ответ на первое место?
        // Изменив порядок сортировки при выбора LO, что позволит удрать это
        if($post['post_lo'] > 0) {
            $lo = AnswerModel::getAnswerLo($post['post_id']);
            $lo['answer_content'] = $lo['answer_content'];
        } else {
            $lo = null;
        }

        $answers = Array();
        foreach($post_answers as $ind => $row) {
            
            if(strtotime($row['answer_modified']) < strtotime($row['answer_date'])) {
                $row['edit'] = 1;
            }

            $row['comm']            = CommentModel::getCommentsAnswer($row['answer_id'], $uid['id']);
            $row['answer_content']  = $Parsedown->text($row['answer_content']);
            $row['answer_date']     = Base::ru_date($row['answer_date']);
            $row['favorite_answ']   = AnswerModel::getMyAnswerFavorite($row['answer_id'], $uid['id']);
            $answers[$ind]          = $row;
        }
       
        $other = [
            'type'      => 'article',
            'url'       => '/post/' . $post['post_id'] . '/' . $post['post_slug'],
            'post_date' => $post['post_date'],
        ];
       
        // title, description
        $meta_desc  = substr(strip_tags($post['post_content']), 0, 160);
        Base::Meta($post['post_title'], $meta_desc, $other); 

        Request::getResources()->addBottomStyles('/assets/js/md/mdeditor.css');  
        Request::getResources()->addBottomScript('/assets/js/md/mdeditor.min.js');
        Request::getResources()->addBottomScript('/assets/js/editor.js');

        $data = [
            'h1'        => lang('Post'),
            'canonical' => '/post/' . $post['post_id'] . '/' . $post['post_slug'],
        ]; 
        
        return view(PR_VIEW_DIR . '/post/post-view', ['data' => $data, 'post' => $post, 'answers' => $answers,  'uid' => $uid,  'recommend' => $recommend,  'lo' => $lo]);
    }

    // Посты участника
    public function userPosts()
    {
        $uid        = Base::getUid();
        $login      = \Request::get('login');
        
        // Если нет такого пользователя 
        $user   = UserModel::getUserLogin($login);
        if(!$user) {
            include HLEB_GLOBAL_DIRECTORY . '/app/Optional/404.php';
            hl_preliminary_exit();
        }
        
        $posts_user  = PostModel::getUsersPosts($login, $uid['id']); 
        
        $result = Array();
        foreach($posts_user as $ind => $row){
            $row['post_date']   = Base::ru_date($row['post_date']);
            $result[$ind]       = $row;
        }

        $meta_title = lang('Posts') . ' ' . $login;
        $meta_desc  = lang('Participant posts') . ' ' . $login;
        
        // title, description
        Base::Meta($meta_title, $meta_desc, $other = false); 
        
        $data = [
            'h1'    => $meta_title 
        ]; 
        
        return view(PR_VIEW_DIR . '/post/post-user', ['data' => $data, 'uid' => $uid, 'posts' => $result]);
    }
    
    // Форма добавление поста
    public function addPost() 
    {
        // Будем проверять ограничение на частоту 
        // print_r(PostModel::getPostSpeed(1));
        
        $uid  = Base::getUid();
        
        $space = SpaceModel::getSpaceSelect($uid);
        
        // Ajax выбор тега в зависимости от id пространства
        // В шаблоне post/add.php
        // Что будет учитываться в методе createPost() (добавлено)
        // В методе AddPost() необходимые изменения внесены
        
        $data = [
            'h1'    => lang('Add post')
        ];  
       
        // title, description
        Base::Meta(lang('Add post'), lang('Add post'), $other = false); 
        
        Request::getResources()->addBottomStyles('/assets/js/md/mdeditor.css');  
        Request::getResources()->addBottomScript('/assets/js/md/mdeditor.min.js');
        Request::getResources()->addBottomScript('/assets/js/editor.js');
       
        return view(PR_VIEW_DIR . '/post/post-add', ['data' => $data, 'uid' => $uid, 'space' => $space]);
    }
    
    // Добавление поста
    public function createPost()
    {
        // Получаем title и содержание
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем
        $post_content_img       = \Request::getPost('content_img');
        $post_url               = \Request::getPost('post_url');
        $post_closed            = \Request::getPostInt('closed');
        $post_top               = \Request::getPostInt('top'); 
        $post_type              = \Request::getPostInt('post_type');
     
        // IP адрес и ID кто добавляет
        $post_ip_int  = \Request::getRemoteAddress();
        $post_user_id = $_SESSION['account']['user_id'];
        
        // Получаем id пространства
        $space_id   = \Request::getPost('space_id');
        $tag_id     = \Request::getPost('tag_id');
        
        $redirect = '/post/add';
        Base::Limits($post_title, lang('Title'), '6', '260', $redirect);
        Base::Limits($post_content, lang('The post'), '6', '10000', $redirect);
        
        // Проверяем выбор пространства
        if ($space_id == '') {
            Base::addMsg(lang('Select space'), 'error');
            redirect($redirect);
            return true;
        }
        
        if($post_url) { 
            $og_img             = self::grabOgImg($post_url);
            $parse              = parse_url($post_url);
            $post_url_domain    = $parse['host']; 
        } 

        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных, для img повторов
        $post_url               = empty($post_url) ? '' : $post_url;
        $post_url_domain        = empty($post_url_domain) ? '' : $post_url_domain;
        $post_content_img       = empty($post_content_img) ? '' : $post_content_img;
        $og_img                 = empty($og_img) ? '' : $og_img;
        $tag_id                 = empty($tag_id) ? 0 : $tag_id;
        
        // Ограничим частоту добавления
        // Добавить условие TL
        $num_post =  PostModel::getPostSpeed($post_user_id);
        if(count($num_post) > 5) {
            Base::addMsg(lang('limit-post-day'), 'error');
            redirect('/');
        }
        
        // Получаем SEO поста
        $slugGenerator  = new UrlRecord();
        $slug           = $slugGenerator->GetSeoFriendlyName($post_title); 
        $post_slug      = substr($slug, 0, 90);

        $data = [
            'post_title'            => $post_title,
            'post_content'          => $post_content,
            'post_content_img'      => $post_content_img,
            'post_thumb_img'        => $og_img,
            'post_slug'             => $post_slug,
            'post_type'             => $post_type,
            'post_ip_int'           => $post_ip_int,
            'post_user_id'          => $post_user_id,
            'post_space_id'         => $space_id,
            'post_tag_id'           => $tag_id,
            'post_url'              => $post_url,
            'post_url_domain'       => $post_url_domain,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
        ];
        
        // Записываем пост
        $post_id = PostModel::AddPost($data);
        
        // Отправим в Discord
        if(Config::get(Config::PARAM_DISCORD)) {
            $url = '/post/'. $post_id .'/'. $post_slug;
            Base::AddWebhook($post_content, $post_title, $url);
        }
        
        redirect('/');   
    }
    
    // Парсим title
    public function grabTitle() 
    {
        $url   = \Request::getPost('uri');
        
        ob_start();
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        $getit = curl_exec($curl_handle);
        curl_close($curl_handle);
        ob_end_clean();
        preg_match("/<title>(.*)<\/title>/i", $getit, $matches);
        
        return $matches[1];
    }
    
    // Получаем данные Open Graph Protocol 
    public static function grabOgImg($post_url) 
    {
        // Возможно использовать библиотеку, пока так...
        $site_html = file_get_contents($post_url);
        $matches=null;
        preg_match_all('~<\s*meta\s+property="(og:[^"]+)"\s+content="([^"]*)~i', $site_html, $matches);
        
        $ogtags=array();
        for($i=0;$i<count($matches[1]);$i++)
        {
            $ogtags[$matches[1][$i]]=$matches[2][$i];
        }
        
        if($ogtags['og:image']) {
            
            $ext = pathinfo(parse_url($ogtags['og:image'], PHP_URL_PATH), PATHINFO_EXTENSION);
            
            if(in_array($ext, array ('jpg', 'jpeg', 'png'))) {
                
                $puth = HLEB_PUBLIC_DIR . '/uploads/thumbnails/';
                $year = date('Y') . '/';
                $filename = 'p-' . time() . '.' . $ext;
                
                if(!is_dir($puth . $year)) { @mkdir($puth . $year); }
                $local = $puth . $year . $filename;
 
                $fp = fopen ($local, 'w+');
                $ch = curl_init($ogtags['og:image']);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);  
 
                // https://github.com/phphleb/imageresizer
                $image = new SimpleImage();
                // Путь к исходному файлу в формате JPEG, GIF или PNG
                $image->load($puth . $year . $filename);
                // По ширине и высоте непропорционально
                $image->resize(165, 125);
                $image->save($puth . $year . $filename, "jpeg");

                if(file_exists($local)) {
                    return $year . $filename;
                }
                
            }

        }

        return false;
    }
    
    // Показ формы поста для редактирование
    public function editPost() 
    {
        $post_id    = \Request::getInt('id');
        $uid        = Base::getUid();
        
        // Получим пост
        $post   = PostModel::getPostId($post_id); 
         
        if(!$post){
            redirect('/');
        }
 
        // Редактировать может только автор и админ
        if ($post['post_user_id'] != $uid['id'] && $uid['trust_level'] != 5) {
            redirect('/');
        }
        
        $space = SpaceModel::getSpaceSelect($uid);
        $tags  = SpaceModel::getSpaceTags($post['post_space_id']);

        $data = [
            'h1'    => lang('Edit post')
        ];

        Request::getResources()->addBottomStyles('/assets/js/md/mdeditor.css');  
        Request::getResources()->addBottomScript('/assets/js/md/mdeditor.min.js');
        Request::getResources()->addBottomScript('/assets/js/editor.js');
        
        // title, description
        Base::Meta(lang('Edit post'), lang('Edit post'), $other = false);

        return view(PR_VIEW_DIR . '/post/post-edit', ['data' => $data, 'uid' => $uid, 'post' => $post, 'space' => $space, 'tags' => $tags]);
    }
    
    // Изменяем пост
    public function editPostRecording() 
    {
        $post_id                = \Request::getPostInt('post_id');
        $post_title             = \Request::getPost('post_title');
        $post_content           = $_POST['post_content']; // не фильтруем 
        $post_content_img       = \Request::getPost('content_img');
        $post_type              = \Request::getPostInt('post_type');
        $post_closed            = \Request::getPostInt('closed');
        $post_top               = \Request::getPostInt('top');
        $post_space_id          = \Request::getPostInt('space_id');
        $post_tag_id            = \Request::getPostInt('tag_id');
        $post_url               = \Request::getPost('post_url');
        
        $account = \Request::getSession('account');
        
        // Получим пост
        $post = PostModel::getPostId($post_id); 
         
        if(!$post){
            redirect('/');
        }
        
        // Редактировать может только автор и админ
        if ($post['post_user_id'] != $account['user_id'] && $account['trust_level'] != 5) {
            redirect('/');
        }
        
        // Проверяем длину title
        if (Base::getStrlen($post_title) < 6 || Base::getStrlen($post_title) > 320) {
            Base::addMsg('Длина заголовка должна быть от 6 до 320 знаков', 'error');
            redirect('/post/edit' .$post_id);
            return true;
        }
        
        // Проверяем длину тела
        if (Base::getStrlen($post_content) < 6 || Base::getStrlen($post_content) > 10000) {
            Base::addMsg('Длина заголовка должна быть от 6 до 10000 знаков', 'error');
            redirect('/post/edit/' .$post_id);
            return true;
        }
        
        // Проверяем url для > TL1
        // Ввести проверку дублей и запрещенных
        // При изменение url считаем частоту смену url после добавления у конкретного пользователя
        // Если больше N оповещение персонала, если изменен на запрещенный, скрытие поста,
        // или более расширенное поведение, а пока просто проверим
        $post_url               = empty($post_url) ? '' : $post_url;
        $post_content_img       = empty($post_content_img) ? '' : $post_content_img;
        $post_tag_img           = empty($post_tag_id) ? '' : $post_tag_id;
        
        $data = [
            'post_id'               => $post_id,
            'post_title'            => $post_title, 
            'post_type'             => $post_type,
            'post_content'          => $post_content,
            'post_content_img'      => $post_content_img,
            'post_closed'           => $post_closed,
            'post_top'              => $post_top,
            'post_space_id'         => $post_space_id,
            'post_tag_id'           => $post_tag_id,
            'post_url'              => $post_url,
        ];
        
        // Перезапишем пост
        PostModel::editPost($data);
        
        redirect('/post/' . $post['post_id'] . '/' . $post['post_slug']);
    }
    
    // Размещение своего поста у себя в профиле
    public function addPostProf()
    {
        $post_id = \Request::getPostInt('post_id');
        
        // Получим пост
        $post = PostModel::getPostId($post_id); 
        
        // Это делать может только может только автор
        if ($post['post_user_id'] != $_SESSION['account']['user_id']) {
            return true;
        }
        
        PostModel::addPostProfile($post_id, $_SESSION['account']['user_id']);
       
        return true;
    }
  
    // Помещаем пост в закладки
    public function addPostFavorite()
    {
        $post_id = \Request::getPostInt('post_id');
        $post    = PostModel::getPostId($post_id); 
        
        if(!$post){
            redirect('/');
        }
        
        PostModel::setPostFavorite($post_id, $_SESSION['account']['user_id']);
       
        return true;
    }
  
    // Удаляем пост / + восстанавливаем пост
    public function deletePost()
    {
        // Доступ только персоналу
        $account = \Request::getSession('account');
        if ($account['trust_level'] != 5) {
            return false;
        }
        
        $post_id = \Request::getPostInt('post_id');
        
        PostModel::PostDelete($post_id);
       
        return true;
    }
  
    // Просмотр поста с титульной страницы
    public function shownPost() 
    {
        $post_id = \Request::getPostInt('post_id');
        $post    = PostModel::getPostId($post_id); 
        
        if(!$post){
            return false;
        }
        
        $Parsedown = new Parsedown(); 
        $Parsedown->setSafeMode(true); // безопасность
        
        $post = $Parsedown->text($post['post_content']);

        return view(PR_VIEW_DIR . '/post/postcode', ['post_content' => $post]);
    }
}