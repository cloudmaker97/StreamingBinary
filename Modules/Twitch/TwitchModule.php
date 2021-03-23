<?php
namespace Modules\Twitch;
use Classes\Module;
use Helper\Output\OutputMessage;

/**
 * Class TwitchModule
 * @package Modules\Twitch
 */
class TwitchModule extends Module {

    function OnInitialize($args): void
    {
        $this->SetModuleName("Twitch");
        $this->SetOutputFolder("Twitch");
        $this->SetPlannedSkipCount(5);
        $this->UpdateTwitchData($args);
    }

    function OnIntervalUpdate($args): void
    {
        if($this->GetIsInactiveSkip()) {
            $this->UpdateTwitchData($args);
        }
    }

    private function UpdateTwitchData($args) {
        $HelixAPI = new TwitchHelixAPI($args["config"]->Twitch->ClientID, $args["config"]->Twitch->ClientSecret);

        $UserID = $HelixAPI->GetUserIDByName("cloudmaker97");
        $LatestFollower = $HelixAPI->GetUserLatestFollowerNameByUserID($UserID);
        $StreamInformation = $HelixAPI->GetStreamInformationByUserID($UserID);

        $this->WriteFile("latest_follower.txt", $LatestFollower);
        $this->WriteFile("current_stream_game.txt", $StreamInformation->game_name);
        $this->WriteFile("current_stream_title.txt", $StreamInformation->title);
        $this->WriteFile("stream_information.json", json_encode(["StreamInformation" => $StreamInformation, "LatestFollower" => $LatestFollower]));
        OutputMessage::create("Die Twitch-Informationen wurden abgerufen", "Modules");
    }
}