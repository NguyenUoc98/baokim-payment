# Very short description of the package

Package là bộ source kết nối với các cổng thanh toán của Bảo Kim, gồm các hình thức: ATM/QR, Momo official, Thu hộ, Chi
hộ

## Installation

You can install the package via composer:

```bash
composer require uocnv/baokim-payment
```

## Usage

- Lấy danh sách ngân hàng:

```php
$arrayBanks = \Uocnv\BaokimPayment\Clients\ATM::getBankList();
```

- Request tạo order thanh toán hình thức ATM/QR:

```php
$transactionId = DB::table('money_atms')->insertGetID([]);
$amount        = 110000;
$bankId        = 124; // $bankId = 0 nếu là hình thức QR
$referer       = 'https://123docz.net/document/123-link-tai-lieu-user-dang-xem.htm';
$email         = $user->use_email;
$phone         = $user->use_phone;
$dataRequest = \Uocnv\BaokimPayment\Clients\ATM::request(
    transactionId: $transactionId,
    amount       : $amount,
    bankId       : $bankId,
    referer      : $referer,
    userEmail    : $email,
    userPhone    : $phone
);
```

- Kiểm tra toàn vẹn dữ liệu nhận từ webhook ATM/QR:

```php
$webhookData  = $request->all();
$verifiedData = \Uocnv\BaokimPayment\Clients\ATM::checkValidData($webhookData);
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email uocnv.soict.hust@gmail.com instead of using the issue tracker.

## Credits

- [Nguyễn Văn Ước](https://github.com/uocnv)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
