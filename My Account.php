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
function createPage($pmethod, $paction, array $pform)
{
    appFormNullAsEmpty($pform, "inputEmail");
    appFormNullAsEmpty($pform, "inputPassword");

    $tcontent = <<<PAGE
<div class="jumbotron">
 <h1 class="lead">My Account page</h1>
    <p class="lead">Sign in or Sign up</p>
        </div>
<form class="form-horizontal" method="{$pmethod}" action="{$paction}">
		    <div class="form-group">
		        <label for="inputEmail" class="control-label col-xs-3">Email</label>
		        <div class="col-xs-9">
		            <input type="email" class="form-control" id="inputEmail" name="inputEmail"
                      placeholder="Email" value="{$pform["inputEmail"]}">
		        </div>
		    </div>
		    <div class="form-group">
		        <label for="inputPassword" class="control-label col-xs-3">Password</label>
		        <div class="col-xs-9">
		            <input type="password" class="form-control" id="inputPassword"
                     name="inputPassword" placeholder="Password" value="{$pform["inputPassword"]}">
		        </div>
		
     <div class="form-group">
		        <div class="col-xs-offset-3 col-xs-9">
		            <input type="submit" class="btn btn-primary" value="Log in">
		        </div>
                <div class="col-xs-offset-3 col-xs-9">
                    \n
                <p>\n Or </o>
            </div>

            <div class="form-group">
		        <div class="col-xs-offset-3 col-xs-9">
		            <a href="Sign_up.php" class="btn btn-primary">Sign Up</a>
		        </div>
		    </div>
		</form>
PAGE;
    return $tcontent;
}

function createErrorPage($pmethod, $paction, array $pform)
{
    appFormNullAsEmpty($pform, "inputEmail");
    appFormNullAsEmpty($pform, "inputPassword");

    $tcontent = <<<PAGE
<div class="jumbotron">
 <h1 class="lead">My Account page</h1>
    <p class="lead">Sign in or Sign up</p>
        </div>
 
<form class="form-horizontal" method="{$pmethod}" action="{$paction}">
 <div class="form-group">
		        <label for="inputEmail" class="control-label col-xs-3"></label>
		        <div class="col-xs-9"> 

					<p class="lead">Please try again</p> 
		        </div>
		    </div>
		    <div class="form-group">
		        <label for="inputEmail" class="control-label col-xs-3">Email</label>
		        <div class="col-xs-9">
		            <input type="email" class="form-control" id="inputEmail" name="inputEmail"
                      placeholder="Email" value="{$pform["inputEmail"]}">
		        </div>
		    </div>
		    <div class="form-group">
		        <label for="inputPassword" class="control-label col-xs-3">Password</label>
		        <div class="col-xs-9">
		            <input type="password" class="form-control" id="inputPassword"
                     name="inputPassword" placeholder="Password" value="{$pform["inputPassword"]}">
		        </div>
		        
     <div class="form-group">
		        <div class="col-xs-offset-3 col-xs-9">
		            <input type="submit" class="btn btn-primary" value="Log in">
		        </div>
                <div class="col-xs-offset-3 col-xs-9">
                    \n
                <p>\n Or </o>
            </div>
            
            <div class="form-group">
		        <div class="col-xs-offset-3 col-xs-9">
		            <a href="Sign_up.php" class="btn btn-primary">Sign Up</a>
		        </div>
		    </div>
		</form>
PAGE;
    return $tcontent;
}

function createResponse(array $pformdata)
{
    $tresponse = <<<RESPONSE
		<section class="panel panel-primary" id="Form Response">
				<div class="jumbotron">
					
				</div>
		</section>
RESPONSE;
    return $tresponse;
}

// ----BUSINESS LOGIC---------------------------------
$taction = appFormActionSelf();
$tmethod = appFormMethod();
$tformdata = processForm($_REQUEST) ?? array();
// var_dump($tformdata);

if (appFormCheckValid($tformdata))
{
    $tfileread = file("data/txt/User.txt");

    $c = 0;
    for ($i = 0; $i < sizeof($tfileread) / 8; $i ++)
    {
        $u = trim($tfileread[$c]);
        $p = trim($tfileread[$c + 1]);
        $c = $c + 8;
        if ($u == $tformdata["inputEmail"] && $p == $tformdata["inputPassword"])
        {

            print("Success");
            header("Location: FavGame.php");
        }
        else
        {

            $tpagecontent = createErrorPage($tmethod, $taction, $tformdata);
        }
    }

    // $tpagecontent = createResponse($tformdata);
}
else
{
    $tpagecontent = createPage($tmethod, $taction, $tformdata);
}

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Data Entry");
$tindexpage->setDynamic2($tpagecontent);
$tindexpage->renderPage();

?>