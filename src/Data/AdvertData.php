<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

use Sashalenz\OlxApi\ApiModels\Adverts;
use Sashalenz\OlxApi\Enums\AdvertStatus;
use Spatie\LaravelData\Attributes\DataCollectionOf;

/**
 * An advert (listing). Models the detail/list RESPONSE shape; create/update
 * request bodies are plain arrays (see {@see Adverts}).
 *
 * `status` is kept as a raw string so an unseen OLX status never breaks
 * hydration; use {@see AdvertData::statusEnum()} for the typed value.
 */
final class AdvertData extends OlxData
{
    /**
     * @param  array<int, ImageData>  $images
     * @param  array<int, AttributeValueData>  $attributes
     */
    public function __construct(
        public ?int $id = null,
        public ?string $status = null,
        public ?string $url = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?int $categoryId = null,
        public ?string $advertiserType = null,
        public ?string $externalId = null,
        public ?string $externalUrl = null,
        public ?ContactData $contact = null,
        public ?AdvertLocationData $location = null,
        public ?PriceData $price = null,
        #[DataCollectionOf(ImageData::class)]
        public array $images = [],
        #[DataCollectionOf(AttributeValueData::class)]
        public array $attributes = [],
        public ?string $createdAt = null,
        public ?string $validTo = null,
    ) {}

    public function statusEnum(): ?AdvertStatus
    {
        return $this->status === null ? null : AdvertStatus::tryFrom($this->status);
    }
}
