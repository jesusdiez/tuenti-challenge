#!/usr/bin/php
<?php

$lines = array(
3, 
1,
6,
2135,
);
$lines = array_map('trim', file('php://stdin'));

$numcases = $lines[0];
for ($i = 0; $i < $numcases; $i++)
{
	$cases[] = $lines[$i + 1];
}
foreach ($cases as $k=>$case)
{
	$max = 0;

	for ($i=$case; $i>=ceil($case/2); $i--)	//Just half of the pile
	{
		$ibin = base_convert($i, 10, 2);
		$iunos = substr_count($ibin, '1');
		for ($j=$i; $j>=0; $j--)
		{
			if (($i+$j) != $case)
				continue;

//			echo 'num '.$case.':  i '.$i.' ('.sprintf('%b', $i).') + j '.$j.' ('.sprintf('%b', $j).')'.PHP_EOL;
			$jbin = base_convert($j, 10, 2);
			$unos = substr_count($jbin, '1') + $iunos;
			$max = ($unos > $max) ? $unos : $max;
		}
	}
	echo 'Case #'.($k+1).': '.$max.PHP_EOL;
}