<?php if (UserData::checkAdmin()) { ?>
  <a class="gray-400 ml10" href="<?= getUrlByName('admin.logip', ['ip' => $ip]); ?>">
    <?= $ip; ?>
  </a>
<?php } ?>