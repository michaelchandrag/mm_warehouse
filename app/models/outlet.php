<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class Outlet extends Model {
    use SoftDeletes;
    protected $table = 'outlet';
    
    public $timestamps = false;

    public function findOutlets($filter = []) {
        $query = DB::table('outlet');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->get();
    }

    public function createOutlet($data) {
        $newOutlet = new Outlet;
        foreach ($data as $key => $value) {
            $newOutlet->{$key} = $value;
        }
        return $newOutlet->save();
    }

    public function updateOutlet($filter, $data) {
        $query = DB::table('outlet');
        foreach ($filter as $key => $value) {
            $query->where($key,$value);
        }
        return $query->update($data);
    }
}