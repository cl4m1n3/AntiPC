<?php

namespace cl4m1n3\antipc\events;

use cl4m1n3\antipc\Main;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent};
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\player\Player;

class Events implements Listener{
    
    public function onDamage(EntityDamageEvent $event) : void{
        $entity = $event->getEntity();
        $penaltydamage = Main::getInstance()->cfg->get("parameter.damage.penalty");
        $delay = Main::getInstance()->cfg->get("parameter.damage.delay");
        if($event instanceof EntityDamageByEntityEvent){
            $damager = $event->getDamager();
            $damage = $event->getBaseDamage();
            if($entity instanceof Player && $damager instanceof Player && Main::getInstance()->cfg->get("status.damage")){
                if(Main::getInstance()->checkPC($damager)){
                    if($penaltydamage > 0 && $penaltydamage >= 15 && $penaltydamage <= 50 && $damage >= 4){
                        $result = round($damage * ($penaltydamage / 100));
                        if($entity->getHealth() > $result){
                            $entity->setHealth($entity->getHealth() + $result);
                        }
                    }
                    if($delay > 0 && $delay >= 11 && $delay <= 20){
                        $event->setAttackCooldown($delay);
                    }
                }
            }
        }
    }
    public function onLogin(PlayerLoginEvent $event) : void{
        $player = $event->getPlayer();
        if(Main::getInstance()->checkPC($player)){
            if(Main::getInstance()->cfg->get("status.kick")){
                $player->kick(Main::getInstance()->cfg->get("parameter.kick.message"), false);
                return;
            }
            if(Main::getInstance()->cfg->get("status.nametag")){
                Main::getInstance()->addLabel($player);
            }
        }
    }
}