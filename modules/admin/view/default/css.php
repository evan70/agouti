<?= includeTemplate(
  '/view/default/menu',
  [
    'data'  => $data,
    'meta'  => $meta,
    'menus' => [],
  ]
); ?>
<h4><?= __('admin.topics'); ?>:</h4>
<a href="#" class="tag">.tag</a>
<a href="#" class="tag-grey">.tag-grey</a>
<a href="#" class="tag-violet">.tag-violet</a>
<a href="#" class="tag-yellow">.tag-yellow</a>


<h4 class="mt15"><?= __('admin.buttons'); ?>:</h4>
<p><i class="btn btn-outline-primary">btn btn-outline-primary</i></p>
<p><i class="btn btn-small btn-outline-primary">btn btn-small btn-outline-primary</i></p>
<p><i class="btn btn-primary">btn btn-primary</i></p>
<p><i class="btn btn-small btn-primary">btn btn-small btn-primary</i></p>

<h4><?= __('admin.other'); ?>:</h4>
<div class="box-flex flex-wrap mb20">
  <div class="mr20">
    <div class="bg-green img-sm"></div>.img-sm
  </div>
  <div class="mr20">
    <div class="bg-green img-base"></div>.img-base
  </div>
  <div class="mr20">
    <div class="bg-green img-lg"></div>.img-lg
  </div>
  <div>
    <div class="bg-green img-xl"></div>.img-xl
  </div>
</div>

<div class="box-flex flex-wrap mb20">
  <div class="label-grey mr5">.label-grey</div>
  <div class="label-green mr5">.label-green</div>
  <div class="label-red mr5">.label-red</div>
  <div class="label-orange">.label-orange</div>
</div>

<div class="box-flex flex-wrap">
  <div class="box mr5">.box</div>
  <div class="box bg-yellow mr5">.box .bg-yellow</div>
  <div class="box bg-blue-200 mr5">.box .bg-blue-200</div>
  <div class="box bg-violet mr5">.box .bg-violet</div>
  <div class="box bg-lightgray mr5">.box .bg-lightgray</div>
  <div class="box bg-beige mr5">.box .bg-beige</div>
  <div class="box bg-lightyellow mr5">.box .bg-lightyellow</div>
  <div class="box bg-purple mr5">.box .bg-purple</div>
  <div class="box bg-red-200 mr5">.box .bg-red-200</div>
  <div class="box bg-green white mr5">.box .bg-green</div>
  <div class="box bg-black white mr5">.box .bg-black</div>
  <div class="box bg-blue white mr5">.box .bg-blue</div>
  <div class="box bg-blue-100">.box .bg-blue-100</div>
</div>

<h4><?= __('admin.icons'); ?>:</h4>
<div class="flex flex-wrap">
<?php foreach ($data['lists'] as $topic) : ?>
    <div class="center box mr5">
        <svg class="icons">
          <use xlink:href="/assets/svg/icons.svg#<?= $topic; ?>"></use>
        </svg>
        <div class="gray-600"><?= $topic; ?></div>
    </div>
<?php endforeach; ?>
</div>

</main>
<?= includeTemplate('/view/default/footer'); ?>