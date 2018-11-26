<?php

//All info
$remoteIp = $_SERVER['REMOTE_ADDR'];
$remoteHost = $_SERVER['HTTP_HOST'];
$remoteRef = $_SERVER['HTTP_REFERER'];
$remoteUrl = $_SERVER['REQUEST_URI'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$servername = $_SERVER['SERVER_NAME'];
$emailSubject = "New request from: ".$remoteIp;
$method = $_SERVER['REQUEST_METHOD'];
$requestproto = $_SERVER['SERVER_PROTOCOL'];
$entityBody = file_get_contents('php://input');

//All headers
$headers = apache_request_headers();
$ApacheHeaders = '';
foreach ($headers as $header => $value) {
    $ApacheHeaders .= "$header: $value \n";
}

if( !function_exists('apache_request_headers') ) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';

        foreach($_SERVER as $key => $val) {
            if( preg_match($rx_http, $key) ) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
           // do some nasty string manipulations to restore the original letter case
           // this should work in most cases
                $rx_matches = explode('_', $arh_key);

                if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    }

                    $arh_key = implode('-', $rx_matches);
                }

                $arh[$arh_key] = $val;
            }
        }

        return( $arh );
    }
}

//file-url
$date = new DateTime();
$filelocation = "dumprequest" . $date->format('Y-m-d%20H:i:sP') . ".txt";


// Telegram Message
$apiToken = "ADD-YOU-TELEGRAM-KEY;
$data = [
    'chat_id' => 'ADD-TELEGRAM-ID',
    'parse_mode' => 'Markdown',
    'text' => "*New Request to Request Log*
The IP: `$remoteIp`
The URL request was made to: `$remoteUrl `
The request REFERER was: `$remoteRef`
The User Agent was: `$userAgent`


*All Headers:*
```
$method $remoteUrl $requestproto
$ApacheHeaders
$entityBody
```

[File Log Link](http://$servername/$filelocation)
[ISP Details Link](https://fullip.info/service/lookup/$remoteIp/)
[Threatcrowd IP Details](https://www.threatcrowd.org/ip.php?ip=$remoteIp/)
[Censys IP Details](https://www.censys.io/ipv4/$remoteIp)
[Robtex IP Details](https://www.robtex.com/ip-lookup/$remoteIp/)
[Threatminer IP Details](https://www.threatminer.org/host.php?q=$remoteIp)"
];

$response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );


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
