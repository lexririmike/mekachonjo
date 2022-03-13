<?php

namespace App\Chonjopay;

use App\Models\Chonjopayhistory;
use Illuminate\Database\Eloquent\Model;
use Mekachonjo\Payment\Facades\Chonjopay;

class Kernel extends \Mekachonjo\Payment\Abstractions\Kernel
{
    /**
     * @inheritDoc
     */
    public function commands(): array
    {
        return [
            //
        ];
    }
    /**
     * @inheritDoc
     */
    public function breakers(): array
    {
        return [
            //
        ];
    }

    /**
     * @inheritDoc
     */
    public function getGainer(): Model
    {
        $cpay = Chonjopay::getUser();

        return Chonjopayhistory::firstOrCreate([
			'chonjoid_id' => $cpay->id
		]);
    }
}
