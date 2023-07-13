# Laravel Bao Kim Payment

Package is the source set to connect to Bao Kim's payment gateways, including the following forms: ATM/QR, Momo official, VA, Disbursement

## Feature

[x] Log request, webhook response, error

[x] Multiple key

## Installation

You can install the package via composer:

```bash
composer require uocnv/baokim-payment
```

## Usage

- Get a list of banks:

```php
$arrayBanks = Uocnv\BaokimPayment\Clients\ATM::getBankList();
```

- Request to create payment order of ATM/QR:

```php
$transactionId = DB::table('money_atms')->insertGetID([]);
$amount        = 110000;
$bankId        = 124; // $bankId = 0 nếu là hình thức QR
$referer       = 'https://123docz.net/document/123-link-tai-lieu-user-dang-xem.htm';
$email         = $user->use_email;
$phone         = $user->use_phone;
$dataRequest = Uocnv\BaokimPayment\Clients\ATM::request(
    transactionId: $transactionId,
    amount       : $amount,
    bankId       : $bankId,
    referer      : $referer,
    userEmail    : $email,
    userPhone    : $phone
);
```

- Request to create payment order of Mobile card:

```php
$transactionId   = DB::table('money_mobile_cards')->insertGetID([]);
$amount          = 110000;
$pin             = '071608559897';
$serial          = '098353000029197';
$telecomOperator = Uocnv\BaokimPayment\Enums\TelecomOperator::VIETTEL;
$dataRequest     = Uocnv\BaokimPayment\Clients\MobileCard::request(
    transactionId  : $transactionId,
    amount         : $amount,
    pin            : $pin,
    serial         : $serial,
    telecomOperator: $telecomOperator
);
```

- Check the integrity of data received from webhooks:

```php
$webhookData  = $request->all();
$verifiedData = Uocnv\BaokimPayment\Clients\ATM::checkValidData($webhookData);
```

### Security

If you discover any security related issues, please email uocnv.soict.hust@gmail.com instead of using the issue tracker.

## Credits

- [Nguyễn Văn Ước](https://github.com/uocnv)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
