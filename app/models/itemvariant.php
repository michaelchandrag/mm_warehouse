<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class ItemVariant extends Model {
    use SoftDeletes;
    protected $table = 'item_variant';

    public function findItemVariants($filter = []) {
        $query = DB::table('item_variant');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->get();
    }

    public function createItemVariant($data) {
        $newItemVariant = new ItemVariant;
        foreach ($data as $key => $value) {
            $newItemVariant->{$key} = $value;
        }
        return $newItemVariant->save();
    }

    public function updateItemVariant($filter, $data) {
        $query = DB::table('item_variant');
        foreach ($filter as $key => $value) {
            $query->where($key,$value);
        }
        return $query->update($data);
    }
}