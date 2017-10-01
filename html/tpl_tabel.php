<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono|Roboto+Condensed" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo CSSLIB.'/editii_cards.css'; ?>" >
</head>

<body>
    <?php include TEMPL . "/tpl_header_home_link.php"; ?>
    <div class = "card-container">
        <?php echo isset($revisteCards) ? $revisteCards : "" ?>
        <?php echo isset($content) ? $content : "" ?>
    </div>
</body>
</html>