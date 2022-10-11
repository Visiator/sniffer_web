<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

ini_set('display_errors', '1');

date_default_timezone_set('UTC');

function get_dt()
{
  $ss = date("Y-m-d H:i"); 
  return $ss;
};

function get_ip($s) {
    $x = explode(":", $s);
    return $x[0];
}

$file = '';
if( isset($_GET['file'])) { $file = get_ip($_GET['file']); };
$new = '';
if( isset($_GET['new'])) { $new = get_ip($_GET['new']); };

rename($file, $new);
$rr = $file;

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
  parent.delfile_result('<? echo $rr; ?>');
};

//-->
</script>
file = <? echo $file; ?><br>
new = <? echo $new; ?><br>
result = <? echo $rr; ?>
</body>

