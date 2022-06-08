<?php

namespace App\Models;

class Client extends BaseModel
{
    const EMAIL = "email";
    const PHONE = "phone";

    protected string $filename = "clients.csv";

    protected array $columns = [
        "id",
        "name",
        "gender",
        "email",
        "phone",
        "address",
        "nationality",
        "dob",
        "educational_background",
        "preferred_mode_of_contact",
    ];

    public array $preffered_mode_of_contact = [
        self::EMAIL,
        self::PHONE,
    ];
}
