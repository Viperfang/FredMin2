<?php
// Config files for the stats


// Get the Server Name
echo '<strong>Server Info:</strong><br />';
	echo gethostname();
	echo '<br />';
	//echo explode(' ',explode(':',explode('inet addr',explode('eth0',trim(`ifconfig`))[1])[1])[1])[0];
	foreach(explode("\n\n",`ifconfig`) as $interface)
	{
		$interface = trim($interface);
		if(strpos($interface,"inet ") == 0) continue; // no IP for interface, skip
		echo substr($interface,0,strpos($interface,':') + 2); // interface name
		foreach(explode("\n",$interface) as $line)
		{
			if(strpos($line,'inet') === FALSE) continue;
			$propcache = explode(' ',trim($line));
			foreach($propcache as $prop) if(strpos($prop,'.') !== FALSE)
			{
				echo $prop . '<br />';
				break 2;
			}
		}
	}
	echo '<br /><br />';
	

	
// Print the Service Status
echo '<strong>Services Online:</strong><br />';
	// Ping Apache
	ServiceOnline('Apache','localhost',80);
	
	//Ping MySql
	ServiceOnline('MySQL','localhost',3306);
	
	//Ping SSH
	ServiceOnline('SSH','localhost',22);
	
	//Ping Samba Share
	ServiceOnline('Samba','localhost',445);

// Print the Software Versions
	echo '<br /><strong>Software Version:</strong><br />';
    echo version();
	
// Print Server Load Status
echo '<br /><strong>Server Load:</strong><br />';
	
	// CPU Usage
	echo 'CPU Usage: ';
	echo ServCPU();
	echo '%<br />';
	
	// Memory Usage
	echo 'Memory Usage: ';
	echo ServMem();
	echo '%<br />';


// Footer Infomation
	echo '<br />';
	echo "<p class='copyright'>&copy; 2016 Fred
	<br />Powered by Tea</p>";
	
	
// Ping Services for Online Status
function ServiceOnline($ServiceName,$IpAddress,$Port) {
		if (!$socket = @fsockopen($IpAddress, $Port, $errno, $errstr, 30)) {
		echo "<img class='onlinestatus' src='images/offline.ico'> $ServiceName<br />";
	}
	
	else 
	{
		echo "<img class='onlinestatus' src='images/online.ico'> $ServiceName<br />";
		fclose($socket);
	}
}


// Gets CPU Usage
	function ServCPU(){
	$load = sys_getloadavg();
// Output the information
	return $load[0];
}

// Gets Memory Usage and rounds to 2 decimal places
function ServMem(){ 
	$free = shell_exec('free');
	$free = (string)trim($free);
	$free_arr = explode("\n", $free);
	$mem = explode(" ", $free_arr[1]);
	$mem = array_filter($mem);
	$mem = array_merge($mem);
	$memory_usage = $mem[2]/$mem[1]*100;
// Output the information
	return round($memory_usage, 2);
}

function version(){
       $getphpver = phpversion();
       $phpver = round($getphpver,2);
       echo "PHP Ver: $phpver <br />"; 
       $version = apache_get_version();
       echo "$version <br />";
}

?>