<?php
namespace HedgebotApi;

use Curl\Curl;

/**
 * JSON-RPC HTTP API client. Simple and polyvalent remote procedure call protocol, using HTTP POST queries as a transport.
 * This client works partly as a singleton, and partly as an instancied client. The base client is a singleton, but
 * for each endpoint, a new instance will be created.
 */
class Client
{
    private $baseUrl; ///< Base webservice URL
    private $accessToken; ///< Access token
    private $endpoint; ///< Section name.
    private $id = 1; ///< Current query ID
    
    /**
     * Constructor. Allows to shortcut the base url and access token settings.
     * 
     * @param string|null $baseUrl The base url to the api server. Optional.
     * @param string|null $accessToken The access token to the api server. Optional.
     * @return void 
     */
    public function __construct($baseUrl = null, $accessToken = null)
    {
        $this->setBaseUrl($baseUrl);
        $this->setAccessToken($accessToken);
    }

    /**
     * Sets the security access token that will be used in all queries.
     * For security reasons, it is impossible to read it back once it has been entered.
     * 
     * @param string $token The access token.
     */
    public function setAccessToken($token)
    {
        $this->accessToken = $token;
    }

    /**
     * Sets the base URL for API calls.
     * 
     * @param string $url The base URL to set.
     */
    public function setBaseUrl($url)
    {
        $this->baseUrl = rtrim($url, '/');
    }

    /**
     * Sets the endpoint.
     * 
     * @param  string $name The endpoint name.
     * @return Client       A new client instance with the endpoint set.
     */
    public function endpoint($name)
    {
        $client = clone $this;
        $client->endpoint = ltrim($name, '/');
        
        return $client;
    }
    
    /**
     * Call an API method on the current endpoint.
     * 
     * @param  string $name The name of the called API method
     * @param  array  $args Array containing the arguments. If the array has as the only argument a NamedArgs instance, named args will be used.
     * @return mixed        The API method return.
     *
     * @throws RPCError
     */
    public function __call($name, $args)
    {
        if(empty($this->baseUrl)) {
            throw new ApiException('No base URL set.');
        }

        // Basic JSON-RPC call
        $data = ['jsonrpc' => '2.0', 'method' => $name, 'id' => $this->id++, 'params' => []];
        
        if (!empty($args)) {
            if (count($args) == 1 && $args[0] instanceof NamedArgs) {
                $data['params'] = $args[0]->toArray();
            } else {
                $data['params'] = $args;
            }
        }
        
        $query = new Curl();
        $query->setHeader('X-Token', $this->accessToken);
        $query->setHeader('Content-Type', 'application/json');
        $query->setHeader('Accept', 'application/json');
        
        $query->post($this->baseUrl.'/'. $this->endpoint, $data);
        
        if (!empty($query->response)) {
            // Check that the call succeeded
            if ($query->httpStatusCode == 200) {
                $json = $query->response;
                if (!empty($json->error)) {
                    throw new ApiException($json->error->message, $json->error->code);
                }
            } else {
                throw new ApiException($query->response, $query->httpStatusCode);
            }
            
            return $json->result;
        } elseif ($query->error == true) { // Handle errors
            throw new ApiException($query->errorMessage, $query->errorCode);
        }
    }
}
