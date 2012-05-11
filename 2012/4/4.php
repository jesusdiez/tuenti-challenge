#!/usr/bin/php
<?php
 
$lines = array_map('trim', file('php://stdin'));
$numcases = $lines[0];
for ($line = 1; $line < $numcases*2; $line+=2) {
	
	list($r, $k, $g) = explode(' ', $lines[$line]);
	$groups = explode(' ', $lines[$line+1]);

//	echo "r: $r k: $k g: $g (".implode(',',$groups).')'.PHP_EOL;
	
	$liters = 0;
	for ($race = 1; $race <= $r; $race++)
	{
//		echo "Race ".$race.'/'.$r.', groups('.implode(',',$groups).')'.PHP_EOL;
		$karts_available = $k;
		$karts_used = 0;
		$race_groups = array();

		// Get racers from 'available groups' to 'race groups'
		while (isset($groups[0]) AND $karts_available >= $groups[0])
		{
			$group = $groups[0];
			$karts_available -= $group;
			$karts_used += $group;
			array_push($race_groups, $group); 
			array_shift($groups);
		}
		
		// Enter the racers on the groups again for next race
		foreach ($race_groups as $item)
		{
			array_push($groups, $item);	
		}		
		$liters += $karts_used;
	//	echo $k.' karts total, usando '.count($race_groups).' karts, race groups: ('.implode(',',$race_groups).')'.PHP_EOL;
	}

	echo $liters.PHP_EOL;
}