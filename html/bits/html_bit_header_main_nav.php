<div class = "header-nav-container">
    <div class = "header-nav-elem header-nav-prev">
        <h2><?php
//            if ($editiaCurenta->isFirst) echo "---";  // TODO implement isFirst
            if ($editieId <= 1) echo "---";
            else echo "<a href='$prevEditieLink'>Prev</a>"; ?>
        </h2>
    </div>
    <div class = "header-nav-current">
        <h1><?php echo $editiaCurenta->outputTitluDetaliat()?></h1>
        <h2><?php echo $editiaCurenta->outputInfoEditie()?></h2>
    </div>
    <div class = "header-nav-elem header-nav-next">
        <h2><?php
//            if ($editiaCurenta->isLast) echo "---";   // TODO implement isLast
            if (false) echo "---";
            else echo "<a href='$nextEditieLink'>Next</a>"; ?>
        </h2>
    </div>
</div>