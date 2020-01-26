<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class Transaction extends Model {
    use SoftDeletes;
    protected $table = 'transaction';

    public function findTransactions($filter = []) {
        $query = DB::table('transaction');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->get();
    }

    public function createTransaction($data) {
        $newTransaction = new Transaction;
        foreach ($data as $key => $value) {
            $newTransaction->{$key} = $value;
        }
        return $newTransaction->save();
    }

    public function updateTransaction($filter, $data) {
        $query = DB::table('transaction');
        foreach ($filter as $key => $value) {
            $query->where($key,$value);
        }
        return $query->update($data);
    }
}