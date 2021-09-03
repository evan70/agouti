<div class="wrap">
  <main>
    <div class="white-box pt5 pr15 pb5 pl15">
      <h1><?= lang('All topics'); ?>
        <?php if ($uid['user_trust_level'] == 5) { ?>
          <a class="right gray-light" href="/admin/topics">
            <i class="icon-pencil size-15"></i>
          </a>
        <?php } ?>
      </h1>
      <?php if (!empty($data['topics'])) { ?>
        <div class="oblong-box-list topic-box-list pb15">
          <?php foreach ($data['topics'] as $topic) { ?>
            <div class="oblong-box relative">
              <a title="<?= $topic['topic_title']; ?>" class="absolute mt10 left-5" href="/topic/<?= $topic['topic_slug']; ?>">
                <?= topic_logo_img($topic['topic_img'], 'max', $topic['topic_title'], 'ava-54'); ?>
              </a>
              <div class="item-desc">
                <a title="<?= $topic['topic_title']; ?>" href="/topic/<?= $topic['topic_slug']; ?>">
                  <?= $topic['topic_title']; ?>
                </a>
                <span class="mr5 ml5"></span>
                <sup class="gray">x<?= $topic['topic_count']; ?></sup>
                <?php if ($topic['topic_is_parent'] == 1 && $uid['user_trust_level'] == 5) { ?>
                  <sup class="red size-13">root</sup>
                <?php } ?>
                <div class="size-13"><?= $topic['topic_cropped']; ?>...</div>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } else { ?>
        <?= no_content('Topics no'); ?>
      <?php } ?>

      <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], '/topics'); ?>
    </div>
  </main>
  <aside>
    <div class="white-box p15">
      <?= lang('topic-info'); ?>
    </div>
    <?php if (!empty($data['news'])) { ?>
      <div class="white-box p15">
        <h3 class="uppercase mb5 mt0 fw300 size-13 gray"><?= lang('New ones'); ?></h3>
        <?php foreach ($data['news'] as $new) { ?>
          <a title="<?= $new['topic_title']; ?>" class="tags gray size-13" href="/topic/<?= $new['topic_slug']; ?>">
            <?= $new['topic_title']; ?>
          </a><br>
        <?php } ?>
      </div>
    <?php } ?>
    <?php if ($data['sheet'] == 'topics' && $uid['user_trust_level'] > 4) { ?>
      <a class="right size-13 button" href="/admin/update/count"><?= lang('Update the data'); ?></a>
    <?php } ?>
  </aside>
</div>