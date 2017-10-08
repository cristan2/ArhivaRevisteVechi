<div class = "header-nav-container">
    <div class = "header-nav-elem header-nav-prev">
        <h2><?php
            if ($editieId <= 1) echo "---";
            else echo "<a href='$navLinkPrev'>Prev</a>"; ?>
        </h2>
    </div>
    <div class = "header-nav-current">
        <h1><?php echo $titluEditieCurenta?></h1>
        <h2><?php echo $lunaEditieCurenta?></h2>
    </div>
    <div class = "header-nav-elem header-nav-next">
        <h2><?php echo "<a href='$navLinkNext'>Next</a>"; ?></h2>
    </div>
</div>