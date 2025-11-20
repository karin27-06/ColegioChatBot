<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Store a newly created contact message.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validar los datos del request
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'subject' => 'required|string',
                'whatsapp_message' => 'required|string',
            ]);

            // Crear el mensaje de contacto
            $contactMessage = ContactMessage::create([
                'first_name' => $validated['first_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => $validated['subject'],
                'whatsapp_message' => $validated['whatsapp_message'],
                'status' => 'pending',
                'sent_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mensaje guardado correctamente',
                'data' => [
                    'id' => $contactMessage->id,
                    'created_at' => $contactMessage->created_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el mensaje: ' . $e->getMessage(),
            ], 500);
        }
    }
}
