<?php

namespace Mekachonjo\Payment\Services;

use Exception;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Mekachonjo\Payment\Interfaces\Response;
use Mekachonjo\Payment\Traits\HasReplyMarkup;

class Chonjopay implements \Mekachonjo\Payment\Interfaces\Chonjopay
{
    public string $baseUrl;

    /**
     * @throws Exception
     */
    public function __construct(public Request $request)
    {
        $this->baseUrl = $this->getBaseUrl();
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function sendResponse(Response|string $response): ClientResponse
    {
        return $this->getPreparedRequest($response);
    }

    /**
     * @inheritDoc
     */
    public function sendAsyncResponses(array $responses): array
    {
        return Http::pool(fn(Pool $pool) => array_map(
            fn (Response $response) => $this->getPreparedRequest($response, $pool),
            $responses
        ));
    }

    /**
     * Returns base URL for sending response using Chonjopay API.
     *
     * @return string
     * @throws Exception
     */
    private function getBaseUrl(): string
    {
        $api_key = config('chonjopay.api_key');
        $secret = config('chonjopay.secret');
       // $timestampHeader = config('chonjopay.timestampHeader');
        
        $window = config('chonjopay.window');


        if (!$api_key && !$secret) {
            throw new Exception('Chonjopay API Key  and Secret is not set. ');
        }
        $tokenHeader = $this->getToken($api_key,$secret);

        return sprintf('http://178.182.161.10f/api/v1/%s',$tokenHeader);
    }

    private function getToken($api,$secret): string
    {
        $client = new \GuzzleHttp\Client();
        // URL
        $apiURL = 'http://178.182.161.10f/api/v1/';
        // Headers
        $headers = [
            'apikey' =>$api,
            'secret' =>$secret
        ];
        $body = [
            //....
        ];
        $response = $client->request('GET', $apiURL, ['form_params' => $body, 'headers' => $headers]);
     
        $responseBody = json_decode($response->getBody(), true);
        return $responseBody["token"];

    }

    /**
     * Returns resolved response
     *
     * @param Response|string $response
     * @return Response
     */
    public function getResolvedResponse(Response|string $response): Response
    {
        return is_string($response) ? App::make($response) : $response;
    }

    /**
     * Merges all needed parameters to return response body
     *
     * @param Response $response
     * @return array
     */
    public function getResponseBody(Response $response): array
    {
        return in_array(HasReplyMarkup::class, class_uses_recursive($response))
            ? $response->resolveWithReplayMarkup()
            : $response->data();
    }

    public function getPreparedRequest(Response|string $response, ?Pool $client = null): array|ClientResponse
    {
        $pending_request = $client ? $client->baseUrl($this->baseUrl) : Http::baseUrl($this->baseUrl);

        $resolved_response = $this->getResolvedResponse($response);

        return $pending_request->post($resolved_response->method(), $this->getResponseBody($resolved_response));
    }

    /**
     * @inheritDoc
     */
    public function getUpdateType(): ?string
    {
        return collect($this::UPDATE_TYPES)
            ->intersect($this->request->keys())
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getPaymentType(): ?string
    {
        $update_type = $this->getUpdateType();

        return match($update_type) {
            'type', 'satus' => $this->request->input(sprintf('%s.payment.type', $update_type)),
            'channel', 'active_channel' => 'transaction',
            'inline_query',  'callback_query', 'response', 'pre_checkout_query' => 'private',
            
        };
    }

    /**
     * @inheritDoc
     */
    public function gethistory(): ?object
    {
        $update_type = $this->getUpdateType();

        $match = match($update_type) {
            default => $this->request->input(sprintf('%s.from', $update_type)),
        };

        return $match ? (object) $match : null;
    }

	/**
	 * @inheritDoc
	 */
	public function isCommand(): bool
	{
		$types = $this->request->input('payment.entities.*.type');

		return $types && count($types) && in_array('chonjo_command', $types);
	}

	/**
	 * @inheritDoc
	 */
	public function commandSignature(): ?string
	{
		if ($this->isCommand()) {
			$text = $this->request->input('type.amount');

			return substr(
				explode(' ', $text)[0],
				1
			);
		}

		return null;
	}
}
