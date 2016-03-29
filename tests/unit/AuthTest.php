<?php

namespace Test\unit;

use Audiens\AppnexusClient\Auth;
use Audiens\AppnexusClient\authentication\AuthStrategyInterface;
use Doctrine\Common\Cache\CacheProvider;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use Prophecy\Argument;
use Test\TestCase;

/**
 * Class AuthTest
 */
class AuthTest extends TestCase
{

    /**
     * @test
     */
    public function should_append_the_authorization_token_when_performing_any_request()
    {

        $username = 'sample_username';
        $password = 'sample_password';
        $token = 'a_sample_token123456789';

        $dummyStream = $this->prophesize(Stream::class);
        $dummyStream->getContents("{'response:{}'}");

        $dummyResponse = $this->prophesize(Response::class);
        $dummyResponse->getBody()->willReturn($dummyStream->reveal());

        $authStrategy = $this->prophesize(AuthStrategyInterface::class);

        $authStrategy->authenticate($username, $password, Argument::any())->willReturn($token)->shouldBeCalled();

        $expectedRequestOptions = [
            'headers' => [
                'Authorization' => $token,
            ],
        ];

        $client = $this->getMock(ClientInterface::class);

        $client
            ->expects($this->at(0))
            ->method('request')
            ->with('POST', 'random_url', $expectedRequestOptions)
            ->willReturn($dummyResponse->reveal());

        $auth = new Auth($username, $password, $client, $authStrategy->reveal());
        $auth->request('POST', 'random_url', []);


    }

    /**
     * @param $token
     *
     * @return Response
     */
    protected function getTokenResponse($token)
    {

        $responseBody = json_encode(
            [
                'response' => [
                    'token' => $token,
                ],
            ]
        );

        return new Response(200, [], $responseBody);

    }

}
