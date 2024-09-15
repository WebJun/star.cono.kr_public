<?php

namespace App\Services;

use App\Models\Feedback;
use Illuminate\Support\Collection;

class FeedbackService
{
    /**
     * Get the latest feedbacks.
     *
     * @return Collection
     */
    public function getFeedbacksLatest(): Collection
    {
        return Feedback::latest()->get();
    }

    /**
     * Create a new feedback.
     *
     * @param string $email
     * @param string|null $phone
     * @param string $content
     * @return Feedback
     */
    public function createFeedback(string $email, ?string $phone, string $content): Feedback
    {
        $feedback = Feedback::create([
            'email' => $email,
            'phone' => $phone,
            'content' => $content,
        ]);

        return $feedback;
    }
}
