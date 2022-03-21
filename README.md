# tor-directory-client 

Use this PHP library to request tor directory information 

## Installation

Use composer
```console
composer require gared/tor-directory-client
```

## Getting started

### Usage

```php
$client = new \Gared\TorDirectoryClient\Client();
$serverDescriptor = $client->getServerDescriptor('FINGERPRINTABC');

// get consensus data
$routerDescriptors = $client->getConsensus();

// get consensus data
$votes = $client->getAllVotes();
foreach ($votes as $vote) {
    foreach ($vote as $routerDescriptor) {
        echo $routerDescriptor->getFingerprint();    
    }
}
```

## Supported Platforms

* You need at least PHP 7.4

## Supported Requests
| API                                | Code                            |
|------------------------------------|---------------------------------|
| /tor/status-vote/current/consensus | $client->getConsensus();        |
| /tor/status-vote/current/authority | $client->getAllVotes();         |
| /tor/server/fp/%s                  | $client->getServerDescriptor(); |