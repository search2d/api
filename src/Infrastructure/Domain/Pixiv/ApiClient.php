<?php
declare(strict_types=1);

namespace Search2d\Infrastructure\Domain\Pixiv;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Search2d\Infrastructure\Domain\Pixiv\Data\Auth;
use Search2d\Infrastructure\Domain\Pixiv\Data\ErrorApi;
use Search2d\Infrastructure\Domain\Pixiv\Data\ErrorAuth;
use Search2d\Infrastructure\Domain\Pixiv\Data\IllustDetail;
use Search2d\Infrastructure\Domain\Pixiv\Data\IllustRanking;
use Search2d\Infrastructure\Domain\Pixiv\Data\UserDetail;

class ApiClient
{
    /** @var \GuzzleHttp\ClientInterface */
    private $client;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /** @var \JsonMapper */
    private $jsonMapper;

    /** @var \Search2d\Infrastructure\Domain\Pixiv\ApiToken */
    private $token;

    /** @var string[] */
    private $apiHeaders = [
        'User-Agent' => 'PixivAndroidApp/5.0.64 (Android 6.0; Google Nexus 5X - 6.0.0 - API 23 - 1080x1920)',
        'App-OS' => 'android',
        'App-OS-Version' => '6.0',
        'App-Version' => '5.0.64',
    ];

    /** @var string[] */
    private $imgHeaders = [
        'User-Agent' => 'PixivAndroidApp/5.0.64 (Android 6.0; Google Nexus 5X - 6.0.0 - API 23 - 1080x1920)',
        'Referer' => 'https://app-api.pixiv.net/',
        'Accept-Encoding' => 'identity',
    ];

    /**
     * @param \GuzzleHttp\ClientInterface $client
     * @param string $username
     * @param string $password
     * @param string $clientId
     * @param string $clientSecret
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(ClientInterface $client, string $username, string $password, string $clientId, string $clientSecret, LoggerInterface $logger)
    {
        // HTTP2サポート必須
        assert((curl_version()['features'] & CURL_VERSION_HTTP2) !== 0);

        $this->client = $client;
        $this->username = $username;
        $this->password = $password;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->logger = $logger;

        $this->jsonMapper = new \JsonMapper();
        $this->jsonMapper->bExceptionOnMissingData = true;
        $this->jsonMapper->bExceptionOnUndefinedProperty = false;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function requestApi(string $method, string $uri, array $options = []): ResponseInterface
    {
        assert(!isset($options[RequestOptions::HEADERS]));
        assert(!isset($options[RequestOptions::HTTP_ERRORS]));

        $token = $this->getToken();

        $options[RequestOptions::HEADERS] = array_merge(
            $this->apiHeaders,
            ['Authorization' => sprintf('Bearer %s', $token->getAccessToken())]
        );

        $options[RequestOptions::HTTP_ERRORS] = true;

        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (ClientException $exception) {
            $error = $this->decodeJson($exception->getResponse(), new ErrorApi());
            throw $exception;
        }

        return $response;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function requestAuth(string $method, string $uri, array $options = []): ResponseInterface
    {
        assert(!isset($options[RequestOptions::HEADERS]));
        assert(!isset($options[RequestOptions::HTTP_ERRORS]));

        $options[RequestOptions::HEADERS] = $this->apiHeaders;
        $options[RequestOptions::HTTP_ERRORS] = true;

        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (ClientException $exception) {
            $error = $this->decodeJson($exception->getResponse(), new ErrorAuth());
            throw $exception;
        }

        return $response;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function requestImg(string $method, string $uri, array $options = []): ResponseInterface
    {
        assert(!isset($options[RequestOptions::VERSION]));
        assert(!isset($options[RequestOptions::HEADERS]));
        assert(!isset($options[RequestOptions::HTTP_ERRORS]));

        $options[RequestOptions::VERSION] = '2.0';
        $options[RequestOptions::HEADERS] = $this->imgHeaders;
        $options[RequestOptions::HTTP_ERRORS] = true;

        try {
            $response = $this->client->request($method, $uri, $options);
        } catch (ClientException $exception) {
            throw $exception;
        }

        return $response;
    }

    /**
     * @return \Search2d\Infrastructure\Domain\Pixiv\ApiToken
     */
    private function getToken(): ApiToken
    {
        if ($this->token === null) {
            $this->signin();
            return $this->token;
        }

        if ($this->token->isExpired()) {
            $this->refresh();
            return $this->token;
        }

        return $this->token;
    }

    /**
     * @return void
     */
    private function signin(): void
    {
        $response = $this->requestAuth('POST', 'https://oauth.secure.pixiv.net/auth/token', [
            RequestOptions::FORM_PARAMS => [
                'username' => $this->username,
                'password' => $this->password,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'password',
                'get_secure_url' => 'true',
            ],
        ]);

        $auth = $this->decodeJson($response, new Auth());

        $this->token = ApiToken::create(
            $auth->response->access_token,
            $auth->response->refresh_token,
            $auth->response->expires_in
        );
    }

    /**
     * @return void
     */
    public function refresh(): void
    {
        $response = $this->requestAuth('POST', 'https://oauth.secure.pixiv.net/auth/token', [
            RequestOptions::FORM_PARAMS => [
                'refresh_token' => $this->token->getRefreshToken(),
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'refresh_token',
                'get_secure_url' => 'true',
            ],
        ]);

        $auth = $this->decodeJson($response, new Auth());

        $this->token = ApiToken::create(
            $auth->response->access_token,
            $auth->response->refresh_token,
            $auth->response->expires_in
        );
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param mixed $object
     * @return mixed
     */
    private function decodeJson(ResponseInterface $response, $object)
    {
        $contents = $response->getBody()->getContents();

        $data = json_decode($contents);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        try {
            return $this->jsonMapper->map($data, $object);
        } catch (\Exception $e) {
            throw new \RuntimeException('JSONデータのマッピングに失敗', 0, $e);
        }
    }

    /**
     * @param int $illustId
     * @return \Search2d\Infrastructure\Domain\Pixiv\Data\IllustDetail
     */
    public function getIllustDetail(int $illustId): IllustDetail
    {
        $response = $this->requestApi('GET', 'https://app-api.pixiv.net/v1/illust/detail', [
            RequestOptions::QUERY => [
                'illust_id' => $illustId,
            ],
        ]);

        /** @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustDetail $illustDetail */
        $illustDetail = $this->decodeJson($response, new IllustDetail());

        return $illustDetail;
    }

    /**
     * @param string $mode
     * @param null|string $date
     * @param null|int $offset
     * @param string $filter
     * @return \Search2d\Infrastructure\Domain\Pixiv\Data\IllustRanking
     */
    public function getIllustRanking(string $mode, ?string $date = null, ?int $offset = null, string $filter = 'for_android'): IllustRanking
    {
        $query = [
            'mode' => $mode,
            'filter' => $filter,
        ];

        if (!is_null($date)) {
            $query['date'] = $date;
        }

        if (!is_null($offset)) {
            $query['offset'] = $offset;
        }

        $response = $this->requestApi('GET', 'https://app-api.pixiv.net/v1/illust/ranking', [
            RequestOptions::QUERY => $query,
        ]);

        /** @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustRanking $illustRanking */
        $illustRanking = $this->decodeJson($response, new IllustRanking());

        return $illustRanking;
    }

    /**
     * @param string $next
     * @return \Search2d\Infrastructure\Domain\Pixiv\Data\IllustRanking
     */
    public function getIllustRankingNext(string $next): IllustRanking
    {
        $response = $this->requestApi('GET', $next);

        /** @var \Search2d\Infrastructure\Domain\Pixiv\Data\IllustRanking $illustRanking */
        $illustRanking = $this->decodeJson($response, new IllustRanking());

        return $illustRanking;
    }

    /**
     * @param int $userId
     * @param string $filter
     * @return \Search2d\Infrastructure\Domain\Pixiv\Data\UserDetail
     */
    public function getUserDetail(int $userId, string $filter = 'for_android'): UserDetail
    {
        $response = $this->requestApi('GET', 'https://app-api.pixiv.net/v1/user/detail', [
            RequestOptions::QUERY => [
                'user_id' => $userId,
                'filter' => $filter,
            ],
        ]);

        /** @var \Search2d\Infrastructure\Domain\Pixiv\Data\UserDetail $userDetail */
        $userDetail = $this->decodeJson($response, new UserDetail());

        return $userDetail;
    }
}