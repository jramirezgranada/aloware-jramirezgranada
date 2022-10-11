<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
        return CommentResource::collection($this->commentService->getAllComments());
    }

    /**
     * @param CommentStoreRequest $request
     * @return CommentResource
     */
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

    /**
     * @param int $id
     * @return AnonymousResourceCollection
     */
    public function show(int $id)
    {
        return CommentResource::collection($this->commentService->getCommentById($id));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $status = $this->commentService->deleteCommentById($id);
        $message = $status == 1 ? 'The comment has been deleted.' : 'The comment with id ' . $id . ' does not exists';

        return response()->json([
            'message' => $message
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return AnonymousResourceCollection
     */
    public function update(CommentUpdateRequest $request, int $id)
    {
        $this->commentService->updateCommentById($id, $request->all());

        return CommentResource::collection($this->commentService->getCommentById($id));
    }
}


