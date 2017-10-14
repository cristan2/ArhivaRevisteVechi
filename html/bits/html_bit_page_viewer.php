<div class = "main-img-nav"><?php echo $thumbsArticolCurent ?></div>
<div class = "main-img">
    <?php
        if (isset($paginaCurentaImagePath)) {
            echo "<a href='$paginaCurentaImagePath'><img
                                  src='$paginaCurentaImagePath'
                                  class = 'fullthumb' alt='Image' /></a>";
        } elseif(isset($paginiThumbsContent)) {
            echo $paginiThumbsContent;
        } else {
            echo "Nu există încă pagini";
        }
    ?>
</div>