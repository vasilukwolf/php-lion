<?php

namespace Lion;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Vasilyuk Dmitry
 * Class ApiConnector
 * Client for connecting to the user
 * API that hides the Guzzle layer
 * @package Lion
 */
class ApiConnector
{
    protected $client;
    protected $token;

    /**
     * ApiConnector constructor.
     * @param array $config Client configuration settings include the options
     * from Guzzle client.
     */
    public function __construct($config = [])
    {
        $this->client = new Client(
            array_merge(
                ['base_uri' => ''], // Required parameter for the connector to work correctly.
                $config
            )
        );
    }

    /**
     * This method is needed to authorize the API by login and password with
     * obtaining a token and further use when calling other API methods.
     * @param $login string The name of the account to which the api will be accessed.
     * @param $password string The password of the account to which the api will be accessed.
     * @throws GuzzleException
     * @throws LionException
     */
    public function login($login, $password): void
    {
        $resp = $this->client->request(
            'GET',
            '/auth',
            ['auth' => [$login, $password]]
        );
        $data = self::decodeResponse($resp);
        $this->token = $data['token'];
    }

    /**
     * Checks the response status for correctness and
     * returns a decoded array from JSON if successful.
     * @param ResponseInterface $resp
     * @return array
     * @throws LionException
     */
    protected static function decodeResponse(ResponseInterface $resp): array
    {
        $statusCode = $resp->getStatusCode();
        $JSON = $resp->getBody()->getContents();
        $data = json_decode($JSON, true);
        $status = $data['status'] ?? '';
        if (200 === $statusCode && 'OK' === $status) {
            return $data;
        }

        if (200 !== $statusCode) {
            throw new LionException('Invalid response code');
        }

        throw new LionException('Invalid status value');
    }

    /**
     * This method receives user data by username
     * and token after authorization
     * @param $username string The name of the account to which the api will be accessed.
     * @return User Returns an instance of user data by username and token
     * @throws LionException User access statuses
     * @throws GuzzleException
     * @throws LionException
     */
    public function getUser($username): User
    {
        $resp = $this->client->request(
            'GET',
            '/get-user/' . $username,
            ['query' => ['token' => $this->token]]
        );
        $data = self::decodeResponse($resp);

        return new User($data);
    }
}
