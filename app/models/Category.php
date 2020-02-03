<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class Category extends Model {
    use SoftDeletes;
    protected $table = 'category';

    public function findCategories($filter = []) {
        $query = DB::table('category');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->get();
    }

    public function findCategory($filter = []) {
        $query = DB::table('category');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->first();
    }

    public function createCategory($data) {
        $newCategory = new Category;
        foreach ($data as $key => $value) {
            $newCategory->{$key} = $value;
        }
        return $newCategory->save();
    }

    public function updateCategory($filter, $data) {
        $query = DB::table('category');
        foreach ($filter as $key => $value) {
            $query->where($key,$value);
        }
        return $query->update($data);
    }
}