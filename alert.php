<?
$xmlData = <<<XML
<?xml version="1.0"?>
	<PolycomIPPhone>
	<IncomingCallEvent>
	<PhoneIP>172.16.99.185</PhoneIP>
	<MACAddress>0004f2173dbb</MACAddress>
	<CallingPartyName/>
	<CallingPartyNumber>sip:4074740214@172.16.20.3</CallingPartyNumber>
	<CalledPartyName>jdyer</CalledPartyName>
	<CalledPartyNumber>sip:jdyer@corpsip.voxeo.com</CalledPartyNumber>
	<TimeStamp>2010-05-21T14:57:39-05:00</TimeStamp>
	</IncomingCallEvent>
	</PolycomIPPhone>
XML;
 

$xml = simplexml_load_string(@file_get_contents('php://input'));
//$xml = simplexml_load_string($xmlData);

$IncomingCallEvent = $xml->IncomingCallEvent[0]->IncomingCallEvent;   
	$TimeStamp = $xml->IncomingCallEvent[0]->TimeStamp;   
$CallingPartyName = $xml->IncomingCallEvent[0]->CallingPartyName;   
$PhoneIP = $xml->IncomingCallEvent[0]->PhoneIP;
	$CallingPartyNumber =  $xml->IncomingCallEvent[0]->CallingPartyNumber;
	$CallingPartyNumber =  preg_split("/:/",$CallingPartyNumber);
	$CallingPartyNumber = preg_split("/@/",$CallingPartyNumber[1]);
	$CallingPartyNumber = $CallingPartyNumber[0];

// Logging
include('lib/functions.php');
cleanLogDir($config['days_to_log'],'*.csv','../logs/');
date_default_timezone_set($config['time_zone']);

$myFile 		=	"logs/LOGS__".date('Y-m-d-H00').".csv";
$year 			=	date('Y');
$timeStamp	=	date('H:i:s');

if(!file_exists($myFile)){
	touch ($myFile);
	$fh = fopen($myFile, 'a');
	fwrite($fh,"CALL LOGS\n");
	fwrite($fh,"CDR - "	.	date('H00')	.	" GMT "	.	$year	.	"\n\n");
	fwrite($fh,"TimeStamp,CallerID,CallerName\n");
	fwrite($fh,
		$TimeStamp				.",".
		$CallingPartyNumber				.",".
		$CallingPartyName					."\n"
		);
	fclose($fh);
	
}else{
	$fh = fopen($myFile, 'a');
	fwrite($fh,
		$TimeStamp				.",".
		$CallingPartyNumber				.",".
		$CallingPartyName					."\n"
		);
	fclose($fh);
	}

//Alerting

exec('/usr/local/bin/growlnotify -n Phone -a Telephone -t "Phone Call" -m ' . $CallingPartyNumber.'');	

?>