<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\EmailTracking;
use Illuminate\Mail\Message;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TrackedMailService
{
    /**
     * Send a tracked HTML email using dynamic SMTP settings.
     */
    public static function sendTracked(string $toEmail, string $subject, string $htmlBody, ?int $sentByUserId = null): bool
    {
        try {
            DynamicConfigService::configureSmtp();

            $emailLog = EmailLog::create([
                'subject' => $subject,
                'body' => $htmlBody,
                'sent_by' => $sentByUserId,
                'total_sent' => 0,
                'preview' => \Str::limit(strip_tags($htmlBody), 100),
                'recipients' => [$toEmail],
            ]);

            $token = (string) Str::uuid();
            $tracking = EmailTracking::create([
                'email_log_id' => $emailLog->id,
                'recipient' => $toEmail,
                'token' => $token,
            ]);

            $personalizedBody = self::injectTracking($htmlBody, $token);

            Mail::html($personalizedBody, function (Message $message) use ($toEmail, $subject) {
                $message->to($toEmail)->subject($subject);
            });

            $emailLog->update(['total_sent' => 1]);
            return true;
        } catch (\Exception $e) {
            Log::error('Tracked mail send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Wrap links and add open pixel.
     */
    public static function injectTracking(string $html, string $token): string
    {
        $htmlWithTrackedLinks = preg_replace_callback('/<a\\s+[^>]*href=\"([^\"]+)\"[^>]*>/i', function ($matches) use ($token) {
            $originalUrl = $matches[1];
            if (str_contains($originalUrl, route('track.click', ['token' => $token]))) {
                return $matches[0];
            }
            $trackedUrl = route('track.click', ['token' => $token]) . '?url=' . urlencode($originalUrl);
            return str_replace($originalUrl, $trackedUrl, $matches[0]);
        }, $html);

        $openPixelTag = '<img src="' . route('track.open', ['token' => $token]) . '" width="1" height="1" style="display:none" alt="" />';
        if (stripos($htmlWithTrackedLinks, '</body>') !== false) {
            return str_ireplace('</body>', $openPixelTag . '</body>', $htmlWithTrackedLinks);
        }
        return $htmlWithTrackedLinks . $openPixelTag;
    }

    /**
     * Send a tracked Laravel Mailable by rendering it to HTML.
     */
    public static function sendTrackedMailable(string $toEmail, Mailable $mailable, ?int $sentByUserId = null): bool
    {
        try {
            DynamicConfigService::configureSmtp();

            $subject = method_exists($mailable, 'envelope') ? optional($mailable->envelope())->subject : null;
            if (empty($subject)) {
                $subject = property_exists($mailable, 'subject') ? $mailable->subject : (method_exists($mailable, 'subject') ? $mailable->subject : '');
            }

            $htmlBody = $mailable->render();

            $emailLog = EmailLog::create([
                'subject' => $subject ?: '',
                'body' => $htmlBody,
                'sent_by' => $sentByUserId,
                'total_sent' => 0,
                'preview' => \Str::limit(strip_tags($htmlBody), 100),
                'recipients' => [$toEmail],
            ]);

            $token = (string) Str::uuid();
            EmailTracking::create([
                'email_log_id' => $emailLog->id,
                'recipient' => $toEmail,
                'token' => $token,
            ]);

            $personalizedBody = self::injectTracking($htmlBody, $token);

            Mail::html($personalizedBody, function (Message $message) use ($toEmail, $subject) {
                if (!empty($subject)) {
                    $message->subject($subject);
                }
                $message->to($toEmail);
            });

            $emailLog->update(['total_sent' => 1]);
            return true;
        } catch (\Exception $e) {
            Log::error('Tracked mailable send error: ' . $e->getMessage());
            return false;
        }
    }
}


