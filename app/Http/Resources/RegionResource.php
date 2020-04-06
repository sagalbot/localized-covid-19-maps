<?php

namespace App\Http\Resources;

use App\Province;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (get_class($this->resource) === Province::class) {
            $countryName = $this->resource->country->name;
        } else {
            $countryName = $this->resource->name;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => get_class($this->resource),
            'country_name' => $countryName,
            'latest' => $this->latestReport->toArray(),
        ];
    }
}
