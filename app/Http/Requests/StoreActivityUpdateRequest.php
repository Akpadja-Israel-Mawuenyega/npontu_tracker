<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ============================================================================
 * StoreActivityUpdateRequest
 * ============================================================================
 *
 * Asserts tracking governance validation rules on sub-task lifecycle modification updates.
 *
 * Validation Constraints:
 * --------------------------------------------------------------------------
 * - status: Must be present and restricted to 'done' or 'pending'.
 * - remark: Must be present and captured as an explanatory text block.
 *
 * Responsibilities:
 * --------------------------------------------------------------------------
 * - Request authorization gating.
 * - Inbound data sanitization and type enforcement.
 * ============================================================================
 */
class StoreActivityUpdateRequest extends FormRequest
{
    /**
     * ----------------------------------------------------------------------
     * Request Authorization
     * ----------------------------------------------------------------------
     *
     * Determine if the user is authorized to submit a status change.
     * Fulfills Requirement #6: Authentication enforcement constraint.
     *
     * @return bool True if the platform worker session is authenticated.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * ----------------------------------------------------------------------
     * Validation Rules
     * ----------------------------------------------------------------------
     *
     * Get the operational progress lifecycle rules that apply to the request.
     * Fulfills Requirement #2: Validates status state changes and text remarks.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:done,pending'],
            'remark' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * ----------------------------------------------------------------------
     * Custom Attribute Names
     * ----------------------------------------------------------------------
     *
     * Customizes attribute names for cleaner validation error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'status' => 'activity operational status',
            'remark' => 'personnel hand-over remark',
        ];
    }
}
