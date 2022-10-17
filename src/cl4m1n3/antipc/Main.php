<?php

namespace cl4m1n3\antipc;

use cl4m1n3\antipc\events\Events;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class Main extends PluginBase{
    
    public $cfg;
    private static $instance;
    
    static function getInstance() : Main{
        return self::$instance;
    }
    protected function onLoad() : void{
        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }
    protected function onEnable() : void{
        self::$instance = $this;
        Server::getInstance()->getPluginManager()->registerEvents(new Events(), $this);
    }
    public function checkPC($player) : bool{
        $data = $player->getPlayerInfo()->getExtraData();
        if($data["DeviceOS"] == 7 or $data["DeviceOS"] == 8){
            return true;
        }
        return false;
    }
    public function addLabel($player) : void{
        $nametag = $player->getNameTag();
        $label = Main::getInstance()->cfg->get("parameter.nametag.label");
        $result = $nametag;
        switch(Main::getInstance()->cfg->get("parameter.nametag.location")){
            case "bottom":
                $result = $nametag ."\n" .$label;
                break;
            case "top":
                $result = $label ."\n". $nametag;
                break;
            case "left":
                $result = $label. " ". $nametag;
                break;
            case "right":
                $result = $nametag. " ". $label;
                break;
            }
        $player->setNameTag($result);
    }
}