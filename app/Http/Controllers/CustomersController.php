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
        #Database Transaction
        DB::transaction(function () use ($request) {

            #Authenticated User ID
            $userId = Auth::user();

            #Sender Account Number
            $senderAcct = Account::where('acct_number', $request['sender_acctNumber'])->first();

            #Validate Authenticated User
            if ($userId != $senderAcct->customer->user_id) {
                return $this->error('', 'Unauthorized', 401);
            }

            #Validate Sender Account Number
            if (!$senderAcct) {
                return $this->error('', 'Invalid sender account number', 404);
            }

            #Receiver Account Number
            $receiverAcct = Account::where('acct_number', $request['receiver_acctNumber'])->first();

            #Validate Receiver Account Number
            if (!$receiverAcct) {
                return $this->error('', 'Invalid receiver account number', 404);
            }

            #Sender Available Balance
            $senderOpeningBal = $senderAcct->available_balance;

            #Transfer Amount
            $transferAmount = $request['amount'];

            #Validate Sender Transaction
            if ($transferAmount > $senderOpeningBal) {
                return $this->error('', 'Insufficient fund', 424);
            }

            #Debit Process
            $senderClosingBal = $senderOpeningBal - $transferAmount;

            #Sender Debit Transaction
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

            #Update Sender Availabe Balance
            $senderAcct->update(['available_balance' => $senderClosingBal]);

            #Receiver Available Balance
            $receiverOpeningBal = $receiverAcct->available_balance;

            #Credit Process
            $receiverClosingBal = $receiverOpeningBal + $transferAmount;

            #Receiver Credit Transaction
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

            #Update Receiver Available Balance
            $receiverAcct->update(['available_balance' => $receiverClosingBal]);
        }, 1);

        return $this->success([
            'message' => 'Transaction successful'
        ]);
    }

    public function retrieveBalance(AcctNumberRequest $request)
    {
        #Authenticated User ID
        $userId = Auth::user()->id;

        #Account Number
        $acct = Account::where('acct_number', $request['acctNumber'])->first();

        #Validate Authenticated User
        if ($userId != $acct->customer->user_id) {
            return $this->error('', 'Unauthorized', 401);
        }

        #Validate Account Number
        if (!$acct) {
            return $this->error('', 'Invalid account number', 404);
        }

        #Account Resource
        return new AccountResource($acct);
    }

    // public function retrieveAllAcct($id)
    // {
    //     $user = Account::where('account_id', $id->customer->id);

    //     dd($user);
    // }

    public function transferHistory(AcctNumberRequest $request)
    {
        #Authenticated User ID
        $userId = Auth::user()->id;

        #Account Number
        $acct = Account::where('acct_number', $request['acctNumber'])->first();

        #Validate Authenticated User
        if ($userId != $acct->customer->user_id) {
            return $this->error('', 'Unauthorized', 401);
        }

        #Validate Account Number
        if (!$acct) {
            return $this->error('', 'Invalid account number', 404);
        }

        #Procees Transaction
        $transaction = Transaction::where('account_id', $acct->id)->get();

        #Transaction Resource
        return $transaction;
    }
}
