<?php

namespace Mekachonjo\Payment\Traits;

use Mekachonjo\Payment\Casts\Serializable;

trait ChonjopayGainer
{
    /**
     * Initializes trait
     *
     * @return void
     */
    public function initializeChonjopayGainer()
    {
        static::mergeFillable([
            'chonjopay_id',
            'handler'
        ]);

        static::mergeCasts([
            'chonjopay_id' => 'integer',
            'handler' => Serializable::class
        ]);
    }
}
