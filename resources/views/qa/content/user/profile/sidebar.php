<?php $user = $data['user']; ?>

<div class="col-span-4 justify-between mb-none">

  <div class="br-box-gray bg-white p15 mb15">
    <blockquote class="ml0 mb10 max-w640 gray">
      <?= $user['user_about']; ?>...
    </blockquote>
    <div class="gray-400">
      <i class="bi bi-calendar-week middle"></i>
      <span class="middle lowercase text-sm">
        <?= $user['user_created_at']; ?> —
        <?= Translate::get('tl' . $user['user_trust_level'] . '.title'); ?>
        <sup class="date">TL<?= $user['user_trust_level']; ?></sup>
      </span>
    </div>
  </div>

  <?php if ($data['blogs']) { ?>
    <div class="br-box-gray bg-white p15 mb15">
      <h3 class="m0 uppercase gray font-normal text-sm mb15">
        <?= Translate::get('created by'); ?>
      </h3>
      <?php foreach ($data['blogs'] as $blog) { ?>
        <div class="w-100 mb-w-100 flex flex-row">
          <a title="<?= $blog['facet_title']; ?>" class="mr10" href="<?= getUrlByName($blog['facet_type'], ['slug' => $blog['facet_slug']]); ?>">
            <?= facet_logo_img($blog['facet_img'], 'max', $blog['facet_title'], 'w64 br-box-gray br-rd-50'); ?>
          </a>
          <div class="ml5 w-100">
            <a class="black" title="<?= $blog['facet_title']; ?>" href="<?= getUrlByName($blog['facet_type'], ['slug' => $blog['facet_slug']]); ?>">
              <?= $blog['facet_title']; ?>
            </a>
            <div class="text-sm pr15 mb-pr-0 gray-400">
              <?= $blog['facet_short_description']; ?>
              <div class="flex mt5 text-sm">
                <i class="bi bi-journal mr5"></i>
                <?= $blog['facet_count']; ?>
                <?php if ($blog['facet_focus_count'] > 0) { ?>
                  <i class="bi bi-people ml15 mr5"></i>
                  <?= $blog['facet_focus_count']; ?>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  <?php } ?>

  <?php if ($user['user_my_post'] != 0) { ?>
    <?php $post = $data['post']; ?>
    <div class="br-box-gray bg-white p15 mb15">
      <h3 class="m0 uppercase gray font-normal text-sm">
        <?= Translate::get('selected post'); ?>
      </h3>
      <div class="mt5">
        <a class="dark-gray-300" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
          <?= $post['post_title']; ?>
        </a>
        <?php if ($uid['user_id'] > 0) { ?>
          <?php if ($uid['user_login'] == $user['user_login']) { ?>
            <a class="del-post-profile ml10" data-post="<?= $post['post_id']; ?>">
              <i class="bi bi-trash red-500"></i>
            </a>
          <?php } ?>
        <?php } ?>
        <div class="text-sm lowercase">
          <a class="gray" href="<?= getUrlByName('profile', ['login' => $user['user_login']]); ?>">
            <?= user_avatar_img($user['user_avatar'], 'small', $user['user_login'], 'w18 mr5'); ?>
            <?= $user['user_login']; ?>
          </a>
          <span class="gray-400 ml5"><?= $post['post_date'] ?></span>
          <?php if ($post['post_answers_count'] != 0) { ?>
            <a class="gray-400 right" href="<?= getUrlByName('post', ['id' => $post['post_id'], 'slug' => $post['post_slug']]); ?>">
              <i class="bi bi-chat-dots middle"></i>
              <?= $post['post_answers_count']; ?>
            </a>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } ?>

  <?php if ($data['topics']) { ?>
    <div class="br-box-gray bg-white p15 mb15">
      <h3 class="uppercase gray m0 font-normal text-sm mb5">
        <?= Translate::get('is reading'); ?>
      </h3>
      <?php foreach ($data['topics'] as  $topic) { ?>
        <div class="mt5 mb5">
          <a class="flex relative pt5 pb5 hidden gray" href="<?= getUrlByName('topic', ['slug' => $topic['facet_slug']]); ?>" title="<?= $topic['facet_title']; ?>">
            <?= facet_logo_img($topic['facet_img'], 'small', $topic['facet_title'], 'w24 h24 mr10'); ?>
            <span class="bar-name text-sm"><?= $topic['facet_title']; ?></span>
          </a>
        </div>
      <?php } ?>
    </div>
  <?php } ?>

  <?php if (!empty($data['participation'][0]['facet_id'])) { ?>
    <div class="br-box-gray bg-white p15 mb15">
      <h3 class="m0 uppercase gray font-normal text-sm mb5">
        <?= Translate::get('understands'); ?>
      </h3>
      <?php foreach ($data['participation'] as $part) { ?>
        <a class="bg-blue-100 bg-hover-green white-hover pt5 pr10 pb5 pl10 mb5 br-rd20 sky-500 inline text-sm" href="<?= getUrlByName('topic', ['slug' => $part['facet_slug']]); ?>">
          <?= $part['facet_title']; ?>
        </a>
      <?php } ?>
    </div>
  <?php } ?>

  <div class="br-box-gray bg-white p15 mb15">
    <h3 class="m0 uppercase gray font-normal text-sm">
      <?= Translate::get('contacts'); ?>
    </h3>
    <?php foreach (Config::get('fields-profile') as $block) { ?>
      <?php if ($user[$block['title']]) { ?>
        <div class="mt5">
          <?= $block['lang']; ?>:
          <?php if ($block['url']) { ?>
            <a href="<?php if ($block['addition']) { ?><?= $block['addition']; ?><?php } ?><?= $user[$block['url']]; ?>" rel="noopener nofollow ugc">
              <span class="mr5 ml5"><?= $user[$block['title']]; ?></span>
            </a>
          <?php } else { ?>
            <span class="mr5 ml5"><?= $user[$block['title']]; ?></span>
          <?php } ?>
        </div>
      <?php } else { ?>
        <?php if ('user_location' == $block['title']) { ?>
          <div class="mb20">
            <?= $block['lang']; ?>: ...
          </div>
        <?php } ?>
      <?php } ?>
    <?php } ?>
  </div>

  <div class="br-box-gray bg-white p15 mb15">
    <h3 class="m0 uppercase gray font-normal text-sm">
      <?= Translate::get('badges'); ?>
    </h3>
    <div class="m0 text-3xl">
      <i title="<?= Translate::get('medal for registration'); ?>" class="bi bi-gift sky-500"></i>
      <?php if ($user['user_id'] < 50) { ?>
        <i title="<?= Translate::get('joined in the early days'); ?>" class="bi bi-award green-600"></i>
      <?php } ?>
      <?php foreach ($data['badges'] as $badge) { ?>
        <?= $badge['badge_icon']; ?>
      <?php } ?>
    </div>
  </div>

  <?php if (UserData::checkAdmin()) { ?>
    <div class="br-box-gray bg-white p15 mb15">
      <h3 class="m0 uppercase gray font-normal text-sm">
        <?= Translate::get('admin'); ?>
      </h3>
      <div class="mt5">
        <?php if ($user['user_trust_level'] != UserData::REGISTERED_ADMIN) { ?>
          <?php if ($user['user_ban_list'] == 1) { ?>
            <span class="type-ban gray mb5 block" data-id="<?= $user['user_id']; ?>" data-type="user">
              <i class="bi bi-person-x-fill red-500 middle mr5"></i>
              <span class="red-500 text-sm"><?= Translate::get('unban'); ?></span>
            </span>
          <?php } else { ?>
            <span class="type-ban text-sm gray mb5 block" data-id="<?= $user['user_id']; ?>" data-type="user">
              <i class="bi bi-person-x middle mr5"></i>
              <?= Translate::get('ban it'); ?>
            </span>
          <?php } ?>
        <?php } ?>

        <a class="gray mb5 block" href="<?= getUrlByName('admin.user.edit', ['id' => $user['user_id']]); ?>">
          <i class="bi bi-gear middle mr5"></i>
          <span class="middle"><?= Translate::get('edit'); ?></span>
        </a>
        <a class="gray block" href="<?= getUrlByName('admin.badges.user.add', ['id' => $user['user_id']]); ?>">
          <i class="bi bi-award middle mr5"></i>
          <span class="middle"><?= Translate::get('reward the user'); ?></span>
        </a>
        <?php if ($user['user_whisper']) { ?>
          <div class="tips text-sm pt15 pb10 gray-600">
            <i class="bi bi-info-square green-600 mr5"></i>
            <?= $user['user_whisper']; ?>
          </div>
        <?php } ?>
        <hr>
        <span class="gray">id<?= $user['user_id']; ?> | <?= $user['user_email']; ?></span>
      </div>
    </div>
  <?php } ?>
</div>