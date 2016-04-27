<?php

namespace Appitized\Optimus;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\Fractal\Fratal
 */
class OptimusFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api';
    }
}
