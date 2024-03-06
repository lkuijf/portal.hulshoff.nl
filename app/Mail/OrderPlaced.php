<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Product;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public $isReservation = 0;
    public $order;

    // public function __construct($canReserve)
    public function __construct(Order $order)
    {
        // if($canReserve) $this->isReservation = $canReserve;
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $subject = ($this->order->is_reservation?__('Reservation'):'Order');
        if($this->order->orderType == 'return-order') {
            $subject = __('Return order');
        }
        return new Envelope(
            subject: $subject . ' ' . __('placed'),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        $aProds = [];
        if(count($this->order->orderArticles)) {
            foreach($this->order->orderArticles as $ordArt) {
                $product = Product::find($ordArt->product_id);
                $aProds[] = $ordArt->amount . 'x ' . $product->omschrijving;
            }
        }
        if(count($this->order->returnOrderArticles)) {
            foreach($this->order->returnOrderArticles as $retOrdArt) {
                // $product = Product::find($ordArt->product_id);
                $aProds[] = $retOrdArt->amount . 'x ' . $retOrdArt->product;
            }
        }
        return new Content(
            view: 'mail.order_placed',
            with: [
                'orderProducts' => $aProds,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
