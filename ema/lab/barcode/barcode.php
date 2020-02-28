<?php

$number = $_GET['number'];
$scale = 2;
$fontsize = 11;
header('Location: barcodegen/html/image.php?filetype=PNG&dpi=72&scale=' . $scale . '&rotation=0&font_family=Arial.ttf&font_size=' . $fontsize . '&text=' . $number . '&thickness=20&start=B&code=BCGcode128');

?>
