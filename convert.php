<?php 
//This is an example call into the Number to Word Class

require 'NumbertoWord.php';

$conv = new NumbertoWord;
$num = 123456;
$word = $conv->number_to_word($num);

echo $word;

?>
