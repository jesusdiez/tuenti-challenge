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

	$case_bin = base_convert($case, 10, 2);
	$max_bin = str_pad('', strlen($case_bin), '1');
	
	if (strcmp($case_bin, $max_bin) == 0)
	{
		$max = strlen($case_bin);
	}
	else
	{
		// We use the second number with max 1s
		$max_bin = substr($max_bin, 1);
		
		$diff = $case - base_convert($max_bin, 2, 10);
		$diff_bin = base_convert($diff, 10,2);

		$max = strlen($max_bin) + substr_count($diff_bin, '1');
	}
	echo 'Case #'.($k+1).': '.$max.PHP_EOL;
}