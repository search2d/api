<?php
declare(strict_types=1);

namespace Search2d\Test\Presentation\Api\Action;

use Search2d\Container;
use Search2d\Presentation\Api\Action\Api\HealthAction;
use Search2d\Presentation\Api\Helper;
use Search2d\Test\TestCase;

/**
 * @covers \Search2d\Presentation\Api\Action\Api\HealthAction
 */
class HealthActionTest extends TestCase
{
    use HelperTrait;

    /**
     * @return void
     */
    public function testOK(): void
    {
        $this->container[HealthAction::class] = function (Container $container) {
            return new HealthAction(new Helper());
        };

        $response = $this->call('GET', '/api/health');
        $this->assertSuccessResponse($response, 200, (object)['status' => 'OK']);
    }
}