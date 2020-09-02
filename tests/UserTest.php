<?php

use Lion\Permission;
use Lion\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserJSONMarshaling(): void
    {
        $user = new User();
        $user->id = 1;
        $user->active = '1';
        $user->name = 'Ivi Hammond';
        $user->blocked = false;
        $user->permissions = array(
            new Permission(['id' => 1, 'permission' => 'comment']),
            new Permission(['id' => 2, 'permission' => 'upload photo']),
            new Permission(['id' => 3, 'permission' => 'add event'])
        );
        $JSON_user = json_encode($user);
        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/fixture/user.json', $JSON_user);
    }
}
