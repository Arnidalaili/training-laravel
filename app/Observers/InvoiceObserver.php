<?php

namespace App\Observers;

use App\Models\Customer;

class InvoiceObserver
{
    /**
     * Handle the Customer "created" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function creating(Customer $customer)
    {
        $lastInvoice = Customer::orderBy('invoice_sequence', 'desc')->first();
        $nextInvoiceSequence = $lastInvoice ? $lastInvoice->invoice_sequence + 1 : 1; 
        $customer->invoice_sequence = $nextInvoiceSequence; 
        
        

        // Generate invoice number dengan prefix "INV"
        $invoiceNumber = 'INV' . sprintf('%03d', $nextInvoiceSequence);
        $customer->invoice = $invoiceNumber;
    }

    /**
     * Handle the Customer "updated" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function restored(Customer $customer)
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     *
     * @param  \App\Models\Customer  $customer
     * @return void
     */
    public function forceDeleted(Customer $customer)
    {
        //
    }
}
