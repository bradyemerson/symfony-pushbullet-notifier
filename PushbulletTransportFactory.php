<?php

namespace Symfony\Component\Notifier\Bridge\Pushbullet;

use Symfony\Component\Notifier\Exception\UnsupportedSchemeException;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;
use Symfony\Component\Notifier\Transport\TransportInterface;

/**
 * @author Oskar Stark <oskarstark@googlemail.com>
 */
final class PushbulletTransportFactory extends AbstractTransportFactory
{
    protected $mailer;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return PushbulletTransport
     */
    public function create(Dsn $dsn): TransportInterface
    {
        $scheme = $dsn->getScheme();

        if (!\in_array($scheme, $this->getSupportedSchemes())) {
            throw new UnsupportedSchemeException($dsn, 'pushbullet', $this->getSupportedSchemes());
        }

        $token = $dsn->getUser();
        $device = $dsn->getOption('device');
        $host = 'default' === $dsn->getHost() ? null : $dsn->getHost();
        $port = $dsn->getPort();
        return new PushbulletTransport($token, $device);
    }

    protected function getSupportedSchemes(): array
    {
        return ['pushbullet'];
    }
}
