<?php

namespace Uocnv\BaokimPayment;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Uocnv\BaokimPayment\Skeleton\SkeletonClass
 */
class BaokimPaymentFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'baokim-payment';
    }
}
