<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @category  ZendService
 * @package   ZendService_Google\Gcm
 */

namespace YuraDev\NotifyDispatcher\NotificationChannels;

use RuntimeException;
use ZendService\Google\Exception;
// use Zend\Http\Client as HttpClient;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Zend\Json\Json;
use ZendService\Google\Gcm\Message;

/**
 * Google Cloud Messaging Client
 * This class allows the ability to send out messages
 * through the Google Cloud Messaging API.
 *
 * @category   ZendService
 * @package    ZendService_Google
 * @subpackage Gcm
 */
class Client
{
    /**
     * @const string Server URI
     */
    const SERVER_URI = 'https://fcm.googleapis.com/fcm/send';

    /**
     * @var Zend\Http\Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * Get API Key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set API Key
     *
     * @param string $apiKey
     * @return Client
     * @throws InvalidArgumentException
     */
    public function setApiKey($apiKey)
    {
        if (!is_string($apiKey) || empty($apiKey)) {
            throw new Exception\InvalidArgumentException('The api key must be a string and not empty');
        }
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Get HTTP Client
     *
     * @return Zend\Http\Client
     */
    public function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new Client();
            // $this->httpClient->setOptions(array('strictredirects' => true));
        }
        return $this->httpClient;
    }

    /**
     * Set HTTP Client
     *
     * @param Zend\Http\Client
     * @return Client
     */
    public function setHttpClient(HttpClient $http)
    {
        $this->httpClient = $http;
        return $this;
    }

    /**
     * Send Message
     *
     * @param Message $message
     * @return Response
     * @throws Exception\RuntimeException
     */
    public function send( $message)
    {
        $client = new HttpClient();

    try {
        $data = [
            'headers' => [
                'Authorization' => 'key=' . $this->getApiKey(),
                'Content-Type' => 'application/json'
            ],
            'json' => $message // Assuming you have a toArray method on your Message class
        ];
        $response = $client->post(self::SERVER_URI, $data);

        return $this->handleResponse($response, $message);
        } catch (RequestException $e) {
            throw new RuntimeException('Request error: ' . $e->getMessage());
        }
    }

    private function handleResponse(ResponseInterface $response,  $message)
{
    $statusCode = $response->getStatusCode();
    $responseBody = (string) $response->getBody();

    switch ($statusCode) {
        case 500:
            throw new RuntimeException('500 Internal Server Error');
            break;
        case 503:
            $exceptionMessage = '503 Server Unavailable';
            if ($retryHeader = $response->getHeaderLine('Retry-After')) {
                $exceptionMessage .= '; Retry After: ' . $retryHeader;
            }
            throw new RuntimeException($exceptionMessage);
            break;
        case 401:
            throw new RuntimeException('401 Forbidden; Authentication Error');
            break;
        case 400:
            throw new RuntimeException('400 Bad Request; invalid message');
            break;
    }

    $decodedResponse = json_decode($responseBody, true);
    // dd($responseBody,);
    if ($decodedResponse === null) {
        throw new RuntimeException('Response body did not contain a valid JSON response');
    }
    return  $responseBody ;

    // return new Response($decodedResponse,  $message);
}
}
