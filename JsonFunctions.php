<?php
require_once ("api/api.inc.php");

function jsonCreateRankingFormat($pfile)
{
    $tGame = new Game_Information();
    $tGame->id = 1;
    $tGame->Rank_no = "";
    $tGame->Title = "";
    $tGame->Infamous = "";
    $tGame->Release_Date = "";
    $tGame->Genre = "";
    $tGame->developer = "";
    $tGame->publisher = "";
    $tGame->Score = "";
    $tGame->role = "";
    $tdata = json_encode($tGame) . PHP_EOL;
    file_put_contents($pfile, $tdata);
    return $tdata;
}

function jsonCreateGame1Format($pfile)
{
    $tGame1 = new GameNo1();
    $tGame1->id = 1;
    $tGame1->name = "";
    $tGame1->capacity = 0;
    $tGame1->desc = "";
    $tGame1->desc_href = "Game1.part.html";
    $tGame1->addr = "";
    $tGame1->long = 0.0;
    $tGame1->lat = 0.0;
    $tGame1->imgdir = "def";
    $tdata = json_encode($tGame1) . PHP_EOL;
    file_put_contents($pfile, $tdata);
    return $tdata;
}

?>