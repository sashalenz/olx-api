<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\CategoryAttributeData;
use Sashalenz\OlxApi\Data\CategoryData;
use Sashalenz\OlxApi\Data\Paginated;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Categories & their attributes. Adverts may only be placed in
 * a leaf category; build your SparePart→leaf map from this tree once.
 *
 * @see https://developer.olx.ua/api/doc
 */
final class Categories extends BaseModel
{
    /**
     * List categories, optionally a single level via `parent_id`.
     *
     * @param  array<string, mixed>  $query  optional: ['parent_id'=>…]
     * @return Paginated<CategoryData>
     *
     * @throws OlxApiException
     */
    public function all(array $query = []): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('categories'), $query)->all(),
            CategoryData::class,
        );
    }

    /**
     * @throws OlxApiException
     */
    public function get(int $categoryId): CategoryData
    {
        return CategoryData::from($this->dataOf($this->httpGet($this->apiPath("categories/{$categoryId}"))));
    }

    /**
     * Required/optional attributes for a category (make/model/condition/…).
     *
     * @return Paginated<CategoryAttributeData>
     *
     * @throws OlxApiException
     */
    public function attributes(int $categoryId): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath("categories/{$categoryId}/attributes"))->all(),
            CategoryAttributeData::class,
        );
    }

    /**
     * Suggest a category from an advert title (min 3 chars).
     *
     * @return Paginated<CategoryData>
     *
     * @throws OlxApiException
     */
    public function suggestion(string $query): Paginated
    {
        return Paginated::fromResponse(
            $this->httpGet($this->apiPath('categories/suggestion'), ['q' => $query])->all(),
            CategoryData::class,
        );
    }
}
