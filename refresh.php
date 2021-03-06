<?php
require_once("bf_functions.php");
require_once("classes/Repository.php");

/**
 * Read the latest flights from the Bodyflight history page and update the local database.
 */

//$url = "http://localhost/bodyflight/history.php";
//$url = "http://bodyflight.arcanel.se/history.php";
$url = "http://home.bodyflight.se/VideoServer/index.jsp";


$tRepository = new Repository();
$tLatest = $tRepository->getLatestCheck();
$tLastFlight = $tRepository->getLastFlight();

$tNow = strtotime(date("Y-m-d H:i:s"));
$refreshDelay = $tNow - $tLatest;

print("<pre>\n");
if ($refreshDelay >= 60 || $tLastFlight == 0)
{
    $tRepository->setLatestCheck($tNow);
    $pHistory = $tLastFlight > 0 ? "?history=".(round(($tNow - $tLastFlight)/60) + 2) : "";
    print("Local time: ".date("Y-m-d H:i:s", $tNow)."\n");
    print("Requesting ".$url.$pHistory."\n");
    $result = get_web_page( $url.$pHistory );

    if ( $result['errno'] != 0 )
    {
        die("Error loading history: errno=".$result['errno'].", errmsg='".$result['errmsg']."'");
    }

    if ( $result['http_code'] != 200 )
    {
        die("Problem loading history: http_code=".$result['http_code']);
    }

    $tPage = $result['content'];
    $tPattern = "/<span class=\"videoselector\" id=\"name\">flight-([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}_[0-9]{2}_[0-9]{2})-camera1<\/span><br>/";
    $tFlights = array();

    if (preg_match_all($tPattern, $tPage, $tMatches))
    {
        foreach($tMatches[1] as $key => $value)
        {
            $tFlights[$key] = strtotime(strtr($value, "_", ":"));
        };
        unset($value); // break the reference with the last element
    }
    $tRepository->addFlights($tFlights);
} else
{
    printf("Waiting %d seconds for next refresh\n", 60 - $refreshDelay);
};
print("</pre>\n");
?>