<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8">
    <link rel="stylesheet" href="<?php echo CSSLIB.'/articole_dual_div.css'; ?>" >
    <link href="https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono|Roboto+Condensed" rel="stylesheet">
</head>

<body>
    <div class = "home-link-container">
        <h2 class = "home-link">
            <?php
                if (isset($homeLink)) echo "<a href='$homeLink'>ARHIVA REVISTE VECHI</a>";
                else echo "ARHIVA REVISTE VECHI";
            ?>
        </h2>
    </div>
    <div class = "header-nav-container">
        <div class = "header-nav-elem header-nav-prev">
            <h2><?php echo "<a href='$navLinkPrev'>Prev</a>"; ?></h2>
        </div>
        <div class = "header-nav-current">
            <h1><?php echo $titluEditieCurenta?></h1>
            <h2><?php echo $lunaEditieCurenta?></h2>
        </div>
        <div class = "header-nav-elem header-nav-next">
            <h2><?php echo "<a href='$navLinkNext'>Next</a>"; ?></h2>
        </div>
    </div>

    <div class = "column-container">
        <div class="col cuprins">
            <?php echo $articoleCardRows ?>
        </div>
        <div class="col main-img-container">
            <div class = "main-img-nav"><p>Aici butoane nav pentru imagini</p></div>
            <div class = "main-img">
                <?php
                    echo "<a href='$paginaCurentaImagePath'><img
                              src='$paginaCurentaImagePath'
                              class = 'fullthumb' alt='Image' /></a>";
                ?>
            </div>
        </div>
    </div>
</body>
</html>
