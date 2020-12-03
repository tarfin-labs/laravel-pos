<?php


namespace TarfinLabs\LaravelPos\Concerns;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use TarfinLabs\LaravelPos\Transaction;

trait ManagesTransactions
{
    public function transactions(): morphMany
    {
        return $this->morphMany(Transaction::class, 'billable')->orderByDesc('created_at');
    }

    protected function handlePaymentSucceeded(array $response)
    {
        $transaction = [
            'transaction_id'     => $response['TransId'],
            'order_id'           => $response['ReturnOid'],
            'card_brand'         => $response['EXTRA_CARDBRAND'],
            'masked_credit_card' => $response['maskedCreditCard'],
            'amount'             => $response['amount'],
            'installment'        => $response['taksit'],
            'currency'           => $response['currency'],
            'status'             => true,
            'paid_at'            => Carbon::parse($response['EXTRA_TRXDATE'])
        ];

        return $this->transactions()->create($transaction);
    }

    protected function handlePaymentFailed(array $response)
    {
        $transaction = [
            'order_id'           => $response['oid'],
            'masked_credit_card' => $response['maskedCreditCard'],
            'amount'             => $response['amount'],
            'installment'        => $response['taksit'],
            'currency'           => $response['currency'],
            'status'             => false,
            'error_code'         => $response['ProcReturnCode'] ?? null,
        ];

        return $this->transactions()->create($transaction);
    }

    protected function check3DHash(array $data)
    {
        $storeKey = config('laravel-pos.banks.'.$data['bank'].'.store_key');

        $hashParams = $data['HASHPARAMS'];
        $hashParamsVal = $data['HASHPARAMSVAL'];
        $hashParam = $data['HASH'];
        $paramsVal = '';

        $hashParamsList = explode(':', $hashParams);
        foreach ($hashParamsList as $value) {
            if (!empty($value) && isset($data[$value])) {
                $paramsVal = $paramsVal.$data[$value];
            }
        }

        $hashVal = $paramsVal.$storeKey;
        $hash = base64_encode(sha1($hashVal, true));

        if ($hashParams && !($paramsVal != $hashParamsVal || $hashParam != $hash)) {
            return true;
        }

        return false;
    }

    public function handlePayment(array $response)
    {
        if ($this->check3DHash($response) && !empty($response['Response']) && $response['Response'] == 'Approved') {
            return $this->handlePaymentSucceeded($response);
        } else {
            return $this->handlePaymentFailed($response);
        }
    }
}
