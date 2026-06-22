<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Exceptions;

/**
 * 401 — missing/invalid access token. Refresh the token and retry.
 */
final class UnauthorizedException extends OlxApiException {}
