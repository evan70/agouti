<?php if (!empty($data['answers'])) : ?>
  <div>
    <h2 class="lowercase m0 text-2xl">
      <?= Html::numWord($post['amount_content'], __('app.num_answer'), true); ?>
    </h2>

    <?php foreach ($data['answers'] as  $answer) : ?>
      <div class="block-answer mb15">
        <?php if ($answer['answer_is_deleted'] == 0) : ?>
          <?php if (UserData::getUserId() == $answer['answer_user_id']) { ?> <?php $otvet = 1; ?> <?php } ?>
          <div class="br-top-dotted mb20"></div>
          <ol class="list-none">
            <li class="content_tree" id="answer_<?= $answer['answer_id']; ?>">
              <div class="max-w780">
                <?= Content::text($answer['answer_content'], 'text'); ?>
              </div>
              <div class="flex text-sm justify-between">
                <div class="flex gap">
                  <?= Html::votes($answer, 'answer'); ?>

                  <?php if (UserData::getRegType(config('trust-levels.tl_add_comm_qa'))) : ?>
                    <?php if ($post['post_closed'] == 0) : ?>
                      <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                        <a data-answer_id="<?= $answer['answer_id']; ?>" class="add-comment gray"><?= __('app.reply'); ?></a>
                      <?php endif; ?>
                    <?php endif; ?>
                  <?php endif; ?>

                  <?php if (Access::author('answer', $answer['answer_user_id'], $answer['date'], 30) === true) : ?>
                    <?php if (UserData::getUserId() == $answer['answer_user_id'] || UserData::checkAdmin()) : ?>
                      <a class="editansw gray" href="<?= url('content.edit', ['type' => 'answer', 'id' => $answer['answer_id']]); ?>">
                        <?= __('app.edit'); ?>
                      </a>
                    <?php endif; ?>
                  <?php endif; ?>

                  <?php if (UserData::checkAdmin()) : ?>
                    <a data-type="answer" data-id="<?= $answer['answer_id']; ?>" class="type-action gray-600">
                      <?= __('app.remove'); ?>
                    </a>
                  <?php endif; ?>

                  <?= Html::favorite($answer['answer_id'], 'answer', $answer['tid']); ?>

                  <?php if (UserData::getUserId() != $answer['answer_user_id'] && UserData::getRegType(config('trust-levels.tl_add_report'))) : ?>
                    <a data-post_id="<?= $post['post_id']; ?>" data-type="answer" data-content_id="<?= $answer['answer_id']; ?>" class="msg-flag gray-600">
                      <svg class="icons"><use xlink:href="/assets/svg/icons.svg#flag"></use></svg>
                    </a>
                  <?php endif; ?>
                </div>
                <div class="text-sm gray-600 flex gap lowercase">
                  <a class="brown" href="<?= url('profile', ['login' => $answer['login']]); ?>"><?= $answer['login']; ?></a>
                  <span class="mb-none"><?= Html::langDate($answer['date']); ?>
                  <?php if (empty($answer['edit'])) : ?>
                    (<?= __('app.ed'); ?>.)
                  <?php endif; ?></span>
                  <?= insert('/_block/admin-show-ip', ['ip' => $answer['answer_ip'], 'publ' => $answer['answer_published']]); ?>
                </div>
              </div>
              <div data-insert="<?= $answer['answer_id']; ?>" id="insert_id_<?= $answer['answer_id']; ?>" class="none"></div>
            </li>
          </ol>
        <?php endif; ?>
      </div>

      <?php $n = 0;
      foreach ($answer['comments'] as  $comment) :
        $n++; ?>
        <?php if ($comment['comment_is_deleted'] == 0) : ?>
          <div class="br-bottom<?php if ($n > 1) : ?> ml30<?php endif; ?>"></div>
          <ol class="max-w780 list-none">
            <li class="content_tree" id="comment_<?= $comment['comment_id']; ?>">
              <div class="qa-comment">
                <?= Content::text($comment['comment_content'], 'line'); ?>
                <span class="qa-comment-footer">
                  — <a class="brown" href="<?= url('profile', ['login' => $comment['login']]); ?>"><?= $comment['login']; ?></a>
                  <span class="qa-comment-ml">
                      <?= Html::langDate($comment['date']); ?>

                      <?php if (UserData::getRegType(config('trust-levels.tl_add_comm_qa'))) : ?>
                        <?php if ($post['post_closed'] == 0) : ?>
                          <?php if ($post['post_is_deleted'] == 0 || UserData::checkAdmin()) : ?>
                            <a data-answer_id="<?= $answer['answer_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="add-comment gray-600">
                              <?= __('app.reply'); ?>
                            </a>
                          <?php endif; ?>
                        <?php endif; ?>
                      <?php endif; ?>

                      <?php if (Access::author('comment', $comment['comment_user_id'], $comment['date'], 30) === true) : ?>
                        <a data-post_id="<?= $post['post_id']; ?>" data-comment_id="<?= $comment['comment_id']; ?>" class="editcomm gray-600">
                          <svg class="icons"><use xlink:href="/assets/svg/icons.svg#edit"></use></svg>
                        </a>
                      <?php endif; ?>

                      <?php if (UserData::checkAdmin()) : ?>
                        <a data-type="comment" data-id="<?= $comment['comment_id']; ?>" class="type-action gray-600">
                          <?= __('app.remove'); ?>
                        </a>
                      <?php endif; ?>

                      <?php if (UserData::getUserId() != $comment['comment_user_id'] && UserData::checkActiveUser()) : ?>
                        <a data-post_id="<?= $post['post_id']; ?>" data-type="comment" data-content_id="<?= $comment['comment_id']; ?>" class="msg-flag gray-600">
                          <?= __('app.report'); ?>
                        </a>
                      <?php endif; ?>
                 </span>     
              </div>
              <div data-insert="<?= $comment['comment_id']; ?>" id="insert_id_<?= $comment['comment_id']; ?>" class="none"></div>
            </li>
          </ol>
        <?php endif; ?>
      <?php endforeach; ?>

    <?php endforeach; ?>
  </div>
<?php else : ?>
  <?php if ($post['post_closed'] != 1) : ?>
    <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.no_answers'), 'icon' => 'info']); ?>
  <?php endif; ?>
<?php endif; ?>

<?php if (!empty($otvet)) : ?>
  <?= insert('/_block/no-content', ['type' => 'small', 'text' => __('app.you_answered'), 'icon' => 'info']); ?>
<?php else : ?>
  <?php if (UserData::checkActiveUser()) : ?>
    <?php if ($post['post_feature'] == 1 && $post['post_draft'] == 0 && $post['post_closed'] == 0) : ?>

      <form class="mb15" action="<?= url('content.create', ['type' => 'answer']); ?>" accept-charset="UTF-8" method="post">
        <?= csrf_field() ?>
        <?= insert('/_block/form/editor', [
          'height'  => '250px',
          'id'      => $post['post_id'],
          'type'    => 'answer',
        ]); ?>

        <div class="clear pt5">
          <input type="hidden" name="post_id" value="<?= $post['post_id']; ?>">
          <input type="hidden" name="answer_id" value="0">
          <?= Html::sumbit(__('app.reply')); ?>
        </div>
      </form>

    <?php endif; ?>
  <?php endif; ?>
<?php endif;  ?>

<?php if ($post['post_closed'] == 1) :
  echo insert('/_block/no-content', ['type' => 'small', 'text' => __('app.question_closed'), 'icon' => 'closed']);
endif; ?>