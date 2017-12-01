<div class = "header-nav-container">
    <div class = "header-nav-elem header-nav-prev">
        <h2><?php if (isset($prevEditieLink)) echo $prevEditieLink; ?></h2>
    </div>
    <div class = "header-nav-current">
        <h1><?php echo $editiaCurenta->outputTitluDetaliat(true)?></h1>
        <h2><?php echo $editiaCurenta->outputInfoEditie()?></h2>
        <p class = "external-links"><?php echo $editiaCurenta->outputLinkuriDownload();?></p>
    </div>
    <div class = "header-nav-elem header-nav-next">
        <h2><?php if (isset($nextEditieLink)) echo $nextEditieLink; ?></h2>
    </div>
</div>