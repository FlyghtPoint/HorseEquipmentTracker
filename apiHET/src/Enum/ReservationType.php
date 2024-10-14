<?php

namespace App\Enum;

enum ReservationType: string
{
    case Loan = 'loan';
    case Maintenance = 'maintenance';
}
