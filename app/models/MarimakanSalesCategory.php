<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class MarimakanSalesCategory extends Model {
    use SoftDeletes;
    protected $table = 'marimakan_sales_category';

    public function findMarimakanSalesCategory($filter = []) {
        $query = DB::table('marimakan_sales_category');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->get();
    }

    public function createMarimakanSalesCategory($data) {
        $newMarimakanSalesCategory = new MarimakanSalesCategory;
        foreach ($data as $key => $value) {
            $newMarimakanSalesCategory->{$key} = $value;
        }
        return $newMarimakanSalesCategory->save();
    }

    public function updateMarimakanSalesCategory($filter, $data) {
        $query = DB::table('marimakan_sales_category');
        foreach ($filter as $key => $value) {
            $query->where($key,$value);
        }
        return $query->update($data);
    }
}