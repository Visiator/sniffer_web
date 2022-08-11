<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

ini_set('display_errors', '1');

date_default_timezone_set('UTC');

function find_local($iip)
{
  $nn = 'spur/'.$iip;
  if(file_exists($nn) == false) return '';

  $s = file_get_contents($nn);

  $x = explode("\t", $s);

  return $s;
};

function get_dt()
{
  $ss = date("Y-m-d H:i"); 
  return $ss;
};

function get_ip($s) {
    $x = explode(":", $s);
    return $x[0];
}

function check_22($v, $iip)
{
  if( $v == $iip ) return '';
  if( $v == '-' ) return '';
  if( substr($v, 0, 1) == "<" ) return '';
  return $v;
};

$ip = '';
if( isset($_GET['ip'])) { $ip = get_ip($_GET['ip']); };

$idx = '';
if( isset($_GET['idx'])) { $idx = $_GET['idx']; };

$r = find_local($ip);

if($r == '') {
  
    //usleep(5000);
    
    $ch = curl_init('https://spur.us/context/'.$ip);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $html = curl_exec($ch);
    curl_close($ch);

    $lll = explode("\n", $html);

    $r = '???';

    $d = -1;

    foreach ($lll as $ll) 
    {
      $ll = trim($ll);

      if(isset($ll))
      { 
        $ll = trim( $ll );
        if($d == -1)
        {
          $pos = strpos($ll, 'ddc mb-3 text-left');
          if($pos > 0) 
          {
            $d = 0;
            $r = '';
          };
        } 
        else 
        {
            if($d == 0)
            {
              if($ll == '</h1>' || $ll == "</h1><br>\r\n") { break; };
              $r .= check_22( $ll , $ip );
            };
        };
      };
    }

    $fd = fopen('spur/'.$ip, 'w') or die("не удалось открыть файл");
    fwrite($fd, get_dt()."\t".$r);
    fclose($fd);

    $r = find_local($ip);

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
  parent.spur_curl_result('<? echo $r; ?>','<? echo $idx; ?>');
};

//-->
</script>
ip = <? echo $ip; ?>
spur = <? echo $r; ?>
</body>

