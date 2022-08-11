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

function get_dt()
{
  $ss = date("Y-m-d H:i"); 
  return $ss;
};

function my_scan_file($dir, $filename, $ip1, $sni)
{
    $rr = '';
    $vv = file_get_contents($dir.'/'.$filename);

    $len = strlen($vv);    
    if($len == 0) {
        $rr = 'len=0';
        return '';
    }

    //***************************************************************//
        
    $pos = strpos($vv, $ip1);
    echo $filename . "|" . $len ."|".$ip1."\n";
    if($pos === false) {
        
    } else {
        $p1 = $pos;
        while($p1 > 0 && ord($vv[$p1])!=10 && ord($vv[$p1])!=13 ) $p1--;
        if(ord($vv[$p1])==10 || ord($vv[$p1])==13) $p1++;
        
        $p2 = $pos;
        while($p2 < $len && ord($vv[$p2])!=10 && ord($vv[$p2])!=13 ) $p2++;
        if($p2 == $len) $p2--;
        if(ord($vv[$p2])==10 || ord($vv[$p2])==13) $p2--;
        
        $sss = '';
        while($p1 <= $p2) {
            if($vv[$p1] == "\t") {
                $sss .= '&nbsp;&nbsp;&nbsp;&nbsp;';
            } else {
                if($vv[$p1] == " ") {
                    $sss .= '&nbsp;';
                } else {
                    $sss .= $vv[$p1];
                };
            };
            $p1++;
        }
        
        $rr = $filename."\t".$sss;
    }
    
    if($sni != '') {
        $pos = strpos($vv, $sni);
        if($pos === false) {

        } else {
            $p1 = $pos;
            while($p1 > 0 && ord($vv[$p1])!=10 && ord($vv[$p1])!=13 ) $p1--;
            if(ord($vv[$p1])==10 || ord($vv[$p1])==13) $p1++;

            $p2 = $pos;
            while($p2 < $len && ord($vv[$p2])!=10 && ord($vv[$p2])!=13 ) $p2++;
            if($p2 == $len) $p2--;
            if(ord($vv[$p2])==10 || ord($vv[$p2])==13) $p2--;

            $sss = '';
            while($p1 <= $p2) {
                if($vv[$p1] == "\t") {
                    $sss .= '&nbsp;&nbsp;&nbsp;&nbsp;';
                } else {
                    if($vv[$p1] == " ") {
                        $sss .= '&nbsp;';
                    } else {
                        $sss .= $vv[$p1];
                    };
                };
                $p1++;
            }

            $rr = $filename."\t".$sss;
        }
    }
    //***************************************************************//
    
    /*$pos = strpos($vv, $ip2);
    if($pos === false) {
        
    } else {
        $v1 = $vv[$pos-1];
        $v2 = $vv[$pos+strlen($ip2)];
        
        $rr = '(2)'.$filename.' pos='.$pos.' v1='.ord($v1).' v2='.ord($v2);
    }*/
    
    
    return $rr;
};

function my_scan_dir($dir, $ip1, $sni)
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
            $r = my_scan_file($dir, $file, $ip1, $sni);
            if($r != '') {
                if($rr != "") $rr .= "\a".$file;
                $rr .= $r;                
            }
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

$ip = '';
if( isset($_GET['ip'])) { $ip = get_ip($_GET['ip']); };

$idx = '';
if( isset($_GET['idx'])) { $idx = $_GET['idx']; };

$sni = '';
if( isset($_GET['sni'])) { $sni = $_GET['sni']; };



$r = 'qwe';



$dir = '/home/smorodin/Work/src/eco-platform/src/apps/econat/econat/ecosig/signatures';


$r = my_scan_dir($dir, ip_to_hex($ip), $sni);


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
  parent.cpp_result('<? echo $r; ?>','<? echo $idx; ?>');
};

//-->
</script>
ip = <? echo $ip; ?>
cpp = <? echo $r; ?>
</body>

