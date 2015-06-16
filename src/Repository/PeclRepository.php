<?php

namespace FancyGuy\Composer\Platform\Repository;

use Composer\Config;
use Composer\IO\IOInterface;
use Composer\EventDispatcher\EventDispatcher;
use Composer\Package\Version\VersionParser;
use Composer\Repository\ArrayRepository;
use Composer\Repository\Pear\ChannelReader;
//use Composer\Repository\Pear\ChannelInfo;
use Composer\Util\RemoteFilesystem;

class PeclRepository extends ArrayRepository
{

    private $url;
    private $io;
    private $rfs;
    private $versionParser;

    public function __construct(array $repoConfig, IOInterface $io, Config $config, EventDispatcher $dispatcher = null, RemoteFilesystem $rfs = null)
    {
        if (!preg_match('{^(?:https?:)?//}', $repoConfig['url'])) {
            $repoConfig['url'] = 'http://'.$repoConfig['url'];
        }

        $urlPieces = parse_url($repoConfig['url']);
        if (empty($urlPieces['scheme']) || empty($urlPieces['host'])) {
            throw new \UnexpectedValueException('Invalid url given for PECL repository: '.$repoCOnfig['url']);
        }

        $this->url = rtrim($repoConfig['url'], '/');
        $this->io = $io;
        $this->rfs = $rfs ?: new RemoteFilesystem($this->io, $config);
        $this->versionParser = new VersionParser();
    }

    protected function initialize()
    {
        parent::initialize();

        $this->io->writeError('Initializing PECL repository '.$this->url);
        $reader = new ChannelReader($this->rfs);
        try {
            $channelInfo = $reader->read($this->url);
        } catch (\Exception $e) {
            $this->io->writeError('<warning>PECL repository from '.$this->url.' could not be loaded. '.$e->getMessage().'</warning>');
            return;
        }
        var_dump($channelInfo); exit;
    }
}
