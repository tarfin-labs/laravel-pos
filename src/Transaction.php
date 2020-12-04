<?php


namespace TarfinLabs\LaravelPos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $guarded = [];

    protected $table = 'laravel_pos_transactions';

    protected $dates = [
        'paid_at',
    ];

    protected $paymentErrorCodes = [
        '00'
    ];

    public function billable(): morphTo
    {
        return $this->morphTo();
    }

    public function getErrorMessage()
    {
        $errorMessage = '';

        switch ($this->error_code) {
            case '00':
                $errorMessage = 'SUCCESSFUL';
                break;
            case '01':
                $errorMessage = 'BANKANIZI ARAYINIZ';
                break;
            case '02':
                $errorMessage = 'BANKANIZI ARAYINIZ O';
                break;
            case '03':
                $errorMessage = 'GECERSIZ ISYERI';
                break;
            case '38':
            case '04':
                $errorMessage = 'KARTA EL KOY';
                break;
            case '05':
                $errorMessage = 'DO NOT HONOR';
                break;
            case '06':
                $errorMessage = 'HATA UYARI DOSYASI D';
                break;
            case '07':
                $errorMessage = 'KARTA EL KOY OZEL';
                break;
            case '08':
                $errorMessage = 'KIMLIK KONTROLLU ONA';
                break;
            case '09':
                $errorMessage = 'PIN TRY AGAIN';
                break;
            case '11':
                $errorMessage = 'SUCCESFUL';
                break;
            case '18':
            case '12':
                $errorMessage = 'GECERSIZ ISLEM';
                break;
            case '13':
                $errorMessage = 'GECERSIZ MIKTAR';
                break;
            case '14':
                $errorMessage = 'GECERSIZ KART NUMARA';
                break;
            case '15':
                $errorMessage = 'MUHATAP BANKA YOK';
                break;
            case '19':
                $errorMessage = 'ISLEMI TEKRARLAYINIZ';
                break;
            case '20':
                $errorMessage = 'BU KART ILE NAKIT AVANS CEKILEMEZ';
                break;
            case '21':
                $errorMessage = 'YAPILMIS ISLEM YOK';
                break;
            case '25':
                $errorMessage = 'KAYIT DOSYADA YOK';
                break;
            case '28':
                $errorMessage = 'DOSYAYA ULASILAMIYOR';
                break;
            case '29':
                $errorMessage = 'PARA CEKME, MIKTAR HATALI';
                break;
            case '30':
                $errorMessage = 'PARA CEKME, DISPENSE ERROR';
                break;
            case '31':
                $errorMessage = 'KONTOR YUKLEME SORGU, TELNO YANLIS';
                break;
            case '33':
                $errorMessage = 'RED KARTA EL KOY';
                break;
            case '34':
                $errorMessage = 'COUNTERFEIT CARD';
                break;
            case '36':
                $errorMessage = 'SINIRLI KART - KARTA EL KOY';
                break;
            case '41':
                $errorMessage = 'KAYIP KART-EL KOY';
                break;
            case '43':
                $errorMessage = 'CALINTI KART-EL KOY';
                break;
            case '65':
            case '66':
            case '67':
            case '51':
                $errorMessage = 'LIMIT YETERSIZ';
                break;
            case '52':
                $errorMessage = 'NO CHECKING ACCOUNT';
                break;
            case '53':
                $errorMessage = 'NO SAVING ACCOUNT';
                break;
            case '54':
                $errorMessage = 'GECERLILIK BITMIS';
                break;
            case '55':
                $errorMessage = 'YANLIS SIFRE';
                break;
            case '56':
                $errorMessage = 'INCORRECT PIN 2. TRI';
                break;
            case '57':
                $errorMessage = 'GECERSIZ ISLEM-KART';
                break;
            case '58':
                $errorMessage = 'GECERSIZ ISLEM-TERM';
                break;
            case '61':
                $errorMessage = 'MIKTAR LIMITI ASILDI';
                break;
            case '62':
                $errorMessage = 'SINIRLI KART';
                break;
            case '63':
                $errorMessage = 'GUVENLIK ASIMI';
                break;
            case '75':
                $errorMessage = 'SIFRE LIMITI ASILDI';
                break;
            case '76':
                $errorMessage = 'ONCEKI KAYIT YOK';
                break;
            case '77':
                $errorMessage = 'ONCEKI MESAJ YOK';
                break;
            case '79':
                $errorMessage = 'ARQC HATASI';
                break;
            case '80':
                $errorMessage = 'GECERSIZ TARIH';
                break;
            case '81':
                $errorMessage = 'CRYPTOGRAPH HATASI';
                break;
            case '82':
                $errorMessage = 'HATALI CVV';
                break;
            case '83':
                $errorMessage = '3. KEZ HATALI SIFRE';
                break;
            case '84':
                $errorMessage = 'PIN CHANGE SUCCESFUL';
                break;
            case '85':
                $errorMessage = 'BASARILI SIFRE DEGISIKLIGI';
                break;
            case '91':
                $errorMessage = 'CEVAP BEKLIYOR';
                break;
            case '92':
                $errorMessage = 'BANKAYA ULASILAMADI';
                break;
            case '93':
                $errorMessage = 'YONLENDIRME HATASI';
                break;
            case '95':
                $errorMessage = 'BATCH ERROR';
                break;
            case '96':
                $errorMessage = 'KURALDISI ISLEM';
                break;
            case '97':
                $errorMessage = 'REVERSAL PERFORMED';
                break;
        }

        return $errorMessage;
    }
}
