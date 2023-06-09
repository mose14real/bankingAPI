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
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'password' =>  Hash::make($request['password']),
                'role' => 'customer',
                'remember_token' => Str::random(10)
            ]);

            #--Create--Customer--
            $customer = Customer::create([
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
                'customer_id' => $customer->id,
                'acct_number' => mt_rand(2023060000, 2023069999),
                'type' => $request['type'],
                'status' => 'active',
                'currency' => $request['currency'],
                'available_balance' => $request['amount'],
                'pin' => mt_rand(0000, 9999),
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

            // return $this->success([
            //     'message' => 'User/Customer/Account/Transaction created successfully'
            // ]);

            // return UserResource::collection(
            //     User::where('id', Auth::user()->id)->get()
            // );

            // return $this->success([
            //     'user' => $user,
            //     'customer' => $customer,
            //     'account' => $transaction,
            //     'transaction' => $account,
            // ]);
        }, 1);
    }

    public function OpenNewAcct(OpenNewAcctRequest $request)
    {
        DB::transaction(function () use ($request) {

            $user = User::where('email', $request['email'])->first();

            if (!$user) {
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
                'pin' => mt_rand(0001, 9999),
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

            return $this->success([
                'message' => 'Account created successfully...'
            ]);

            // return $this->success([
            //     'account' => $transaction,
            //     'transaction' => $account,
            // ]);
        }, 1);
    }

    public function checkAccts()
    {
        return AccountResource::collection(
            Account::all()
        );
    }

    public function checkAcct(AcctNumberRequest $request)
    {
        $acctNumber = Account::where('acct_number', $request['acctNumber'])->first();

        if (!$acctNumber) {
            return $this->error('', 'Invalid account number', 404);
        }

        return new AccountResource($acctNumber);
    }

    public function counterDeposit(CreditDebitRequest $request)
    {
        DB::transaction(function () use ($request) {

            $acct = Account::where('acct_number', $request['acctNumber'])->first();

            if (!$acct) {
                return $this->error('', 'Invalid account number', 404);
            }

            $amount = $request['amount'];
            $openingBal = $acct->available_balance;
            $closingBal = $openingBal + $amount;

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

            $acct->update(['available_balance' => $closingBal]);
        }, 1);
    }

    public function counterWithdrawal(CreditDebitRequest $request)
    {
        DB::transaction(function () use ($request) {

            $acct = Account::where('acct_number', $request['acctNumber'])->first();

            if (!$acct) {
                return $this->error('', 'Invalid account number', 404);
            }

            $amount = $request['amount'];
            $openingBal = $acct->available_balance;

            if ($amount > $openingBal) {
                return $this->error('', 'Insufficient fund', 404);
            }

            $closingBal = $openingBal - $amount;

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

            $acct->update(['available_balance' => $closingBal]);

            return $this->success([
                'message' => 'Transaction successful'
            ]);
        }, 1);
    }

    public function closeAcct(CloseAcctRequest $request)
    {
        $user = User::where('email', $request['email']);
        $user->delete();

        return $this->success([
            'message' => 'Account successfully closed/delete'
        ]);
    }
}
