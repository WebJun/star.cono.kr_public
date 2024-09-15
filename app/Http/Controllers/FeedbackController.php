<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FeedbackService;
use App\Services\TelegramService;
use App\Http\Middleware\BlockIpMiddleware;

class FeedbackController extends Controller
{
    protected $feedbackService;
    protected $telegramService;

    public function __construct(FeedbackService $feedbackService, TelegramService $telegramService)
    {
        $this->feedbackService = $feedbackService;
        $this->telegramService = $telegramService;
        $this->middleware(BlockIpMiddleware::class)->only(['index']);
    }

    public function index()
    {
        $feedbacks = $this->feedbackService->getFeedbacksLatest();
        return response()->json($feedbacks);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
            'content' => 'required|string',
        ]);
        $email = $validatedData['email'];
        $phone = $validatedData['phone'];
        $content = $validatedData['content'];

        $feedback = $this->feedbackService->createFeedback($email, $phone, $content);
        $this->telegramService->send('피드백 : ' . $request->content);

        return response()->json($feedback, 201);
    }
}