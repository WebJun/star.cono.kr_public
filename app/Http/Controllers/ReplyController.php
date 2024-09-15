<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\ReplyService;
use Illuminate\Validation\ValidationException;

class ReplyController extends Controller
{
    protected $replyService;

    public function __construct(ReplyService $replyService)
    {
        $this->replyService = $replyService;
    }

    public function index($area, $toon)
    {
        if (in_array($area, getAreas()) === false) {
            return response()->json(['error' => 'Invalid area'], 400);
        }
        if (mb_strlen($toon) === 0) {
            return response()->json(['error' => 'Invalid id'], 400);
        }
        $replys = $this->replyService->gets($area, $toon);

        return response()->json($replys);
    }

    public function store(Request $request)
    {
        // TODO::area에 해당 toon이 존재하는 지 체크하기
        $areasStr = implode(',', getAreas());
        $validatedData = $request->validate([
            'toon' => 'required',
            'area' => "required|in:{$areasStr}",
            'nickname' => 'required',
            'content' => 'required',
            'password' => 'required|min:4',
        ]);
        $toon = $validatedData['toon'];
        $area = $validatedData['area'];
        $nickname = $validatedData['nickname'];
        $content = $validatedData['content'];
        $password = $validatedData['password'];

        $reply = $this->replyService->create($toon, $area, $nickname, $content, $password);

        return response()->json($reply, 201);
    }

    public function destroy($id, Request $request)
    {
        try {
            $validatedData = $request->validate([
                'password' => 'required|min:4',
            ]);
            $password = $validatedData['password'];
            $this->replyService->delete($id, $password);
            return response()->json(null, 204);
        } catch (ValidationException $e) {
            return response()->json(null, 403);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }
}
