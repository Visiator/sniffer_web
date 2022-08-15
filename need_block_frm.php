<?php

header('Last-Modified: Mon, 1 Jul 2019 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');


date_default_timezone_set('UTC');


function get_ip($s) {
    $x = explode(":", $s);
    return $x[0];
}

$tt = '<table boder=1>'."\r\n";
$rr = '';
$f = scandir("need_block");
foreach ($f as $file)
{
    if($file != '.' && $file != '..')
    {
        
        $rr .= $file;
        
        break;
    };

  
}

$tt .= '</table>';

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body onLoad=on_load() bgcolor=#ffffff>
<style type="text/css">


</style> 

<script language="JavaScript" type="text/javascript">
<!--

function on_load()
{
  parent.need_block_result("<?php echo $rr; ?>");
};


//-->
</script>

<?php echo $tt; ?>

</body>

