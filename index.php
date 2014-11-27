<?php

// LOAD -> 				            www.example.com/index.php?t=0
// SUBMIT ->						www.example.com/index.php?t=1&n=[NAME]&s=[SCORE]

//echo "1:TEST:123";

if (isset($_GET["t"])) { $type = $_GET["t"]; } else { $type = 0; }
if (isset($_GET["n"])) { $name = $_GET["n"]; } else { $name = ""; }
if (isset($_GET["s"])) { $score = $_GET["s"]; } else { $score = ""; }

if($type == 0)
{
	$f = file("highscore.txt");
	$scoreFile = $f[0];
	
	$scoreList = explode("#", $scoreFile);
	$scoreListLength = count($scoreList);
	$resultString = "";
	
	$len = strlen($scoreFile);
	if($len > 3)
	{
		for($i = 0; $i < $scoreListLength; $i++)
		{
			list($data_name, $data_score) = explode(":", $scoreList[$i]);
			
			if($i > 0)
			{
				$resultString .= "#";
			}
			
			$resultString .= $i . ":" . $data_name . ":" . $data_score;

			if($i == 9)
			{
				break;
			}
		}
	}
	
	echo $resultString;
}
else if($type == 1)
{
	$f = file("highscore.txt");
	$scoreFile = $f[0];
	
	$scoreList = explode("#", $scoreFile);
	$scoreListLength = count($scoreList);
	$newFile = "";
	$pos = 0;
	
	$len = strlen($scoreFile);
	if($len < 3)
	{
		$scoreList = array($name . ":" . $score);
	}
	else
	{
		for($i = 0; $i < $scoreListLength; $i++)
		{
			list($data_name, $data_score) = explode(":", $scoreList[$i]);
			
			if ($score > $data_score)
			{
				$pos = $i;
				array_splice($scoreList, $i, 0, array($name . ":" . $score));
				break;
			}
			
			if($i == $scoreListLength - 1)
			{
				$pos = $i;
				array_splice($scoreList, $i + 1, 0, array($name . ":" . $score));
				break;
			}
		}
	}
	
	$scoreListLength = count($scoreList);
	$fixedLength = $scoreListLength;
	if($fixedLength > 50)
	{
		$fixedLength = 50;
	}
	
	for($j = 0; $j < $fixedLength; $j++)
	{
		if($j > 0)
		{
			$newFile .= "#";
		}
		
		$newFile .= $scoreList[$j];
	}
	
	$overwritefile = fopen("highscore.txt", "w");
	fwrite($overwritefile, $newFile);
	
	$start = $pos - 5;
	$end = $pos + 5;
	
	if ($start < 0)
	{
		$start = 0;
		$end = 10;
	}
	
	if ($end > $scoreListLength)
	{
		$start -= $end - $scoreListLength;
		$end = $scoreListLength;
	}
	
	if ($start < 0)
	{
		$start = 0;
	}
	
	$resultString = "";
	for($i = $start; $i < $end; $i++)
	{
		list($data_name, $data_score) = explode(":", $scoreList[$i]);
		
		if($i > $start)
		{
			$resultString .= "#";
		}
		
		$resultString .= $i . ":" . $data_name . ":" . $data_score;

		if($i == $end)
		{
			break;
		}
	}
	
	echo $resultString;
}

/*
if (isset($_GET["s"])) { $server = $_GET["s"]; } else { $server = 0; }
if (isset($_GET["l"])) { $ipLocal = $_GET["l"]; } else { $ipLocal = ""; }
if (isset($_GET["n"])) { $serverName = $_GET["n"]; } else { $serverName = ""; }

if($server == 1)
{
	$ipRemote = "" . $_SERVER['REMOTE_ADDR'];
	$ipRemote = str_replace(".","D", $ipRemote);
	
	$f = file("servers.txt");
	$serverFile = $f[0];
	
	$serverList = explode("#", $serverFile);
	$serverListLength = count($serverList);
	$newList = "";
	$match = false;
	$first = true;
	
	if($serverListLength == 0)
	{
		$newList = $ipRemote . ";" . $ipLocal . ";" . $serverName . ";" . date('i');
	}
	else
	{
		for ($i = 0; $i < $serverListLength; $i++)
		{
			list($data_remote, $data_local, $data_name, $data_time) = explode(";", $serverList[$i]);
			$currentTime = date('i');
			
			if($data_local == $ipLocal && $data_name == $serverName)
			{
				$match = true;
				if($first == false)
				{
					$newList .= "#";
				}
				$first = false;
				
				$newList .= $data_remote . ";" . $data_local . ";" . $data_name . ";" . date('i');
			}
			else if(($data_time + 3 > $currentTime && $data_time - 3 < $currentTime) || ($data_time > 56 && $currentTime < 3))
			{
				if($first == false)
				{
					$newList .= "#";
				}
				$first = false;

				$newList .= $data_remote . ";" . $data_local . ";" . $data_name . ";" . $data_time;
			}
		}
		
		if($match == false)
		{
			if($first == false)
			{
				$newList .= "#";
			}
			$newList .= $ipRemote . ";" . $ipLocal . ";" . $serverName . ";" . date('i');
		}
	}
	
	$overwritefile = fopen("servers.txt", "w");
	fwrite($overwritefile, $newList);
}
else
{
	$f = file("servers.txt");
	$serverFile = $f[0];
	$serverList = explode("#", $serverFile);
	$serverListLength = count($serverList);
	$newList = "";
	$first = true;
	
	for ($i = 0; $i < $serverListLength; $i++)
	{
		list($data_remote, $data_local, $data_name, $data_time) = explode(";", $serverList[$i]);
		$currentTime = date('i');
		
		if(($data_time + 3 > $currentTime && $data_time - 3 < $currentTime) || ($data_time > 56 && $currentTime < 3))
		{
			if($first == false)
			{
				$newList .= "#";
			}
			$first = false;

			$newList .= $data_remote . ";" . $data_local . ";" . $data_name . ";" . $data_time;
		}
	}
	
	$overwritefile = fopen("servers.txt", "w");
	fwrite($overwritefile, $newList);
	echo $newList;
	
	//echo "111.255.255.255;111.255.255.255;Server een;52#222.255.255.255;222.255.255.255;Server twee;51";
}
*/
?>