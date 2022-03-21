<?php
declare(strict_types=1);

namespace Gared\TorDirectoryClient;

use Gared\TorDirectoryClient\Model\RouterDescriptor;
use Gared\TorDirectoryClient\Parser\RouterStatusParser;
use Gared\TorDirectoryClient\Parser\ServerDescriptorParser;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\RequestOptions;
use Gared\TorDirectoryClient\Model\ServerDescriptor;

class Client
{
    public const DIRECTORY_AUTHORITIES = [
        '9695DFC35FFEB861329B9F1AB04C46397020CE31' => '128.31.0.39:9131', // moria1
        '847B1F850344D7876491A54892F904934E4EB85D' => '86.59.21.38:80', // tor26
        '7EA6EAD6FD83083C538F44038BBFA077587DD755' => '45.66.33.45:80', // dizum
        'F2044413DAC2E02E3D6BCF4735A19BCA1DE97281' => '131.188.40.189:80', // gabelmoo
        '7BE683E65D48141321C5ED92F075C55364AC7123' => '193.23.244.244:80', // dannenberg
        'BD6A829255CB08E66FBE7D3748363586E46B3810' => '171.25.193.9:443', // maatuska
        'CF6D0AAFB385BE71B8E111FC5CFF4B47923733BC' => '154.35.175.225:80', // Faravahar
        '74A910646BCEEFBCD2E874FC1DC997430F968145' => '199.58.81.140:80', // longclaw
        '24E2F139121D4394C54B5BCC368B3B411857C413' => '204.13.164.118:80', // bastet
    ];

    private ClientInterface $httpClient;
    private array $directoryServers = self::DIRECTORY_AUTHORITIES;

    /**
     * @param ClientInterface|null $client Optional Client object used to do requests
     */
    public function __construct(ClientInterface $client = null)
    {
        if ($client === null) {
            $this->httpClient = $this->createDefaultHttpClient();
        } else {
            $this->httpClient = $client;
        }
    }

    public function setDirectoryServers(array $directoryServers): void
    {
        $this->directoryServers = $directoryServers;
    }

    private function getDirectoryServer(): string
    {
        $key = array_rand($this->directoryServers);
        return $this->directoryServers[$key];
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    protected function createDefaultHttpClient(): ClientInterface
    {
        return new \GuzzleHttp\Client([
            RequestOptions::TIMEOUT => 5,
            RequestOptions::CONNECT_TIMEOUT => 5,
        ]);
    }

    /**
     * @return RouterDescriptor[]
     */
    public function getConsensus(): array
    {
        $response = $this->httpClient->request('GET', $this->getDirectoryServer() . '/tor/status-vote/current/consensus.z');

        $responseData = (string)$response->getBody();

        $parser = new RouterStatusParser();
        return $parser->parse($responseData);
    }

    /**
     * @return RouterDescriptor[][]
     */
    public function getAllVotes(): array
    {
        $promises = [];
        foreach ($this->directoryServers as $fingerprint => $directoryAuthority) {
            $promises[$fingerprint] = $this->httpClient->requestAsync('GET', $directoryAuthority . '/tor/status-vote/current/authority.z');
        }
        $responses = Utils::settle($promises)->wait();

        $return = [];
        $parser = new RouterStatusParser();
        foreach ($responses as $fingerprint => $response) {
            if ($response['state'] !== 'fulfilled') {
                continue;
            }
            $responseData = (string)$response['value']->getBody();
            $return[$fingerprint] = $parser->parse($responseData);
        }

        return $return;
    }

    public function getServerDescriptor(string $fingerprint): ?ServerDescriptor
    {
        $path = sprintf('/tor/server/fp/%s.z', $fingerprint);
        for ($i = 0; $i < 10; $i++) {
            try {
                $server = $this->getDirectoryServer();
                $response = $this->httpClient->request('GET', $server . $path);
            } catch (GuzzleException $e) {

            }
        }

        if (!isset($response)) {
            return null;
        }

        $responseData = (string)$response->getBody();

        $parser = new ServerDescriptorParser();
        return $parser->parse($responseData);
    }
}