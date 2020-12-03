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
            'transaction_id'     => $response['TransId'],
            'order_id'           => $response['ReturnOid'],
            'card_brand'         => $response['EXTRA_CARDBRAND'],
            'masked_credit_card' => $response['maskedCreditCard'],
            'amount'             => $response['amount'],
            'installment'        => $response['taksit'],
            'currency'           => $response['currency'],
            'status'             => false,
            'paid_at'            => Carbon::parse($response['EXTRA_TRXDATE'])
        ];

        return $this->transactions()->create($transaction);
    }

    public function handlePayment(array $response)
    {
        if ($response['Response'] == 'Approved') {
            return $this->handlePaymentSucceeded($response);
        } else {
            return $this->handlePaymentFailed($response);
        }
    }
}
