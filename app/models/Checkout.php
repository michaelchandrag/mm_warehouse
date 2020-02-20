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

    public function getItemBySalesCategory($filter) {
        $query = DB::table('checkout as c');
        $query->leftJoin('transaction as t', 't.moka_transaction_id','=','c.moka_transaction_id');
        $query->leftJoin('sales_type as st','st.moka_sales_type_id','=','c.moka_sales_type_id');
        $query->leftJoin('marimakan_sales_category as msc','msc.id','=','st.marimakan_sales_category_id');

        if (isset($filter['msc.id']) && !empty($filter['msc.id'])) {
            $query->where('msc.id','=',$filter['msc.id']);
        }
        if (isset($filter['since']) && !empty($filter['since'])) {
            $query->where('t.moka_created_at','>=',$filter['since']);
        }
        if (isset($filter['until']) && !empty($filter['until'])) {
            $query->where('t.moka_created_at','<=',$filter['until']);
        }

        if (isset($filter['t.is_refund'])) {
            $query->where('t.is_refund','=',$filter['t.is_refund']);
        }
        $query->select(
            DB::raw('c.moka_item_variant_name'),
            DB::raw('sum(c.moka_quantity) as total')
        );
        $query->groupBy('c.moka_item_variant_id');
        return $query->get();
    }
}