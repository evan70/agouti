<?php foreach ($facets as $key => $facet) : ?> <?= print_r($facet); ?>
  <div class="mb20 items-center flex flex-row">
    <a title="<?= $facet['translation_title']; ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
      <?= Html::image($facet['facet_img'], $facet['translation_title'], 'img-lg mr10', 'logo', 'max'); ?>
    </a>
    <div class="w-100">
      <a class="black text-xl" title="<?= $facet['facet_title']; ?>" href="<?= url($facet['facet_type'], ['slug' => $facet['facet_slug']]); ?>">
        <?= $facet['facet_title']; ?>
      </a>
      <span class="gray-600">•
        <?= Html::signed([
          'type'            => 'facet',
          'id'              => $facet['facet_id'],
          'content_user_id' => $facet['facet_user_id'],
          'state'           => $facet['signed_facet_id'],
        ]); ?>
      </span>
      <?php if (UserData::getUserId() == $facet['facet_user_id']) : ?>
        <svg class="icons icon-small sky"><use xlink:href="/assets/svg/icons.svg#mic"></use></svg>
      <?php endif; ?>
      <div class="mr10 mt5 gray">
        <?= Content::fragment(Content::text($facet['translation_short_description'], 'line'), 68); ?>
        <span class="flex right gray-600 text-sm">
          <svg class="icons"><use xlink:href="/assets/svg/icons.svg#post"></use></svg>
          <?= $facet['facet_count']; ?>
          <?php if ($facet['facet_focus_count'] > 0) : ?>
            <svg class="icons ml15"><use xlink:href="/assets/svg/icons.svg#users"></use></svg>
            <?= $facet['facet_focus_count']; ?>
          <?php endif; ?>
        </span>
      </div>
    </div>
  </div>
<?php endforeach; ?>