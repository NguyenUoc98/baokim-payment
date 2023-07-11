<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'log' => [
        'chanel' => 'baokim-payment',
        'request' => true,
        'webhook' => true,
        'error'   => true,
    ],

    'jwt' => [
        'host' => 'https://dev-api.baokim.vn/payment/api/v5/',

        // MOMO OFFICIAL
        'momo' => [
            'uri_api'          => 'order/send',
            'webhook'          => 'https://1lib.vn/api/payment/baokim/webhook',
            'bpm_id'           => 311,
            'transaction_type' => 'money_momo',
            'secret_key'       => [
                '123doc_momo' => [
                    'key'         => 'a18ff78e7a9e44f38de372e093d87ca1',
                    'secret'      => '9623ac03057e433f95d86cf4f3bef5cc',
                    'merchant_id' => 40002
                ],
            ],
        ],

        // ATM & QR
        'atm'  => [
            'uri_bank_list'    => 'bpm/list',
            'uri_api'          => 'order/send',
            'webhook'          => 'https://1lib.vn/api/payment/baokim/webhook',
            'bpm_id'           => 297,
            'transaction_type' => 'money_atm',
            'secret_key'       => [
                '123doc_key'      => [
                    'key'         => 'a18ff78e7a9e44f38de372e093d87ca1',
                    'secret'      => '9623ac03057e433f95d86cf4f3bef5cc',
                    'merchant_id' => 40002
                ],
            ],
        ],
    ],

];