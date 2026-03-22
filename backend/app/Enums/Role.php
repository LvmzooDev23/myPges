<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Student = 'student';
    case Company = 'company';
    case Supervisor = 'supervisor';
}
