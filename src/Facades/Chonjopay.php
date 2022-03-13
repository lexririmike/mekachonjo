<?php

namespace Mekachonjo\Payment\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;

/**
 * @method static sendResponse(string|\Mekachonjo\Payment\Interfaces\Response $response)
 * @method static sendAsyncResponses(array $responses)
 * @method static getUpdateType
 * @method static getChatType
 * @method static getUser
 * @method static isCommand
 * @method static commandSignature
 */

class Chonjopay extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Mekachonjo\Payment\Interfaces\Chonjopay::class;
    }

    public static function handleUpdate(Request $request)
    {
        return App::make(\Mekachonjo\Payment\Abstractions\Kernel::class)->handleUpdate($request);
    }
}
