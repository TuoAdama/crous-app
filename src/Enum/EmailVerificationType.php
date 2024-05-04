<?php

namespace App\Enum;

enum EmailVerificationType
{
    case RESET_PASSWORD;
    case CHANGE_EMAIL;
    case VERIFICATION_AFTER_REGISTRATION;
}
