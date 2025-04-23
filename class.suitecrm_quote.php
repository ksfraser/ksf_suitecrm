<?php

namespace App\Models;

use Exception;

/**
 * Model class for handling SuiteCRM Quotes.
 */
class QuotesModel
{
    protected $attributes = [];

    /**
     * Constructor to initialize attributes.
     *
     * @param array $attributes Key-value pairs of attributes.
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Set an attribute value.
     *
     * @param string $key Attribute name.
     * @param mixed $value Attribute value.
     */
    public function set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get an attribute value.
     *
     * @param string $key Attribute name.
     * @return mixed|null Attribute value or null if not set.
     */
    public function get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Create a new Quote record.
     *
     * @throws Exception If the record is not created.
     */
    public function create()
    {
        // Simulate record creation logic.
        $this->attributes['id'] = uniqid(); // Example ID generation.

        if (!isset($this->attributes['id'])) {
            throw new Exception("Record not created!");
        }
    }
}

/**
 * Controller class for managing SuiteCRM Quotes operations.
 */
class QuotesController
{
    protected $model;

    /**
     * Constructor to initialize the model.
     *
     * @param QuotesModel $model The model instance.
     */
    public function __construct(QuotesModel $model)
    {
        $this->model = $model;
    }

    /**
     * Handle the creation of a new Quote.
     *
     * @return void
     */
    public function createQuote()
    {
        $this->model->create();
    }
}
