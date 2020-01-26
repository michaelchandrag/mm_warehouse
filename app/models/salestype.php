<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class SalesType extends Model {
    use SoftDeletes;
    protected $table = 'sales_type';

    public function findSalesType($filter = []) {
        $query = DB::table('sales_type');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->get();
    }

    public function createSalesType($data) {
        $newSalesType = new SalesType;
        foreach ($data as $key => $value) {
            $newSalesType->{$key} = $value;
        }
        return $newSalesType->save();
    }

    public function updateSalesType($filter, $data) {
        $query = DB::table('sales_type');
        foreach ($filter as $key => $value) {
            $query->where($key,$value);
        }
        return $query->update($data);
    }
}