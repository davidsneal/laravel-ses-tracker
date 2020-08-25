<?php
namespace DavidNeal\LaravelSesTracker\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;
use DavidNeal\LaravelSesTracker\Models\SentEmail;
use DavidNeal\LaravelSesTracker\Models\EmailComplaint;
use Carbon\Carbon;
use GuzzleHttp\Client;

class ComplaintController extends BaseController
{
    public function hasComplained($email)
    {
        $emailComplaints =  EmailComplaint::whereEmail($email)->get();

        if ($emailComplaints->isEmpty()) {
            return response()->json([
                'success' => true,
                'complained' => false
            ]);
        }

        return response()->json([
            'success' => true,
            'complained' => true,
            'complaints' => $emailComplaints
        ]);
    }

    public function complaint(ServerRequestInterface $request)
    {
        $this->validateSns($request);

        $result = json_decode(request()->getContent());

        // if amazon is trying to confirm the subscription
        if (isset($result->Type) && $result->Type == 'SubscriptionConfirmation') {
            $client = new Client;
            $client->get($result->SubscribeURL);

            return response()->json(['success' => true]);
        }

        $message = json_decode($result->Message);

        $messageId = $message
            ->mail
            ->commonHeaders
            ->messageId;

        $messageId = str_replace(array('<', '>'), '', $messageId);

        try {
            $sentEmail = SentEmail::whereMessageId($messageId)
                ->whereComplaintTracking(true)
                ->firstOrFail();

            EmailComplaint::create([
                'message_id' => $messageId,
                'sent_email_id' => $sentEmail->id,
                'type' => $message->complaint->complaintFeedbackType,
                'email' => $message->mail->destination[0],
                'complained_at' =>  Carbon::parse($message->mail->timestamp)
            ]);
        } catch (ModelNotFoundException $e) {
            // complaint not logged
        }
    }
}
