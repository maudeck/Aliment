<?php
// Usage: echo view('components/image_placeholder', ['size'=>'medium']);
$size = $size ?? 'medium';
?>
<div class="img-placeholder <?= esc($size) ?>" role="img" aria-label="Image Placeholder">
  <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:rgba(11,11,11,0.18)">
    <rect x="3" y="3" width="18" height="14" rx="2"></rect>
    <path d="M3 17l5-5 4 4 5-7 3 5"></path>
  </svg>
  <div style="position:absolute;opacity:0.6;font-size:12px">Image_Placeholder</div>
</div>