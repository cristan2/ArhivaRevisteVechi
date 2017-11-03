<?php
    $homeLink = ROOT . "/index.php";
?>

<div class = "home-link-container">
    <h2 class = "home-link">
        <?php
            if (isset($homeLink)) echo "<a href='$homeLink'>ARHIVA REVISTE VECHI</a>";
            else echo "ARHIVA REVISTE VECHI";
        ?>
    </h2>
    <h2 class = "preset-search" style = "position:absolute; top: 0; right: 0" >
        <a href = "<?php echo ARHIVA . "/search.php" ?> ">SEARCH</a>
    </h2>
</div>