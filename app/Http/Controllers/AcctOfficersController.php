<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CloseAcctRequest;
use App\Http\Requests\CreateAllRequest;
use App\Http\Resources\AccountResource;
use App\Http\Requests\AcctNumberRequest;
use App\Http\Requests\CreditDebitRequest;
use App\Http\Requests\OpenNewAcctRequest;
use App\Http\Resources\UserResource;

class AcctOfficersController extends Controller
{

    use HttpResponses;

    public function createAll(CreateAllRequest $request)
    {
        #--Database--Transaction--
        DB::transaction(function () use ($request) {

            #--Create--User--
            $user = User::create([
                'uuid' => Str::orderedUuid(),
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'password' =>  Hash::make($request['password']),
                'role' => 'customer',
                'remember_token' => Str::random(10)
            ]);

            #--Create--Customer--
            $customer = Customer::create([
                'uuid' => Str::orderedUuid(),
                'user_id' => $user->id,
                'bvn' => $request['bvn'],
                'employment' => $request['employment'],
                'marital' => $request['marital'],
                'maiden' => $request['maiden'],
                'address' => $request['address'],
                'nationality' => $request['nationality']
            ]);

            #--Create--Account--
            $account = Account::create([
                'uuid' => Str::orderedUuid(),
                'customer_id' => $customer->id,
                'acct_number' => mt_rand(2023060000, 2023069999),
                'type' => $request['type'],
                'status' => 'active',
                'currency' => $request['currency'],
                'available_balance' => $request['amount'],
                'pin' => mt_rand(1000, 9999),
                'officer_name' => Auth::user()->name,
                'officer_email' => Auth::user()->email,
                'officer_phone' => Auth::user()->phone,
            ]);

            #--Create--Transaction--
            $transaction = Transaction::create([
                'uuid' => Str::orderedUuid(),
                'account_id' => $account->id,
                'date_time' => Carbon::now(),
                'description' =>  "Initial cash deposit by " . Auth::user()->name,
                'type' => 'credit',
                'amount' => $account->available_balance,
                'currency' => $account->currency,
                'opening_balance' => "0.00",
                'closing_balance' => $account->available_balance,
                'reference' => Str::random(10),
                'transact_status' => 'successful'
            ]);
        }, 1);

        return $this->success([
            'message' => 'User/Customer/Account/Transaction created successfully'
        ]);
    }

    public function OpenNewAcct(OpenNewAcctRequest $request)
    {
        #Database Transaction
        DB::transaction(function () use ($request) {

            #Open Account for existing Customer

            #User Email
            $userEmail = User::where('email', $request['email'])->first();

            #Validate User Email
            if (!$userEmail) {
                return $this->error('', 'User/Customer do not exist...', 404);
            }

            #--Create--Account--
            $account = Account::create([
                'customer_id' => $user->customer->id,
                'acct_number' => mt_rand(2023060000, 2023069999),
                'type' => $request['type'],
                'status' => 'active',
                'currency' => $request['currency'],
                'available_balance' => $request['amount'],
                'pin' => mt_rand(1000, 9999),
                'officer_name' => Auth::user()->name,
                'officer_email' => Auth::user()->email,
                'officer_phone' => Auth::user()->phone,
            ]);

            #--Create--Transaction--
            $transaction = Transaction::create([
                'account_id' => $account->id,
                'date_time' => Carbon::now(),
                'description' =>  "Initial cash deposit by " . Auth::user()->name,
                'type' => 'credit',
                'amount' => $account->available_balance,
                'currency' => $account->currency,
                'opening_balance' => "0.00",
                'closing_balance' => $account->available_balance,
                'reference' => Str::random(10),
                'transact_status' => 'successful'
            ]);
        }, 1);

        return $this->success([
            'message' => 'Account created successfully...'
        ]);
    }

    public function checkAccts()
    {
        #Account Resource for all Customers
        return AccountResource::collection(
            Account::all()
        );
    }

    public function checkAcct(AcctNumberRequest $request)
    {
        #Customer Account Number
        $acctNumber = Account::where('acct_number', $request['acctNumber'])->first();

        #Validate Customer Account Number
        if (!$acctNumber) {
            return $this->error('', 'Invalid account number', 404);
        }

        #Account Resource for a Customer
        return new AccountResource($acctNumber);
    }

    public function counterDeposit(CreditDebitRequest $request)
    {
        #Database Transaction
        DB::transaction(function () use ($request) {

            #Customer Account Number
            $acct = Account::where('acct_number', $request['acctNumber'])->first();

            #validate Customer Account
            if (!$acct) {
                return $this->error('', 'Invalid account number', 404);
            }

            #Deposit Amount
            $amount = $request['amount'];

            #Customer Avaiable Balance
            $openingBal = $acct->available_balance;

            #Customer Credit Process
            $closingBal = $openingBal + $amount;

            #Customer Credit Transaction
            Transaction::create([
                'customer_id' => $acct->customer_id,
                'date_time' => Carbon::now(),
                'description' =>  "Initial cash deposit by " . Auth::user()->name,
                'type' => 'credit',
                'currency' => $acct->currency,
                'amount' => $amount,
                'opening_balance' => $openingBal,
                'closing_balance' => $closingBal,
                'reference' => Str::random(10),
                'transact_status' => 'successful'
            ]);

            #Update Customer Available Balance
            $acct->update(['available_balance' => $closingBal]);
        }, 1);
    }

    public function counterWithdrawal(CreditDebitRequest $request)
    {
        #Database Transaction
        DB::transaction(function () use ($request) {

            #Customer Account Number
            $acct = Account::where('acct_number', $request['acctNumber'])->first();

            #Validate Customer Account Number
            if (!$acct) {
                return $this->error('', 'Invalid account number', 404);
            }

            #Withdrawal Amount
            $amount = $request['amount'];

            #Customer Available Balance
            $openingBal = $acct->available_balance;

            #Validate Customer Transaction
            if ($amount > $openingBal) {
                return $this->error('', 'Insufficient fund', 424);
            }

            #Customer Debit Process
            $closingBal = $openingBal - $amount;

            #Customer Debit Transaction
            Transaction::create([
                'customer_id' => $acct->customer_id,
                'date_time' => Carbon::now(),
                'description' =>  "Cash withdrawal by " . $acct->customer->user->name,
                'type' => 'debit',
                'currency' => $acct->currency,
                'amount' => $amount,
                'opening_balance' => $openingBal,
                'closing_balance' => $closingBal,
                'reference' => Str::random(10),
                'transact_status' => 'successful'
            ]);

            #Update Customer Available Balance
            $acct->update(['available_balance' => $closingBal]);
        }, 1);

        return $this->success([
            'message' => 'Transaction successful'
        ]);
    }

    public function closeAcct(CloseAcctRequest $request)
    {
        #Customer Email
        $user = User::where('email', $request['email']);

        #Delete User/Customer/Accounts/Transactions
        $user->delete();

        return $this->success([
            'message' => 'Account successfully closed/delete'
        ]);
    }
}
