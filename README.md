# Web-Request-Logger

This simple PHP request logger will send you a telegram with all the requested information. Useful to detect Server Side Request. Support GET and POST request.

Install:
1. Add your telegram keys from https://my.telegram.org/apps to index.php
2. Upload index.php to any PHP server.




## Now all HTTP request will be send to your telegram account with this format:

New Request to Request Log
```
The IP: 64.233.190.102
The URL request was made to: /index.php 
The request REFERER was: 
The User Agent was: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:63.0) Gecko/20100101 Firefox/63.0
```

All Headers:
```
POST /index.php HTTP/1.1
HOST: logserver.com
Connection: Keep-Alive 
Proxy-Connection: Keep-Alive  
X-Forwarded-Proto: https 
X-Real-IP: 64.233.190.102
X-Forwarded-For: 64.233.190.102
Content-Length: 9 
User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:63.0) Gecko/20100101 Firefox/63.0 
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8 
Accept-Language: en-US,en;q=0.5 
Accept-Encoding: gzip, deflate 
DNT: 1 
Upgrade-Insecure-Requests: 1 

TEST=TEST
```

```
File Log Link
ISP Details Link
Threatcrowd IP Details
Censys IP Details
Robtex IP Details
Threatminer IP Details
```
