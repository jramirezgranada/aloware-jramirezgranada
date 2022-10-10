<?php

namespace App\Services;

use App\Http\Resources\CommentResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentService
{

    /**
     * @param Request $request
     * @return object|null
     * @throws Exception
     */
    public function createComment(Request $request): object|null
    {
        $this->getLevel($request);
        $data = $request->all();

        if ($data['level'] > 3) {
            throw new Exception('Comments system only allows 3 layers of comments');
        }

        if (isset($data['post_id']) && isset($data['comment_id'])) {
            $parentComment = $this->getCommentById($data['comment_id'])->first();

            if ($parentComment->post_id != $data['post_id']) {
                throw new Exception('The indicated post id is different than parent comment post id, please check');
            }
        }

        $commentId = DB::table('comments')->insertGetId($data);

        return DB::table('comments')->where('id', $commentId)->first();
    }

    /**
     * @param Request $request
     * @return Request
     */
    private function getLevel(Request &$request): Request
    {
        if (is_null($request->get('comment_id'))) {
            $request['level'] = 1;
            return $request;
        }

        $parentComment = $this->getCommentById($request->get('comment_id'))->first();

        $request['level'] = $parentComment->level + 1;

        return $request;
    }

    /**
     * @param int $commentId
     * @return object|null
     */
    public function getCommentById(int $commentId): object|null
    {
        return DB::table('comments')->where('id', $commentId)->get();
    }

    public function getAllComments()
    {
        return DB::table('comments')->get();
    }

    public function getSubCommentsByCommentId($commentId)
    {
        $subComments = DB::table('comments')->where('comment_id', $commentId)->get();
        return CommentResource::collection($subComments);
    }
}
