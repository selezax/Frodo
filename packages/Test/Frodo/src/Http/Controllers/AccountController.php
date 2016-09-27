<?php 

namespace Test\Frodo\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Laravel\Lumen\Routing\Controller as BaseController;
use Test\Frodo\Models\Accounts;
use Test\Frodo\Lib\Account;

class AccountController extends BaseController {


    /**
     * @return json
     */
    public function indexAccount(){
        $accounts = Account::getAccountsWithCountPosts();
        return response()->json($accounts);
    }

    public function listPostsAccount(Request $request, $account_id){
        $posts = Account::getPostsByAccount($account_id, $request->get('limit', 100));
        return response()->json($posts);
    }

    /**
     * @param Request $request
     * @return json
     */
    public function newAccount(Request $request){
        $v = Validator::make($request->all(), Accounts::$rules);

        // Is Validate -----------------------------------------------------------
        if ($v->passes()) {
            $acnt = new Account();
            if($acnt->createNewAccount($request->all())){
                $result['status'] = $acnt->success['status'];
                $result['title'] = $acnt->success['title'];
            } else {
                $result['status'] = $acnt->error['status'];
                $result['description'] = $acnt->error['description'];
            }


        // No Validate -----------------------------------------------------------
        } else {
            $result['status'] = 'error';
            $result['description'] = 'Error validation: ' . $this->fillValidateError($v->failed());
        }

        return response()->json($result);
    }


    /**
     * @param Request $request
     * @param $account_id
     * @return json
     */
    public function editAccount(Request $request, $account_id){
        $rules =  Accounts::$rules;
        $rules['account_id'] = 'required|max:50';
        $v = Validator::make($request->all(), $rules);

        // Is Validate -----------------------------------------------------------
        if($v->passes()){

            $acnt = Accounts::where('account_id', $account_id)->first();
            if($acnt){
                $acnt->refresh_interval = $request->refresh_interval;
                $acnt->save();
                $result['status'] = 'success';
                $result['title'] = $acnt->title;

            } else {
                $result['status'] = 'error';
                $result['description'] = 'Account not found';
            }

        // No Validate -----------------------------------------------------------
        } else {
            $result['status'] = 'error';
            $result['description'] = 'Error validation: ' . $this->fillValidateError($v->failed());
        }

        return response()->json($result);
    }

    /**
     * @param $account_id
     * @return json
     */
    public function deleteAccount($account_id){
        $acnt = Accounts::where('account_id', $account_id)->first();
        if($acnt){
            $acnt->delete();
            $result['status'] = 'success';
        } else {
            $result['status'] = 'error';
            $result['description'] = 'Account not found';
        }
        return response()->json($result);
    }

    /**
     * @param object $failed
     * @return string
     */
    protected function fillValidateError($failed){
        $txt = '';
        foreach($failed as $field => $v){
            $txt .= $field . '; ';
        }
        return $txt;
    }

}