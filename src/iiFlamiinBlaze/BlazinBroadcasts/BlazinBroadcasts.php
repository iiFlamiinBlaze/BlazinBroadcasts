<?php
/**
 *  ____  _            _______ _          _____
 * |  _ \| |          |__   __| |        |  __ \
 * | |_) | | __ _ _______| |  | |__   ___| |  | | _____   __
 * |  _ <| |/ _` |_  / _ \ |  | '_ \ / _ \ |  | |/ _ \ \ / /
 * | |_) | | (_| |/ /  __/ |  | | | |  __/ |__| |  __/\ V /
 * |____/|_|\__,_/___\___|_|  |_| |_|\___|_____/ \___| \_/
 *
 * Copyright (C) 2018 iiFlamiinBlaze
 *
 * iiFlamiinBlaze's plugins are licensed under MIT license!
 * Made by iiFlamiinBlaze for the PocketMine-MP Community!
 *
 * @author iiFlamiinBlaze
 * Twitter: https://twitter.com/iiFlamiinBlaze
 * GitHub: https://github.com/iiFlamiinBlaze
 * Discord: https://discord.gg/znEsFsG
 */
declare(strict_types=1);

namespace iiFlamiinBlaze\BlazinBroadcasts;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class BlazinBroadcasts extends PluginBase{

    const VERSION = "v1.0.1";
    const PREFIX = TextFormat::AQUA . "BlazinBroadcasts" . TextFormat::GOLD . " > ";

    /** @var self $instance */
    private static $instance;

    public function onEnable() : void{
        self::$instance = $this;
        $this->getLogger()->info("BlazinBroadcasts " . self::VERSION . " by iiFlamiinBlaze is enabled");
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->messageIntegerCheck();
        $this->commandIntegerCheck();
    }

    private function messageIntegerCheck() : bool{
        if(is_integer($this->getConfig()->get("message_interval"))){
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new BroadcastTask($this), $this->getConfig()->get("message_interval") * 20);
        }else{
            $this->getLogger()->error(TextFormat::RED . "Please enter an integer for the message interval! Plugin Disabling...");
            $this->getPluginLoader()->disablePlugin($this);
            return false;
        }
        return true;
    }

    private function commandIntegerCheck() : bool{
        if(is_integer($this->getConfig()->get("command_interval"))){
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new CommandTask($this), $this->getConfig()->get("command_interval") * 20);
        }else{
            $this->getLogger()->error(TextFormat::RED . "Please enter an integer for the command interval! Plugin Disabling...");
            $this->getPluginLoader()->disablePlugin($this);
            return false;
        }
        return true;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        if($command->getName() === "blazinbroadcasts"){
            if(!$sender instanceof Player){
                $sender->sendMessage(self::PREFIX . TextFormat::RED . "Use this command in-game");
                return false;
            }
            if(!$sender->hasPermission("blazinbroadcasts.command")){
                $sender->sendMessage(self::PREFIX . TextFormat::RED . "You do not have permission to use this command");
                return false;
            }
            if(empty($args)){
                $sender->sendMessage(self::PREFIX . TextFormat::GRAY . "Usage: /blazinbroadcasts <info | set | reload> <messageinterval | commandinterval | prefix> <message>");
                return false;
            }
            switch($args[0]){
                case "info":
                    $sender->sendMessage(TextFormat::DARK_GRAY . "-=========" . TextFormat::GOLD . "BlazinBroadcasts " . self::VERSION . TextFormat::DARK_GRAY . "=========-");
                    $sender->sendMessage(TextFormat::GREEN . "Author: " . TextFormat::AQUA . "BlazeTheDev");
                    $sender->sendMessage(TextFormat::GREEN . "GitHub: " . TextFormat::AQUA . "https://github.com/iiFlamiinBlaze");
                    $sender->sendMessage(TextFormat::GREEN . "Support: " . TextFormat::AQUA . "https://discord.gg/znEsFsG");
                    $sender->sendMessage(TextFormat::GREEN . "Description: " . TextFormat::AQUA . "Allows you to customize messages that will send at a select interval of time");
                    $sender->sendMessage(TextFormat::DARK_GRAY . "-===============================-");
                    break;
                case "set":
                    switch($args[1]){
                        case "messageinterval":
                            if(is_integer((int)$args[2])){
                                $this->getConfig()->set("message_interval", (int)$args[2]);
                                $this->getConfig()->save();
                                $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You have set the message interval successfully to $args[2]");
                            }else{
                                $sender->sendMessage(self::PREFIX . TextFormat::RED . "Please enter an integer to set the interval too");
                                return false;
                            }
                            break;
                        case "commandinterval":
                            if(is_integer((int)$args[2])){
                                $this->getConfig()->set("command_interval", (int)$args[2]);
                                $this->getConfig()->save();
                                $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You have set the command interval successfully to $args[2]");
                            }else{
                                $sender->sendMessage(self::PREFIX . TextFormat::RED . "Please enter an integer to set the interval too");
                                return false;
                            }
                            break;
                        case "prefix":
                            if(is_string($args[2])){
                                $this->getConfig()->set("prefix", $args[2]);
                                $this->getConfig()->save();
                                $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "You have set the prefix successfully to $args[2]");
                            }else{
                                $sender->sendMessage(self::PREFIX . TextFormat::RED . "Please enter an string to set the prefix too");
                                return false;
                            }
                            break;
                        default:
                            $sender->sendMessage(self::PREFIX . TextFormat::GRAY . "Usage: /blazinbroadcasts <info | set | reload> <messageinterval | commandinterval | prefix> <message>");
                            break;
                    }
                    break;
                case "reload":
                    $this->getConfig()->save();
                    $this->getConfig()->reload();
                    $sender->sendMessage(self::PREFIX . TextFormat::GREEN . "BlazinBroadcasts successfully reloaded");
                    break;
                default:
                    $sender->sendMessage(self::PREFIX . TextFormat::GRAY . "Usage: /blazinbroadcasts <info | set | reload> <messageinterval | commandinterval | prefix> <message>");
                    break;
            }
        }
        return true;
    }

    public static function getInstance() : self{
        return self::$instance;
    }
}