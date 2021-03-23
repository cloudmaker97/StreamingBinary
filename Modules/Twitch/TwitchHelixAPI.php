<?php
namespace Modules\Twitch;

use Helper\Output\OutputMessage;

/**
 * Class TwitchHelixAPI
 * @package Modules\Twitch
 */
class TwitchHelixAPI
{
    private $_AccessCode;
    private $_ClientID;
    private $_ClientSecret;

    public function __construct($ClientID, $ClientSecret)
    {
        $this->_ClientID = $ClientID;
        $this->_ClientSecret = $ClientSecret;
        $this->GetAccessCode();
    }

    /**
     * Get the access code by given Client ID and Secret
     * for API-Calls in the scope channel_read
     * @throws \Exception
     */
    private function GetAccessCode(): void {
        $ch = curl_init();
        $AuthURL = sprintf('https://id.twitch.tv/oauth2/token?client_id=%s&client_secret=%s&grant_type=client_credentials&scope=channel_read', $this->_ClientID, $this->_ClientSecret);
        curl_setopt_array($ch, array(
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $AuthURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer #NOTSET#'
            ))
        );
        $result = curl_exec($ch);
        $this->_AccessCode = json_decode($result)->access_token;

        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);
    }

    /**
     * Set the CURL Options for all requests (extraction)
     * modified for further methods (incl. Bearer-Token)
     * @param $ch object CURL Handler
     * @param $URL string Target URL
     * @param string $Method HTTP-Method (GET as default)
     */
    private function SetDefaultCurlOptions($ch, $URL, $Method='GET') {
        curl_setopt_array($ch, array(
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_URL => $URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $Method,
                CURLOPT_HTTPHEADER => array(
                    'Client-ID: '.$this->_ClientID,
                    'Authorization: Bearer '.$this->_AccessCode
                ))
        );
    }

    /**
     * Get the user id by a specified username
     * @param string $Name Username
     * @return int User ID
     * @throws \Exception
     */
    public function GetUserIDByName(string $Name): int {
        $ch = curl_init();
        $AuthURL = sprintf('https://api.twitch.tv/helix/users?login=%s', $Name);
        $this->SetDefaultCurlOptions($ch, $AuthURL);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);
        $Result = json_decode($result)->data[0]->id;
        if($Result) {
            return $Result;
        } else {
            throw new \Exception("Failed request");
        }
    }

    /**
     * Get the latest follower-object by specified user id
     * @param int $UserID Target User ID
     * @return object Latest Follower
     * @throws \Exception
     */
    public function GetUserLatestFollowerByUserID(int $UserID): object {
        $ch = curl_init();
        $AuthURL = sprintf('https://api.twitch.tv/helix/users/follows?to_id=%s&first=1', $UserID);
        $this->SetDefaultCurlOptions($ch, $AuthURL);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);

        $Result = json_decode($result)->data[0];
        if($Result) {
            return $Result;
        } else {
            throw new \Exception("Failed request");
        }
    }

    /**
     * Get the latest followers from a specified userid
     * @param int $UserID Target User ID
     * @return string Latest Follower-Username
     * @throws \Exception
     */
    public function GetUserLatestFollowerNameByUserID(int $UserID): string {
        return $this->GetUserLatestFollowerByUserID($UserID)->from_name;
    }

    /**
     * Get the current stream information from broadcaster (user id)
     * @param int $UserID Target Broadcaster User ID
     * @return object Stream-Information Collection
     * @throws \Exception
     */
    public function GetStreamInformationByUserID(int $UserID): object {
        $ch = curl_init();
        $AuthURL = sprintf('https://api.twitch.tv/helix/channels?broadcaster_id=%s', $UserID);
        $this->SetDefaultCurlOptions($ch, $AuthURL);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);
        $Result = json_decode($result)->data[0];
        if($Result) {
            return $Result;
        } else {
            throw new \Exception("Failed request");
        }
    }

    /**
     * Get the stream viewer count
     * @param int $UserID Target Stream User ID
     * @return int Viewer Count
     */
    public function GetStreamViewerCountByUserID(int $UserID): int {
        $ch = curl_init();
        $AuthURL = sprintf('https://api.twitch.tv/helix/streams?user_id=%s', $UserID);
        $this->SetDefaultCurlOptions($ch, $AuthURL);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);
        $Result = json_decode($result)->data[0]->viewer_count;
        if($Result) {
            return $Result;
        } else {
            return 0;
        }
    }
}