<?php include TEMPLATE_DIR . '/header.php'; ?>
<?php include TEMPLATE_DIR . '/menu.php'; ?>
<main>
    <div class="left-ots">
        <?php if (!empty($posts)) { ?>
        
            <?php foreach ($posts as  $post) { ?> 
                <div class="post-telo">
                    <?php if (!$uid['id']) { ?> 
                        <div id="vot<?= $post['post_id']; ?>" class="voters">
                            <a rel="nofollow" href="/login"><div class="post-up-id"></div></a>
                            <div class="score"><?= $post['post_votes']; ?></div>
                        </div>
                    <?php } else { ?> 
                        <?php if ($post['votes_post_user_id'] || $uid['id'] == $post['post_user_id']) { ?>
                            <div class="voters active">
                                <div class="post-up-id"></div>
                                <div class="score"><?= $post['post_votes']; ?></div>
                            </div>
                        <?php } else { ?>
                            <div id="up<?= $post['post_id']; ?>" class="voters">
                                <div data-id="<?= $post['post_id']; ?>" class="post-up-id"></div>
                                <div class="score"><?= $post['post_votes']; ?></div>
                            </div>
                        <?php } ?> 
                    <?php } ?> 
                    <div class="post-body">
                        <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                            <h3 class="titl"><?= $post['post_title']; ?></h3>
                        </a>
          
                        <div class="footer">
                            <img class="ava" src="/uploads/avatar/small/<?= $post['avatar'] ?>">
                            <span class="user"> 
                                <a href="/u/<?= $post['login']; ?>"><?= $post['login']; ?></a> 
                            </span>
                            <span class="date">
                                <?= $post['post_date']; ?>
                            </span>
                            <?php if($post['post_comments'] !=0) { ?> 
                                <span class="otst"> | </span>
                                <a class="u-url" href="/posts/<?= $post['post_slug']; ?>">
                                    <?= $post['post_comments']; ?>  <?= $post['num_comments']; ?>
                                </a>                                
                            <?php } ?>
                        </div>  
                    </div>
                </div>    
            <?php } ?>

        <?php } else { ?>

            <h3>Нет постов (в разработке)</h3>

            <p>К сожалению поcтов по данному пространству нет...</p>

        <?php } ?>
    </div>
</main>

<aside id="sidebar">
    <?php if(!$uid['id']) { ?> 
            <div class="right"> 
                <a href="/login"><div class="hide-space-id add-space">Подписаться</div></a>
            </div>
        <?php } else { ?>
            <div class="right"> 
                <?php if($data['space_hide'] == 1) { ?> 
                    <div data-id="<?= $space['space_id']; ?>" class="hide-space-id add-space">Подписаться</div>
                <?php } else { ?> 
                    <div data-id="<?= $space['space_id']; ?>" class="hide-space-id no-space">Отписаться</div>
                <?php } ?>   
            </div>  
    <?php } ?> 
    <br> <br> 
    <div class="space-text">
        <img class="space-img" src="/uploads/space/<?= $space['space_img']; ?>">
    
        <?php print_r($space); ?>
   
    <?= $space['space_name']; ?>
    
        <?= $space['space_text']; ?>
    
        <?php if($uid['trust_level'] == 5) { ?>
            <a class="right" href="/space/<?= $space['space_slug']; ?>/edit">
                <svg class="md-icon moon">
                    <use xlink:href="/assets/svg/icons.svg#edit"></use>
                </svg>
            </a>
        <?php } ?>
    </div>
</aside> 

<?php include TEMPLATE_DIR . '/footer.php'; ?>