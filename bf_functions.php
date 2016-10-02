<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('CET');

// Set up tab stops: $t[0] = no tabs, $t[9] = 9 tabs.
unset($t);$t[0]="";for($i=1;$i<10;$i++)$t[$i]=$t[$i-1]."\t";

$gStoragePath = "storage/serverstate.php";
$gServerState = null;
$tGetArgsCount = count($_GET);
$pFlags = isset($_GET['flags']) ? trim($_GET['flags']) : 42;

$tPrintDebug = $pFlags == 73;
printDebug("<!-- debugging output...");

/**
  * Load the old server state, or create it if it didn't previously exist
  */
function loadServerState()
{
    global $gServerState, $gStoragePath;
    if(file_exists($gStoragePath))
    {
        $gServerState = unserialize(file_get_contents($gStoragePath));
    } else
    {
        $gServerState = array(
            "flights" => array(),
            "lastFlight" => 0
        );
        saveServerState();
    }
}

/**
  * Add the supplied array of timestamps to the server state
  */
function addTimestamps($timestamps)
{
    global $gServerState;
    $oldFlights = $gServerState['flights'];
    $lastFlight = $gServerState['lastFlight'];
    print("Previous last flight: ".date("Y-m-d H:i:s", $lastFlight)."\n");
    printf("Received %d flights\n", count($timestamps));
    foreach ($timestamps as $timestamp)
    {
        if($timestamp > $lastFlight)
        {
            $oldFlights[] = $timestamp;
            $lastFlight = $timestamp;
            print(date("Y-m-d H:i:s", $timestamp)." added\n");
        } else
        {
            print(date("Y-m-d H:i:s", $timestamp)." skipped\n");
        };
    };
    unset($timestamp); // break the reference with the last element
    $gServerState['lastFlight'] = $lastFlight;
    $gServerState['flights'] = $oldFlights;
}

/**
  * Save the current server state
  */
function saveServerState()
{
    global $gServerState, $gStoragePath;
    $gServerState['latestCheck'] = strtotime(date("Y-m-d H:i:s"));
    file_put_contents($gStoragePath, serialize($gServerState));
}

/**
  * If the global debug flag is set, print the supplied debug message, otherwise do nothing.
  */
function printDebug($message)
{
    global $tPrintDebug, $t;
    if ($tPrintDebug) print "$t[1]$message\n";
}

/**
  * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
  * array containing the HTTP server response header fields and content.
  */
function get_web_page( $url )
{
    $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

    $options = array(

        CURLOPT_CUSTOMREQUEST  => "GET",        // Set request type post or get
        CURLOPT_POST           => false,        // Set to GET
        CURLOPT_USERAGENT      => $user_agent,  // Set user agent
        CURLOPT_COOKIEFILE     => "cookie.txt", // Set cookie file
        CURLOPT_COOKIEJAR      => "cookie.txt", // Set cookie jar
        CURLOPT_RETURNTRANSFER => true,			// Return web page
        CURLOPT_HEADER         => false,		// Don't return headers
        CURLOPT_FOLLOWLOCATION => true,			// Follow redirects
        CURLOPT_ENCODING       => "",			// Handle all encodings
        CURLOPT_AUTOREFERER    => true,			// Set referrer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,			// Timeout on connect
        CURLOPT_TIMEOUT        => 120,			// Timeout on response
        CURLOPT_MAXREDIRS      => 10,			// Stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}
?>