<?php
// ----INCLUDE APIS------------------------------------
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage($pInfoid)
{
    $tcontent = // Get the Data we need for this page
    $tPS4Info = jsonLoadOneAdditional($pInfoid);
    $tPS4Info->report = file_get_contents("data/html/{$tPS4Info->report_href}");
    // Build the UI Components
    $tPS4Infohtml = renderAdditional($tPS4Info, "PS4");

    // Construct the Page
    $tcontent = <<<PAGE
<div class="jumbotron">
 <h1 class="lead">PS4 Additional Information page</h1>
    <p class="lead">Click on the button to view more below</p>
    
    </div>
    <section class="row details" id="fixture-details">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Additional Information</h3>
        </div>
        <div class="panel-body">
            {$tPS4Infohtml}
        </div>
    </div>
PAGE;
    return $tcontent;
}

function createAdditionalPage()
{

    // Get the Data we need for this page
    $tInfoarray = jsonLoadAllInformation();
    $tInfohtml = "";
    $tInfohtml .= renderAdditionalSummary($tInfoarray);

    // Construct the Page
    $tcontent = <<<PAGE
<div class="jumbotron">
 <h1 class="lead">PS4 Additional Information page</h1>
    <p class="lead">Click on the button to view more below</p>
    
    </div>
    <article id="fixtures">
        {$tInfohtml}
    </article>
PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
// Start up a PHP Session for this user.
session_start();

$tpagecontent = "";

$tid = $_REQUEST["fixid"] ?? - 1;

// Handle our Requests and Search for Players
if (is_numeric($tid) && $tid > 0)
{
    $tpagecontent = createPage($tid);
}
else
{
    $tpagecontent = createAdditionalPage();
}

// Build up our Dynamic Content Items.
$tpagetitle = "Additional";
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