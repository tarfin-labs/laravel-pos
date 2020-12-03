<?php


namespace TarfinLabs\LaravelPos;


use Illuminate\Support\Facades\Http;

class PaymentBuilder
{
    protected string $okUrl;
    protected string $failUrl;
    protected string $bank;
    protected Card $card;
    protected array $bankConfig;
    protected Order $order;
    protected string $processType = 'Auth';
    protected string $storeType = '3d_pay';

    /**
     * @param  string  $okUrl
     * @return PaymentBuilder
     */
    public function okUrl(string $okUrl): PaymentBuilder
    {
        $this->okUrl = $okUrl;
        return $this;
    }

    /**
     * @param  string  $failUrl
     * @return PaymentBuilder
     */
    public function failUrl(string $failUrl): PaymentBuilder
    {
        $this->failUrl = $failUrl;
        return $this;
    }

    /**
     * @param  Card  $card
     * @return PaymentBuilder
     */
    public function card(Card $card): PaymentBuilder
    {
        $this->card = $card;

        return $this;
    }

    /**
     * @param  Order  $order
     * @return PaymentBuilder
     */
    public function order(Order $order): PaymentBuilder
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param  string  $bank
     * @return PaymentBuilder
     */
    public function bank(string $bank): PaymentBuilder
    {
        $this->bank = $bank;
        $this->bankConfig = config('laravel-pos.banks.'.$this->bank);

        return $this;
    }

    protected function getClientId()
    {
        return $this->bankConfig['merchant_id'];
    }

    public function generateHash()
    {
        $hashKeys = [
            $this->getClientId(),
            $this->order->getId(),
            $this->order->getAmount(),
            $this->okUrl,
            $this->failUrl,
            $this->processType,
            $this->order->getInstallment(),
            $this->order->getRnd(),
            $this->bankConfig['store_key']
        ];

        return base64_encode(pack('H*', sha1(implode('', $hashKeys))));
    }

    protected function build()
    {
        $hash = $this->generateHash();

        return [
            'pan'                             => $this->card->getNumber(),
            'cv2'                             => $this->card->getCvv(),
            'Ecom_Payment_Card_ExpDate_Year'  => $this->card->getExpireYear(),
            'Ecom_Payment_Card_ExpDate_Month' => $this->card->getExpireMonth(),
            'cardType'                        => $this->card->getType(),
            'firmaadi'                        => $this->card->getHolderName(),
            'clientid'                        => $this->getClientId(),
            'amount'                          => $this->order->getAmount(),
            'oid'                             => $this->order->getId(),
            'okUrl'                           => $this->okUrl,
            'failUrl'                         => $this->failUrl,
            'rnd'                             => $this->order->getRnd(),
            'hash'                            => $hash,
            'islemtipi'                       => $this->processType,
            'taksit'                          => $this->order->getInstallment(),
            'storetype'                       => $this->storeType,
            'lang'                            => config('laravel-pos.locale'),
            'currency'                        => config('laravel-pos.currency'),
        ];
    }

    public function charge()
    {
        return Http::post($this->bankConfig['base_url'], $this->build());
    }
}
