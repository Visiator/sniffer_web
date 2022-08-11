<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

ini_set('display_errors', '1');

date_default_timezone_set('UTC');

function get_ip($s) {
    $x = explode(":", $s);
    return $x[0];
}

$ip = '';
if( isset($_GET['ip'])) { $ip = get_ip($_GET['ip']); };

$idx = '';
if( isset($_GET['idx'])) { $idx = $_GET['idx']; };

require_once("geoip2.phar");
use GeoIp2\Database\Reader;
// City DB

$reader = new Reader('GeoLite2-City.mmdb');
try
{
    $record = $reader->city($ip);
    $r = $record->country->name . ' ' . $record->city->name;
} catch(GeoIp2\Exception\AddressNotFoundException $e) {
    $r = '';
}
//$r = geoip_isp_by_name($ip);




?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad=on_load() bgcolor=#ffffff style="margin:0;">

<script language="JavaScript" type="text/javascript">
<!--


function on_load()
{
  parent.geoip_result('<? echo $r; ?>','<? echo $idx; ?>');
};

//-->
</script>
geoip <?php  echo $r; ?>
</body>

