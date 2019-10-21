<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    $tcitems = dalfactoryCreateHomeCarousel();
    $tcarouselhtml = renderCarousel($tcitems, "img");
    $tarticles = xmlLoadAll("data/xml/PS4-Desc.xml", "PLNHomeArticle", "Article");

    $tarticlehtml = "";
    foreach ($tarticles as $ta)
    {
        if (! empty($ta->article_href))
            $ta->Article = file_get_contents("data/html/{$ta->article_href}");
        $tarticlehtml .= renderUIHomeArticle($ta);
    }

    // Build the Articles

    $tcontent = <<<PAGE

                <div class="jumbotron">

<h1 class="lead">Welcome to home page</h1>
<p class="lead">My top 3 games and brief intro on Ps4</p>

</div>
            {$tcarouselhtml}
        <div class="row details">
                    {$tarticlehtml}
                    
        		</div>
		<div class="row">
			<div class="alert alert-dismissible alert-warning">
				<button class="close" type="button" data-dismiss="alert">&times;</button>
				<h4>Welcome!</h4>
				<p>This site is updated on a weekly basis. Make sure you check back
					regularly.</p>
			</div>
		</div>

PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();
appSessionLoginExists();

// Build up our Dynamic Content Items.
$tpagetitle = "Home Page";
$tpagelead = "";
$tpagecontent = createPage();
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
if (! empty($tpagecontent))
    $tpage->setDynamic2($tpagecontent);
//
if (! empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
// Return the Dynamic Page to the user.
$tpage->renderPage();
?>