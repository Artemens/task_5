<?php

use Bitrix\Main\EventManager;

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/dev.site/include.php')) {
    require_once $_SERVER['DOCUMENT_ROOT'].'/local/modules/dev.site/include.php';
}
$eventManager = EventManager::getInstance();
$eventManager->addEventHandler('Iblock', 'OnAfterIBlockElementAdd', ['Only\\Site\\Handlers\\Iblock', 'addLog']);

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