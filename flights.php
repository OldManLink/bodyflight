<?php
require_once("bf_functions.php");
require_once("classes/Repository.php");

/**
 * Read the latest flights from the local database and return them
 */

$pFlightCount = isset($_GET['latest']) ? trim($_GET['latest']) : 16;

printDebug("latest = ".$pFlightCount);
printDebug("...end debugging output -->");

$tRepository = new Repository();
$tLatestFlights = $tRepository->getLatestFlights($pFlightCount);

print(json_encode($tLatestFlights));
?>