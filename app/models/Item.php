<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class Item extends Model {
    use SoftDeletes;
    protected $table = 'item';

    public function findItems($filter = []) {
        $query = DB::table('item');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->get();
    }

    public function createItem($data) {
        $newItem = new Item;
        foreach ($data as $key => $value) {
            $newItem->{$key} = $value;
        }
        return $newItem->save();
    }

    public function updateItem($filter, $data) {
        $query = DB::table('item');
        foreach ($filter as $key => $value) {
            $query->where($key,$value);
        }
        return $query->update($data);
    }
}