<?php
// Variables: $title, $meta
$title = $title ?? 'Titre du régime';
$meta = $meta ?? 'Durée • Niveau';
$cta = $cta ?? 'Voir';
?>
<article class="regime-card card">
  <div class="media">
    <?= view('components/image_placeholder', ['size'=>'medium']) ?>
  </div>
  <div class="body">
    <h3 class="title"><?= esc($title) ?></h3>
    <div class="meta"><?= esc($meta) ?></div>
    <div class="cta"><a class="btn btn--primary" href="#"><?= esc($cta) ?></a></div>
  </div>
</article>
