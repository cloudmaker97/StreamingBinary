<?php
namespace Modules;
use Classes\Module;
use Helper\Output\OutputMessage;

/**
 * Class SongModule
 * @package Modules
 */
class SongModule extends Module
{
    function OnInitialize($args): void
    {
        $this->SetModuleName("Songs");
        $this->SetPlannedSkipCount(5);
        $this->SetSettings([
            "API_Key" => $args["config"]["last_fm"]["api_key"],
            "Username" => $args["config"]["last_fm"]["username"]
        ]);
        $this->UpdateSongInformation();
    }

    function OnIntervalUpdate($args): void
    {
        if($this->GetIsInactiveSkip()) {
            $this->UpdateSongInformation();
        }
    }

    private function WriteTextFile(array $SongData) {
        file_put_contents(__DIR__."/../Output/current_song.txt", $SongData["SongTitle"]);
        file_put_contents(__DIR__."/../Output/current_artist.txt", $SongData["ArtistTitle"]);
    }

    private function WriteJsonFile(array $SongData) {
        file_put_contents(__DIR__."/../Output/current_song.json", json_encode($SongData));
    }

    private function UpdateSongInformation(): void
    {
        $RequestUrl = sprintf("https://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&limit=1&extended=1&user=%s&api_key=%s&format=json",
            $this->GetSettingsValue("Username"),
            $this->GetSettingsValue("API_Key"),
        );
        $RequestData = file_get_contents($RequestUrl);
        $RequestData = json_decode($RequestData)->recenttracks->track[0];
        $SongData = [
            "FullText" => sprintf("%s - %s", $RequestData->name, $RequestData->artist->name),
            "SongTitle" => $RequestData->name,
            "ArtistTitle" => $RequestData->artist->name,
            "LastUpdate" => time(),
        ];

        $this->WriteJsonFile($SongData);
        $this->WriteTextFile($SongData);
    }
}