<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * OAuth2 token response. `expiresIn` is the access-token lifetime in seconds;
 * the `refreshToken` is valid for ~1 month and MUST be persisted by the consumer
 * — once it lapses the account has to re-authorize from scratch.
 */
final class TokenData extends OlxData
{
    public function __construct(
        public ?string $accessToken = null,
        public ?int $expiresIn = null,
        public ?string $tokenType = null,
        public ?string $scope = null,
        public ?string $refreshToken = null,
    ) {}
}
