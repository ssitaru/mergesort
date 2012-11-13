<?php
// wie viele durchläufe?
$mainLoop = 15;

// wie viele durchschnitts-schleifen?
$avgLoop = 20;

// welcher umfangsmultiplikator?
$arrayMultiplikator = 300;


// ab hier: don't touch this!
$totalNumbers = array_sum(range(1,$mainLoop))*$arrayMultiplikator*$avgLoop;
$numbersDone = 0;
$scriptStartTime = time();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Merge-Sort</title>
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="shortcut icon" href="img/favicon.ico"/>
    <script type="text/javascript" src="js/jquery.js"></script>
</head>
<body>
<script type="text/javascript">
    var updateN = 0;
    var lastProgress = 0;
    var lastDate = new Date();
    var nowDate = new Date();
    var estT = 0;
    var passedT = 0;
    
    function updateProgress(p) {
        var w = $("#progressbar-outer").css('width');
        var cw = Math.round(p * (w.slice(0, -2)));
        $("#progressbar-inner").css('width', cw);
        if((updateN % 2) == 0) { // every 2 updates, update ETA
            nowDate = new Date();

            var deltaT = nowDate.getTime() - lastDate.getTime();
            passedT += deltaT;
            totalT = passedT*(1/p);
            estT = totalT - passedT;
            
            lastProgress = p;
            lastDate = new Date();
        }
        
        if((p < 1) && (p > 0.3))
        {
            $("#progresstext").text(Math.round(p*100)+'% (ETA: '+Math.round(estT/1000)+'s)');
        } else {
            $("#progresstext").text(Math.round(p*100)+'%');
        }
            
        
        updateN++;
    }
</script>
<div id="container">
<h1>Merge-Sort</h1>
<h2>&ndash; by David, Kilian, Rick, Sebastian</h2>
<div id="progressdiv">
    <div id="progressbar-outer"><div id="progresstext"></div><div id="progressbar-inner"></div></div>
    <img id="progressimg" alt="progress image" style="" src="img/progress_loop.gif"/>
</div>
<table>
<thead>
<tr>
<th>Durchlauf-Nr.</th>
<th>Umfang des Arrays</th>
<th>Durchschnitts-Zeit bei <?php echo $avgLoop; ?> [ms]</th>
</tr>
</thead>
<tbody>
<?php
for($i = 1; $i <= $mainLoop; $i++) {
    echo "<tr>";
    echo "<td>".$i."</td>";
    echo "<td>".($i*$arrayMultiplikator)."</td>";
    // run 10x and get the avg
    $ar = array();
    for($z = 0; $z < $avgLoop; $z++) {
        $ar[] = mergeSortWithN($i*$arrayMultiplikator);
        $numbersDone += ($i*$arrayMultiplikator);
        $p = $numbersDone/$totalNumbers;
        updateProgress($p);
    }
    echo "<td>".(array_sum($ar)/count($ar))."</td>";
    echo "</tr>";
}

jsOut('$("#progressimg").attr("src", "img/green_tick.png");');

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

function updateProgress($i) {
    $p = round($i*100);
    jsOut('updateProgress('.($p/100).');');
}

function jsOut($js) {
    echo '<script type="text/javascript">'.$js.'</script>';
    flush();
}
?>
</tbody>
</table>
<div id="footer">Finished on <?php echo date(DateTime::RFC2822); ?> [&#916;t = <?php echo time()-$scriptStartTime; ?>s] </div>
</div>
</body>
</html>
