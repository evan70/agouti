<?php include TEMPLATE_DIR . '/header.php'; ?>
<div class="wrap">
    <main class="admin">
        <div class="white-box">
            <div class="inner-padding">
                <h1>
                    <a href="/admin"><?= lang('Admin'); ?></a> / <span class="red"><?= $data['meta_title']; ?></span>
                </h1>

                <?php if (!empty($comments)) { ?>
              
                    <?php foreach ($comments as $comment) { ?>  
                    
                        <div class="comm-telo_bottom" id="comment_<?= $comment['comment_id']; ?>">
                            <div class="small">
                                <img class="ava" src="<?= user_avatar_url($comment['avatar'], 'small'); ?>">
                                <a class="date" href="/u/<?= $comment['login']; ?>"><?= $comment['login']; ?></a> 
                                
                                <span class="date"><?= $comment['date']; ?></span>

                                <span class="indent"> &#183; </span>
                                <a href="/post/<?= $comment['post_id']; ?>/<?= $comment['post_slug']; ?>">
                                    <?= $comment['post_title']; ?>
                                </a>
                            </div>
                            <div class="comm-telo-body">
                                <?= $comment['content']; ?> 
                            </div>
                           <div class="post-full-footer date">
                               + <?= $comment['comment_votes']; ?>
                               <span id="cm_dell" class="right comment_link small">
                                    <a data-id="<?= $comment['comment_id']; ?>" class="recover-comment">
                                        <?= lang('Recover'); ?>
                                    </a>
                               </span>
                           </div>
                        </div>
                    <?php } ?>
                    
                    <div class="pagination">
                  
                    </div>
                    
                <?php } else { ?>
                    <div class="no-content"><?= lang('no-comment'); ?>...</div>
                <?php } ?>
            </div>
        </div>
    </main>
    <?php include TEMPLATE_DIR . '/_block/admin-menu.php'; ?>
</div>
<?php include TEMPLATE_DIR . '/footer.php'; ?>  