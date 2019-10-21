<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    $tGame1 = jsonLoadAllTop3Games();
    $tGame2 = jsonLoadAllRestGames();

    $tGame1html = renderTop3GamesTable($tGame1);
    $tGame2html = renderRestGamesTable($tGame2);

    $tcontent = <<<PAGE
    <div class="jumbotron">
 
         <h1 class="lead">Top 3 Game page</h1>
         <p class="lead">This page shows all 10 games, but mainly focus on top 3 games and it will show the reviews for all game.</p>
 
     </div>

 <div class="panel panel-info">
        <div class="panel-heading">
        <h1 class="panel-title">My Top 3 Games</h1>
        </div>
                <div class="panel-body">
                {$tGame1html}
                </div>
        </div>

 <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Rest Of The Games</h3>
        </div>
        <div class="panel-body">
        {$tGame2html}
        </div>
    </div>
PAGE;

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

// Build up our Dynamic Content Items.
$tpagetitle = "Top 3 Games";
$tpagelead = "";
$tpagecontent = createPage();
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tpage = new MasterPage($tpagetitle);
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if (! empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
// Return the Dynamic Page to the user.
$tpage->renderPage();
?>