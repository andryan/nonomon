<?php
if (($_SERVER['REMOTE_ADDR'] == '103.54.217.102') || ($_SERVER['REMOTE_ADDR'] == '182.253.42.187') || ($_GET['nono'])) {
//  echo "Authorized!";
}
else {
  echo "Unauthorized!";
  exit;
}

$file = 'test-sorted.csv';
$csv = array_map(function($v){return str_getcsv($v,'|');}, file($file));
$lineCount = count($csv);
$viewers = 0;
?>
<html>
<head>
<title>Monitoring Tool</title>
<style>
table, th, td {border: 1px solid black;} table {border-collapse: collapse;}
</style>
<meta http-equiv="refresh" content="10" />
</head>
<body>
<p>Current hosts online: <?php echo $lineCount; ?></p>
<!-- <p>Total viewers online: <?php #foreach ($csv as $key => $value) { $viewers += trim($csv[$key][4]); } echo ($viewers-$lineCount); ?></p> -->
<table style="border-width:1px; border-style:solid; font-family:verdana; font-size:10px;">
<tr>
<td>ID/Name/Profile Pic</td><td>Thumb/Viewer</td><td style="border-right-style: solid; border-width:1px;">Last Snap</td><td>ID/Name/Profile Pic</td><td>Thumb/Viewer</td><td style="border-right-style: solid; border-width:1px;">Last Snap</td><td>ID/Name/Profile Pic</td><td>Thumb/Viewer</td><td>Last Snap</td>
</tr>
<?php
$countrow=0;
for ($i=0;$i<count($csv);$i++) {
  if ($countrow>2) {
    echo "<tr>\n";
  }
  foreach ($csv[$i] as $key => $value) { 
    if ($key == 0) {
      $userid=$value;
    }
    if ($key == 1) {
      $username=$value;
    }
    if ($key == 3) {
      $thumburl=$value;
    }
    if ($key == 2) {
      echo "<td><img height=\"150\" width=\"150\" src=\"".$value."\"/><br/>";
      echo "$username ";
      echo "<a href=\"http://www.nonolive.com/liveroom/".$userid."\" target=\"_new\">$userid</a></td>";
    } else if ($key == 4) {
      if ($i == 0) {
        echo "<td style=\"background-color:red;\"><img height=\"50\" width=\"50\" src=\"".$thumburl."\"/><br/><strong>$value</strong></td>";
      } else {
        echo "<td><img height=\"50\" width=\"50\" src=\"".$thumburl."\"/><br/>$value</td>";
      }
    } else if (($key == 0) || ($key == 1) || ($key == 3) || ($key == 5)) {
    } else {
      echo "<td>$value</td>";
    }
  }
#  if ($countrow == 2) {
    echo "<td><img src=\"".$userid.".png\"/></td>\n";
#  } else {
#    echo "<td style=\"border-right-style:solid; border-width:1px;\"><img src=\"".$userid.".png\"/></td>\n";
#  }
  $countrow++;
  if ($countrow>2) {
    echo "</tr>\n";
    $countrow=0;
  }
}
//print_r($csv);
?>
</table>
</body>
</html>
