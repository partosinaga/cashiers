<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Transactions;
use App\TransactionItems;
use DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{

    public function getTransaction()
    {
        $data = Transactions::whereNull('deleted_at')->paginate(env('MAX_PAGINATE'));
        return $data;
    }

    public function getTransactionById($trxId)
    {
        $return = [];
        $summary = Transactions::where('id', $trxId)->whereNull('deleted_at')->get();
        $items = TransactionItems::select('id', 'uuid', 'title', 'qty', 'price')->where('transaction_id', $trxId)->whereNull('deleted_at')->get();
        $return['summary'] = $summary;
        $return['items'] = $items;
        return $return;
    }

    public function storeTransaction(Request $request)
    {
        try {
            DB::beginTransaction();

            
            $summary = $request->summary[0];
            
            if((int)$summary['total_amount'] > (int)$summary['paid_amount'])
            {
                return $this->sendFailed('not enought payment'); 
            }
            
            $transaction = new Transactions;
            $transaction->uuid = Str::uuid();
            $transaction->user_id = \Auth::user()->id;
            $transaction->device_timestamp = date('Y-m-d H:i:s');
            $transaction->total_amount = $summary['total_amount'];
            $transaction->paid_amount = $summary['paid_amount'];
            $transaction->change_amount = $summary['change_amount'];
            $transaction->payment_method = $summary['payment_method'];
            $transaction->save();

            $items = $request->items;
            $arrayToInsert = [];
            $total = 0;
            foreach ($items as $val) {
                $arrayToRow = [
                    "uuid" => Str::uuid(),
                    "transaction_id" => $transaction->id,
                    "title" => $val['title'],
                    "qty" => $val['qty'],
                    "price" => $val['price'],
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ];
                $total += $val['qty'] * $val['price'];
                array_push($arrayToInsert, $arrayToRow);
            }
            if((int)$total != (int)$summary['total_amount'])
            {
                return $this->sendFailed('total amount and items grand total not match');

            }
            TransactionItems::insert($arrayToInsert);
            DB::commit();
            return $this->sendSuccess($transaction);
        } catch (\Exception $e) {
			\Log::error($e->getMessage());
            DB::rollBack();
            return $this->sendFailed($e->getMessage());
        }
        
    }

    public function deleteTransaction($trxId)
    {
        // soft delete
        try {
            DB::beginTransaction();
            Transactions::where('id', $trxId)
                ->update(['deleted_at' => date('Y-m-d H:i:s')]);

            TransactionItems::where('transaction_id', $trxId)
                ->update(['deleted_at' => date('Y-m-d H:i:s')]);

            DB::commit();
            return $this->sendSuccess();
        } catch (\Exception $e) {
			\Log::error($e->getMessage());
            DB::rollBack();
            return $this->sendFailed($e->getMessage());
        }

    }

    public function updateTransaction($trxId, Request $request)
    {
        try {
            DB::beginTransaction();
            $summary = $request->summary[0];
            $transaction = Transactions::where('id', $trxId)->first();
            if(!$transaction)
            {
                return $this->sendFailed('transaction not found'); 
            }

            if((int)$summary['total_amount'] > (int)$summary['paid_amount'])
            {
                return $this->sendFailed('not enought payment'); 
            }

            $transaction->user_id = \Auth::user()->id;
            $transaction->device_timestamp = date('Y-m-d H:i:s');
            $transaction->total_amount = $summary['total_amount'];
            $transaction->paid_amount = $summary['paid_amount'];
            $transaction->change_amount = $summary['change_amount'];
            $transaction->payment_method = $summary['payment_method'];
            $transaction->updated_at = date('Y-m-d H:i:s');
            $transaction->save();

            $items = $request->items;
            
            TransactionItems::where('transaction_id', $trxId)->delete();
            $arrayToInsert = [];
            $total = 0;
            foreach ($items as $val) {
                $arrayToRow = [
                    "uuid" => Str::uuid(),
                    "transaction_id" => $trxId,
                    "title" => $val['title'],
                    "qty" => $val['qty'],
                    "price" => $val['price'],
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ];
                $total += $val['qty'] * $val['price'];
                array_push($arrayToInsert, $arrayToRow);
            }

            if((int)$total != (int)$summary['total_amount'])
            {
                return $this->sendFailed('total amount and items grand total not match');

            }

            TransactionItems::insert($arrayToInsert);
            DB::commit();
            return $this->sendSuccess();
        } catch (\Exception $e) {
			\Log::error($e->getMessage());
            DB::rollBack();
            return $this->sendFailed($e->getMessage());
        }
    }

}
