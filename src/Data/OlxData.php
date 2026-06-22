<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

/**
 * Base for all OLX response DTOs. OLX payloads are snake_case; the DTOs expose
 * idiomatic camelCase properties via the snake-case input mapper. Unknown
 * payload keys are ignored, so the DTOs tolerate OLX adding fields.
 */
#[MapInputName(SnakeCaseMapper::class)]
abstract class OlxData extends Data {}
