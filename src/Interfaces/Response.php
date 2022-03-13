<?php

namespace Mekachonjo\Payment\Interfaces;

interface Response
{
    /**
     * Chonjopay API method.
     *
     * @return string
     */
    public function method(): string;

    /**
     * Data to use for sending response using Chonjopay API.
     *
     * @return array
     */
    public function data(): array;
}