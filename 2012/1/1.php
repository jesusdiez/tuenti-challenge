#!/usr/bin/php
<?php
 
$lines = file('php://stdin');
$lines = array_map('trim', $lines);

$numcases = $lines[0];
for($i = 0; $i < $numcases; $i++) {
	$cases[] = $lines[$i + 1];
}
foreach($cases as $case) {
	$m = new Mobile;
	echo $m->calculate_text($case) . PHP_EOL;
	unset($m);
}

class Mobile {

	const MOVE_UD = 300;
	const MOVE_LR = 200;
	const MOVE_DI = 350;
	const PRESS   = 100;
	const WAIT    = 500;

	protected static $keyboard = array(
		0 => array(
			0 => ' 1',
			1 => 'ABC2',
			2 => 'DEF3',
		),
		1 => array(
			0 => 'GHI4',
			1 => 'JKL5',
			2 => 'MNO6',
		),
		2 => array(
			0 => 'PQRS7',
			1 => 'TUV8',
			2 => 'WXYZ9',
		),
		3 => array(
			1 => '0',
			2 => '',
		)
	);

	protected $y;
	protected $x;
	protected $may;
	
	protected function get_char_coords_pos($char)
	{
		$char = strtoupper($char);
		// echo 'coordenadas de '.$char.PHP_EOL;
		if($char==" ") return array(0,0,0);
		if($char=="0") return array(3,1,0);
		
		// echo PHP_EOL.'Looking for "'.$char.'" ('.ord($char).') in keyboard'.PHP_EOL;
		foreach(static::$keyboard as $y=>$row)
		{
			foreach($row as $x=>$keys)
			{	
				$pos = mb_stripos($keys, $char);
				if (is_numeric($pos))
				{
					// echo 'Found in pos '.$pos.' of ('.$y.','.$x.')'.PHP_EOL;
					return array($y, $x, $pos);
				}
				//echo 'strpos('.$keys.', '.$char.') ';
				// echo $pos;
			}
		}
		return array($this->y, $this->x, 0);
	}
	
	protected function move_to_coords($tar_y, $tar_x)
	{
		$sum = 0;
		while (($this->y != $tar_y) OR ($this->x != $tar_x))
		{
			if ($this->y > $tar_y AND $this->x > $tar_x)
			{
				$this->y--;	$this->x--;
				$sum += self::MOVE_DI;
			}
			elseif ($this->y < $tar_y AND $this->x < $tar_x)
			{
				$this->y++;	$this->x++;
				$sum += self::MOVE_DI;
			}
			elseif ($this->y < $tar_y AND $this->x > $tar_x)
			{
				$this->y++;	$this->x--;
				$sum += self::MOVE_DI;
			}
			elseif ($this->y > $tar_y AND $this->x < $tar_x)
			{
				$this->y--;	$this->x++;
				$sum += self::MOVE_DI;
			}
			elseif ($this->y == $tar_y AND $this->x < $tar_x)
			{
				$this->x++;
				$sum += self::MOVE_LR;
			}
			elseif ($this->y == $tar_y AND $this->x > $tar_x)
			{
				$this->x--;
				$sum += self::MOVE_LR;
			}
			elseif ($this->x == $tar_x AND $this->y < $tar_y)
			{
				$this->y++;
				$sum += self::MOVE_UD;
			}
			elseif ($this->x == $tar_x AND $this->y > $tar_y)
			{
				$this->y--;
				$sum += self::MOVE_UD;
			}
		}
		
		return $sum;
	}

	public function calculate_text($text)
	{
		$sum = 0;
		$this->y = 3;
		$this->x = 1;
		$this->may = FALSE;

		for ($i=0; $i<strlen($text); $i++)
		{	
			$char = substr($text, $i, 1);
			
			$is_may = (ord($char) >= 65 AND ord($char) <= 90);
			$is_space_or_numeric = ($char == ' ' OR is_numeric($char));
			if ( ! $is_space_or_numeric)
			{
				if ($is_may AND ! $this->may)
				{	
					$sum += $this->mayus();
				}
				elseif (! $is_may AND $this->may)
				{	
					$sum += $this->mayus();
				}
			}

			list($tar_y, $tar_x, $pos) = $this->get_char_coords_pos($char);
			if (($i > 0) AND ($tar_y == $this->y) AND ($tar_x == $this->x))
			{
				$sum += self::WAIT;
			}
			else
			{
				$sum += $this->move_to_coords($tar_y, $tar_x);
			}
			
			for ($v = 0; $v <= $pos; $v++)
			{
				$sum += self::PRESS;
			}
		}
		return $sum;
	}

	protected function mayus()
	{
		$this->may = ! $this->may;
		return $this->move_to_coords(3,2) + self::PRESS;
	}

}