<?php
/**
 * Created by PhpStorm
 * Filename: Helper.php
 * User: Nguyễn Văn Ước
 * Date: 12/07/2023
 * Time: 15:26
 */

namespace Uocnv\BaokimPayment\Lib;

use Illuminate\Support\Arr;

class Helper
{
    /**
     * Get key random weight
     *
     * @param array $input
     * @return mixed
     */
    public static function getRandomWeight(array $input): mixed
    {
        $data = Arr::map($input, function($value, $key) {
            return Arr::get($value, 'weight', 0);
        });
        $rands = [];
        foreach ($data as $key => $value) {
            for ($i = 0; $i < $value; $i++) {
                $rands[] = $key;
            }
        }
        if (count($rands)) {
            return $rands[array_rand($rands)];
        }
        return null;
    }
}