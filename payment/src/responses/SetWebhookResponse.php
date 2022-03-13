<?php

namespace Mekachonjo\Payment\Responses;

use Mekachonjo\Payment\Interfaces\Response;

class SetWebhookResponse implements Response
{
    /**
     * @inheritDoc
     */
    public function method(): string
    {
        return 'setWebhook';
    }

    /**
     * @inheritDoc
     */
    public function data(): array
    {
        return [
            'url' => route(config('chonjopay.update-route'))
        ];
    }
}
