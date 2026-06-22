<?php

declare(strict_types=1);

namespace Sashalenz\OlxApi\ApiModels;

use Sashalenz\OlxApi\Data\BusinessUserData;
use Sashalenz\OlxApi\Exceptions\OlxApiException;

/**
 * Partner API v2 — Business profile (the OLX-store storefront: name, description,
 * subdomain, logos, banners).
 *
 * @see https://developer.olx.ua/api/doc
 */
final class UsersBusiness extends BaseModel
{
    /**
     * @throws OlxApiException
     */
    public function me(): BusinessUserData
    {
        return BusinessUserData::from($this->dataOf($this->httpGet($this->apiPath('users-business/me'))));
    }

    /**
     * Update the business profile.
     *
     * @param  array<string, mixed>  $attributes  ['name'=>…, 'description'=>…, 'subdomain'=>…, 'website_url'=>…, 'address'=>…, 'phones'=>[…]]
     *
     * @throws OlxApiException
     */
    public function update(array $attributes): BusinessUserData
    {
        return BusinessUserData::from($this->dataOf($this->httpPut($this->apiPath('users-business/me'), $attributes)));
    }

    /**
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function logos(): array
    {
        return $this->dataOf($this->httpGet($this->apiPath('users-business/me/logos')));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function addLogo(array $payload): array
    {
        return $this->dataOf($this->httpPost($this->apiPath('users-business/me/logos'), $payload));
    }

    /**
     * @throws OlxApiException
     */
    public function deleteLogo(int $logoId): bool
    {
        $this->httpDelete($this->apiPath("users-business/me/logos/{$logoId}"));

        return true;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function banners(): array
    {
        return $this->dataOf($this->httpGet($this->apiPath('users-business/me/banners')));
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws OlxApiException
     */
    public function addBanner(array $payload): array
    {
        return $this->dataOf($this->httpPost($this->apiPath('users-business/me/banners'), $payload));
    }

    /**
     * @throws OlxApiException
     */
    public function deleteBanner(int $bannerId): bool
    {
        $this->httpDelete($this->apiPath("users-business/me/banners/{$bannerId}"));

        return true;
    }
}
