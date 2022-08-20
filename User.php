<?php 
namespace Profile;

class User 
{
    public $steamid64;
    private $webapikey;
    private $url;

    public function __construct($steamid64 = 76561199197845115, $webapikey = '')
    {
        $this->webapikey = $webapikey;
        $this->steamid64 = $steamid64;
        $this->url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $this->webapikey . "&steamids=" . $this->steamid64);
    }

    /**
     * 
     * Get SteamID 32 
     * 
     */

    public function getST32()
    {
        $char = "/^(7656119)([0-9]{10})$/";
        if (preg_match($char, $this->steamid64, $match)) {
            $const1 = 7960265728;
            $const2 = "STEAM_1:";
            $steamid32 = '';
            if ($const1 <= $match[2]) {
                $a = ($match[2] - $const1) % 2;
                $b = ($match[2] - $const1 - $a) / 2;
                $steamid32 = $const2 . $a . ':' . $b;
            }
            return $steamid32;
        }
        return false;
    }

    /**
     * 
     * Get SteamID 3 
     * 
     */

    public function getST3()
    {
        $steamid32 = $this->getST32();
        if (preg_match('/^STEAM_1\:1\:(.*)$/', $steamid32, $res)) {

            $steamid3 = '[U:1:';
            $steamid3 .= $res[1] * 2 + 1;
            $steamid3 .= ']';
            return $steamid3;
        }
        return false;
    }

    /**
     * 
     * Get User Avatar
     * 
     */

    public function getAvatar()
    {
        $content = json_decode($this->url, true);
        $avatar = (empty($content['response']['players'][0]['avatarfull'])) ? 'https://yt3.ggpht.com/ytc/AKedOLQyloy0kaS_6NaDzM2kTcZvdXNmAHzTrs5Cuisg=s900-c-k-c0x00ffffff-no-rj' : $content['response']['players'][0]['avatarfull'];
        return $avatar;
    }

    /**
     * 
     * Get User Name
     * 
     */

    public function getName()
    {
        $content = json_decode($this->url, true);
        $name = (empty($content['response']['players'][0]['personaname'])) ? 'Незнакомец' : $content['response']['players'][0]['personaname'];
        return $name;
    }

    /**
     * 
     * Get User Link
     * 
     */

    public function getProfileUrl() 
    {
        $content = json_decode($this->url, true);
        $profileurl = (empty($content['response']['players'][0]['profileurl'])) ? 'Незнакомец' : $content['response']['players'][0]['profileurl'];
        return $profileurl;
    }

    /**
     * 
     * Get User Friends
     * 
     */

    public function getFriends() 
    {
        $url = file_get_contents("http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=" . $this->webapikey . "&steamid=" . $this->steamid64 . '&relationship=friend');
        $content = json_decode($url, true);
        $friends = (empty($content['friendslist']['friends'])) ? 'Друзей не найдено' : $content['friendslist']['friends'];
        return $friends;
    }

    /**
     * 
     * Get User Games
     * 
     */

    public function getGames() 
    {
        $url = file_get_contents("http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" . $this->webapikey . "&steamid=" . $this->steamid64 . '&format=json');
        $content = json_decode($url, true);
        $friends = (empty($content['response'])) ? 'Игр не найдено' : $content['response'];
        return $friends;
    }
}
