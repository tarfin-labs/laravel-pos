<?php

namespace TarfinLabs\LaravelPos\Integrators;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class NestPay extends Pos
{
    /**
     * Create hash for request.
     *
     * @return string
     */
    public function generateHash()
    {
        $hashKeys = [
            $this->paymentBuilder->getClientId(),
            $this->paymentBuilder->order->getId(),
            $this->paymentBuilder->order->getAmount(),
            $this->paymentBuilder->okUrl,
            $this->paymentBuilder->failUrl,
            $this->paymentBuilder->processType,
            $this->paymentBuilder->order->getInstallment(),
            $this->paymentBuilder->order->getRnd(),
            $this->paymentBuilder->bankConfig['store_key']
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
        $hash = $this->generateHash();

        $requestParams = [
            'pan'                             => $this->paymentBuilder->card->getNumber(),
            'cv2'                             => $this->paymentBuilder->card->getCvv(),
            'Ecom_Payment_Card_ExpDate_Year'  => $this->paymentBuilder->card->getExpireYear(),
            'Ecom_Payment_Card_ExpDate_Month' => $this->paymentBuilder->card->getExpireMonth(),
            'cardType'                        => $this->paymentBuilder->card->getType(),
            'firmaadi'                        => $this->paymentBuilder->card->getHolderName(),
            'clientid'                        => $this->paymentBuilder->getClientId(),
            'amount'                          => $this->paymentBuilder->order->getAmount(),
            'oid'                             => $this->paymentBuilder->order->getId(),
            'okUrl'                           => $this->paymentBuilder->okUrl,
            'failUrl'                         => $this->paymentBuilder->failUrl,
            'rnd'                             => $this->paymentBuilder->order->getRnd(),
            'hash'                            => $hash,
            'islemtipi'                       => $this->paymentBuilder->processType,
            'taksit'                          => $this->paymentBuilder->order->getInstallment(),
            'storetype'                       => $this->paymentBuilder->storeType,
            'lang'                            => config('laravel-pos.locale'),
            'currency'                        => config('laravel-pos.currency'),
            'bank'                            => $this->paymentBuilder->bank,
        ];

        if (!empty($this->paymentBuilder->customer)) {
            $requestParams = array_merge($requestParams, $this->paymentBuilder->customer);
        }

        return $requestParams;
    }

    public function charge(): Response
    {
        return Http::asForm()->post($this->paymentBuilder->bankConfig['base_url'], $this->build());
    }
}
