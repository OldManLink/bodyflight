//Read a web page and check for errors:

$result = get_web_page( $url );

if ( $result['errno'] != 0 )
    ... error: bad url, timeout, redirect loop ...

if ( $result['http_code'] != 200 )
    ... error: no page, no permissions, no service ...

$page = $result['content'];