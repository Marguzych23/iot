<?php


namespace App\Service;

use unreal4u\MQTT\Client;
use unreal4u\MQTT\DataTypes\BrokerPort;
use unreal4u\MQTT\DataTypes\ClientId;
use unreal4u\MQTT\DataTypes\Message;
use unreal4u\MQTT\DataTypes\TopicName;
use unreal4u\MQTT\Protocol\Connect;
use unreal4u\MQTT\Protocol\Connect\Parameters;
use unreal4u\MQTT\Protocol\Publish;

class MQTTService
{
    const USER     = '';
    const PASSWORD = '';

    const TOPIC = 'iot/project';
    const HOST  = '10.10.0.15';
    const PORT  = 1883;

    /**
     * @param array $color
     *
     * @return array
     */
    public static function sendToRaspberry(array $color = [])
    {
        $result  = false;
        $message = 'Error';

        try {
            $connectionParameters = new Parameters(
                new ClientId(basename(__FILE__)),
                self::HOST
            );
            $connectionParameters->setBrokerPort(new BrokerPort(self::PORT, 'ssl'));
            $connectionParameters->setCredentials(self::USER, self::PASSWORD);

            $connect = new Connect();
            $connect->setConnectionParameters($connectionParameters);

            $client = new Client();
            $client->processObject($connect);

            if ($client->isConnected()) {
                $publish = new Publish();

                $publish->setMessage(
                    new Message(
                        json_encode($color),
                        new TopicName(self::TOPIC)
                    )
                );
                $client->processObject($publish);
            }

            $result = true;
        } catch (\Throwable $e) {
            $message = $e->getMessage();
        }

        return [$result, $message];
    }

}