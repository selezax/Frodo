<?php

namespace Test\Frodo\Models;


use Illuminate\Database\Eloquent\Model;

class Posts extends Model {

    public $timestamps  = false;
    protected $table    = 'frodo_account_posts';
    protected $fillable = [ 'account_id',
                            'post_id',
                            'title',
                            'datetime',
                            'description',
                            'num_favorites',
                            'num_replies',
                            'num_retweets'
                            ];

    protected $guarded  = [ 'id' ];

    public static $rules = [ 'post_id'          => 'required|max:255|unique:frodo_account_posts',
                             'datetime'         => 'required|date',
                             'num_favorites'    => 'required|numeric',
                             'num_replies'      => 'required|numeric',
                             'num_retweets'     => 'required|numeric',
                            ];

    public function account() {
        return $this->belongsTo('Test\Frodo\Models\Accounts', 'account_id');
    }

}