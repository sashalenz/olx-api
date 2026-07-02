<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

/**
 * One wallet charge from the billing history (`/users/me/billing`) — a packet
 * purchase, a paid feature, a single listing fee, … `price` is a signed
 * decimal string (spend is negative, e.g. "-2009.00"); `advertId` is set when
 * the charge is tied to a specific advert (null for account-wide packets).
 */
final class BillingEntryData extends OlxData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $date = null,
        public ?string $price = null,
        public ?int $advertId = null,
    ) {}
}
