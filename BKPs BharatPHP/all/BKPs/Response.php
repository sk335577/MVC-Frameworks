<?php

namespace BharatPHP;

class Response
{

    /**
     * Response codes & messages
     * @var array
     */
    protected static $responseCodes = [
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',

        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',

        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',

        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',

        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required'
    ];

    /**
     * HTTP version for response, i.e. 1.0, 1.1, 2.0, 3.0, etc.
     * @var string
     */
    protected $version = 1.1;

    /**
     * Response code
     * @var int
     */
    protected $code = null;

    /**
     * Response message
     * @var string
     */
    protected $message = null;

    public function setCode($code = 200)
    {
        if (!array_key_exists($code, self::$responseCodes)) {
            // throw new \Pop\Http\Exception('The header code ' . $code . ' is not allowed.');
        }

        $this->code    = $code;
        $this->message = self::$responseCodes[$code];

        return $this;
    }


    /**
     * Get the response code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the response message
     *
     * @param  string $message
     * @return AbstractResponse
     */
    public function setMessage($message = null)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get the response HTTP message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }


    /**
     * Determine if the response is a success
     *
     * @return boolean
     */
    public function isSuccess()
    {
        $type = floor($this->code / 100);
        return (($type == 1) || ($type == 2) || ($type == 3));
    }

    /**
     * Determine if the response is a redirect
     *
     * @return boolean
     */
    public function isRedirect()
    {
        $type = floor($this->code / 100);
        return ($type == 3);
    }

    /**
     * Determine if the response is an error
     *
     * @return boolean
     */
    public function isError()
    {
        $type = floor($this->code / 100);
        return (($type == 4) || ($type == 5));
    }

    /**
     * Determine if the response is a client error
     *
     * @return boolean
     */
    public function isClientError()
    {
        $type = floor($this->code / 100);
        return ($type == 4);
    }

    /**
     * Determine if the response is a server error
     *
     * @return boolean
     */
    public function isServerError()
    {
        $type = floor($this->code / 100);
        return ($type == 5);
    }

    public static function json($response)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        exit;
    }



    /**
     * Prepare response body
     *
     * @param  boolean $length
     * @param  boolean $mb
     * @return string
     */
    // public function prepareBody($length = false, $mb = false)
    // {
    //     $body = $this->body->render();

    //     if ($this->hasHeader('Content-Encoding')) {
    //         $body = Parser::encodeData($body, strtoupper($this->getHeader('Content-Encoding')->getValue()));
    //         if ($length) {
    //             $this->addHeader('Content-Length', (($mb) ? mb_strlen($body) : strlen($body)));
    //         }
    //     } else if ($length) {
    //         $this->addHeader('Content-Length', (($mb) ? mb_strlen($body) : strlen($body)));
    //     }

    //     return $body;
    // }

    /**
     * Get the response headers as a string
     *
     * @param  boolean $status
     * @param  string  $eol
     * @return string
     */
    // public function getHeadersAsString($status = null, $eol = "\r\n")
    // {
    //     $httpStatus = ($status === true) ? "HTTP/{$this->version} {$this->code} {$this->message}" : $status;
    //     return parent::getHeadersAsString($httpStatus, $eol);
    // }

    /**
     * Send response headers
     *
     * @throws Exception
     * @return void
     */
    // public function sendHeaders()
    // {
    //     if (headers_sent()) {
    //         // throw new Exception('The headers have already been sent.');
    //     }

    //     header("HTTP/{$this->version} {$this->code} {$this->message}");
    //     foreach ($this->headers as $name => $value) {
    //         if ($value instanceof \Pop\Mime\Part\Header) {
    //             header((string)$value);
    //         } else {
    //             header($name . ": " . $value);
    //         }
    //     }
    // }

    /**
     * Send full response
     *
     * @param  int     $code
     * @param  array   $headers
     * @param  boolean $length
     * @return void
     */
    // public function send($code = null, array $headers = null, $length = false)
    // {
    //     if (null !== $code) {
    //         $this->setCode($code);
    //     }
    //     if (null !== $headers) {
    //         $this->addHeaders($headers);
    //     }

    //     $body = $this->prepareBody($length);

    //     $this->sendHeaders();
    //     echo $body;
    // }

    /**
     * Send full response and exit
     *
     * @param  int   $code
     * @param  array $headers
     * @param  boolean $length
     * @return void
     */
    // public function sendAndExit($code = null, array $headers = null, $length = false)
    // {
    //     $this->send($code, $headers, $length);
    //     exit();
    // }

    /**
     * Return entire response as a string
     *
     * @return string
     */
    // public function __toString()
    // {
    //     $body = $this->prepareBody();
    //     return $this->getHeadersAsString(true) . "\r\n" . $body;
    // }

    /**
     * Send redirect
     *
     * @param  string $url
     * @param  string $code
     * @param  string $version
     * @throws Exception
     * @return void
     */
    public static function redirect($url, $code = '302', $version = '1.1')
    {
        if (headers_sent()) {
            // throw new Exception('The headers have already been sent.');
        }

        if (!array_key_exists($code, self::$responseCodes)) {
            // throw new Exception('The header code ' . $code . ' is not allowed.');
        }

        header("HTTP/{$version} {$code} " . self::$responseCodes[$code]);
        header("Location: {$url}");
    }

    /**
     * Send redirect and exit
     *
     * @param  string  $url
     * @param  string  $code
     * @param  string  $version
     * @return void
     */
    public static function redirectAndExit($url, $code = '302', $version = '1.1')
    {
        static::redirect($url, $code, $version);
        exit();
    }

    /**
     * Get response message from code
     *
     * @param  int $code
     * @throws Exception
     * @return string
     */
    public static function getMessageFromCode($code)
    {
        if (!array_key_exists($code, self::$responseCodes)) {
            // throw new Exception('The header code ' . $code . ' is not valid.');
        }

        return self::$responseCodes[$code];
    }
}
