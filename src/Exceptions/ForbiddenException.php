<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Exceptions;

/**
 * 403 — token lacks the required scope, or the resource is not owned by the
 * authenticated account.
 */
final class ForbiddenException extends OlxApiException {}
