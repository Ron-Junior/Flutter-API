<?php

/**
 * Class PushNotificationAndroid is used to send PUSH notifications to Android devices.
 *
 * @property string $apiKey SERVER KEY of the PUSH service (Firebase, Google Cloud, etc)
 * @property string $title Title of the notification to be sent
 * @property string $message Text of notification
 * @property string $count Number that appears next to the message
 * @property string $audio Sound to be played upon arrival of the notification
 * @property string $androidChannelId Android Channel Id for Android: "8^" support
 * @property string $destinations Array with the registration IDs of recipients devices
 */

namespace api\modules\v1\components;

use yii\base\Component;

class PushNotificationAndroid extends Component
{
    public $apiEndPoint;
    public $apiKey = "MY_SERVER_KEY_FROM_FIREBASE_CONSOLE_OR_ANOTHER_SERVICE";
    public $title;
    public $message;
    public $count;
    public $audio;
    public $androidChannelId;
    public $destinations;
    public $sandboxMode = false;

    /**
     * Performs sends notification through APN PUSH service
     * @param bool $sandboxMode
     */
    function __construct($sandboxMode = false)
    {
        $this->sandboxMode = $sandboxMode;
        $this->apiEndPoint = $this->sandboxMode == true ? 'sandbox_url' : 'https://fcm.googleapis.com/fcm/send';
        $this->androidChannelId = "my_app_push_channel";
    }

    /**
     * Performs sends notification through GCM PUSH service
     * @return bool|mixed|string
     */
    public function send()
    {
        $validate = $this->validate();
        if ($validate) {
            $headers = array("Content-Type: application/json", "Authorization: key=$this->apiKey");

            $data = array(
                'data' => $this->formatNotification(),
                'registration_ids' => $this->destinations
            );

            $conn = curl_init();
            curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($conn, CURLOPT_URL, $this->apiEndPoint);
            curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($data));
            $result = curl_exec($conn);
            curl_close($conn);

            return $result;
        } else {
            return $validate;
        }
    }

    /**
     * Validate all of the required fields of the class
     * @return bool|string
     */
    public function validate()
    {
        if (empty($this->apiKey))
            return "Error: API KEY is required";
        if (empty($this->title))
            return "Error: Title is required";
        if (empty($this->message))
            return "Error: Message is required";
        if (empty($this->destinations))
            return "Error: At least one destination is required";
        else
            return true;
    }

    /**
     * Formats the notification to a correct format to be sent to the PUSH service
     * @return array
     */
    public function formatNotification()
    {
        $notification = array();

        $notification['title'] = $this->title;
        $notification['message'] = $this->message;
        $notification['android_channel_id'] = $this->androidChannelId;

        if (!empty($this->count))
            $notification['msgcnt'] = $this->count;
        if (!empty($this->audio))
            $notification['soundname'] = $this->audio;


        return $notification;
    }
}