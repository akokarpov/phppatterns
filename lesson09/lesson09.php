<?php

// 1. Создать массив на миллион элементов и отсортировать его различными способами. Сравнить скорости.
function bubbleSort($array){
    for($i=0; $i<count($array); $i++){
$count = count($array);
       for($j=$i+1; $j<$count; $j++){
           if($array[$i]>$array[$j]){
               $temp = $array[$j];
               $array[$j] = $array[$i];
               $array[$i] = $temp;
           }
      }         
   }
   return $array;
}

function shakerSort ($array) {
    $n = count($array);
    $left = 0;
    $right = $n - 1;
    do {
    for ($i = $left; $i < $right; $i++) {
    if ($array[$i] > $array[$i + 1]) {
    list($array[$i], $array[$i + 1]) = array($array[$i + 1], $array[$i]);
    }
    }
    $right -= 1;
    for ($i = $right; $i > $left; $i--) {
    if ($array[$i] < $array[$i - 1]) {
    list($array[$i], $array[$i - 1]) = array($array[$i - 1], $array[$i]);
    }
    }
    $left += 1;
    } while ($left <= $right);
    }
    

$testArr = [];
for($i=0; $i<1000000; $i++) {
    array_push($testArr, $i);
}

bubbleSort($testArr);
shakerSort($testArr);

// 2. Реализовать удаление элемента массива по его значению. Обратите внимание на возможные дубликаты!
$array = array_filter($array, function($e) use ($del_val) {
    return ($e !== $del_val);
});

// 3.	Подсчитать практически количество шагов при поиске описанными в методичке алгоритмами.
function ShellSort($elements) {
    $k=0;
	$length = count($elements);
    $gap[0] = (int) ($length / 2);
 
     while($gap[$k] > 1) {
         $k++;
         $gap[$k]= (int)($gap[$k-1] / 2);
     }
 
     for($i = 0; $i <= $k; $i++){
         $step = $gap[$i];
			
         for($j = $step; $j < $length; $j++) {
             $temp = $elements[$j];
             $p = $j - $step;
			 
             while($p >= 0 && $temp['price'] < $elements[$p]['price']) {
                 $elements[$p + $step] = $elements[$p];
                 $p = $p - $step;
             }
			 
             $elements[$p + $step] = $temp;
         }
     }
 
     return $elements;
 }



?>