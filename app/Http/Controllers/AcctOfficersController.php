<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\OpenAcctRequest;
use App\Http\Resources\BankingResource;

class AcctOfficersController extends Controller
{

    use HttpResponses;

    public function checkCustomersAcct()
    {
        // 
    }

    public function checkCustomerAcct()
    {
        // 
    }

    public function openAcct(OpenAcctRequest $request)
    {
        // $request->validated($request->only(['name', 'email', 'password']));
        $request->validated();

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
                'user_id' => $user->id,
                'acct_no' => mt_rand(2023000000, 2023999999),
                'acct_type' => $request['acct_type'],
                'acct_status' => 'active',
                'currency' => $request['currency'],
                'balance' => $request['balance'],
                'pin' => mt_rand(0000, 9999),
                'officer_name' => Auth::user()->name,
                'officer_email' => Auth::user()->email,
                'officer_phone' => Auth::user()->phone,
            ]);

            #--Create--Transaction--
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'transact_date' => now(),
                'transact_desc' => $request['transact_desc'],
                'transact_type' => 'credit',
                'transact_amount' => $account->balance,
                'transact_reference' => Str::random(10),
                'transact_status' => 'successful'

            ]);

            return $this->success(
                [
                    $user, $customer, $account, $transaction,
                    'user' => $user,
                    'customer' => $customer,
                    'account' => $account,
                    'transaction' => $transaction
                ]
            );

            // return BankingResource::collection(
            //     User::where('user_id', Auth::user()->id)->get()
            // );

        }, 1);
    }

    public function fundAcct()
    {
        // 
    }

    public function upgradeAcct()
    {
        // 
    }

    public function closeAcct()
    {
        // 
    }
}
