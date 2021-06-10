# symfony-pushbullet-notifier
Symfony Notification factory for [Pushbullet](https://www.pushbullet.com/) service

# How to use
To register the new factory, add the following section to your services.xml:

    Symfony\Component\Notifier\Bridge\Pushbullet\PushbulletTransportFactory:
	    parent: 'notifier.transport_factory.abstract'
	    tags: ['chatter.transport_factory']

Configure the DSN in packages/notifier.yaml

    framework:
	    notifier:
		    chatter_transports:
			    pushbullet: '%env(PUSHBULLET_DSN)%'

Pushbullet DSN format is:

    pushbullet://<apikey>@default?device=<deviceId>

device attribute can be excluded or set to "all" to send to all devices