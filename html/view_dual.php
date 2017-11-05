<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8">
    <title><?php echo SITE_NAME ?> </title>
    <link rel="stylesheet" href="<?php echo CSSLIB.'/stylesheet_basic.css'; ?>" >
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono|Roboto+Condensed" rel="stylesheet">
</head>

<body>
    <?php include HTMLBITS . "/html_bit_header_home_link.php"; ?>
    <?php include HTMLBITS . "/html_bit_header_main_nav.php"; ?>
    <div class = "column-container">
        <div class="col cuprins">
            <?php echo $articoleCardRows ?>
        </div>
        <div class="col main-img-container">
            <?php include HTMLBITS . "/html_bit_page_viewer.php"; ?>
        </div>
    </div>
</body>
</html>
