<?php
namespace App\Services;

class WhatsAppService
{

    public static function getPlatformOptions(){
        return [
            'facebook' => 'Facebook',
              'instagram' => 'Instagram',
              'whatsapp' => 'Whatsapp',
              'telegram' => 'Telegram',
              'snapchat' => 'Snapchat',

              'tiktok' => 'TikTok',
              'youtube' => 'Youtube',
              'twitter' => 'Twitter',
              'linkedin' => 'Linkedin',
              'other' => 'Other',

              // Add more platforms as needed
        ];
    }

    public static function getDaysOptions(){
        return [
            "1" => 'Saturday',
            "2"=> 'Sunday',
            "3" => 'Monday',
            "4" => 'Tuesday',
            "5" => 'Wednesday',
            "6" => 'Thursday',
            "7" => 'Friday',
        ];
    }
    public static function getOptions(){
        return [
            'post' => 'Post',
            'video' => 'Video',
            'reel' => 'Reel',
            'image' => 'Image',
            'bio' => 'Bio',
            'cover' => 'Cover',
        ];
    }
    public static function send_with_wapi($auth, $profileId, $phone, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ads.2moh.net/wapi2024/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'auth=' . $auth . '&profile_id=' . $profileId . '&phone=' . $phone . '&msg=' . $message,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: 40703bb7812b727ec01c24f2da518c407342559c'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);



        return $response;
    }

    public static  function sendPostRequest($url, $data) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }

        curl_close($curl);

        if (isset($error_msg)) {
            return "cURL Error: " . $error_msg;
        }

        return $response;
    }
}
