<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// -----FORM VALIDATION---------------------------------
function processForm(array $pformdata): array
{
    foreach ($pformdata as $tfield => $tvalue)
    {
        $pformdata[$tfield] = appFormProcessData($tvalue);
    }
    $tvalid = true;
    if ($tvalid && empty($pformdata["inputEmail"]))
    {
        $tvalid = false;
        $pformdata["err-inputEmail"] = "<p id=\"help-inputEmail\" class=\"help-block\">Email required</p>";
    }
    if ($tvalid && empty($pformdata["inputPassword"]))
    {
        $tvalid = false;
    }
    if ($tvalid)
    {
        appFormSetValid($pformdata);
    }
    return $pformdata;
}

// ----PAGE GENERATION LOGIC---------------------------
function createResponse($pmethod, $paction, array $pformdata)
{
    appFormNullAsEmpty($pformdata, "inputEmail");
    appFormNullAsEmpty($pformdata, "inputPassword");
    $tmethod = appFormMethod();
    $taction = appFormActionSelf();

    $tresponse = <<<RESPONSE
		<section class="panel panel-primary" id="Form Response">
				<div class="jumbotron">
					you have succesfully logged in {$pformdata["inputEmail"]}.
                    You can now select your favourite game and write review on it.
				</div>
		</section>

<form class="form-horizontal" method="{$tmethod}" action="{$taction}">
	<fieldset>
		<!-- Form Name -->
		<legend>Enter your favourite game</legend>

		<!-- Text input-->
		<div class="form-group">
			<label class="col-md-4 control-label" for="Rank_no">Rank number</label>
			<div class="col-md-4">
				<input id="Rank_no" name="Rank_no" type="text" placeholder=""
					class="form-control input-md" required=""> <span class="help-block">Enter
					the rank Number</span>
			</div>
		</div>

        <!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="Title">Game Title</label>
			<div class="col-md-4">
				<select id="pos" name="Title" class="form-control">
					<option value="Infamous">Infamous</option>
					<option value="Battlefield 5">Battlefield 5</option>
					<option value="Titanfall 2">Titanfall 2</option>
					<option value="Fortnite">Fortnite</option>
                    <option value="Bloodborne">Bloodborne</option>
					<option value="Overwatch">Overwatch</option>
                    <option value="Spider-Man">Spider-Man</option>
                    <option value="Pubg">Pubg</option>
                    <option value="Darksouls 3">Darksouls 3</option>
                    <option value="Uncharted">Uncharted</option>
				</select>
                <span class="help-block">Select Your Game</span>
			</div>
		</div>

		<!-- Select Basic -->
		<div class="form-group">
			<label class="col-md-4 control-label" for="Score">Rating</label>
			<div class="col-md-4">
				<select id="Score" name="Score" class="form-control">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
				</select>
                <span class="help-block">Select the score you want to give to it</span>
			</div>
		</div>

		

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="form-sub">Submit Form</label>
  <div class="col-md-4">
    <button id="form-sub" name="form-sub" type="submit" class="btn btn-danger">Add Fav Game</button>
  </div>
</div>
	</fieldset>
</form>


<!-- Review -->
<div>
<form action = "" method = "POST">
Review: <textarea rows = "10" cols = "30" name = "commentContent"></textarea><br/>
Game name: <input type = "text" name = "name"><br/>
<input type = "submit" value = "post!"><br/>
</form>
</div>

RESPONSE;
    return $tresponse;
}

// ----BUSINESS LOGIC---------------------------------
$taction = appFormActionSelf();
$tmethod = appFormMethod();
$tformdata = processForm($_REQUEST) ?? array();
// var_dump($tformdata);

if ($_POST)
{
    $Gamename = $_POST['name'];
    $Review = $_POST['commentContent'];
    $read = fopen("data/html/Store-Review.part.html", "a");
    fwrite($read, "<b>" . $Gamename . "</b>:<br/>" . $Review . "<br/>");
    fclose($read);
}
include "data/html/Store-Review.part.html";

if (appFormMethodIsPost())
{

    // Map the Form Data

    $tGame = new Game_Info();
    $tGame->Rank_no = $_REQUEST["sno"] ?? "";
    $tGame->Title = $_REQUEST["tit"] ?? "";
    $tGame->Score = $_REQUEST["sc"] ?? "";
    $tvalid = true;
    // -----------------------------

    if ($tvalid)
    {
        $tid = jsonNextGameID();
        $tGame->id = $tid;

        $tsavedata = json_encode($tGame) . PHP_EOL;
        $tfilecontent = file_get_contents("data/json/Ranking.json");
        $tfilecontent .= $tsavedata;

        file_put_contents("data/json/Ranking.json", $tfilecontent);
        $tpagecontent = "<h1>Player with id = {$tGame->id} has been saved</h1>";
    }
    else
    {
        $tdest = appFormActionSelf();
        $tpagecontent = <<<ERROR
                         <div class="well">
                            <h1>Form was Invalid</h1>
                            <a class="btn btn-warning" href="{$tdest}">Try Again</a>
                         </div>
ERROR;
    }
}
else
{
    // This page will be created by default.
    $tpagecontent = createResponse($tmethod, $taction, $tformdata);
}

if (appFormCheckValid($tformdata))
{
    $tfileread = file("data/txt/User.txt");

    $c = 0;
    for ($i = 0; $i < sizeof($tfileread) / 8; $i ++)
    {
        $u = trim($tfileread[$c]);
        $c = $c + 8;
        $u == $tformdata["inputEmail"];
    }
}

// $tpagecontent = createResponse($tformdata);
else
{
    $tpagecontent = createResponse($tmethod, $taction, $tformdata);
}

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Data Entry");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();

?>