<?php

namespace App\Models;

class Client extends BaseModel
{
    const MALE = "male";
    const FEMALE = "female";
    const OTHERS = "others";
    const EMAIL = "email";
    const PHONE = "phone";
    const PRIMARY = "primary";
    const LOWER_SECONDARY = "lower_secondary";
    const SECONDARY = "secondary";
    const HIGHER_SECONDARY = "higher_secondary";
    const UNDERGRADUATE = "undergraduate";
    const GRADUATE = "graduate";
    const POST_GRADUATE = "post_graduate";
    const NEPALI = "nepali";

    protected string $filename = "clients.csv";

    protected array $columns = [
        "name",
        "gender",
        "email",
        "phone",
        "address",
        "nationality",
        "dob",
        "educational_background",
        "preferred_mode_of_contact",
        "id",
    ];

    public static array $gender = [
        self::MALE,
        self::FEMALE,
        self::OTHERS,
    ];

    public static array $educational_backgrounds = [
        self::PRIMARY,
        self::LOWER_SECONDARY,
        self::SECONDARY,
        self::HIGHER_SECONDARY,
        self::UNDERGRADUATE,
        self::GRADUATE,
        self::POST_GRADUATE,
    ];

    public static array $preffered_mode_of_contact = [
        self::EMAIL,
        self::PHONE,
        "none",
    ];

    public static array $nationality = [
        self::NEPALI,
    ];
}
