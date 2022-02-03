<?= includeTemplate('/view/default/header', ['data' => $data, 'user' => $user, 'meta' => $meta]); ?>
<h1 class="ml20"><?= Translate::get('categories'); ?></h1>

<div class="grid grid-cols-3 gap-2 mb-block mb20 p20 bg-violet-50">
  <?php foreach (Config::get('web-root-categories') as  $cat) { ?>
    <div class="mb10">
      <a class="underline-hover text-2xl block" href="<?= getUrlByName('web.topic', ['slug' => $cat['url']]); ?>">
        <?= $cat['title']; ?>
      </a>
      <?php if (!empty($cat['sub'])) { ?>
        <?php foreach ($cat['sub'] as $sub) { ?>
          <a class="pr10 text-sm black inline" href="<?= getUrlByName('web.topic', ['slug' => $sub['url']]); ?>">
            <?= $sub['title']; ?>
          </a>
        <?php } ?>
      <?php } ?>
      <?php if (!empty($cat['help'])) { ?>
        <div class="text-sm gray-400"><?= $cat['help']; ?>...</div>
      <?php } ?>
    </div>
  <?php } ?>
</div>

<div class="grid grid-cols-12 gap-4">
  <main class="col-span-9 mb-col-12 ml20 mr20">
    <p>
      <?= num_word($data['count'], Translate::get('num-website'), false); ?>:
      <?= $data['count']; ?>
      <span class="right">
        <a class="<?php if ($data['sheet'] == 'web.all') { ?>bg-gray-100 p5 gray-600 <?php } ?>mr20" href="<?= getUrlByName('web.all'); ?>">
          <?= Translate::get('by.date'); ?>
        </a>
        <a class="<?php if ($data['sheet'] == 'web.top') { ?>bg-gray-100 p5 gray-600 <?php } ?>" href="<?= getUrlByName('web.top'); ?>">
          TOP
        </a>
      </span>
    </p>

    <?php if (!empty($data['items'])) { ?>
      <?= includeTemplate('/view/default/site', ['data' => $data, 'user' => $user]); ?>
    <?php } else { ?>
      <?= no_content(Translate::get('no'), 'bi bi-info-lg'); ?>
    <?php } ?>

    <?= pagination($data['pNum'], $data['pagesCount'], $data['sheet'], getUrlByName($data['sheet'])); ?>
  </main>
  <aside class="col-span-3 mb-col-12 mb-none">
    <div class="right mt15 mr20"><?= Translate::get('being.developed'); ?></div>
  </aside>
</div>
<?= includeTemplate('/view/default/footer', ['user' => $user]); ?>