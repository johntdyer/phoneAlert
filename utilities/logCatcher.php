<?



include('../lib/functions.php');
cleanLogDir($config['days_to_log'],'*.csv','../logs/');

date_default_timezone_set($config['time_zone']);

$myFile 		=	"../logs/LOGS__".date('Y-m-d-H00').".csv";
$year 			=	date('Y');
$timeStamp	=	date('H:i:s');



if(!file_exists($myFile)){
	touch ($myFile);
	$fh = fopen($myFile, 'a');
	fwrite($fh,"CONFIDENTIAL INFORMATION\n");
	fwrite($fh,"CDR - "	.	date('H00')	.	" GMT "	.	$year	.	"\n\n");
	fwrite($fh,"TimeStamp,SessionID,Caller ID,Call Result\n");
	fwrite($fh,
		$timeStamp				.",".
		$sessionID				.",".
		$callerID					.",".
		$callDisposition	."\n"
		);
	fclose($fh);
	
}else{
	$fh = fopen($myFile, 'a');
	fwrite($fh,
		$timeStamp				.",".
		$sessionID				.",".
		$callerID					.",".
		$callDisposition	."\n"
		);
	fclose($fh);
	}


if(trim($callDisposition)=="Application Error"){
	sendAlertEmail($sessionID,$callerID);
}
?>
