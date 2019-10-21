<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function renderBreadCrumb()
{
    $tbread = <<<BREAD
    <ul class="breadcrumb">
    <li><a href="index.php">Home</a></li>
    <li class="active">Squad</li>
    </ul>
BREAD;
    return $tbread;
}

function createPage($pimgdir, $pcurrpage, $psortmode, $psortorder)
{
    $tGame = new Game_List();
    $tGame->FavGame1 = 1;
    $tGame->FavGame2 = 2;
    $tGame->FavGame3 = 3;
    $tGame->GameName = "List of top 10 games";
    $tGame->GameList = jsonLoadAllGameList();

    $tsortfunc = "";
    if ($psortmode == "Rank_no")
        $tsortfunc = "Ranksortbyno";
    else 
        if ($psortmode == "name")
            $tsortfunc = "Ranksortbyname";

    if (! empty($tsortfunc))
        usort($tGame->GameList, $tsortfunc);

    // The pagination working out how many elements we need and
    $tnoitems = sizeof($tGame->GameList);
    $tperpage = 5;
    $tnopages = ceil($tnoitems / $tperpage);

    // Create a Pagniated Array based on the number of items and what page we want.
    $tfilterGame = appPaginateArray($tGame->GameList, $pcurrpage, $tperpage);
    $tGame->GameList = $tfilterGame;

    // Render the HTML for our Table and our Pagination Controls
    $tGameTable = renderGamelistTable($tGame);
    $tpagination = renderPagination($_SERVER['PHP_SELF'], $tnopages, $pcurrpage);

    $tcontent = <<<PAGE
        <div class="jumbotron">
            <h1 class="lead">Welcome to Ranking Page</h1>
            <p class="lead">My top 10 games in the list</p>
        </div>
                    <div class="row">
                			<div class="panel panel-primary">
                    			<div class="panel-body">
                    				<h2>Ranking Table</h2>
                    				<p>{$tGame->GameName}</p>
                        				<div id="squad-table">
                        			    {$tGameTable}
                                        {$tpagination}
                        		        </div>
                    		    </div>
                			</div>
            		</div>
		
PAGE;

    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();
$tcurrpage = $_REQUEST["page"] ?? 1;
$tcurrpage = is_numeric($tcurrpage) ? $tcurrpage : 1;
$tsortmode = $_REQUEST["sortmode"] ?? "";
$tsortorder = $_REQUEST["sortorder"] ?? "asc";
$tpagetitle = "Ranking";
$tpage = new MasterPage($tpagetitle);
$timgdir = $tpage->getPage()->getDirImages();

// Build up our Dynamic Content Items.
$tpagelead = "";

$tpagecontent = createPage($timgdir, $tcurrpage, $tsortmode, $tsortorder);
$tpagefooter = "";

// ----BUILD OUR HTML PAGE----------------------------
// Set the Three Dynamic Areas (1 and 3 have defaults)
if (! empty($tpagelead))
    $tpage->setDynamic1($tpagelead);
$tpage->setDynamic2($tpagecontent);
if (! empty($tpagefooter))
    $tpage->setDynamic3($tpagefooter);
// Return the Dynamic Page to the user.
$tpage->renderPage();
?>