<?php
namespace Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Capsule\Manager as DB;

class PortalVisitor extends Model {
    use SoftDeletes;
    protected $table = 'portal_visitor';

    public function findPortalVisitors($filter = []) {
        $query = DB::table('portal_visitor');
        foreach ($filter as $key => $value) {
            $query->where($key, '=', $value);
        }
        return $query->get();
    }

    public function createPortalVisitor($data) {
        $newPortalVisitor = new PortalVisitor;
        foreach ($data as $key => $value) {
            $newPortalVisitor->{$key} = $value;
        }
        return $newPortalVisitor->save();
    }

    public function updatePortalVisitor($filter, $data) {
        $query = DB::table('portal_visitor');
        foreach ($filter as $key => $value) {
            $query->where($key,$value);
        }
        return $query->update($data);
    }
}