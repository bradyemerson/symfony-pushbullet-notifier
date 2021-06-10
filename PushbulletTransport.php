<?php

namespace Symfony\Component\Notifier\Bridge\Pushbullet;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Notifier\Exception\UnsupportedMessageTypeException;
use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Transport\TransportInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Pushbullet\Exceptions\PushbulletException;

/**
 * @author Oskar Stark <oskarstark@googlemail.com>
 */
final class PushbulletTransport implements TransportInterface
{
    protected const HOST = 'default';

    private $pb;
    private $device;

    public function __construct(string $apiKey, $device)
    {
        $this->pb = new \Pushbullet\Pushbullet($apiKey);
        $this->device = $device;
    }

    public function __toString(): string
    {
        return sprintf('pushbullet://%s?device=%s', 'default', $this->device);
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof ChatMessage;
    }

    /**
     * @param MessageInterface|ChatMessage $message
     *
     * @throws TransportExceptionInterface
     */
    public function send(MessageInterface $message): SentMessage
    {
        if (!$this->supports($message)) {
            throw new UnsupportedMessageTypeException(__CLASS__, ChatMessage::class, $message);
        }

        try {
            if ($this->device && $this->device !== 'all') {
                $this->pb->device($this->device)->pushNote($message->getSubject(), null);
            } else {
                $this->pb->allDevices()->pushNote($message->getSubject(), null);
            }
        } catch (PushbulletException $ex) {
            throw new TransportException('Unable to post the Pushbullet message: ' . $ex->getMessage(), null, 0, $ex);
        }

        return new SentMessage($message, (string) $this);
    }
}
