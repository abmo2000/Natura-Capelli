<?php


namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use App\Managers\CartStorageManager;


class CartService{
 public function __construct(private CartStorageManager $manager)
    {
    }

    /**
     * Add product to cart
     */
    public function addProduct(int $productId, int $quantity = 1): bool
    {
        $product = Product::find($productId);
        
        if (!$product) {
            throw new \Exception("Product not found");
        }
        
        if (! $product->in_stock) {
            throw new \Exception("Insufficient stock");
        }
        
        return $this->manager->add($productId, $quantity, $product->price);
    }

    /**
     * Update quantity
     */
    public function updateQuantity(int $productId, int $quantity): bool
    {
        $product = Product::find($productId);
        
        if (!$product) {
            throw new \Exception("Product not found");
        }
        
        if (! $product->in_stock) {
            throw new \Exception("Insufficient stock");
        }
        
        return $this->manager->update($productId, $quantity);
    }

    /**
     * Remove product
     */
    public function removeProduct(int $productId): bool
    {
        return $this->manager->remove($productId);
    }

    /**
     * Get all cart items
     */
    public function getItems(): Collection
    {
        return $this->manager->getItems();
    }

    /**
     * Get cart count
     */
    public function getCount(): int
    {
        return $this->manager->getCount();
    }

    /**
     * Get cart total
     */
    public function getTotal(): float
    {
        return $this->manager->getTotal();
    }

    /**
     * Clear cart
     */
    public function clear(): bool
    {
        return $this->manager->clear();
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty(): bool
    {
        return $this->manager->isEmpty();
    }
}