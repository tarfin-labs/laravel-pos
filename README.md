# Laravel Pos

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tarfin-labs/laravel-pos.svg?style=flat-square)](https://packagist.org/packages/tarfin-labs/laravel-pos)
[![Build Status](https://img.shields.io/travis/tarfin-labs/laravel-pos/master.svg?style=flat-square)](https://travis-ci.org/tarfin-labs/laravel-pos)
[![Quality Score](https://img.shields.io/scrutinizer/g/tarfin-labs/laravel-pos.svg?style=flat-square)](https://scrutinizer-ci.com/g/tarfin-labs/laravel-pos)
[![Total Downloads](https://img.shields.io/packagist/dt/tarfin-labs/laravel-pos.svg?style=flat-square)](https://packagist.org/packages/tarfin-labs/laravel-pos)

EST altyapısını kullanan bankalar için Laravel sanal pos entegrasyonu.

## Kurulum

laravel-pos paketini composer ile aşağıdaki komutu çalıştırarak kolayca ekleyebilirsiniz :

```bash
composer require tarfin-labs/laravel-pos
```

Sonrasında config dosyasını ve migrationları publish etmeniz gerekmektedir:

```bash
php artisan vendor:publish --provider="TarfinLabs/LaravelPos/LaravelPosServiceProvider"
```

Konfigürasyonu tamamlamak için kullanacağız bankalara ait gerekli bilgileri config dosyasında tanımlayıp
`.env` dosyasına aşağıdaki şekilde ekleyebilirsiniz:

```.dotenv
LARAVEL_POS_XXXXX_BASE_URL=
LARAVEL_POS_XXXXX_MERCHANT_ID=
LARAVEL_POS_XXXXX_STORE_KEY=
LARAVEL_POS_XXXXX_BANK_NAME=
```

Varsayılan banka konfigürasyonu için aşağıdaki .env değişkenini tanımlamanız gerekmektedir.

```.dotenv
LARAVEL_POS_DEFAULT_BANK=`BANK_CONFIG_KEY_IN_CONFIG_FILE`
```

### Örnek Config

```php
return [
    'currency' => env('LARAVEL_POS_CURRENCY', 949),
    'locale' => env('LARAVEL_POS_LOCALE','tr'),
    'default_bank' => env('LARAVEL_POS_DEFAULT_BANK',''),//banks dizisindeki key
    'bin_file_path' => env('LARAVEL_POS_BIN_FILE_PATH', 'resources/bin.json'),
    'banks' => [
        'ZİRAAT BANKASI' => [
            'name' => env('LARAVEL_POS_ZIRAAT_BANK_NAME',''),//bin dosyasındaki banka adı
            'base_url' => env('LARAVEL_POS_ZIRAAT_BASE_URL',''),
            'merchant_id' => env('LARAVEL_POS_ZIRAAT_MERCHANT_ID', ''),
            'store_key' => env('LARAVEL_POS_ZIRAAT_STORE_KEY', ''),
        ]
    ]
];
```

####Önemli bilgi
banks -> ZİRAAT BANKASI -> name değerinin bin dosyasındaki banka ismiyle eşleşiyor olması gerekmektedir.


## Kullanım

``` php
$card = new Card('Kart No', 'YY', 'MM', 'CV2', 'Kart Üzerindeki İsim');
$orderId = Str::random();//Benzersiz sipariş numarası
$tutar = 20;//TL
$taksit = 1;
$order = new Order($orderId, $tutar, $taksit);
$paymentBuilder = LaravelPos::builder();
$paymentBuilder->bank('ZİRAAT BANKASI')
                    ->card($card)
                    ->order($order)
                    ->okUrl('http://odeme.siteadresi.com/ok')
                    ->failUrl('http://odeme.siteadresi.com/fail')
```

Ek request bilgileri (müşteri id, email adresi vb.) için bir `Customer` nesnesi oluşturup `$paymentBuilder` nesnesine geçebilirsiniz. 

``` php
new Customer([
    'id' => 1,
    'email': 'foo@bar.com',
    'foo': 'bar'
]);

$paymentBuilder->customer($customer);
```

Oluşturduğunuz `Customer` nesnesindeki bilgiler ödeme alma işleminde gönderilen http isteğine eklenecektir.

##Ödeme alma

```php 
$paymentBuilder->charge();
```
methodu ile ödeme işlemini başlatabilirsiniz.

###Ödeme sonucu

Ödeme işlemi tamamlandığında veya iptal olduğunda ilgili banka `okUrl` ve `failUrl` parametrelerinde tanımlı adreslerden birisine işlemle ilgili post isteği gönderir. 

Ödeme işlemi ile ilgili sonucu kaydedebilmek için kullandığınız `User` modeline `Billable` trait'inin eklenmesi gereklidir.

Trait eklendikten sonra;

``` php
$user = User::find(1);
$user->handlePayment(request()->all());
```

Şeklinde ödeme sonucu veritabanına kaydedilir.


###Ek bilgiler
- LaravelPos paketiyle yalnızca 3d-pay methoduyla ödeme alınabilmektedir.

- Bank() parametresi tanımlanmazsa İlgili banka, girilen kredi kartının ilk 6 hanesinden (Bin Number) tespit edilir. 
Bulunan banka config dosyasında tanımlanmışsa o banka için tanımlı olan ayarlar kullanılır. (Ödeme ilgili bankadan çekilir.) Aksi durumlarda varsayılan banka ayarları kullanılır.

- Daha güncel bir BIN listesine sahipseniz BIN dosyası yolunu .env dosyanızdan değiştirebilirsiniz. (LARAVEL_POS_BIN_FILE_PATH)


### Test

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email hakanozdemirr@gmail.com instead of using the issue tracker.

## Credits

- [Faruk Can](https://github.com/frkcn)
- [Hakan Özdemir](https://github.com/hozdemir)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
