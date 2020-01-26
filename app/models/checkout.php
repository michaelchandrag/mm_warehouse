<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class Checkout extends Model {
    use SoftDeletes;
    protected $table = 'checkout';

    public function findCheckouts($filter = []) {
        $query = DB::table('checkout');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->get();
    }

    public function createCheckout($data) {
        $newCheckout = new Checkout;
        foreach ($data as $key => $value) {
            $newCheckout->{$key} = $value;
        }
        return $newCheckout->save();
    }

    public function updateCheckout($filter, $data) {
        $query = DB::table('checkout');
        foreach ($filter as $key => $value) {
            $query->where($key,$value);
        }
        return $query->update($data);
    }
}