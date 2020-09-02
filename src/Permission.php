<?php

namespace Lion;

/**
 * @author Vasilyuk Dmitry
 * Class Permission
 * The class declares an access structure
 * @property int id
 * @property string permission
 * @package Lion
 */
class Permission
{
    public $id;
    public $permission;

    /**
     * Permission constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (is_array($data)) {
            $this->id = $data['id'] ?? '';
            $this->permission = $data['permission'] ?? '';
        }
    }
}
