<?php

namespace App\Services;

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

        $parentComment = $this->getCommentById($request->get('comment_id'));

        $request['level'] = $parentComment->level + 1;

        return $request;
    }

    /**
     * @param int $commentId
     * @return object|null
     */
    private function getCommentById(int $commentId): object|null
    {
        return DB::table('comments')->where('id', $commentId)->first();
    }
}
