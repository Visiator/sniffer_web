<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

$auto_ban_spur_nic = "Xvpn";


date_default_timezone_set('UTC');

function get_ip($s) {
    $x = explode(":", $s);
    return $x[0];
}

$id = '';
if( isset($_GET['id'])) { $id = $_GET['id']; };

$ee = explode("_", $id);
$id = $ee[0].' - '.$ee[1];


$tt = '<table border=1 cellpadding=5 cellspacing=0>'."\r\n"."<tbody>";

$i = 0;

$dir = "sess/TCP ".$id;

if(file_exists($dir) == false) {
    $dir = "sess/UDP ".$id;    
}

$f = scandir($dir);
foreach ($f as $file)
{
    if($file != '.' && $file != '..')
    {

        $tt .= '<tr>'.
                '<td>/var/www/html/sniffer_web/'.$dir.'/'.$file.'</td>'.
                '</tr>'."\r\n";
        $i++;
    };

  
}

$tt .= "</tbody>".'</table>'."\r\n";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad=on_load() bgcolor=#ffffff>
<style type="text/css">
body
{
	border: 0px solid #777777;
        margin: 3;
	font-size: 12px;
	font-family : "DejaVu Sans";
}

input 
{
	background-color: #ffffff;
	font-size: 12px;
	font-family : "DejaVu Sans";
	border: 1px solid #7777ff;
}

input.check_box
{
  width: 15px;
  height: 15px;
  display: block;
  clear: left;
  float: left;
  font-size: 12px;
  font-family : "DejaVu Sans";
}

:root {
  --main-a-color: #086FA1;
}

a:visited {font-family : "DejaVu Sans"; font-size: 12px; font-weight: none; color: var(--main-a-color); text-decoration: none}
a:active  {font-family : "DejaVu Sans"; font-size: 12px; font-weight: none; color: var(--main-a-color); text-decoration: none}
a:link    {font-family : "DejaVu Sans"; font-size: 12px; font-weight: none; color: var(--main-a-color); text-decoration: none}
a:hover   {font-family : "DejaVu Sans"; font-size: 12px; font-weight: none; color: #ffaaaa; text-decoration: none}

td
{
	font-size: 12px;
	font-family : "DejaVu Sans";
}

td.ipFirewall
{
	font-size: 12px;
	font-family : "DejaVu Sans";
        background-color: red;
        color: #ffffff;
}

</style> 

<script language="JavaScript" type="text/javascript">
<!--

function on_load() {
    s = '<?php echo $id; ?>';
};

//-->
</script>
<?php

?>
    <? echo $tt; ?>
</body>

