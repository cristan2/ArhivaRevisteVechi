<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono|Roboto+Condensed" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSSLIB.'/stylesheet_basic.css'; ?>" >
</head>

<body>
    <?php include HTMLBITS . "/html_bit_header_home_link.php"; ?><br>
    <?php include HTMLBITS . "/html_bit_search.php"; ?><br>
    <div class = "page-content">
        <?php echo isset($pageTitle)   ? "<h1>$pageTitle</h1>" : "" ?>
        <?php echo isset($pageContent) ? $pageContent : "" ?>
    </div>
</body>
</html>
