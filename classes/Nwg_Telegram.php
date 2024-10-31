<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Telegram Bot Class adapted for WordPress removing CURL and using wp_remote_post
 * Removed also unused functions
 * @author Roberto Bruno
 * FORKED FROM Gabriele Grillo <gabry.grillo@alice.it>
 */
class Nwg_Telegram {

    private $bot_id = "";
    private $data = array();
    private $updates = array();

    /// Class constructor
    /**
     * Create a Telegram instance from the bot token
     * \param bot_id the bot token
     * \return an instance of the class
     */
    public function __construct($bot_id) {
        $this->bot_id = $bot_id;
        $this->data = $this->getData();
    }

    /// Do requests to Telegram Bot API
    /**
     * Contacts the various API's endpoints
     * \param api the API endpoint
     * \param $content the request parameters as array
     * \param $post boolean tells if $content needs to be sends
     * \return the JSON Telegram's reply
     */
    public function endpoint($api, array $content, $post = true, $ctype = 'text') {
        $url = 'https://api.telegram.org/bot' . $this->bot_id . '/' . $api;

        if ($ctype == 'photo') {
            $reply = $this->sendAPIRequest($url, $content, true, 'photo');
            return $reply;
        }

        if ($post)
            $reply = $this->sendAPIRequest($url, $content);
        else
            $reply = $this->sendAPIRequest($url, array(), false);
        return $reply;
    }

    /// A method for testing your bot.
    /**
     * A simple method for testing your bot's auth token. Requires no parameters. 
     * Returns basic information about the bot in form of a User object.
     * \return the JSON Telegram's reply
     */
    public function getMe() {
        return $this->endpoint("getMe", array(), false);
    }

    /// Send a message
    
    public function sendMessage(array $content) {
        return $this->endpoint("sendMessage", $content);
    }


    /// Send a photo
    
    public function sendPhoto(array $content) {
        return $this->endpoint("sendPhoto", $content, true, 'photo');
    }

    /// Send an audio (for future use)
    
    public function sendAudio(array $content) {
        return $this->endpoint("sendAudio", $content);
    }

    /// Send a document (for future use)
   
    public function sendDocument(array $content) {
        return $this->endpoint("sendDocument", $content);
    }

    /// Send a sticker (for future use)
   
    public function sendSticker(array $content) {
        return $this->endpoint("sendSticker", $content);
    }

    /// Send a video (for future use)
    
    public function sendVideo(array $content) {
        return $this->endpoint("sendVideo", $content);
    }

    /// Send a voice message (for future use)
    
    public function sendVoice(array $content) {
        return $this->endpoint("sendVoice", $content);
    }

    /// Send a location (for future use)
    
    public function sendLocation(array $content) {
        return $this->endpoint("sendLocation", $content);
    }

        /// Get the data of the current message
    /** Get the POST request of a user in a Webhook or the message actually processed in a getUpdates() enviroment.
     * \return the JSON users's message
     */
    public function getData() {
        if (empty($this->data)) {
            $rawData = file_get_contents("php://input");
            return json_decode($rawData, true);
        } else {
            return $this->data;
        }
    }

    /// Set the data currently used
    public function setData(array $data) {
        $this->data = data;
    }


    private function sendAPIRequest($url, array $content, $post = true, $ctype ='text') {
        if (isset($content['chat_id'])) {
            $url = $url . "?chat_id=" . $content['chat_id'];
            unset($content['chat_id']);
        }

        if ($ctype == 'text') {
            $args=array(
                   
                'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
                'body'      => json_encode($content),
                'method'    => 'POST'
            );
           }  


        // not working - for future use
        if ($ctype == 'photo') {
            $args=array(
                'method' => 'POST',
                'headers'   => array('Content-Type' => 'application/json;'),
                'httpversion' => '1.0',
                'sslverify' => true,
                    'headers'     => array(
                                         'accept'        => 'application/json', 
                                        'content-type'  => 'application/binary', // Set content type to binary 
                                    ),
                'body'      => json_encode($content),
            );
           } 


        $result = wp_remote_post( $url, $args );
        return $result;
    }

}

// Helper for Uploading file using CURL --- NOT IMPLEMENTED
if (!function_exists('nwg_file_create')) {

    function nwg_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
                . ($postname ? : basename($filename))
                . ($mimetype ? ";type=$mimetype" : '');
    }

}
?>
