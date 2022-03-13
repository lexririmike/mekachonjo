<?php

namespace Mekachonjo\Payment\Commands;

use Exception;
use Illuminate\Console\Command;
use Mekachonjo\Payment\Facades\ChonjoPay;
use Mekachonjo\Payment\Responses\SetWebhookResponse;
use Symfony\Component\HttpFoundation\Response;

class SetWebhookCommand extends Command
{
    protected $signature = 'pay:set-webhook';

    protected $description = 'Sets ChonjoPay webhook.';

    public function handle()
    {
        try {
            $response = Chonjopay::sendResponse(SetWebhookResponse::class);

            if ($response->status() !== Response::HTTP_OK) {
                throw new Exception($response->object()->description ?? 'An error has occurred.');
            }

            $this->info('Webhook set successfully.');
        }
        catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
