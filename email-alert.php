<?php
## make sure to have mail() configure
//All info
$remoteIp = $_SERVER['REMOTE_ADDR'];
$remoteHost = $_SERVER['HTTP_HOST'];
$remoteRef = $_SERVER['HTTP_REFERER'];
$remoteUrl = $_SERVER['REQUEST_URI'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$yourEmailAddress = "panchofmx@gmail.com";
$servername = $_SERVER['SERVER_NAME'];
$emailSubject = "New request from: ".$remoteIp;
$method = $_SERVER['REQUEST_METHOD'];
$requestproto = $_SERVER['SERVER_PROTOCOL'];
$entityBody = file_get_contents('php://input');

//file-url
$date = new DateTime();
$filelocation = "dumprequest" . $date->format('Y-m-d%20H:i:sP') . ".txt";

//All headers
$headers = apache_request_headers();
$ApacheHeaders = '';
foreach ($headers as $header => $value) {
    $ApacheHeaders .= "$header: $value \n";
}

//Email message
$emailContent = "The URL Request was made to: $remoteUrl 
The request REFERER was: $remoteRef
The IP was $remoteIp
The User Agent was: $userAgent

All Headers: 

$method $remoteUrl $requestproto
$ApacheHeaders
$entityBody


Request file: http://$servername/$filelocation
ISP Details: https://www.ultratools.com/tools/ipWhoisLookupResult?ipAddress=$remoteIp";

// send the message
mail($yourEmailAddress, $emailSubject, $emailContent);



// https://gist.github.com/magnetikonline/650e30e485c0f91f2f40
class DumpHTTPRequestToFile {
	public function execute($targetFile) {
		$data = sprintf(
			"%s %s %s\n\nHTTP headers:\n",
			$_SERVER['REQUEST_METHOD'],
			$_SERVER['REQUEST_URI'],
			$_SERVER['SERVER_PROTOCOL']
		);
		foreach ($this->getHeaderList() as $name => $value) {
			$data .= $name . ': ' . $value . "\n";
		}
		$data .= "\nRequest body:\n";
		file_put_contents(
			$targetFile,
			$data . file_get_contents('php://input') . "\n"
		);
		echo("Not found!\n\n");
	}
	private function getHeaderList() {
		$headerList = [];
		foreach ($_SERVER as $name => $value) {
			if (preg_match('/^HTTP_/',$name)) {
				// convert HTTP_HEADER_NAME to Header-Name
				$name = strtr(substr($name,5),'_',' ');
				$name = ucwords(strtolower($name));
				$name = strtr($name,' ','-');
				// add to list
				$headerList[$name] = $value;
			}
		}
		return $headerList;
	}
}
(new DumpHTTPRequestToFile)->execute('./dumprequest.txt');

rename("dumprequest.txt", "dumprequest" . $date->format('Y-m-d H:i:sP') . ".txt");
