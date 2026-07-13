<?php

namespace Tests\Feature;

use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Tests\TestCase;

class ActivityLogIpAddressTest extends TestCase
{
    public function test_logs_activity_trait_extracts_the_request_ip_address(): void
    {
        $request = Request::create('/test', 'GET', [], [], [], ['REMOTE_ADDR' => '203.0.113.10']);
        $this->app->instance('request', $request);

        $model = new class {
            use LogsActivity;

            public function getKey()
            {
                return 1;
            }

            public function getActivityDescription(string $event): string
            {
                return 'Test';
            }

            public function getActivityIdentifier(): string
            {
                return 'id';
            }

            public function getOriginal(): array
            {
                return [];
            }

            public function getDirty(): array
            {
                return [];
            }

            public function getChanges(): array
            {
                return [];
            }

            public function exposeIpAddress(): ?string
            {
                return $this->getIpAddress();
            }
        };

        $this->assertSame('203.0.113.10', $model->exposeIpAddress());
    }
}
