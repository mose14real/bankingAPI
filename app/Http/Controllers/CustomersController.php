<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransferRequest;
use App\Http\Resources\AccountResource;
use App\Http\Requests\AcctNumberRequest;

class CustomersController extends Controller
{

    use HttpResponses;

    public function transferFund(TransferRequest $request)
    {
        DB::transaction(function () use ($request) {

            $senderAcct = Account::where('acct_number', $request['sender_acctNumber'])->first();
            if (!$senderAcct) {
                return $this->error('', 'Invalid sender account number', 404);
            }

            $receiverAcct = Account::where('acct_number', $request['receiver_acctNumber'])->first();
            if (!$receiverAcct) {
                return $this->error('', 'Invalid receiver account number', 404);
            }

            $senderOpeningBal = $senderAcct->available_balance;
            $transferAmount = $request['amount'];

            if ($transferAmount > $senderOpeningBal) {
                return $this->error('', 'Insufficient fund', 404);
            }

            #Debit Process
            $senderClosingBal = $senderOpeningBal - $transferAmount;

            #Sender Transaction
            Transaction::create([
                'customer_id' => $senderAcct->customer_id,
                'date_time' => Carbon::now(),
                'receiver_name' => $receiverAcct->customer->user->name,
                'receiver_acct' => $receiverAcct->acct_number,
                'description' =>  "Cash transfer to " . $receiverAcct->customer->user->name,
                'type' => 'debit',
                'currency' => $senderAcct->currency,
                'amount' => $transferAmount,
                'opening_balance' => $senderOpeningBal,
                'closing_balance' => $senderClosingBal,
                'reference' => Str::random(10),
                'transact_status' => 'successful'
            ]);

            #Sender New Balance
            $senderAcct->update(['available_balance' => $senderClosingBal]);

            #Credit Process
            $receiverOpeningBal = $receiverAcct->available_balance;
            $receiverClosingBal = $receiverOpeningBal + $transferAmount;

            #Receiver Transaction
            Transaction::create([
                'customer_id' => $receiverAcct->customer_id,
                'date_time' => Carbon::now(),
                'sender_name' => $senderAcct->customer->user->name,
                'sender_acct' => $senderAcct->acct_number,
                'description' =>  "Cash transfer from " . $senderAcct->customer->user->name,
                'type' => 'credit',
                'currency' => $senderAcct->currency,
                'amount' => $transferAmount,
                'opening_balance' => $receiverOpeningBal,
                'closing_balance' => $receiverClosingBal,
                'reference' => Str::random(10),
                'transact_status' => 'successful'
            ]);

            #Receiver New Balance
            $receiverAcct->update(['available_balance' => $receiverClosingBal]);

            return $this->success([
                'message' => 'Transaction successful'
            ]);
        }, 1);
    }

    public function retrieveBalance(AcctNumberRequest $request)
    {
        $acct = Account::where('acct_number', $request['acctNumber'])->first();
        return new AccountResource($acct);
    }

    public function retrieveAllAcct($id)
    {
        $user = Account::where('account_id', $id->customer->id);

        dd($user);
    }

    public function transferHistory(AcctNumberRequest $request)
    {
        $acct = Account::where('acct_number', $request['acctNumber'])->first();
        $transaction = Transaction::where('customer_id', $acct->customer_id)->get();
        return $transaction;
    }
}
