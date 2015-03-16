<?php
/**
 * Quick and ez wrapper for the jublonet's Twitter library in PHP (https://github.com/jublonet/codebird-php)
 *
 * IMPORTANT: Make sure your Twitter API credentials don't end up in a public repo.
 *
 * PHP version 5.3
 *
 * LICENSE: TODO
 *
 * @package WPezClasses
 * @author Mark Simchock <mark.simchock@alchemyunited.com>
 * @since 0.5.1
 * @license TODO
 */

/**
 * == Change Log ==
 *
 * == 0.5.0 - Sun 15 March 2015 ==
 * --- Pop the champagne!
 */

/**
 * == TODO ==
 *
 *
 */

// No WP? Die! Now!!
if (!defined('ABSPATH')) {
    header('HTTP/1.0 403 Forbidden');
    die();
}

if (!class_exists('Class_WP_ezClasses_API_Twitter_Codebird')) {
    class Class_WP_ezClasses_API_Twitter_Codebird extends Class_WP_ezClasses_Master_Singleton
    {

        protected $_str_consumer_key;
        protected $_str_consumer_secret;
        protected $_str_oauth_token;
        protected $_str_oauth_secret;

        protected $_return_format;

        protected $_obj_codebird;

        public function __construct()
        {
            parent::__construct();
        }

        /**
         *
         */
        public function ez__construct($arr_args = '')
        {

            require('codebird-php-master/codebird-php-master/src/codebird.php');

            $this->_obj_codebird = \Codebird\Codebird::getInstance();

            $this->codebird_todo();

            $this->_obj_codebird->setConsumerKey($this->_str_consumer_key, $this->_str_consumer_secret);

            $this->_obj_codebird->setToken($this->_str_oauth_token, $this->_str_oauth_secret);

            // dealing with objects proved to be "spotty" so let's KISS and use array. yeah. sucks.
            $this->_obj_codebird->setReturnFormat($this->_return_format);
        }



        /*
         * once you extends this class, add this method, just fill in these blanks and let'er rip
         *
         * IMPORANT - Make sure your credentials don't end up in a public repo
         */
        protected function codebird_todo()
        {

            $this->_str_consumer_key = 'YOURKEY';
            $this->_str_consumer_secret = 'YOURSECRET';

            $this->_str_oauth_token = 'YOURTOKEN';
            $this->_str_oauth_secret = 'YOURTOKENSECRET';

            $this->_return_format = CODEBIRD_RETURNFORMAT_ARRAY;

        }

        /*
         *
         */
        public function __call($method, $parameters)
        {

            $arr_parse_methods = $this->parse_methods();

            if (isset($arr_parse_method[$method])) {

                if ($arr_parse_methods[$method] !== true || !method_exists($this, $method)) {
                    return false;
                }

                return $this->$method($parameters);

            } else {

                // else it just goes to codebird
                // TODO? - pre-parse parms before going to codebird?
                return $this->_obj_codebird->$method($parameters[0]);
            }
        }

        /*
         * presuming eventually we'll have more than one "pre-parser"
         */
        protected function parse_methods()
        {
            $arr_parse_method = array(

                'latest_tweets' => true
            );
            return $arr_parse_method;
        }

        /**
         * Take a twitter latest tweets object / array and "pre-processes" it to return an array
         */
        public function latest_tweets($obj_tweets)
        {

            $arr_tweets = (array)$obj_tweets;

            $arr_return = array();

            foreach ($arr_tweets as $key => $arr_tweet) {
                if ( strval($key) == 'httpstatus' || strval($key) == 'rate' ) {
                    $arr_return[$key] = $arr_tweet;
                    continue;
                } elseif ($key == 0) {
                    $arr_return['user'] = $arr_tweet['user'];
                }
                $arr_return['tweets'][$key] = $arr_tweet;
            }
            return $arr_return;
        }

    }

}