<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createTop3GamesPage(Top3Games $pGame1)
{
    $tGame1html = renderTop3GamesOverview($pGame1);
    $tcontent = <<<PAGE
    {$tGame1html}
PAGE;
    return $tcontent;
}

function createRestGamesPage(RestGames $pGame2)
{
    $pGame2html = renderRestGamesOverview($pGame2);
    $tcontent = <<<PAGE
    {$pGame2html}
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tpagecontent = "";

$tid = $_REQUEST["id"] ?? - 1;
$tname = $_REQUEST["type"] ?? "";
$tvalid = false;

// Handle our Requests and Search for Players
if ($tname === "Top3Games")
{
    // Handle our Requests and Search for Players
    if (is_numeric($tid) && $tid > 0)
    {
        $tcoach = jsonLoadOneTop3Games($tid);
        $tpagecontent = createTop3GamesPage($tcoach);
        $tvalid = true;
    }
}
else 
    if ($tname === "RestGames")
    {
        // Handle our Requests and Search for Players
        if (is_numeric($tid) && $tid > 0)
        {
            $tcoach = jsonLoadOneRestGames($tid);
            $tpagecontent = createRestGamesPage($tcoach);
            $tvalid = true;
        }
    }

if (! $tvalid)
{
    header("Location: app_error.php");
    return;
}

// Build up our Dynamic Content Items.
$tpagetitle = "User Review";
$tpagelead = "";
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