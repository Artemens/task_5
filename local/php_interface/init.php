<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/local/modules/dev.site/include.php';
AddEventHandler('Iblock', 'OnAfterIBlockElementAdd', Array("Iblock", "addLog"));
AddEventHandler('Iblock', 'OnAfterIBlockElementUpdate', Array("Iblock", "addLog"));

$agentName = "MyAgent";
$agentFunction = "\\Only\\Site\\Agents\\Iblock::clearOldLogs();";
$agentInterval = 3600;
$agentParams = array(
    "MODULE_ID" => "dev.site",
    "NAME" => $agentName,
    "AGENT_INTERVAL" => $agentInterval,
    "IS_PERIOD" => "Y",
    "NEXT_EXEC" => ConvertTimeStamp(time() + $agentInterval, "FULL"),
    "ACTIVE" => "Y",
    "SORT" => 100,
);

CAgent::AddAgent(
    $agentFunction,
    $agentParams["MODULE_ID"],
    $agentParams["ACTIVE"],
    $agentParams["AGENT_INTERVAL"],
    "",
    $agentParams["IS_PERIOD"],
    $agentParams["NEXT_EXEC"],
    $agentParams["SORT"],

);
?>