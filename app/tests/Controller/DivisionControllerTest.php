<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DivisionControllerTest extends WebTestCase
{
    private const APPLICATION_JSON = 'application/json';
    private const KEY_ERRORS = 'errors';
    private const KEY_DIVIDEND = 'dividend';
    private const KEY_DIVIDER = 'divider';
    private const ERROR_PHP_FLOAT_MAX = 'PHP_FLOAT_MAX';
    private const URI = '/division';
    private const ERROR_PHP_FLOAT_MIN = 'PHP_FLOAT_MIN';

    public function testPositive(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', self::URI, [self::KEY_DIVIDEND => 9, self::KEY_DIVIDER => 3]);
        $this->assertResponseIsSuccessful();
        $this->assertEquals(self::APPLICATION_JSON, $client->getResponse()->headers->get('Content-Type'));
        $result = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('result', $result);
        $this->assertEquals(3, $result['result']);
    }

    public function testMinimalFloat(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::URI,
            [],
            [],
            [],
            '{"dividend": -1.9976931348623E+308, "divider": -1.9976931348623E+308}'
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals(self::APPLICATION_JSON, $client->getResponse()->headers->get('Content-Type'));
        $result = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertIsArray($result);
        $this->assertArrayHasKey(self::KEY_ERRORS, $result);
        $this->assertArrayHasKey(self::KEY_DIVIDEND, $result[self::KEY_ERRORS]);
        $this->assertNotFalse(mb_strpos($result[self::KEY_ERRORS][self::KEY_DIVIDEND], self::ERROR_PHP_FLOAT_MAX));
        $this->assertArrayHasKey(self::KEY_DIVIDER, $result[self::KEY_ERRORS]);
        $this->assertNotFalse(mb_strpos($result[self::KEY_ERRORS][self::KEY_DIVIDER], self::ERROR_PHP_FLOAT_MAX));
    }

    public function testMaximalFloat(): void
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::URI,
            [],
            [],
            [],
            '{"dividend": 1.9976931348623E+308, "divider": 1.9976931348623E+308}'
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertEquals(self::APPLICATION_JSON, $client->getResponse()->headers->get('Content-Type'));
        $result = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertIsArray($result);
        $this->assertArrayHasKey(self::KEY_ERRORS, $result);
        $this->assertArrayHasKey(self::KEY_DIVIDEND, $result[self::KEY_ERRORS]);
        $this->assertNotFalse(mb_strpos($result[self::KEY_ERRORS][self::KEY_DIVIDEND], self::ERROR_PHP_FLOAT_MAX));
        $this->assertArrayHasKey(self::KEY_DIVIDER, $result[self::KEY_ERRORS]);
        $this->assertNotFalse(mb_strpos($result[self::KEY_ERRORS][self::KEY_DIVIDER], self::ERROR_PHP_FLOAT_MAX));
    }

    public function testZeroDivided(): void
    {
        $client = static::createClient();
        $client->jsonRequest('POST', self::URI, [self::KEY_DIVIDEND => 10, self::KEY_DIVIDER => 0.0]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $result = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey(self::KEY_ERRORS, $result);
        $this->assertArrayHasKey(self::KEY_DIVIDER, $result[self::KEY_ERRORS]);
        $this->assertArrayNotHasKey(self::KEY_DIVIDEND, $result[self::KEY_ERRORS]);
        $this->assertNotFalse(mb_strpos($result[self::KEY_ERRORS][self::KEY_DIVIDER], self::ERROR_PHP_FLOAT_MIN));
    }

    public function testMaxNegativeFloatDivided(): void
    {
        $client = static::createClient();
        $client->request('POST', self::URI, [], [], [], '{"dividend": 10, "divider":-1.2250738585072E-308}');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $result = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey(self::KEY_ERRORS, $result);
        $this->assertArrayHasKey(self::KEY_DIVIDER, $result[self::KEY_ERRORS]);
        $this->assertArrayNotHasKey(self::KEY_DIVIDEND, $result[self::KEY_ERRORS]);
        $this->assertNotFalse(mb_strpos($result[self::KEY_ERRORS][self::KEY_DIVIDER], self::ERROR_PHP_FLOAT_MIN));
    }

    public function testMinPositiveFloatDivided(): void
    {
        $client = static::createClient();
        $client->request('POST', self::URI, [], [], [], '{"dividend": 10, "divider":1.2250738585072E-308}');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $result = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey(self::KEY_ERRORS, $result);
        $this->assertArrayHasKey(self::KEY_DIVIDER, $result[self::KEY_ERRORS]);
        $this->assertArrayNotHasKey(self::KEY_DIVIDEND, $result[self::KEY_ERRORS]);
        $this->assertNotFalse(mb_strpos($result[self::KEY_ERRORS][self::KEY_DIVIDER], self::ERROR_PHP_FLOAT_MIN));
    }

    public function testBadJson(): void
    {
        $client = static::createClient();
        $client->request('POST', self::URI, [], [], [], '{ broken');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $result = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey(self::KEY_ERRORS, $result);
        $this->assertEquals('Json error: Syntax error', $result[self::KEY_ERRORS][0]);
    }
}
