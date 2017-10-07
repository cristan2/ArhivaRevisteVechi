<?php

$paginaCurentaNr = "1";
if (isset($_GET['pagina'])) $paginaCurentaNr = $_GET['pagina'];
$paginaCurentaImagePath = getImage($editiaCurenta, $paginaCurentaNr);