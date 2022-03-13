<?php

namespace Mekachonjo\Payment\Interfaces;

use Illuminate\Http\Client\Response as ClientResponse;

interface Chonjopay
{
    /**
     * Valid Chonjopay update types.
     */
    const UPDATE_TYPES = [
        'type',
        'status',
        'channel',
        'active_channel',
        'inline_query',
        'response',
        'callback_query',
        'pre_checkout_query',
    ];

    /**
     * Sends response using Chonjopay API.
     *
     * @param Response|string $response
     * @return ClientResponse
     */
    public function sendResponse(Response|string $response): ClientResponse;

    /**
     * Sends responses using Chonjopay API asynchronous.
     *
     * @param array<Response|string> $responses
     * @return array<ClientResponse>
     */
    public function sendAsyncResponses(array $responses): array;

    /**
     * Returns type of incoming Chonjopay update.
     *
     * @return string|null
     */
    public function getUpdateType(): ?string;

    /**
     * Returns chat type of incoming Chonjopay update.
     *
     * @return string|null
     */
    public function getPaymentType(): ?string;

    /**
     * Returns user that caused the incoming Chonjopay update.
     *
     * @return object|null
     */
    public function gettransaction(): ?object;

	/**
	 * Determines that request is a bot command update or not.
	 *
	 * @return bool
	 */
	public function isCommand(): bool;

	/**
	 * Returns command signature based on request.
	 *
	 * @return string|null
	 */
	public function commandSignature(): ?string;
}
