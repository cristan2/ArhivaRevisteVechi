<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8">
    <title><?php echo (!empty($currentPageTitle) ? $currentPageTitle . " &bull; " : "") . SITE_NAME ?></title>
    <link rel="stylesheet" href="<?php echo CSSLIB.'/fonts.css'; ?>" >
    <link rel="stylesheet" href="<?php echo CSSLIB.'/stylesheet_basic.css'; ?>" >
</head>

<body>
    <header>
        <?php include HTMLBITS . "/html_bit_header_home_link.php"; ?>
        <?php include HTMLBITS . "/html_bit_header_main_nav.php"; ?>
    </header>
    <div class = "column-container">
        <!--div-- class="col cuprins"-->
            <?php echo $articoleCardRows ?>
        <!--/div-->
        <!--div class = 'col articol-card-container'>...</div-->
        <div class="col main-img-container">
            <?php include HTMLBITS . "/html_bit_page_viewer.php"; ?>
        </div>
    </div>

    <!--footer>
        <?php /*include HTMLBITS . "/html_bit_footer.php";*/ ?>
    </footer-->
    <?php include HTMLBITS . "/html_bit_analytics.php" ?>
</body>
</html>
