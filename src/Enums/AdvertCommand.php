<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Enums;

/**
 * Commands accepted by `POST /adverts/{id}/commands`.
 */
enum AdvertCommand: string
{
    case Activate = 'activate';
    case Deactivate = 'deactivate';
    case Finish = 'finish';
    case Extend = 'extend';
}
