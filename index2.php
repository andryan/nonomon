<?php
if (($_SERVER['REMOTE_ADDR'] == '103.54.217.102') || ($_SERVER['REMOTE_ADDR'] == '182.253.42.187') || ($_GET['nono'])) {
//  echo "Authorized!";
}
else {
  echo "Unauthorized!";
  exit;
}

$file = 'test-staging.csv';
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
// 159857|Ursulla|1|http://up.nonolive.com/1d4c86a469c26702ea61f9ae955e2971|http://up.nonolive.com/41ecabb508def5e4330b620a14a90d7c|3|rtmp://52.220.19.107:8301/live
for ($i=0;$i<count($csv);$i++) {
  if ($countrow>2) {
    echo "<tr>\n";
  }
  #print_r($csv);
  foreach ($csv[$i] as $key => $value) { 
    if ($key == 0) {
      $userid=$value;
    }
    if ($key == 1) {
      $username=$value;
    }
    if ($key == 2) {
      $official=$value;
    }
    if ($key == 4) {
      $thumburl=$value;
    }
    if ($key == 3) {
      echo "<td><img height=\"150\" width=\"150\" src=\"".$value."\"/><br/>";
      echo "$username ";
      echo "<a href=\"http://www.nonolive.com/liveroom/".$userid."\" target=\"_new\">$userid</a></td>";
    } else if ($key == 5) {
      if ($official == 0) {
        echo "<td style=\"font-size:18px;background-color:yellow;\"><img height=\"50\" width=\"50\" src=\"".$thumburl."\"/><br/><strong>$value</strong></td>";
      } else {
        echo "<td style=\"font-size:18px;background-color:green;\"><img height=\"50\" width=\"50\" src=\"".$thumburl."\"/><br/><strong>$value</strong></td>";
      }
    } else if (($key == 0) || ($key == 1) || ($key == 2) || ($key == 4) || ($key == 6)) {
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
