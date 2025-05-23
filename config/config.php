<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'log' => [
        'chanel'  => 'baokim-payment',
        'request' => true,
        'webhook' => true,
        'error'   => true,
    ],

    'jwt' => [
        'host'   => 'https://dev-api.baokim.vn/payment/api/v5/',

        'key'    => 'key_id',

        // MOMO OFFICIAL
        'momo'   => [
            'uri_api'          => 'order/send',
            'webhook'          => 'http://example/api/payment/baokim/webhook',
            'bpm_id'           => 311,
            'transaction_type' => 'money_momo',
            'secret_key'       => [
                'key1' => [
                    'key'         => 'a18ff78e7de372e093d87ca1',
                    'secret'      => '9623ac03057e43cf4f3bef5cc',
                    'merchant_id' => 400,
                    'weight'      => 1,
                ],
            ],
        ],

        // ATM & QR
        'atm'    => [
            'uri_bank_list'    => 'bpm/list',
            'uri_api'          => 'order/send',
            'webhook'          => 'http://example/api/payment/baokim/webhook',
            'bpm_id'           => 297,
            'transaction_type' => 'money_atm',
            'secret_key'       => [
                'noidungso12_key' => [
                    'key'         => '40f1efdbb177417578d12bb6c8668',
                    'secret'      => '749e1762d976b0dad9bfc5d39',
                    'merchant_id' => 178,
                    'weight'      => 1,
                ],
            ],
        ],

        // MOBILE CARD
        'mobile' => [
            'uri_api'          => 'kingcard/api/v1/strike-card',
            'webhook'          => 'http://example/api/payment/baokim/webhook',
            'transaction_type' => 'money_mobile',
            'secret_key'       => [
                'key1' => [
                    'key'         => 'a18ff78e7a9e44f32e093d87ca1',
                    'secret'      => '9623ac030595d86cf4f3bef5cc',
                    'merchant_id' => '',
                    'weight'      => 1,
                ]
            ],
        ],
    ],

    'virtual_account' => [
        'environment' => env('BAO_KIM_VA_ENV', 'development'),

        'signature_structure' => env('BAO_KIM_VA_SIGNATURE_STRUCTURE', 'RequestId|RequestTime|PartnerCode|AccNo|ClientIdNo|TransId|TransAmount|TransTime|BefTransDebt|AffTransDebt|AccountType|OrderId'),

        /*
        |--------------------------------------------------------------------------
        | Config for development environment
        |--------------------------------------------------------------------------
        */

        'development' => [
            'url'          => "https://devtest.baokim.vn/Sandbox/Collection/V2",
            'public_key'   => '',
            'private_key'  => '',
            'partner_code' => '',
        ],

        /*
        |--------------------------------------------------------------------------
        | Config for production environment
        |--------------------------------------------------------------------------
        */

        'production' => [
            'url'          => "https://devtest.baokim.vn/Sandbox/Collection/V2",
            'public_key'   => '',
            'private_key'  => '',
            'partner_code' => '',
        ],
    ],

    'disbursement' => [
        'environment' => env('BAO_KIM_VA_ENV', 'development'),

        /*
        |--------------------------------------------------------------------------
        | Config for development environment
        |--------------------------------------------------------------------------
        */

        'development' => [
            "url"           => "https://devtest.baokim.vn/Sandbox/FirmBanking",
            "bk_public_key" => "",
            "public_key"    => "",
            "private_key"   => "",
            "partner_code"  => ""
        ],

        /*
        |--------------------------------------------------------------------------
        | Config for production environment
        |--------------------------------------------------------------------------
        */

        'production' => [
            "url"          => "",
            "public_key"   => "",
            "private_key"  => "",
            "partner_code" => ""
        ],
    ],
];