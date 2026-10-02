<?php
$html = file_get_contents('debug_manager.html');
$pos = strpos($html, 'function toggleMobileSidebar()');
$start = strrpos(substr($html, 0, $pos), '<script>') + strlen('<script>');
$end = strpos($html, '</script>', $pos);
$js = substr($html, $start, $end - $start);
file_put_contents('debug_inline.js', $js);
echo 'js bytes: ' . strlen($js) . PHP_EOL;
