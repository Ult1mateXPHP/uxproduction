<?php

namespace Application\Play\Minecraft;

use Infrastructure\Play\Minecraft\Rcon\RconDriver;

class RconApi
{
    /**
     * Send command to remote server via RCON Driver
     * @param $command
     * @return string[]
     */
    private function sendCommand($command) : array {
        $connection = new RconDriver('host', 'port', 'passwd',  5);
        $connection->connect();
        if(!$connection->sendCommand($command)) {
            return ['Error' => 'Connection Error'];
        } else {
            return ['Status' => 'Success'];
        }
    }

    /**
     * Method
     * @param $params
     * @return string[]
     */
    public static function say($params) {
        $command = "say ".$params;
        return self::sendCommand($command);
    }
}
