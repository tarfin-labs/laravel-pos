<?php


namespace TarfinLabs\LaravelPos;


use Illuminate\Support\Facades\Http;
use TarfinLabs\LaravelPos\Integrators\NestPay;

class PaymentBuilder
{
    const INTEGRATOR_NESTPAY = 'NestPay';
    public string $okUrl;
    public string $failUrl;
    public string $bank;
    public Card $card;
    public array $bankConfig;
    public Order $order;
    public Customer $customer;
    public string $processType = 'Auth';
    public string $storeType = '3d_pay';
    public string $ip;

    /**
     * The success url which will be triggered if payment is successful.
     *
     * @param  string  $okUrl
     * @return PaymentBuilder
     */
    public function okUrl(string $okUrl): PaymentBuilder
    {
        $this->okUrl = $okUrl;
        return $this;
    }

    /**
     * The failure url which will be triggered if payment is failed.
     *
     * @param  string  $failUrl
     * @return PaymentBuilder
     */
    public function failUrl(string $failUrl): PaymentBuilder
    {
        $this->failUrl = $failUrl;
        return $this;
    }

    /**
     * Set credit card object to the PaymentBuilder..
     *
     * @param  Card  $card
     * @return PaymentBuilder
     */
    public function card(Card $card): PaymentBuilder
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Set order object to the PaymentBuilder.
     *
     * @param  Order  $order
     * @return PaymentBuilder
     */
    public function order(Order $order): PaymentBuilder
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Set customer object to the PaymentBuilder.
     *
     * @param  Customer  $customer
     * @return $this
     */
    public function customer(Customer $customer): PaymentBuilder
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Set pos bank. If the bank is not set up, the bank that issued the credit card or default bank is selected.
     *
     * @param  string  $bank
     * @return PaymentBuilder
     */
    public function bank(string $bank): PaymentBuilder
    {
        $this->bank = $bank;
        $this->bankConfig = config('laravel-pos.banks')[$this->bank];

        return $this;
    }

    /**
     * Get bank config variables from laravel config.
     *
     * @return array
     */
    public function getBankConfig(): array
    {
        return $this->bankConfig;
    }

    /**
     * If the bank is not set up, this method chooses the pos bank.
     *
     * @param $issuer
     */
    public function setConfigWithIssuer($issuer)
    {
        $defaultBank = config('laravel-pos.default_bank');
        $this->bank = $defaultBank;
        $this->bankConfig = config('laravel-pos.banks.'.$defaultBank);

        if ($issuer) {
            $bankConfigList = config('laravel-pos.banks');
            $result = array_filter($bankConfigList, function ($item) use ($issuer) {
                return $item['name'] == $issuer;
            });

            if (count($result) > 0) {
                $firstKey = key($result);
                $this->bank = (string) $firstKey;
                $this->bankConfig = config('laravel-pos.banks.'.$firstKey);
            }
        }
    }

    /**
     * Virtual merchant id for selected bank.
     *
     * @return mixed
     */
    public function getClientId()
    {
        return $this->bankConfig['merchant_id'];
    }

    /**
     * Create hash for request.
     *
     * @return string
     */
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

    /**
     * Build request.
     *
     * @return array
     */
    protected function build()
    {
        if (!$this->bankConfig) {
            $this->setConfigWithIssuer($this->card->getCardIssuer());
        }

        $hash = $this->generateHash();

        $requestParams = [
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
            'bank'                            => $this->bank,
        ];

        if (!empty($this->customer)) {
            $requestParams = array_merge($requestParams, $this->customer);
        }

        return $requestParams;
    }

    /**
     * Charge customer for the given amount with given credit card.
     *
     * @return false|\Illuminate\Http\Client\Response
     */
    public function charge()
    {
        if (!$this->bankConfig) {
            $this->setConfigWithIssuer($this->card->getCardIssuer());
        }

        if ($this->getBankConfig()['integrator'] == self::INTEGRATOR_NESTPAY) {
            $nestPay = new NestPay($this);
            return $nestPay->charge();
        }

        return false;
    }
}
