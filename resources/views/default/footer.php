<div class="clear"></div>
<footer class="footer mt15">
  <div class="wrap white">
    <div class="text-info no-mob">
      <h4 class="p-head two"><?= lang('Info'); ?></h4>
      <a class="footer white size-13" title="<?= lang('Spaces'); ?>" href="/spaces"><?= lang('Spaces'); ?></a>
      <a class="footer white size-13" title="<?= lang('Topics'); ?>" href="/topics"><?= lang('Topics'); ?></a>
      <a class="footer white size-13" title="<?= lang('Users'); ?>" href="/users"><?= lang('Users'); ?></a>
    </div>
    <div class="text-info no-mob">
      <h4 class="p-head three"><?= lang('Other'); ?></h4>
      <a class="footer white size-13" title="<?= lang('All answers'); ?>" href="/answers"><?= lang('Answers-n'); ?></a>
      <a class="footer white size-13" title="<?= lang('All comments'); ?>" href="/comments"><?= lang('Comments-n'); ?></a>
      <a class="footer white size-13" title="<?= lang('All domains'); ?>" href="/web"><?= lang('Domains'); ?></a>
    </div>
    <div class="text-info">
      <h4 class="p-head one"><?= lang('Help'); ?></h4>
      <a class="footer white size-13" title="<?= lang('Info'); ?>" href="/info"><?= lang('Info'); ?></a>
      <a class="footer white size-13 no-mob" title="<?= lang('Privacy'); ?>" href="/info/privacy"><?= lang('Privacy'); ?></a>
    </div>
    <div class="text-oth ots">
      <h4 class="p-head-n"><?= lang('Social networks'); ?></h4>
      <a rel="nofollow noopener" class="white" title="DISCORD" href="https://discord.gg/dw47aNx5nU">
        <i class="icon-wechat size-21 mr5"></i>
      </a>
      <a rel="nofollow noopener" class="white" title="GitHub" href="https://github.com/LoriUp/loriup">
        <i class="icon-github-circled size-21"></i>
      </a>

      <div class="size-13">
        Loriup &copy; <?= date('Y'); ?>
        <span class="no-mob">— <?= lang('community'); ?></span>
      </div>
    </div>
  </div>
</footer>

<script async src="/assets/js/common.js"></script>
<script src="/assets/js/layer/layer.js"></script>

<?php if ($uid['msg']) { ?>
  <?php foreach ($uid['msg'] as $message) { ?>
    <script nonce="<?= $_SERVER['nonce']; ?>">
      layer.msg('<?= $message[0]; ?>', {
        icon: <?= $message[1]; ?>,
        time: 3000,
        skin: 'layui-layer-molv',
      });
    </script>
  <?php } ?>
<?php } ?>

<?= getRequestResources()->getBottomStyles(); ?>
<?= getRequestResources()->getBottomScripts(); ?>

<a id="scroll_top" class="red" title="Наверх">&#8593;</a>
</body>

</html>