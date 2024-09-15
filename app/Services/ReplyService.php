<?php

namespace App\Services;

use App\Models\Reply;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ReplyService
{
    /**
     * Get replies by area and toon.
     *
     * @param string $area
     * @param string $toon
     * @return LengthAwarePaginator
     */
    public function gets(string $area, string $toon): LengthAwarePaginator
    {
        $where = [
            'area' => $area,
            'toon' => urldecode($toon),
        ];
        $perPage = 100;
        return Reply::where($where)
            ->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Create a new reply.
     *
     * @param string $toon
     * @param string $area
     * @param string $nickname
     * @param string $content
     * @param string $password
     * @return Reply
     */
    public function create(string $toon, string $area, string $nickname, string $content, string $password): Reply
    {
        $reply = new Reply([
            'toon' => $toon,
            'area' => $area,
            'nickname' => $nickname,
            'content' => $content,
            'password' => $password,
        ]);
        $reply->password = Hash::make($password);
        $reply->save();
        return $reply;
    }

    /**
     * Delete a reply by id and password.
     *
     * @param int $id
     * @param string $password
     * @return void
     *
     * @throws ModelNotFoundException
     * @throws ValidationException
     */
    public function delete(string $id, string $password): void
    {
        $reply = Reply::findOrFail($id);
        if (Hash::check($password, $reply->password) === false) {
            throw new ValidationException('비밀번호가 틀렸습니다.');
        }
        $reply->delete();
    }
}
