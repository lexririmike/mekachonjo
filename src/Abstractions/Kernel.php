<?php

namespace Mekachonjo\Payment\Abstractions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Mekachonjo\Payment\Facades\Chonjopay;
use Mekachonjo\Payment\ChonjopayRequest;
use ReflectionException;
use ReflectionMethod;

abstract class Kernel
{
    /**
     * Handles incoming Chonjopay update.
     *
     * @param Request $request
     * @throws Exception
     */
    public function handleUpdate(Request $request)
    {
        if (!Chonjopay::getUpdateType()) {
            throw new Exception('This request doesnt have any valid Chonjopay update.');
        }

        $gainer = $this->getGainer();

        /**
         * Handle update using available commands.
         */
        if ($command_signature = Chonjopay::commandSignature()) {
            foreach ($this->commands() as $command) {
                if ($command->signature === $command_signature) {
                    $command->handle($request, $gainer);

                    return;
                }
            }
        }

        /**
         * method that uses to handle update
         */
        $method = sprintf(
            'handle%s',
            Str::studly(Chonjopay::getUpdateType())
        );

        /**
         * Handle update using available breakers.
         */
        foreach ($this->breakers() as $breaker) {
            if (method_exists($breaker, $method) && $breaker->{$method}($request, $gainer)) {
                return;
            }
        }

        /**
         * Handle update using available handlers.
         */
        if ($gainer->handler) {
            $this->callHandlerMethod(
				$this->resolveHandler($gainer),
				$method,
				$request,
				$gainer
			);
        }
    }

	/**
	 * Resolves handler for specified Chonjopay gainer.
	 *
	 * @param Model $gainer
	 * @return mixed
	 */
	protected function resolveHandler(Model $gainer)
	{
		return is_object($gainer->handler) ? $gainer->handler : App::make($gainer->handler);
	}

	/**
	 * Returns validated request if exists, otherwise returns initial request.
	 *
	 * @param $handler
	 * @param string $method
	 * @param Request $request
	 * @return Request
	 * @throws ReflectionException
	 */
	protected function getValidatedRequest($handler, string $method, Request $request): Request
	{
		$parameters = (new ReflectionMethod($handler, $method))->getParameters();

		$request_type = $parameters[0]->getType();

		if ($request_type && is_subclass_of($request_type->getName(), ChonjopayRequest::class)) {
			$prepared_request = App::make($request_type->getName());
		}

		return $prepared_request ?? $request;
	}

	/**
	 * Calls update handler method on handler class.
	 *
	 * @param $handler
	 * @param string $method
	 * @param Request $request
	 * @param Model $gainer
	 */
	protected function callHandlerMethod($handler, string $method, Request $request, Model $gainer)
	{
		if (method_exists($handler, $method)) {
			try {
				$verified_request = $this->getValidatedRequest($handler, $method, $request);
			}
			catch (ReflectionException) {
				return;
			}

			$handler->{$method}(
				$verified_request,
				$gainer
			);
		}
	}

    /**
     * Get or create the gainer that handlers should work with
     *
     * @return Model
     */
    abstract public function getGainer(): Model;

    /**
     * An array of Chonjopay command classes
     *
     * @return array
     */
    abstract public function commands(): array;

    /**
     * An array of breakers classes that run before handlers and don't care about which handler should be used.
     * if all breakers returns false then handlers will execute
     *
     * @return array
     */
    abstract public function breakers(): array;
}
