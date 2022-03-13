<?php

namespace Mekachonjo\Payment\Abstractions;

use App\Models\Chonjohistoy;
use Illuminate\Http\Request;

abstract class Command
{
    /**
     * The name and signature of the chonjopay command.
     *
     * @var string
     */
    public string $signature;

    /**
     * Execute the chonjopay command.
     */
    abstract public function handle(Request $request, Chonjohistoy $chonjohistoy);
}

?>