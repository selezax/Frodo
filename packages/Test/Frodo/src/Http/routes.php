<?php
$this->app->group([ 'prefix' => 'accounts',
                    'namespace' => 'Test\Frodo\Http\Controllers'], function ($group) {

    $group->get ('/',                    ['as' => 'frodo.account.index', 'uses' => 'AccountController@indexAccount']);
    $group->get('/{account_id}/posts',   ['as' => 'frodo.account.delete','uses' => 'AccountController@listPostsAccount']);
    $group->post('/new',                 ['as' => 'frodo.account.new',   'uses' => 'AccountController@newAccount']);
    $group->post('/{account_id}',        ['as' => 'frodo.account.edit',  'uses' => 'AccountController@editAccount']);
    $group->post('/{account_id}/delete', ['as' => 'frodo.account.delete','uses' => 'AccountController@deleteAccount']);

});