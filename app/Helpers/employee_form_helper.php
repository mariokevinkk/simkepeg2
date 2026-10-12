<?php

use App\Libraries\EmployeeProfileData;

if (! function_exists('employee_field_input')) {
    /**
     * Renders the input for one form field definition [key, label, type, options].
     * Types: text, textarea, date, email, tel, numeric (digits typed as text), number,
     * year, and select (options from EmployeeProfileData::fieldOptions()).
     */
    function employee_field_input(array $field, string $name, $value, array $attributes = []): string
    {
        [, $label, $type] = array_pad($field, 3, 'text');
        $value = $value === null ? '' : (string) $value;
        $attributes = ['class' => 'form-control', 'name' => $name] + $attributes;
        $attributeHtml = static function (array $attributes): string {
            $html = '';
            foreach ($attributes as $attribute => $attributeValue) {
                if ($attributeValue === false || $attributeValue === null) {
                    continue;
                }
                $html .= $attributeValue === true ? ' ' . $attribute : ' ' . $attribute . '="' . esc((string) $attributeValue) . '"';
            }

            return $html;
        };

        if ($type === 'textarea') {
            return '<textarea' . $attributeHtml($attributes + ['rows' => 3]) . '>' . esc($value) . '</textarea>';
        }

        if ($type === 'select') {
            $options = EmployeeProfileData::fieldOptions($field[3] ?? '');
            $html = '<select' . $attributeHtml($attributes) . '><option value="">Pilih ' . esc(mb_strtolower($label)) . '</option>';
            if ($value !== '' && ! in_array($value, $options, true)) {
                // Keep values saved before the option list existed.
                $html .= '<option value="' . esc($value) . '" selected>' . esc($value) . '</option>';
            }
            foreach ($options as $option) {
                $html .= '<option value="' . esc($option) . '"' . ($option === $value ? ' selected' : '') . '>' . esc($option) . '</option>';
            }

            return $html . '</select>';
        }

        $attributes += match ($type) {
            'date' => ['type' => 'date'],
            'email' => ['type' => 'email'],
            'tel' => ['type' => 'tel', 'inputmode' => 'tel', 'maxlength' => 20, 'placeholder' => '08xxxxxxxxxx'],
            'numeric' => ['type' => 'text', 'inputmode' => 'numeric', 'maxlength' => $field[3] ?? 30],
            'number' => ['type' => 'number', 'min' => 0, 'step' => 'any'],
            'year' => ['type' => 'number', 'min' => 1900, 'max' => 2100, 'step' => 1, 'placeholder' => 'YYYY'],
            default => ['type' => 'text'],
        };

        return '<input' . $attributeHtml($attributes + ['value' => $value]) . '>';
    }
}

if (! function_exists('employee_table_columns')) {
    /**
     * Groups field definitions into table columns. Consecutive fields sharing the 5th element
     * (a column title, as in the Word form) are stacked in one column.
     */
    function employee_table_columns(array $fields): array
    {
        $columns = [];
        foreach ($fields as $field) {
            $title = $field[4] ?? $field[1];
            $last = array_key_last($columns);
            if ($last !== null && isset($field[4]) && $columns[$last]['title'] === $title) {
                $columns[$last]['fields'][] = $field;
                continue;
            }
            $columns[] = ['title' => $title, 'fields' => [$field]];
        }

        return $columns;
    }
}

if (! function_exists('employee_table_cell')) {
    /** Content of one table cell: inputs (named "$namePrefix[key]") when editable, otherwise values. */
    function employee_table_cell(array $column, array $record, bool $editable, string $namePrefix): string
    {
        $stacked = count($column['fields']) > 1;
        $html = '';
        foreach ($column['fields'] as $field) {
            $value = $record[$field[0]] ?? '';
            if ($editable) {
                $input = employee_field_input($field, $namePrefix . '[' . $field[0] . ']', $value, ['aria-label' => $field[1]]);
                $html .= $stacked ? '<label class="repeat-stack"><small>' . esc($field[1]) . '</small>' . $input . '</label>' : $input;
                continue;
            }
            if (! $stacked) {
                $html .= employee_field_display($value, $field);
            } elseif ($value !== null && trim((string) $value) !== '') {
                $html .= '<div class="repeat-stack-line"><small>' . esc($field[1]) . '</small>' . employee_field_display($value, $field) . '</div>';
            }
        }

        return $html === '' ? '—' : $html;
    }
}

if (! function_exists('employee_field_display')) {
    /** Read-only value for a field: dates as dd-mm-yyyy, empty values as a dash. */
    function employee_field_display($value, array $field): string
    {
        $type = $field[2] ?? 'text';
        if ($value === null || trim((string) $value) === '') {
            return '—';
        }
        if ($type === 'date') {
            $date = \DateTimeImmutable::createFromFormat('!Y-m-d', substr((string) $value, 0, 10));
            if ($date !== false) {
                return $date->format('d-m-Y');
            }
        }

        return esc((string) $value);
    }
}
