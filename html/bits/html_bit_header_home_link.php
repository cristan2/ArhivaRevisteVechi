<?php
    $homeLink = ROOT . "/index.php";
    $siteName = SITE_NAME;
    $siteTag  = SITE_TAG;
?>

<div class = "home-link-container">
    <?php
        echo "<a class = 'home-link' href='$homeLink'>"; ?>
            <div>
                <h2 class = 'home-link-logo'>
                    <?php
                        if (isset($siteName)) echo $siteName;
                        else echo "ARHIVA REVISTE VECHI";
                    ?>
                </h2>
                <p class = "home-link-tag">
                    <?php
                        if (isset($siteTag)) echo $siteTag;
                    ?>
                </p>
            </div>
    <?php echo "</a>"?>

    <h2 class = "home-nav-search"><?php if (empty($suppresMainHeaderSearch)) echo buildHtmlSimpleSearch(); ?></h2>
    <h2 class = "home-nav-preset-search" >
        <!--a href = "<?php echo ARHIVA . "/search.php" ?> ">Preset Search (future Quick Nav)</a-->
        <a href = ' <?php echo HTMLSTATIC . "/about.php" ?>'>Despre</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href = '<?php echo HTMLSTATIC . "/changelog.php" ?>'>Updates</a>
    </h2>
</div>

<?php
function buildHtmlSimpleSearch()
{
    $searchPage = ARHIVA . "/search.php";
    $searchHint = SEARCH_HINT;
    return <<<START_HTML
	<form action = "$searchPage">
		 <input type = "text" class = "home-nav-search-box" name = "filter" placeholder = "$searchHint"/>
         <input type = "submit" value = "Cauta" id="search-btn" />
      </form>

START_HTML;

}
?>
