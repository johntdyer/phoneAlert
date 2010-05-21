<?

$config = parse_ini_file("config/config.ini");
$base_url = str_replace($_SERVER['DOCUMENT_ROOT'], "", dirname($_SERVER['PHP_SELF'])); 

/**
	 * Cleans log directory of files older then n days
	 *
	 * @param string $daysToExpung 
	 * @param string $fileTypes					Filetypes to check (you can also use *.*)
	 * @param string $logFolder 					Define the folder to clean (keep trailing slashes)
	 * @return null or record count
	 * @author John Dyer
	 */
	function cleanLogDir($daysToExpung,$fileTypes,$logFolder){
		$expire_days    = $daysToExpung;
			foreach (glob($logFolder . $fileTypes) as $Filename) {
				// Read file creation time
				$FileCreationTime = filectime($Filename);
				// Calculate file age in seconds
				$FileAge = time() - $FileCreationTime; 
				// Is the file older than the given time span?
				if ($FileAge > ($expire_days*60*60*24)){
						//print "The file $Filename is older than $expire_days days<br/>";
						// For example deleting files:
						unlink($Filename);
					}
				}
			}     
			
?>