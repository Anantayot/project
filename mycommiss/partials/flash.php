<?php if (!empty($_SESSION['flash'])): ?>
  <?php foreach ($_SESSION['flash'] as $type => $msgs): ?>
    <?php foreach ($msgs as $msg): ?>
      <div class="alert alert-<?php echo htmlspecialchars($type); ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($msg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endforeach; ?>
  <?php endforeach; unset($_SESSION['flash']); ?>
<?php endif; ?>
