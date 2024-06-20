<?php

namespace Tests\Presentation\Controller;

use App\Application\Service\ClientContractContractService;
use App\Domain\Validator\CreateClientContractValidator;
use App\Presentation\Controller\CrmResponseContractController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CrmResponseContractControllerTest extends TestCase
{
    private $clientService;
    private $validator;
    private $controller;

    protected function setUp(): void
    {
        $this->clientService = $this->createMock(ClientContractContractService::class);
        $this->validator = $this->createMock(CreateClientContractValidator::class);
        $this->controller = new CrmResponseContractController($this->clientService, $this->validator);
    }

    public function testCreateClientWithInvalidData()
    {
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getContent')
            ->willReturn('{"clientId": "49a085cc-1ac4-45f6-ae17-c8275b43132a", "name": "John Doe"}');

        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(['error' => 'Invalid data']);

        $response = $this->controller->createClient($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }

}