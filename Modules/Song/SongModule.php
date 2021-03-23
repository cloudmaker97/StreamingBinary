<?php
namespace Modules\Song;
use Classes\Module;
use Configuration\ConfigurationLoader;
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
        $this->SetOutputFolder("Song");
        $this->SetPlannedSkipCount(5);

        $this->UpdateSongInformation($args);
    }

    function OnIntervalUpdate($args): void
    {
        if($this->GetIsInactiveSkip()) {
            $this->UpdateSongInformation($args);
        }
    }

    private function WriteTextFile(array $SongData) {
        $this->WriteFile("current_song.txt", $SongData["SongTitle"]);
        $this->WriteFile("current_artist.txt", $SongData["ArtistTitle"]);
    }

    private function WriteJsonFile(array $SongData) {
        $this->WriteFile("current_song.json", json_encode($SongData));
    }

    private function UpdateSongInformation($args): void
    {
        $RequestUrl = sprintf("https://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&limit=1&extended=1&user=%s&api_key=%s&format=json",
            $args["config"]->LastFM->Username,
            $args["config"]->LastFM->ClientSecret,
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
        OutputMessage::create("Die Song-Informationen wurden abgerufen", "Modules");
    }
}