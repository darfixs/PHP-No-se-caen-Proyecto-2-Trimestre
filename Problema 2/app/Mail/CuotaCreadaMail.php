<?php

/**
 * Correo que se envía al cliente cuando se le genera una cuota.
 *
 * Adjunta el PDF de la factura.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Mail;

use App\Clases\FacturaPdf;
use App\Models\Cuota;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CuotaCreadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Cuota $cuota;

    /** Constructor: recibe la cuota que se acaba de crear. */
    public function __construct(Cuota $cuota)
    {
        $this->cuota = $cuota;
    }

    /** Asunto y remitente. */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva cuota emitida - Nosecaen S.L.',
        );
    }

    /** Vista Blade del cuerpo del correo. */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cuota_creada',
            with: [
                'cuota'   => $this->cuota,
                'cliente' => $this->cuota->cliente,
            ],
        );
    }

    /** Adjuntos: el PDF de la factura. */
    public function attachments(): array
    {
        // Genero el PDF en memoria y lo adjunto como "attachment desde datos"
        $pdfBinario = FacturaPdf::generar($this->cuota);

        return [
            Attachment::fromData(
                fn () => $pdfBinario,
                'factura_'.$this->cuota->id.'.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
