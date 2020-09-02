<?php

namespace Lion;

/**
 * @author Vasilyuk Dmitry
 * Class User
 * The class declares the user data structure
 * @property int id
 * @property string active
 * @property bool blocked
 * @property string name
 * @property Permission[] permissions
 * @package Lion
 */
class User
{
    public $id;
    public $active;
    public $blocked;
    public $name;
    public $permissions;

    /**
     * User constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->name = $data['name'] ?? null;
            $this->active = $data['active'] ?? null;
            $this->blocked = $data['blocked'] ?? null;
            $permissions = $data['permissions'] ?? [];
            foreach ($permissions as $value) {
                $this->permissions[] = new Permission($value);
            }
        }
    }
}
