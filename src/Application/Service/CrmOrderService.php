<?php

namespace App\Application\Service;

use App\Application\Command\PlaceOrderCommand;
use GuzzleHttp\ClientInterface;

class CrmOrderService
{

    public function __construct(
        private readonly ClientInterface $httpClient
    )
    {
    }

    public function sendOrder(PlaceOrderCommand $command): void
    {
        //serializacja danych do wysłania
        $data = [
            'orderId' => $command->getOrderId()->toString(),
            'clientId' => $command->getClientId()->toString(),
            'products' => $command->getProducts(),
        ];

        $this->httpClient->request('POST', '/order', [
            'json' => $data,
        ]);
    }

    public function handleOrderResponse(mixed $data)
    {
        //obsługa odpowiedzi z CRM
        $httpStatus = $data['httpStatus'] ?? null;

        if ($httpStatus === null) {
            throw new \InvalidArgumentException('Invalid response');
        }

        if ($httpStatus !== 200) {
            throw new \InvalidArgumentException('Invalid response status');
        }

        //
    }


}
