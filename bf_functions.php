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
        CURLOPT_AUTOREFERER    => true,			// Set referer on redirect
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