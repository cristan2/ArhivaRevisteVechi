<div class = "main-img-nav">
    <?php echo $outputMicroThumbsNav ?>
</div>
<div class = "main-img">
    <?php
        if (isset($paginaCurentaHugeThumb)) {
            echo $paginaCurentaHugeThumb;
        } elseif(isset($paginiThumbsContent)) {
            echo $paginiThumbsContent;
        } else {
            echo "Nu există încă pagini";
        }
    ?>
</div>