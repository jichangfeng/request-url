<?php

/**
 * 请求URL
 * 
 * @author Changfeng Ji <jichf@qq.com>
 */
class RequestUrl {

    private $cookieFile = '';
    private $gzip = false;
    private $userAgent = '';
    private $referer = '';
    private $timeout = 10;
    private $connectTimeout = 5;
    private $headers = array();
    private $params = array();
    private $proxy = array();
    private static $instance;

    /**
     * 单例模式
     * 
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 启用或禁用Cookie
     * 
     * @param string $cookieFile Cookie文件，若不为空代表启用Cookie，默认为空代表禁用Cookie:
     *  - Cookie文件将用于读和写Cookie设置
     *  - Cookie文件将保存在系统临时目录下的 requesturl-cookie 目录里
     *  - Cookie文件参数示例：abcdefg.tmp
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function enableCookie($cookieFile) {
        $this->cookieFile = $cookieFile;
        return $this;
    }

    /**
     * 启用或禁用GZIP压缩
     * 
     * @param boolean $gzip 是否启用GZIP压缩，默认为FALSE
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function enableGzip($gzip) {
        $this->gzip = $gzip;
        return $this;
    }

    /**
     * 设置 User-Agent，用于表明自己的身份（是哪种浏览器）
     * 
     * @param sring $userAgent User-Agent，默认为空
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * 设置请求来源，用于表明自己是从哪个网页URL获得点击当前请求中的网址/URL
     * 
     * @param string $referer 请求来源，默认为空
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function setReferer($referer) {
        $this->referer = $referer;
        return $this;
    }

    /**
     * 设置执行超时时间
     * 
     * @param int $timeout 执行超时时间，单位为秒，默认为 10
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * 设置连接超时时间
     * 
     * @param int $connectTimeout 连接超时时间，单位为秒，默认为 5
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function setConnectTimeout($connectTimeout) {
        $this->connectTimeout = $connectTimeout;
        return $this;
    }

    /**
     * 设置请求头
     * 
     * @param array $headers 头信息，默认为空数组
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function setHeaders($headers) {
        if (!is_array($headers)) {
            return $this;
        }
        foreach ($headers as $key => $value) {
            if (!is_numeric($key)) {
                $headers[$key] = $key . ': ' . $value;
            }
        }
        $this->headers = array_values($headers);
        return $this;
    }

    /**
     * 设置请求参数
     * 
     * @param array|string $params 参数，格式为数组或JSON字符串或XML字符串...，默认为空
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    /**
     * 设置代理
     * 
     * @param string $proxy
     * @param string $proxyPort
     * @param string $proxyUserPwd
     * @param string $proxyType
     * @param string $proxyAuth
     * @return self
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function setProxy($proxy, $proxyPort, $proxyUserPwd = '', $proxyType = CURLPROXY_HTTP, $proxyAuth = CURLAUTH_BASIC) {
        $this->proxy = array(
            'PROXY' => $proxy,
            'PROXYPORT' => $proxyPort,
            'PROXYUSERPWD' => $proxyUserPwd,
            'PROXYTYPE' => $proxyType,
            'PROXYAUTH' => $proxyAuth
        );
        return $this;
    }

    /**
     * GET 请求
     * 
     * @param string $url URL地址
     * @return array
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function get($url) {
        return $this->request('get', $url);
    }

    /**
     * POST 请求
     * 
     * @param string $url URL地址
     * @return array
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function post($url) {
        return $this->request('post', $url);
    }

    /**
     * PUT 请求
     * 
     * @param string $url URL地址
     * @return array
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function put($url) {
        return $this->request('put', $url);
    }

    /**
     * DELETE 请求
     * 
     * @param string $url URL地址
     * @return array
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function delete($url) {
        return $this->request('delete', $url);
    }

    /**
     * 请求
     * 
     * @param string $method HTTP请求方法
     * @param string $url URL地址
     * @return array
     * @author Changfeng Ji <jichf@qq.com>
     */
    private function request($method, $url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if (stripos($url, "https://") !== false) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            //curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        if ($this->cookieFile) {
            $cookieDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'requesturl-cookie';
            if (!is_dir($cookieDir)) {
                mkdir($cookieDir);
            }
            $cookieFile = $cookieDir . DIRECTORY_SEPARATOR . $this->cookieFile;
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);
        }
        if ($this->gzip) {
            $this->headers[] = 'Accept-Encoding: gzip, deflate';
            curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        }
        if ($this->userAgent) {
            curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
        }
        if ($this->referer) {
            curl_setopt($curl, CURLOPT_REFERER, $this->referer);
        }
        if ($this->timeout) {
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        }
        if ($this->connectTimeout) {
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        switch ($method) {
            case 'get':
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                curl_setopt($curl, CURLOPT_URL, $url . ($this->params ? '?' . http_build_query($this->params) : ''));
                break;
            case 'post':
                curl_setopt($curl, CURLOPT_POST, true);
                if ($this->params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
                }
                break;
            case 'put':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($this->params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
                }
                break;
            case 'delete':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                if ($this->params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
                }
                break;
        }
        foreach ($this->proxy as $key => $value) {
            if (!$value) {
                continue;
            }
            curl_setopt($curl, constant('CURLOPT_' . $key), $value);
        }
        $response = curl_exec($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = false;
        $body = false;
        if (is_numeric($headerSize)) {
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
        }
        $info = array(
            'url' => curl_getinfo($curl, CURLINFO_EFFECTIVE_URL),
            'httpCode' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
            'contentType' => curl_getinfo($curl, CURLINFO_CONTENT_TYPE),
            'contentTypeDownload' => curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD),
            'sizeDownload' => curl_getinfo($curl, CURLINFO_SIZE_DOWNLOAD),
            'errno' => curl_errno($curl),
            'error' => curl_error($curl),
            'proxy' => $this->proxy ? true : false
        );
        curl_close($curl);
        $this->reset();
        return array('header' => $header, 'body' => $body, 'info' => $info);
    }

    /**
     * 解析头信息为数组键值对形式
     * 
     * @param string $header 头信息
     * @return array
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function parseHeader($header) {
        if (!$header) {
            return array();
        }
        $headers = str_replace("\r\n", "\n", $header);
        $headers = preg_replace('/\n[ \t]/', ' ', $headers);
        $headers = explode("\n", $headers);
        array_shift($headers);
        $return = array();
        foreach ($headers as $header) {
            if (!$header || strpos($header, ':') === false) {
                continue;
            }
            list($key, $value) = explode(':', $header, 2);
            $value = trim($value);
            preg_replace('#(\s+)#i', ' ', $value);
            $return[$key] = $value;
        }
        return $return;
    }

    /**
     * 重置请求配置
     * 
     * @author Changfeng Ji <jichf@qq.com>
     */
    public function reset() {
        $this->cookieFile = '';
        $this->gzip = false;
        $this->userAgent = '';
        $this->referer = '';
        $this->timeout = 10;
        $this->connectTimeout = 5;
        $this->headers = array();
        $this->params = array();
        $this->proxy = array();
    }

}
