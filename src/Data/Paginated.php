<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\Data;

use Spatie\LaravelData\Data;

/**
 * A typed, paginated list response. OLX list endpoints return
 * `{data: [...], metadata: {...}, links: {...}}`; this holds the hydrated
 * `data` items plus the raw `metadata`/`links` blocks for offset/limit paging.
 *
 * @template T of Data
 */
final class Paginated extends Data
{
    /**
     * @param  array<int, T>  $data  the hydrated items
     * @param  array<string, mixed>  $metadata  raw metadata block (total_elements, visible_total_count, …)
     * @param  array<string, mixed>  $links  raw links block (self/next/previous with offset/limit)
     */
    public function __construct(
        public array $data = [],
        public array $metadata = [],
        public array $links = [],
    ) {}

    /**
     * Build from a raw OLX list response by hydrating each `data` item into the
     * given DTO class. Tolerates a bare top-level list too.
     *
     * @template TData of Data
     *
     * @param  array<array-key, mixed>  $response
     * @param  class-string<TData>  $dataClass
     * @return self<TData>
     */
    public static function fromResponse(array $response, string $dataClass): self
    {
        if (array_key_exists('data', $response)) {
            /** @var array<int, mixed> $items */
            $items = is_array($response['data']) ? $response['data'] : [];
            /** @var array<string, mixed> $metadata */
            $metadata = is_array($response['metadata'] ?? null) ? $response['metadata'] : [];
            /** @var array<string, mixed> $links */
            $links = is_array($response['links'] ?? null) ? $response['links'] : [];
        } else {
            /** @var array<int, mixed> $items */
            $items = array_is_list($response) ? $response : [];
            $metadata = [];
            $links = [];
        }

        return new self(
            data: array_map(static fn (mixed $item): Data => $dataClass::from($item), array_values($items)),
            metadata: $metadata,
            links: $links,
        );
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function totalCount(): ?int
    {
        $count = $this->metadata['total_elements'] ?? $this->metadata['visible_total_count'] ?? null;

        return $count === null ? null : (int) $count;
    }
}
