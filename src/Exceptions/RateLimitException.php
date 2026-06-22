<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Exceptions;

/**
 * 429 — too many requests. Back off and retry; DELETE on adverts is especially
 * expensive (throttling cost 5).
 */
final class RateLimitException extends OlxApiException {}
