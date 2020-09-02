<?php

use Lion\Permission;
use PHPUnit\Framework\TestCase;

class PermissionTest extends TestCase
{
    public function testPermissionJSONMarshaling(): void
    {
        $JSON_user = json_encode(new Permission(['id' => 1, 'permission' => 'permission text']));
        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/fixture/permission.json', $JSON_user);
    }
}
