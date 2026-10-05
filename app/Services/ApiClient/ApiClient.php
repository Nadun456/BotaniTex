<?php

namespace App\Services\ApiClient;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiClient
{

    public static function client()
    {
        $http = new \GuzzleHttp\Client([
            'verify' => false,
            'headers' => [
                'Accept-Encoding' => 'gzip, deflate',
            ],
        ]);
        return $http;
    }

    public function formatUrl($query)
    {
        $ip = empty($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_CF_CONNECTING_IP'];

        $endpoint = rtrim(config('auction.api_endpoint'), '/');

        $apiPassword = config('auction.api_password');
        $apiMethod = config('auction.method');
        $responseType = config('auction.response_type');

        $url = $endpoint . '/' . $apiMethod . '/?';

        $url .= 'ip=' . $ip . '&';

        if (!empty($responseType)) {
            $url .= $responseType . '&';
        }

        $url .= 'code=' . $apiPassword . '&';

        // $url .= 'sql=' . urlencode(preg_replace("/%25/", "%", $query));
        $url .= 'sql=' . $query;

        return $url;
    }

    public static function callAuctionApi($query, $cached = false)
    {
        try {
            $instance = new self();

            $url = $instance->formatUrl($query);

            if ($cached) {

                $cacheKey = md5($url);
                $cachedData = $instance->getCacheApiResponse($cacheKey);

                if ($cachedData !== null) {
                    return $cachedData;
                }
            }

            $response = self::client()->get($url, ['decode_content' => false,]);

            if ($response->getStatusCode() === 200) {
                $response = $response->getBody()->getContents();

                if (config('auction.method') == 'gzip') {
                    if (str_contains(config('auction.api_endpoint'), '144.76.203.145')) {
                        $response = gzinflate(substr($response, 10, -8));
                    } else {
                        $response = gzuncompress(preg_replace("/^\\x1f\\x8b\\x08\\x00\\x00\\x00\\x00\\x00/", "", $response));
                    }
                }

                $decodedResponse = json_decode($response, true);
                if ($cached) {
                    $instance->putApiResponseToCache($cacheKey, $decodedResponse);
                }
                return $decodedResponse;
            } else {
                Log::error("Unexpected status code: " . $response->getStatusCode());
            }
        } catch (ClientException $e) {
            // 4xx errors
            echo 'Client error: ' . $e->getMessage();
        } catch (ServerException $e) {
            // 5xx errors
            echo 'Server error: ' . $e->getMessage();
        } catch (ConnectException $e) {
            // Network errors (e.g., DNS issues, connection timeouts)
            echo 'Connection error: ' . $e->getMessage();
        } catch (RequestException $e) {
            // Any other request-related errors
            echo 'Request error: ' . $e->getMessage();
        } catch (\Exception $e) {
            // General exceptions
            echo 'General error: ' . $e->getMessage();
        }

        return [];
    }

    public function getCacheApiResponse($key)
    {
        $cachedData = Cache::get($key);

        return $cachedData;
    }

    public function putApiResponseToCache($key, $data, $period = 10)
    {
        Log::info("Put into cache");
        Cache::put($key, $data, now()->addMinutes($period));
    }
}
