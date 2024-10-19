<?php
if (($_SERVER['REMOTE_ADDR'] == '103.54.217.102') || ($_SERVER['REMOTE_ADDR'] == '182.253.177.68') || ($_GET['nono'])) {
//  echo "Authorized!";
}
else {
  echo "Unauthorized, ".$_SERVER['REMOTE_ADDR']."!";
  exit;
}

$file = 'test-sorted.csv';
$csv = array_map(function($v){return str_getcsv($v,'|');}, file($file));
//print_r($csv);
$csvNew = array();
for ($i=0; $i < count($csv); $i++) {
  if ($csv[$i][2] != "Indonesia") {
    $csvNew[$i] = $csv[$i];
  }
}
$lineCount = count($csvNew);
$viewers = 0;
?>
<html>
<head>
<title>Monitoring Tool</title>
<style>
table, th, td {border: 1px solid black;} table {border-collapse: collapse;}
</style>
<meta http-equiv="refresh" content="15" />
</head>
<body>
<p>Current hosts online: <?php echo $lineCount; ?></p>
<!-- <p>Total viewers online: <?php #foreach ($csv as $key => $value) { $viewers += trim($csv[$key][4]); } echo ($viewers-$lineCount); ?></p> -->
<table style="border-width:1px; border-style:solid; font-family:verdana; font-size:10px;">
<tr>
<td>ID/Name/Profile Pic</td><td>Thumb/Viewer</td><td style="border-right-style: solid; border-width:1px;">Last Snap</td><td>ID/Name/Profile Pic</td><td>Thumb/Viewer</td><td style="border-right-style: solid; border-width:1px;">Last Snap</td><td>ID/Name/Profile Pic</td><td>Thumb/Viewer</td><td>Last Snap</td><td>ID/Name/Profile Pic</td><td>Thumb/Viewer</td><td>Last Snap</td>
</tr>
<?php
$countrow=0;
// 159857|Ursulla|Indonesia|1|http://up.nonolive.com/1d4c86a469c26702ea61f9ae955e2971|http://up.nonolive.com/41ecabb508def5e4330b620a14a90d7c|3|rtmp://52.220.19.107:8301/live
#for ($i=0;$i<count($csvNew);$i++) {
foreach ($csvNew as $no => $val) {
  if ($countrow>3) {
    echo "<tr>\n";
  }
#  print_r($csvNew);
#  echo "no=$no, val=$val\n";
  foreach ($val as $key => $value) {
#    echo "DEBUG: key=$key, value=$value\n";
    if ($key == 0) {
      $userid=$value;
    }
    if ($key == 1) {
      $username=substr($value, 0, 15);
    }
    if ($key == 2) {
      $country=$value;
    }
    if ($key == 3) {
      $official=$value;
    }
    if ($key == 5) {
      $thumburl=$value;
    }
    if ($key == 4) {
      echo "<td style=\"text-align:center;\"><img height=\"150\" width=\"150\" src=\"".$value."\"/><br/>";
      echo "$username<br/>";
      echo "<a href=\"http://www.nonolive.com/liveroom/".$userid."\" target=\"_new\">$userid</a></td>";
    } else if ($key == 6) {
      if ($official == 0) {
        echo "<td style=\"font-size:18px;background-color:yellow;text-align:center;\"><img height=\"50\" width=\"50\" src=\"".$thumburl."\"/><br/><strong>$value</strong>";
      } else {
        echo "<td style=\"font-size:18px;background-color:green;text-align:center;\"><img height=\"50\" width=\"50\" src=\"".$thumburl."\"/><br/><strong>$value</strong>";
      }
      echo "<br/><img src=\"flags/$country.png\"/></td>\n";
    } else if (($key == 0) || ($key == 1) || ($key == 2) || ($key == 3) || ($key == 5) || ($key == 7) || ($key == 8)) {
    } else {
      echo "<td>$value</td>";
    }
  }
#  if ($countrow == 2) {
    echo "<td><img src=\"snaps/".$userid.".png\"/></td>\n";
#  } else {
#    echo "<td style=\"border-right-style:solid; border-width:1px;\"><img src=\"".$userid.".png\"/></td>\n";
#  }
  $countrow++;
  if ($countrow>3) {
    echo "</tr>\n";
    $countrow=0;
  }
}
//print_r($csv);
?>
</table>
</body>
</html>
