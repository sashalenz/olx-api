<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Exceptions;

/**
 * 400/422 — request failed field validation. The per-field errors are in
 * {@see OlxApiException::$validation}.
 */
final class ValidationException extends OlxApiException {}
