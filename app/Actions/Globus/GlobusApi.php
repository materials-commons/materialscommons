<?php

namespace App\Actions\Globus;

use Carbon\Carbon;
use GuzzleHttp\Client;

class GlobusApi
{
    const AuthUrlBase = "https://auth.globus.org/v2";
    const TransferManagerUrlBase = "https://transfer.api.globus.org/v0.10";

    private $token;
    private $ccUser;
    private $ccPassword;
    private $client;

    public function __construct($ccUser, $ccPassword)
    {
        $this->ccUser = $ccUser;
        $this->ccPassword = $ccPassword;
        $this->client = new Client();
    }

    public static function createGlobusApi()
    {
        $ccUser = config('globus.cc_user');
        $ccPassword = config('globus.cc_token');
        $globusApi = new GlobusApi($ccUser, $ccPassword);
        $globusApi->authenticate();
        return $globusApi;
    }

    public function authenticate()
    {
        $url = self::AuthUrlBase."/oauth2/token";
        $resp = $this->client->request('POST', $url, [
            'query' => [
                'grant_type' => 'client_credentials',
                'scope'      => 'urn:globus:auth:scope:transfer.api.globus.org:all',
            ],
            'auth'  => [$this->ccUser, $this->ccPassword],
        ]);

        $result = json_decode($resp->getBody(), true);
        $this->token = $result['access_token'];
        return $result;
    }

    public function getEndpointTaskList($endpointId, $forPastDays)
    {
        $completionTime = Carbon::now()->add(-$forPastDays, 'day')->format('Y-m-d');

        $url = self::TransferManagerUrlBase."/endpoint_manager/task_list";
        $params = $this->createParams([
            'filter_endpoint'        => $endpointId,
            'filter_completion_time' => $completionTime,
            'filter_status'          => 'SUCCEEDED',
        ]);

        $resp = $this->client->request('GET', $url, $params);

        if ($resp->getStatusCode() === 401) {
            // Auth timeout
            $this->authenticate();
            $resp = $this->client->request('GET', $url, $params);
        }

        return json_decode($resp->getBody(), true);
    }

    public function getTaskSuccessfulTransfers($taskId, $marker = null)
    {
        $query = [];
        if (isset($marker) && $marker !== 0) {
            $query['marker'] = $marker;
        }

        $params = $this->createParams($query);
        $url = self::TransferManagerUrlBase."/endpoint_manager/task/{$taskId}/successful_transfers";
        $resp = $this->client->request('GET', $url, $params);

        if ($resp->getStatusCode() === 401) {
            $this->authenticate();
            $resp = $this->client->request('GET', $url, $params);
        }

        return json_decode($resp->getBody(), true);
    }

    public function getIdentities($users)
    {
        $usernames = implode(",", $users);

        $url = self::AuthUrlBase."/api/identities";
        $params = $this->createParams(['usernames' => $usernames]);
        $resp = $this->client->request('GET', $url, $params);

        if ($resp->getStatusCode() === 401) {
            $this->authenticate();
            $resp = $this->client->request('GET', $url, $params);
        }

        return json_decode($resp->getBody(), true);
    }

    public function addEndpointAclRule(EndpointAclRule $endpointAclRule)
    {
        $params = $this->createParams([]);
        $params['json'] = [
            'DATA_TYPE'      => 'access',
            'principal_type' => $endpointAclRule->principalType,
            'principal'      => $endpointAclRule->identityId,
            'path'           => $endpointAclRule->path,
            'permissions'    => $endpointAclRule->permissions,
        ];
        $url = self::TransferManagerUrlBase."/endpoint/{$endpointAclRule->endpointId}/access";
        $resp = $this->client->request('POST', $url, $params);
        if ($resp->getStatusCode() === 401) {
            $this->authenticate();
            $resp = $this->client->request('POST', $url, $params);
        }

        return json_decode($resp->getBody(), true);
    }

    public function deleteEndpointAclRule($endpointId, $accessId)
    {
        $params = $this->createParams([]);
        $url = self::TransferManagerUrlBase."/endpoint/${endpointId}/access/{$accessId}";
        $resp = $this->client->request('DELETE', $url, $params);
        if ($resp->getStatusCode() === 401) {
            $this->authenticate();
            $resp = $this->client->request('DELETE', $url, $params);
        }
        return json_decode($resp->getBody(), true);
    }

    public function getEndpointAccessRules($endpointId)
    {
        $params = $this->createParams([]);
        $url = self::TransferManagerUrlBase."/endpoint/{$endpointId}/access_list";
        $resp = $this->client->request('GET', $url, $params);
        if ($resp->getStatusCode() === 401) {
            $this->authenticate();
            $resp = $this->client->request('GET', $url, $params);
        }

        return json_decode($resp->getBody(), true);
    }

    private function createParams($queryParams)
    {
        return [
            'query'       => $queryParams,
            'headers'     => [
                'Authorization' => 'Bearer '.$this->token,
            ],
            'http_errors' => false,
        ];
    }
}