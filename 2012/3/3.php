#!/usr/bin/php
<?php

$lines = array_map('trim', file('php://stdin'));
$size = count($lines);

$mins = $lines;
asort($mins, SORT_NUMERIC);

$benefit = 0;
$buy     = NULL;
$sell    = NULL;
for($i=0; $i<($size-1); $i++)
{
	for($j=($size-1); $j>$i; $j--)
	{
		$diff = $lines[$j] - $lines[$i];
		if($diff > $benefit)
		{
			$benefit = $diff;
			$buy = $i*100;
			$sell = $j*100;
		}
	}
}

echo $buy.' '.$sell.' '.$benefit.PHP_EOL;