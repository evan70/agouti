<main class="col-span-12 mb-col-12 bg-white pt5 pr15 pb5 pl15 create">
  <?= breadcrumb('/', lang('home'), null, null, lang('add post')); ?>

    <form action="/post/create" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <?= includeTemplate('/_block/form/field-input', [
        'data' => [
          ['title' => lang('heading'), 'type' => 'text', 'name' => 'post_title', 'value' => null, 'min' => 6, 'max' => 250, 'id' => 'title', 'help' => '6 - 250 ' . lang('characters'), 'red' => 'red']
        ],
      ]); ?>

      <?= includeTemplate('/_block/form/select-topic-post', ['uid' => $uid,'data' => $data, 'action' => 'add', 'title' => lang('topics'), 'help' => lang('necessarily'), 'red' => 'red']); ?>

      <?php if ($uid['user_trust_level'] >= Config::get('trust-levels.tl_add_url')) { ?>
        <div class="boxline">
          <label class="block" for="post_title">URL</label>
          <input id="link" class="form-input" type="text" name="post_url" />
          <input id="graburl" readonly="readonly" class="right center mt15 mb15" type="submit_url" name="submit_url" value="<?= lang('to extract'); ?>" />
          <br>
        </div>
      <?php } ?>

      <div class="boxline post">
        <div class="boxline">
          <div class="input-images"></div>
        </div>
        <div class="size-14 gray-light-2"><?= lang('format-cover-post'); ?>.</div>
      </div>

      <?= includeTemplate('/_block/editor/post-editor', ['post_id' => null, 'type' => 'post']); ?>

      <?= includeTemplate('/_block/form/field-radio', [
        'data' => [
          ['title' => lang('is this a draft?'), 'name' => 'post_draft', 'checked' => 0]
        ],
      ]); ?>

      <?php if ($uid['user_trust_level'] > 0) { ?>
        <?= includeTemplate('/_block/form/select-content-tl', ['uid' => $uid, 'data' => null]); ?>
        <?= includeTemplate('/_block/form/field-radio', ['data' => [
          ['title' => lang('format Q&A?'), 'name' => 'post_type', 'checked' => 0],
          ['title' => lang('to close?'), 'name' => 'closed', 'checked' => 0],
        ]]); ?>
      <?php } ?>

      <?= includeTemplate('/_block/form/field-radio', ['data' => [
        ['title' => lang('is this a translation?'), 'name' => 'translation', 'checked' => 0],
      ]]); ?>

      <?php if ($uid['user_trust_level'] > 2) { ?>
        <?= includeTemplate('/_block/form/field-radio', ['data' => [
          ['title' => lang('raise?'), 'name' => 'top', 'checked' => 0],
        ]]); ?>
      <?php } ?>

      <?= includeTemplate('/_block/form/select-content', ['type' => 'post', 'data' => $data, 'action' => 'add', 'title' => lang('related')]); ?>

      <div class="boxline">
        <input type="submit" class="button white br-rd-5" name="submit" value="<?= lang('create'); ?>" />
      </div>
    </form>
  <!--?php if ($data['topics']) {  
     includeTemplate('/_block/no-content', ['lang' => 'no-topic-to-add']); ?>
  ? -->
</main>