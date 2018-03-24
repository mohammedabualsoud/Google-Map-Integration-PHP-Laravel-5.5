<?php
/**
 * Created by PhpStorm.
 * User: mohammed
 * Date: 24/03/18
 * Time: 14:36
 * This service is used to communicate with googlemap api
 * https://maps.googleapis.com/maps/api/place/
 *
 */

namespace App\Services;
use \GuzzleHttp\Client;
use Psr\Log\InvalidArgumentException;

class GoogleMapService
{
    // Api Key of the service
    protected $_apiKey;
    // Endpoint of the request.
    protected $_endPoint;
    // Query string parameters to sent over the request
    protected $_parameters = [];
    // Intended format of the response.
    protected $_format = 'json';

    const BASE_URL = 'https://maps.googleapis.com/maps/api/place/';

    public function __construct(String $apiKey)
    {
        if (!is_string($apiKey)) {
            throw new InvalidArgumentException('apiKey must be string');
        }
        $this->setApiKey($apiKey);
    }

    /**
     * @param $endPoint
     * @return $this
     */
    public function setEndPoint($endPoint)
    {
        $this->_endPoint = $endPoint;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getEndpoint() {
      return $this->_endPoint;
    }

    /**
     * @param $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getApiKey() {
        return $this->_apiKey;
    }

    /**
     * add query string parameter within the request
     * @param String $parameter
     * @param $value
     * @return $this
     */
    public function addParameter(String $parameter, $value) {

        if (!is_string($parameter)) {
            throw new \InvalidArgumentException('$parameter must be string');
        }
        $this->_parameters[$parameter] = $value;
        return $this;
    }

    /**
     * Set the query string parameters.
     * @param array $parameters: Dictionary of key value pairs.
     * @return $this
     */
    public function setParameters(Array $parameters) {

        if (!is_array($parameters)) {
            throw new \InvalidArgumentException('$parameters must be Dictionary');
        }

        foreach ($parameters as $key => $value) {
            $this->addParameter($key, $value);
        }
        return $this;
    }

    /**
     * Reset the query string parameters.
     * @return $this
     */
    protected function reset() {
        $this->_parameters = [];
        return $this;
    }

    /**
     * @return array
     */
    protected function getParameters() {
        return $this->_parameters;
    }

    /**
     * set the intended response format
     * @param String $responseFormat: possible values (json|xml)
     * @return $this
     */
    public function format(String $responseFormat = 'json')
    {
        if ($responseFormat) {
            $this->_format = $responseFormat;
        }
        // TODO:: Add support for xml if needed.
        return $this;
    }

    /**
     * @return string
     */
    protected function getFormat() {
        return $this->_format;
    }

    /**
     * Query String builder
     * @return string
     */
    private function buildQuery()
    {
        $qs = '';
        $paramCount = sizeof($this->getParameters());
        $idx = 0;
        foreach ($this->getParameters() as $param => $value) {
            if ($value) {
                $qs .= "${param}=${value}";
                if ($idx !== $paramCount - 1) {
                    $qs .= '&';
                }
            }
            $idx++;
        }
        return $qs;
    }

    /**
     * Format the received response.
     * @param $body
     * @return mixed
     */
    protected function formatResponse($body) {
        if ($this->getFormat() === 'json') {
            return json_decode($body);
        }
        return $body;
    }

    /**
     * Dispatch the HTTP request and return the data.
     * @return array
     */
    public function getData() {

        $this->addParameter('key', $this->getApiKey());
        $qs = $this->buildQuery();
        $uri = self::BASE_URL . $this->getEndpoint() . '/' . $this->getFormat() .
            ($qs ? "?${qs}" : '');

        $client = new Client();
        $res = $client->request('GET', $uri);
        // reset the parameters.
        $this->reset();

        return [
            'status' => $res->getStatusCode(),
            'body' => $this->formatResponse($res->getBody())
        ];
    }

}