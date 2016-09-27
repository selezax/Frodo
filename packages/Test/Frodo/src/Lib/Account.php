<?php

namespace Test\Frodo\Lib;

use Abraham\TwitterOAuth\TwitterOAuth;
use Carbon\Carbon;
use Test\Frodo\Models\Accounts;
use Test\Frodo\Models\Posts;

class Account{
    public $success = array();
    public $error = array();
    public $connection ;


    protected $configTwiter;

    public function __construct(){
        $this->setConfigTwiter();
    }

    /**
     * @return $this
     */
    protected function setConnection(){
        $this->connection = new TwitterOAuth( $this->configTwiter['ConsumerKey'],
            $this->configTwiter['ConsumerSecret'],
            $this->configTwiter['AccessToken'],
            $this->configTwiter['AccessTokenSecret']
        );
        return $this;
    }

    /**
     * @param array $fields
     * @return bool
     *
     * Create new Account by id_account with Title from Twiter API
     */
    public function createNewAccount($fields){
        $this->setConnection();
        $response = $this->connection->get('users/show', [ 'screen_name' => $fields['account_id'] ] );

        if(isset($response->errors)){
            $this->error['status'] = 'error';
            $this->error['description'] = '';
            foreach($response->errors as $k => $v){
                $this->error['description']  .= $v->message . '; ';
            }
            return false;

        } else {
            $fields['title'] = $response->name ;
            Accounts::create($fields);
            $this->success = [  'title'  => $response->name,
                                'status' => 'success'];
        }
        return true;
    }

    /**
     * @param string $ConsumerKey
     * @param string $ConsumerSecret
     * @param string $AccessToken
     * @param string $AccessTokenSecret
     * @return $this
     *
     * Set parameters to connect to Twitter. From the config or options
     */
    public function setConfigTwiter($ConsumerKey = null, $ConsumerSecret = null, $AccessToken = null, $AccessTokenSecret = null){
        if($ConsumerKey) $this->configTwiter['ConsumerKey'] = $ConsumerKey;
        else $this->configTwiter['ConsumerKey'] = config('frodo.twiter.ConsumerKey');

        if($ConsumerSecret) $this->configTwiter['ConsumerSecret'] = $ConsumerSecret;
        else $this->configTwiter['ConsumerSecret'] = config('frodo.twiter.ConsumerSecret');

        if($AccessToken) $this->configTwiter['AccessToken'] = $AccessToken;
        else $this->configTwiter['AccessToken'] = config('frodo.twiter.AccessToken');

        if($AccessTokenSecret) $this->configTwiter['AccessTokenSecret'] = $AccessTokenSecret;
        else $this->configTwiter['AccessTokenSecret'] = config('frodo.twiter.AccessTokenSecret');

        return $this;
    }

    /**
     *
     */
    public function getPostsByAccountInterval(){
        $this->setConnection();

        foreach(Accounts::get() as $_account){

            //Checking the time interval for the latest updates
            if(Carbon::now()->diffInHours(Carbon::parse($_account->last_updated)) < $_account->refresh_interval) continue;

            try{
                $response = $this->connection->get('statuses/user_timeline', [  'screen_name'   => $_account->account_id     , //Selezax
                                                                                'count'         => 10 ,
                                                                            ] );
                if(isset($response->errors)){
                    foreach($response->errors as $k => $v){
                        echo $v->message . "\n";
                    }
                    continue;
                }

                foreach($response as $_post){
                    $post = Posts::firstOrNew(['post_id' => $_post->id]);

                    if ($post->exists) {
                        continue;
                    } else {
                        $post->datetime         = Carbon::parse($_post->created_at);
                        $post->account_id       = $_account->id;
                        $post->title            = $_post->text;
                        //$post->description      = $_post->description;
                        $post->num_favorites    = $_post->favorite_count  ;
                        $post->num_retweets     = $_post->retweet_count  ;
                        //$post->num_replies = $_post->  ;
                        $post->save();
                    }
                }

            } catch (\Exception $ex) {
                echo $ex->getMessage() . "\n";
                continue;
            }

            //Setting the time of last update
            $_account->last_updated = Carbon::now();
            $_account->save();

        }

        return true;

    }
    
    /**
     * @return array
     */
    public static function getAccountsWithCountPosts(){
        $_accounts = array();
        foreach(Accounts::with('posts')->get() as $_account){
            $_accounts[] = ['account_id'        => $_account->account_id,
                            'title'             => $_account->title,
                            'refresh_interval'  => $_account->refresh_interval,
                            'posts_number'      => $_account->posts->count()
                        ];
        }
        return $_accounts;
    }
    
    /**
     * @return array
     */
    public static function getPostsByAccount( $account_id, $limit ){
        $_posts = array();
        foreach(Accounts::where('account_id', $account_id)->first()
                                ->posts()
                                ->take($limit)
                                ->orderBy('datetime', 'DESC')
                                ->get()
                as $post){

            $_posts[] = [   'post_id'       => $post->post_id,
                            'title'         => $post->title,
                            'datetime'      => $post->datetime,
                            'num_favorites' => $post->num_favorites,
                            'num_retweets'  => $post->num_retweets,
                        ];
        }
        return $_posts;
    }
    
}