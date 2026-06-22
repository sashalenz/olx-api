<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Exceptions;

/**
 * 5xx — OLX-side error. Safe to retry idempotent reads after a back-off.
 */
final class ServerException extends OlxApiException {}
