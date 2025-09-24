<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Setting;
use App\Mail\ContactMessage;
use Illuminate\Support\Facades\Mail;
use App\Services\TrackedMailService;

class ContactController extends Controller
{
    /**
     * Afficher le formulaire de contact
     */
    public function index()
    {
        $recaptchaSiteKey = Setting::get('recaptcha_site_key');
        
        return view('contact.index', compact('recaptchaSiteKey'));
    }

    /**
     * Traiter l'envoi du message de contact
     */
    public function store(Request $request)
    {
        // Vérifier si reCAPTCHA est configuré
        $recaptchaSecret = Setting::get('recaptcha_secret_key');
        $isRecaptchaRequired = !empty($recaptchaSecret);
        
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'type' => 'required|in:contact,support',
        ];
        
        $validationMessages = [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'subject.required' => 'Le sujet est obligatoire.',
            'message.required' => 'Le message est obligatoire.',
            'message.max' => 'Le message ne peut pas dépasser 2000 caractères.',
            'type.required' => 'Le type de message est obligatoire.',
        ];
        
        // Ajouter la validation reCAPTCHA seulement si configuré
        if ($isRecaptchaRequired) {
            $validationRules['g-recaptcha-response'] = 'required';
            $validationMessages['g-recaptcha-response.required'] = 'Veuillez vérifier le reCAPTCHA.';
        }
        
        $validated = $request->validate($validationRules, $validationMessages);

        // Vérifier reCAPTCHA
        $recaptchaSecret = Setting::get('recaptcha_secret_key');
        if ($recaptchaSecret) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
            $responseKeys = json_decode($response, true);
            
            if (!$responseKeys["success"]) {
                return back()
                    ->withErrors(['recaptcha' => 'Veuillez vérifier le reCAPTCHA.'])
                    ->withInput();
            }
        }

        // Créer le message
        $message = Message::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'user_id' => auth()->id(),
            'status' => 'open',
        ]);

        // Envoyer l'email de notification aux admins (tracked)
        try {
            $contactEmail = Setting::get('contact_email', 'contact@iptv2smartv.com');
            $adminHtml = view('emails.contact-admin', ['message' => $message])->render();
            TrackedMailService::sendTracked($contactEmail, 'Nouveau message de contact', $adminHtml, auth()->id());
            
            // Envoyer une copie au client (tracked)
            $clientHtml = view('emails.contact-confirmation', ['message' => $message])->render();
            TrackedMailService::sendTracked($message->email, 'Confirmation de votre message', $clientHtml, auth()->id());
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la création du message
            logger()->error('Erreur envoi email contact: ' . $e->getMessage());
        }

        return redirect()->route('contact.index')
            ->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
    }
}