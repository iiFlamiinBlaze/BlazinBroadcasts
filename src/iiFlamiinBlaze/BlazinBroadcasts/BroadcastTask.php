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

use pocketmine\scheduler\PluginTask;

class BroadcastTask extends PluginTask{

    public function __construct(BlazinBroadcasts $main){
        parent::__construct($main);
    }

    public function onRun(int $tick) : void{
        $messages = BlazinBroadcasts::getInstance()->getConfig()->get("messages");
        $message = $messages[array_rand($messages)];
        $message = str_replace(array(
            "&",
            "{line}",
            "{max_players}",
            "{online_players}",
            "{tps}",
            "{motd}"
            ), array(
                "§",
                "\n",
                BlazinBroadcasts::getInstance()->getServer()->getMaxPlayers(),
                count(BlazinBroadcasts::getInstance()->getServer()->getOnlinePlayers()),
                BlazinBroadcasts::getInstance()->getServer()->getTicksPerSecond(),
                BlazinBroadcasts::getInstance()->getServer()->getMotd()
            ), $message);
        $prefix = str_replace("&", "§", BlazinBroadcasts::getInstance()->getConfig()->get("prefix"));
        BlazinBroadcasts::getInstance()->getServer()->broadcastMessage($prefix . $message);
    }
}