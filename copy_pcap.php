<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

ini_set('display_errors', '1');

date_default_timezone_set('UTC');

$home_dir = '/home/smorodin';


function get_dt()
{
  $ss = date("Y-m-d H:i"); 
  return $ss;
};





function s10_to_s16($v) {
    $v10 = (int)$v;
    $z = dechex($v10);
    if(strlen($z) == 1) $z = '0'.$z;
    return $z;
    
}

function ip_to_hex($ii) {
    $rr = '0x';
    
    $xx = explode(".", $ii);
    foreach ($xx as $x)
    {
        $rr .= s10_to_s16($x);
    };
    
    return $rr;
}


$id = '';
if( isset($_GET['id'])) { $id = $_GET['id']; };
/*
$idx = '';
if( isset($_GET['idx'])) { $idx = $_GET['idx']; };

$sni = '';
if( isset($_GET['sni'])) { $sni = $_GET['sni']; };
*/


$ee = explode("_", $id);
$dd = $ee[0].' - '.$ee[1];

$d = "sess/TCP ".$dd;

if(file_exists($d)) {
    
    echo 'E1';
    if(is_dir($d)) {
        echo 'E2';
    }
} else {
    echo 'BAD ['.$dd.']';
}


$src_file_name = "sess/TCP " . $dd . "/_sess.pcap";
if(file_exists($src_file_name) == false) {
    $src_file_name = "sess/UDP " . $dd . "/_sess.pcap";    
}
if(file_exists($src_file_name) == false) {
    $r = 'src file not found '.$src_file_name;
} else {

    $dst_file_name = $home_dir . '/sniffer.pcap';

    if(file_exists($src_file_name) == false) {
        $r = 'not found ' . $src_file_name;
    } else {


        if(file_exists($dst_file_name)) {
            unlink($dst_file_name);
        } 
        if(file_exists($dst_file_name)) {
            $r = 'delete error ' . $dst_file_name;
        } else {
            copy($src_file_name, $dst_file_name);
            if(file_exists($dst_file_name)) {
                $r = 'ok';    
            } else {
                $r = 'copy fail';
            }
        }
    };
};
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
  parent.copy_pcap_result("<? echo $r; ?>");
};

//-->
</script>
copy pcap <? echo $r; ?>
</body>

