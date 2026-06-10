<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ============================================================================
 * StoreActivityRequest
 * ============================================================================
 *
 * Enforces validation contracts when compiling new operational tracking items.
 */
class StoreActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to execute this transaction.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the operational validation parameters.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'activity_date' => ['required', 'date'],
        ];
    }
}
