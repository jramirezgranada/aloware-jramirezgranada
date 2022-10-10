<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * @var CommentService
     */
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index()
    {
        $comments = DB::table('comments')->get();

        return CommentResource::collection($comments);
    }

    public function store(CommentStoreRequest $request)
    {
        try {
            $comment = $this->commentService->createComment($request);

            return new CommentResource($comment);
        } catch (Exception $e) {
            $response = response()->json([
                'error' => $e->getMessage(),
            ], 422);

            throw new HttpResponseException($response);
        }
    }
}
