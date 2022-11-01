<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

ini_set('display_errors', '1');

date_default_timezone_set('UTC');

function get_sni($dir, $filename)
{
    $fff = file_get_contents($dir .'/'. $filename);

    $ff = explode("\n", $fff);
    $s = '';
    $c = '';
    foreach($ff as $f)
    {

        $v = explode(":", $f, 2);
        if($v[0] == 'sni') {
	    $s = $v[1];
        }
        if($v[0] == 'cert') {
            $c = 'cert='.$v[1];
        }
    };
    //if($c == '') return $s;
    //if($s == '') return $c;
    return $c.' | '.$s;
};


function get_dt()
{
  $ss = date("Y-m-d H:i"); 
  return $ss;
};

function get_traffic($dir, $filename)
{
    $fff = file_get_contents($dir .'/'. $filename);
    
    $ff = explode("\n", $fff);
    
    foreach($ff as $f) 
    {
        echo($f."=");
        $v = explode(":", $f, 3);
        if(count($v)==3) {
            
        
            if($v[0] == 'tcp' && ($v[1] != '00000' || $v[2] != '00000')) return $f;
            if($v[0] == 'udp' && ($v[1] != '00000' || $v[2] != '00000')) return $f;
        } else {
            echo "\r\n";
        }
    };
    return 'no';
};

function get_dns($dir, $filename)
{
    $fff = file_get_contents($dir .'/'. $filename);
    $ff = explode("\n", $fff);
    foreach($ff as $f)
    {
        echo($f."=");
        $v = explode(":", $f, 3);
        if(count($v)==2) {


            if($v[0] == 'dns_name') return $v[1];

        } else {
            
        }
    };
    return 'no';
};


function my_scan_dir($dir)
{
    $rr = '';
    
    $f = scandir($dir);
    foreach ($f as $file)
    {
      if($file != '.' && $file != '..')
      {
          if(is_dir($dir.'/'.$file)) {
            //$rr .= 'D='.$file.'|';  
          } else {
            
            
            
            $rr .= $file."\\t".get_traffic($dir, $file)."\\t".get_dns($dir, $file)."\\t".get_sni($dir, $file)."\\n";
            
          };
      };
    }    
    
    return $rr;
}

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

/*
$ip = '';
if( isset($_GET['ip'])) { $ip = $_GET['ip']; };

$idx = '';
if( isset($_GET['idx'])) { $idx = $_GET['idx']; };

$sni = '';
if( isset($_GET['sni'])) { $sni = $_GET['sni']; };
*/


$r = 'qwe';



$dir = '/var/www/html/sniffer_web/ip3';

$r = my_scan_dir($dir);


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
  parent.update_result("<? echo $r; ?>");
};

//-->
</script>
update <? echo $r; ?>
</body>

