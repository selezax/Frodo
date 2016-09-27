<?php

namespace Test\Frodo\Models;


use Illuminate\Database\Eloquent\Model;

class Accounts extends Model {
    protected $table    = 'frodo_accounts';
    protected $fillable = [ 'account_id', 'title', 'refresh_interval', 'last_updated' ];
    protected $guarded  = [ 'id', 'created_at', 'updated_at' ];

    public static $rules = [ 'account_id'       => 'required|max:50|unique:frodo_accounts',
                             'refresh_interval' => 'required|numeric'
                            ];
 
    public function posts() {
        return $this->hasMany('Test\Frodo\Models\Posts', 'account_id', 'id');
    }
    
    
}