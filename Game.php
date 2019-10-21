<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pGames)
{

    // Build the UI Components

    // $tfixturehtml = renderLastFixture();
    $tGameprofile = "";
    foreach ($pGames as $tp)
    {
        $tGameprofile .= renderGamelistOverview($tp);
    }
    $tcontent = <<<PAGE
       <div class="jumbotron">
    
    <h1 class="lead">Welcome to Game Profile</h1>
    <p class="lead">Game important information and link to my review</p>
    
    </div>
     <section class="row details" id="club-info">
{$tGameprofile}
    
    </section>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

// Use our Data Access Layer to create our Squad

$tGame = [];
$tname = $_REQUEST["name"] ?? "";
$tRankno = $_REQUEST["Rank_no"] ?? - 1;
$tid = $_REQUEST["id"] ?? - 1;

// Handle our Requests and Search for Players using different methods
if (is_numeric($tid) && $tid > 0)
{
    $tGame1 = jsonLoadOneGameList($tid);
    $tGame[] = $tGame1;
}
else 
    if (! empty($tname))
    {
        // Filter the name
        $tname = appFormProcessData($tname);
        $tGamelist = jsonLoadAllGameList();
        foreach ($tGamelist as $tp)
        {
            if (strtolower($tp->Title) === strtolower($tname))
            {
                $tGame[] = $tp;
            }
        }
    }
    else 
        if ($tRankno > 0)
        {
            $tGamelist = jsonLoadAllGameList();
            foreach ($tGamelist as $tp)
            {
                if ($tp->Rank_no === $tRankno)
                {
                    $tGame[] = $tp;
                    break;
                }
            }
        }

// Page Decision Logic - Have we found a player?
// Doesn't matter the route of finding them
if (count($tGame) === 0)
{
    appGoToError();
}
else
{
    // We've found our player
    $tpagecontent = createPage($tGame);
    $tpagetitle = "Game info Page";

    // ----BUILD OUR HTML PAGE----------------------------
    // Create an instance of our Page class
    $tpage = new MasterPage($tpagetitle);
    $tpage->setDynamic2($tpagecontent);
    $tpage->renderPage();
}
?>