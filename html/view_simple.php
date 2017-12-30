<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8">
    <title><?php echo (!empty($currentPageTitle) ? $currentPageTitle . " &bull; " : "") . SITE_NAME ?></title>
    <link rel="stylesheet" href="<?php echo CSSLIB.'/fonts.css'; ?>" >
    <link rel="stylesheet" href="<?php echo CSSLIB.'/stylesheet_basic.css'; ?>" >
    <!--link rel="icon" type = "image/png" href="<?php /*echo HTMLLIB.'/favicon/reviste-vechi.png';*/ ?>" -->
</head>

<body>
    <header>
        <?php include HTMLBITS . "/html_bit_header_home_link.php"; ?>
        <?php include HTMLBITS . "/html_bit_search.php"; ?>
        <?php include HTMLBITS . "/html_bit_editii_header_filter_list.php"; ?>
    </header>
    <div class = "page-content">
        <?php echo isset($pageTitle)   ? "<h1>$pageTitle</h1>" : "" ?>
        <?php echo isset($pageContent) ? $pageContent : "" ?>
    </div>

    <!--footer>
        <?php /*include HTMLBITS . "/html_bit_footer.php";*/ ?>
    </footer-->

    <!-- statistics for mythweb.ro (de sters/modificat pentru alte domenii -->
    <script type='text/javascript' src='http://mythweb.ro/fstats/js/fs2.js.php?site_id=1'></script>
</body>
</html>
