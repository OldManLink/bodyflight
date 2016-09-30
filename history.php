





<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">



<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Insert title here</title>
<style type="text/css">
body{
margin: 0;
padding: 0
}

.videoselector
{
	cursor: pointer;
}
</style>

<script src='/static/js/jquery-1.11.0.min.js'></script>
<script src='/static/js/json2.js'></script>
<script>

$(document).ready(function() {
	$('.videoselector').click(function(event) {
		event.preventDefault();
		var clip_name = $(this).text();
		var frame = document.getElementById('frame');
		frame.src = "iframe.jsp?name="+clip_name;
	});
	
	$('#show_more').click(function(event) {
		event.preventDefault();
		top.location.href = "/VideoServer/index.jsp?history="+(420 + 60)

	});
});
</script>

</head>
<body>
<table width="100%" style="border-spacing: 0px;">
<tr>
<td valign="top">
<iframe id="frame" width="768" height="432" src="iframe.jsp"></iframe>
<br>
<button>Ladda ner filmen</button>
<button id="show_more">Visa fler klipp</button>

</td>
<td>
<div style="height:99vh;border:0px solid #ccc;font:16px/26px Georgia, Garamond, Serif;overflow:auto;">
<?
/**
  * This is a test page designed to mimic the functionality of
  * Bodyflight's video server page, http://home.bodyflight.se/VideoServer/index.jsp?history=180
  * 1) if `history` is supplied, records from now minus the supplied number of minutes are generated at the rate of one
  *    every two minutes.
  * 2) if `history` is omitted, or zero, records from 2016-04-17 15:08:19 are generated at the rate of one every two
  *    minutes (this could take some time).
  */

include_once "bf_functions.php";

$pHistory = isset($_GET['history']) ? abs(intval(trim($_GET['history']))) : 0;
$tNow = strtotime(date("Y-m-d H:i:s"));

printDebug("history: '$pHistory', now: $tNow");
printDebug("...end debugging output -->");

if($pHistory > 0)
{
    $tFirst = $tNow - 60 * $pHistory;
    print("First clip: " . date("Y-m-d H:i:s", $tFirst) . "<br>\n");

} else
{
    $tFirst = 1460898499; // 2016-04-17 15:08:19
}

$tCurrent = $tFirst;
while($tCurrent + 120 < $tNow)
{
    print "\n<span class=\"videoselector\" id=\"name\">flight-". date("Y-m-d H_i_s", $tCurrent)."-camera1</span><br>\n";
    print "\n<span class=\"videoselector\" id=\"name\">flight-". date("Y-m-d H_i_s", $tCurrent)."-camera4</span><br>\n";
    print "\n<span class=\"videoselector\" id=\"name\">flight-". date("Y-m-d H_i_s", $tCurrent)."-camera5</span><br>\n";
    $tCurrent += 120;
}
?>

</div>
</td>
</tr>
</table>

</body>
</html>