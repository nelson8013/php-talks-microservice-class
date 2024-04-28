<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repository\ProductRepository;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProductRequest;

class ProductUpdated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $product;
    /**
     * Create a new job instance.
     */
    public function __construct($product)
    {
        $this->product =  $product;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $productRepo    = new ProductRepository;
            $productRequest = new ProductRequest($this->product);

            $product = $productRepo->update($this->product['id'], $productRequest);
            
            if ($product) {
                Log::info('Product updated successfully.');
            } else {
                Log::error('Failed to update product.');
            }
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
        }
    }
}
