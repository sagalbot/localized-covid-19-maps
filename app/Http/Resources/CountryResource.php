<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * @var \App\Country
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $toArray = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if ($reports = $this->whenLoaded('reports')) {
            $toArray['reports'] = ReportResource::collection($this->resource->timeSeries());
        }

        return $toArray;
    }
}
