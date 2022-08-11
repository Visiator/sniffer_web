<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

ini_set('display_errors', '1');

date_default_timezone_set('UTC');

$cc = '';

function get_dt()
{
  $ss = date("Y-m-d H:i"); 
  return $ss;
};

function get_ip($s) {
    $x = explode(":", $s);
    return $x[0];
}

function is_ip_in_rule_exists($server, $login, $pass, $ip) {
    
    $r = '';
    
    $API = new RouterosAPI();
    $API->debug = false;
    if ($API->connect($server, $login ,$pass)) {
        $API->write('/ip/firewall/filter/print');
        $READ = $API->read(false);
        
        $ARRAY = $API->parseResponse($READ);

//        echo '<pre>';
//var_dump($ARRAY);
//echo '</pre>';
        
        $i = count($ARRAY);
        $j = 0;
        while($j < $i)
        {
            if(isset($ARRAY[$j]['dst-address'])) {
                $ii = $ARRAY[$j]['dst-address'];
                if($ii == $ip) {
                    $r = $ARRAY[$j]['.id'];
                };
            };
            $j++;
        };
        $API->disconnect();        
    }  
    
    return $r;
}

function exec_command_firewall_add($server, $login, $pass, $ip) {
    echo 'add<br>';
    $r = 'add';
    
    if(is_ip_in_rule_exists($server, $login, $pass, $ip) != '') {
        return 'already exists ';
    }
    
    $API = new RouterosAPI();
    $API->debug = false;
    if ($API->connect($server, $login, $pass)) {
        $API->comm("/ip/firewall/filter/add", array("chain" => "forward", "action" => "drop", "dst-address" => $ip));
        $API->disconnect();        
    }  
    
    return $r;
}
function exec_command_firewall_del($server, $login, $pass, $ip) {
    $r = '';
    
    $iid = is_ip_in_rule_exists($server, $login, $pass, $ip);
    
    echo '['.$iid.']';
    
    $API = new RouterosAPI();
    $API->debug = false;
    if ($API->connect($server, $login, $pass)) {
        $API->comm("/ip/firewall/filter/remove", array(".id" => $iid));
        $API->disconnect();        
    }  
    
    return $r;
}
function exec_command_firewall_print($server, $login, $pass, $ip) {
    
    global $cc;
    
    $r = '';
    
    $API = new RouterosAPI();
    $API->debug = false;
    if ($API->connect($server,$login, $pass)) {
        $API->write('/ip/firewall/filter/print');
        $READ = $API->read(false);
        
        $ARRAY = $API->parseResponse($READ);

        $i = count($ARRAY);
        $j = 0;
        while($j < $i)
        {
            if($r != '') $r .= "\t";
            if(isset($ARRAY[$j]['dst-address'])) {
                $r .= $ARRAY[$j]['dst-address'].'|'.$ARRAY[$j]['bytes'].'|'.$ARRAY[$j]['packets'];
                $cc .= $ARRAY[$j]['dst-address']."\r\n";
            };
            $j++;
        };
        $API->disconnect();        
    }  
    
    return $r;
}

$ip = '';
if( isset($_GET['ip'])) { $ip = get_ip($_GET['ip']); };

$mode = '';
if( isset($_GET['mode'])) { $mode = $_GET['mode']; };

$idx = '';
if( isset($_GET['idx'])) { $idx = $_GET['idx']; };

$r = '';

require('routeros_api.class.php');

//$server = '192.168.1.115';
//$server = '10.212.65.34';
$server = '192.168.5.5';
//$server = '192.168.1.195';
$login = 'admin';
$pass = 'Qq1233!!';//'Mikro1233$}}!!';



if($mode == 'firewall_add') $r = exec_command_firewall_add($server, $login, $pass, $ip);
if($mode == 'firewall_del') $r = exec_command_firewall_del($server, $login, $pass, $ip);
if($mode == 'firewall_print') $r = exec_command_firewall_print($server, $login, $pass, $ip);

/*
$API = new RouterosAPI();

$API->debug = true;

if ($API->connect('192.168.1.142', 'admin', '111')) {

        
    // /ip firewall filter add chain=blah action=accept protocol=tcp port=123 nth=4,2 
            
    //$API->write('/interface/getall');
    $API->write('/ip/firewall/filter/print');

    //$API->write('/ip/firewall/filter/add?=chain=blah action=accept protocol=tcp port=123

    //$API->comm("/ip/firewall/filter/add", array("chain" => "forward", "action" => "drop", "dst-address" => "8.8.8.8"));
        
    
    $READ = $API->read(false);
    $ARRAY = $API->parseResponse($READ);

    $i = count($ARRAY);
    $j = 0;
    while($j < $i)
    {
      echo 'id='.$ARRAY[$j]['.id'].' | '.
           'name='.$ARRAY[$j]['name'].

           '<br>';
      $j++;
    };
    
    
    print_r($ARRAY);

    $API->disconnect();
};
*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad=on_load() bgcolor=#ffffff style="margin:0;">

<script language="JavaScript" type="text/javascript">
<!--

/*
<? echo $cc; ?>
*/

function on_load()
{
  parent.mikrotik_result('<? echo $r; ?>','<? echo $mode; ?>','<? echo $idx; ?>');
};

//-->
</script>
   
<table border="1"><tr>
<td>ip = <? echo $ip; ?></td>
<td>r = <? echo $r; ?></td>
</tr></table>
</body>

