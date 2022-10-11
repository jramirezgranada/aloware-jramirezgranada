<?php

namespace App\Http\Resources;

use App\Services\CommentService;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * @var CommentService
     */
    protected CommentService $commentService;

    /**
     * @var bool
     */
    public bool $preserveKeys = true;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->commentService = new CommentService();
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'message' => $this->message,
            'comments' => $this->commentService->getSubCommentsByCommentId($this->id)
        ];
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->resource);
    }
}
