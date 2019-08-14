# Request Url for PHP

RequestUrl is a PHP HTTP client library.

RequestUrl allows you to send **GET**, **POST**, **PUT**, **DELETE** HTTP requests.
You can enable cookie, enable gzip, set user-agent, set referer, set timeout, set connect timeout and set proxy.
You can add headers, form data, and parameters with simple arrays, and access the response data in the same way.

RequestUrl uses cURL, but abstracts all the nasty stuff out of your way, providing a simple API.

# Install
```composer require jichangfeng/request-url```

# Usage
```php
// Complex request
$result = RequestUrl::getInstance()
        ->enableCookie('test.tmp')
        ->enableGzip(true)
        ->setUserAgent('Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36')
        ->setReferer('https://example.com')
        ->setTimeout(10)
        ->setConnectTimeout(5)
        ->setHeaders(array('Accept' => 'application/json'))
        ->setParams(array('name' => 'admin', 'password' => '123456'))
        ->setProxy('127.0.0.1', 1080)
        ->post('https://www.example.com/user/login');

// Simple request
$result = RequestUrl::getInstance()->setParams(array('key' => 'value'))->post('https://www.example.com');
$result = RequestUrl::getInstance()->get('https://www.example.com');

var_dump($result['body']);
// string '[...]'... (length=1270)

var_export($result['header']);
/*
'HTTP/2 200 
cache-control: max-age=604800
content-type: text/html; charset=UTF-8
date: Tue, 13 Aug 2019 10:26:21 GMT
etag: "1541025663+ident"
expires: Tue, 20 Aug 2019 10:26:21 GMT
last-modified: Fri, 09 Aug 2013 23:54:35 GMT
server: ECS (dcb/7F38)
vary: Accept-Encoding
x-cache: HIT
content-length: 1270

'
*/

$headers = RequestUrl::getInstance()->parseHeader($result['header']);
var_export($headers);
/*
array (
  'cache-control' => 'max-age=604800',
  'content-type' => 'text/html; charset=UTF-8',
  'date' => 'Tue, 13 Aug 2019 10:26:21 GMT',
  'etag' => '"1541025663+ident"',
  'expires' => 'Tue, 20 Aug 2019 10:26:21 GMT',
  'last-modified' => 'Fri, 09 Aug 2013 23:54:35 GMT',
  'server' => 'ECS (dcb/7F38)',
  'vary' => 'Accept-Encoding',
  'x-cache' => 'HIT',
  'content-length' => '1270',
)
*/

var_export($result['info']);
/*
array (
  'url' => 'https://www.example.com',
  'httpCode' => 200,
  'contentType' => 'text/html; charset=UTF-8',
  'contentTypeDownload' => 1270.0,
  'sizeDownload' => 1270.0,
  'errno' => 0,
  'error' => '',
  'proxy' => false,
)
*/

```