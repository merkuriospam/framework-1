<?php

namespace Kraken\Console\Server\Command\Project;

use Kraken\Channel\ChannelBaseInterface;
use Kraken\Channel\Extra\Request;
use Kraken\Runtime\Command\Command;
use Kraken\Command\CommandInterface;
use Kraken\Config\Config;
use Kraken\Config\ConfigInterface;
use Kraken\Throwable\Exception\Runtime\Execution\RejectionException;
use Kraken\Runtime\RuntimeCommand;

class ProjectStatusCommand extends Command implements CommandInterface
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var ChannelBaseInterface
     */
    protected $channel;

    /**
     *
     */
    protected function construct()
    {
        $core = $this->runtime->getCore();

        $config  = $core->make('Kraken\Config\ConfigInterface');
        $channel = $core->make('Kraken\Runtime\Channel\ChannelInterface');

        $this->config  = $this->createConfig($config);
        $this->channel = $channel;
    }

    /**
     *
     */
    protected function destruct()
    {
        unset($this->channel);
        unset($this->config);
    }

    /**
     * @param mixed[] $params
     * @return mixed
     * @throws RejectionException
     */
    protected function command($params = [])
    {
        $req = $this->createRequest(
            $this->channel,
            $this->config->get('main.alias'),
            new RuntimeCommand('arch:status')
        );

        return $req->call();
    }

    /**
     * Create Request.
     *
     * @param ChannelBaseInterface $channel
     * @param string $receiver
     * @param string $command
     * @return Request
     */
    protected function createRequest(ChannelBaseInterface $channel, $receiver, $command)
    {
        return new Request($channel, $receiver, $command);
    }

    /**
     * Create Config.
     *
     * @param ConfigInterface|null $config
     * @return Config
     */
    protected function createConfig(ConfigInterface $config = null)
    {
        return new Config($config === null ? [] : $config->get('core.project'));
    }
}
