<?php
$file='test-sorted.csv';
$arr = array_map(function($v){return str_getcsv($v,'|');}, file($file));

#print_r($arr);
for ($i = 0; $i < count($arr); ++$i) {
#if ($arr[$i][2]=="Indonesia") {
  $pid = pcntl_fork();

  if (!$pid) {
    #sleep(rand(0,3));
    print "In child $i\n";
    #echo "id=".$arr[$i][0]." url=".$arr[$i][5]."\n";
    $cmd="./ffmpegsnap.sh ".$arr[$i][7]." ".$arr[$i][0];
    #echo $cmd;
    system($cmd);
    exit($i);
  }
#}
}

while (pcntl_waitpid(0, $status) != -1) {
  $status = pcntl_wexitstatus($status);
  echo "Child $status completed\n";
}
?>
