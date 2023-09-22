<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDebugResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public $additional = [
    //     "author" => "Anita Silvi Ferdina"
    // ];
    public static $wrap = "data";

    public function toArray(Request $request): array
    {
        return [
            "author" => "Anita Silvi Ferdina",
            "server_time" => now()->toDateTimeString(),
            "data" => [
                "id" => $this->id,
                "name" => $this->name,
                "price" => $this->price
            ]
        ];
    }
}
