# Laravel Bao Kim Payment

Package is the source set to connect to Bao Kim's payment gateways, including the following forms: ATM/QR, Momo
official, VA, Disbursement

## Feature

[x] Log request, webhook response, error

[x] Multiple key

## Installation

You can install the package via composer:

```bash
composer require uocnv/baokim-payment
```

## Usage

### ATM/QR, Momo Official, Mobile card

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

### Collection payment & Disbursement payments

- Create new virtual account:

```php
try {
    $vaClient = new Uocnv\BaokimPayment\Clients\VA('production');
    $data     = $vaClient->registerVirtualAccount(
        accName   : 'CONG THANH TOAN',
        orderId   : 'trans' . rand(1, 99) . time(),
        amountMax : 5000000,
        expireDate: Carbon::now()->addDay()->format('Y-m-d H:i:s')
    );
} catch (GuzzleException|CollectionRequestException|SignFailedException) {
}
```

- Update virtual account:

```php
try {
    $vaClient = new Uocnv\BaokimPayment\Clients\VA('production');
    $data     = $vaClient->updateVirtualAccount(
        accName   : 'CONG THANH TOAN',
        orderId   : 'trans' . rand(1, 99) . time(),
        amountMax : 5000000,
        expireDate: Carbon::now()->addDay()->format('Y-m-d H:i:s')
    );
} catch (GuzzleException|CollectionRequestException|SignFailedException) {
}
```

- Check the integrity of data received from webhooks:

```php
$vaClient      = new Uocnv\BaokimPayment\Clients\VA('production');
$webhookData   = $request->getContent();
$dataValidated = $vaClient->checkValidData($webhookData);
```

- Look up for Partner balance:

```php
$disbursement = new Uocnv\BaokimPayment\Clients\Disbursement('production');
try {
    $data    = $disbursement->lookUpForBalance();
    $balance = $data['Available'];
} catch (GuzzleException|CollectionRequestException|SignFailedException) {
}
```

- Look up for transfer info:

```php
$disbursement = new Uocnv\BaokimPayment\Clients\Disbursement('production');
try {
    $referenceId = 'gd_123123';
    $data    = $disbursement->lookUpForTransferInfo($referenceId);
} catch (GuzzleException|CollectionRequestException|SignFailedException) {
}
```

- Verify customer information

```php
$disbursement = new Uocnv\BaokimPayment\Clients\Disbursement('production');
try {
    $accNo   = '21110001400973';
    $bankNo  = 970437; // Get from Uocnv\BaokimPayment\Clients\Disbursement::BANK_TRANSFER_ASSISTANCE
    $data    = $disbursement->verifyCustomerInfo($accNo, $bankNo);
} catch (GuzzleException|CollectionRequestException|SignFailedException) {
}
```

- Transfer money

```php
$disbursement = new Uocnv\BaokimPayment\Clients\Disbursement('production');
$money        = 1000000;
$referenceId  = 'gd_123123';
$memo         = '123doc chuyen tien';
$accNo        = '21110001400973';
$bankNo       = 970437;
try {
    $response = $disbursement->transferMoney($money, $referenceId, $memo, $accNo, $bankNo);
} catch (GuzzleException|CollectionRequestException|SignFailedException) {
}
```

### Security

If you discover any security related issues, please email uocnv.soict.hust@gmail.com instead of using the issue tracker.

## Credits

- [Nguyễn Văn Ước](https://github.com/uocnv)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
