<!DOCTYPE html>
<html>
<head>
    <title>Merge Sort</title>
    <link rel="stylesheet" href="main.css"/>
    <script type="text/javascript" src="jquery.js"></script>
</head>
<body>
<script type="text/javascript">
    function updateProgress(p) {
        var w = $("#progressbar-outer").css('width');
        var cw = Math.round(p * (w.slice(0, -2)));
        $("#progressbar-inner").css('width', cw);
    }
</script>
<div id="container">
<h1>Merge-Sort</h1>
<h2>&ndash; by David, Kilian, Rick, Sebastian</h2>
<div id="progressdiv">
    <div id="progressbar-outer"><div id="progresstext"></div><div id="progressbar-inner"></div></div>
    <img id="progressimg" alt="progress image" style=""/>
</div>
<table>
<thead>
<tr>
<th>Durchlauf-Nr.</th>
<th>Stellen des Arrays</th>
<th>Durchschnitts-Zeit bei 10 [ms]</th>
</tr>
</thead>
<tbody>
<?php
$mainLoop = 10;
$avgLoop = 10;
$arrayMultiplikator = 300;
for($i = 1; $i <= $mainLoop; $i++) {
    echo "<tr>";
    echo "<td>".$i."</td>";
    echo "<td>".($i*$arrayMultiplikator)."</td>";
    // run 10x and get the avg
    $ar = array();
    for($z = 0; $z < $avgLoop; $z++) {
        $ar[] = mergeSortWithN($i*$arrayMultiplikator);
        updateProgress((($i)/$mainLoop), (($z+1)/$avgLoop));
    }
    echo "<td>".(array_sum($ar)/count($ar))."</td>";
    echo "</tr>";
}

jsOut('$("#progressimg").attr("src", "green_tick.png");');

function mergeSortWithN($n){
$zahlen = array();
for ($i = 0; $i < $n; $i++) {
    $zahlen[] = rand(0,$n);
}
//echo "Array generated (n = ".count($zahlen).")<br/>";

$zeit1 = microtime(true);
$sortiertesArray = mergeSort($zahlen);
$zeit2 = microtime(true);
//echo "Benötigte Zeit: ".(($zeit2 - $zeit1)*1000)." ms <br/>";

return (($zeit2 - $zeit1)*1000);
}


function mergeSort($array){
    //echo "mergeSort([".implode($array, ', ')."]) count = ".count($array)."<br/>";
    $listSize = count($array);
    if ($listSize > 1) {
       $middle = floor($listSize/2);
       //echo "-&gt; middle: $middle <br/>";
    
       $array1 = array_splice($array, 0, $middle);
       $array2 = $array;
       //echo "array1: [".implode($array1, ', ')."] // array2: [".implode($array2, ', ')."] <br/>";
       
       $return1 = mergeSort($array1);
       $return2 = mergeSort($array2);
       //echo "return1: [".implode($return1, ', ')."] // return2: [".implode($return2, ', ')."] <br/>";
       $mergedArray = merge($return1, $return2);
       return $mergedArray;
    } else if($listSize == 1) {
      return $array;
    }

}

function merge($ar1, $ar2) {
   $return = array();
   while ((count($ar1) > 0) && (count($ar2) > 0)) {
         if ($ar1[0] <= $ar2[0]) {
             $return[] = array_shift($ar1);
         } else {
             $return[] = array_shift($ar2);
         }
   }
   while ((count($ar1) > 0)) {
         $return[] = array_shift($ar1);
   }
   while ((count($ar2) > 0)) {
         $return[] = array_shift($ar2);
   }
   return $return;
}

function updateProgress($i, $n) {
    $p = round(($i+($n/100-0.01))*100);
    jsOut('$("#progresstext").text("'.$p.'%");');
    jsOut('updateProgress('.($p/100).');');
}

function jsOut($js) {
    echo '<script type="text/javascript">'.$js.'</script>';
    flush();
}
?>
</tbody>
</table>
<div id="footer">Finished on <?php echo date(DateTime::RFC2822); ?> </div>
</div>
</body>
</html>
