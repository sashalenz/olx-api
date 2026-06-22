<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Exceptions;

/**
 * 404 — the advert / thread / resource does not exist (or was removed).
 */
final class NotFoundException extends OlxApiException {}
