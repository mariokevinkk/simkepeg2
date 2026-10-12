<?php

namespace App\Libraries;

/**
 * Minimal .xlsx writer (no external library): text-only cells on single lines, a bold header row
 * that stays frozen with an autofilter, and column widths sized to the content (at most 60). Every value is written as
 * text so NIP/NIK keep their digits instead of turning into scientific notation.
 */
final class XlsxWriter
{
    /** @var list<array{name: string, headers: list<string>, rows: list<list<string>>}> */
    private array $sheets = [];

    public function addSheet(string $name, array $headers, array $rows): self
    {
        // Excel sheet names: max 31 characters, none of : \ / ? * [ ]
        $name = str_replace([':', '\\', '/', '?', '*', '[', ']'], '-', $name);
        $name = rtrim(mb_substr(trim(preg_replace('/\s+/', ' ', $name) ?? ''), 0, 31), ' -');
        $this->sheets[] = ['name' => $name !== '' ? $name : 'Sheet' . (count($this->sheets) + 1), 'headers' => array_values($headers), 'rows' => $rows];

        return $this;
    }

    /** Writes the workbook to $path. */
    public function save(string $path): void
    {
        $zip = new \ZipArchive();
        if ($zip->open($path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('File Excel tidak dapat dibuat.');
        }

        $sheetCount = count($this->sheets);
        $overrides = '';
        $workbookSheets = '';
        $workbookRels = '';
        foreach ($this->sheets as $index => $sheet) {
            $number = $index + 1;
            $overrides .= '<Override PartName="/xl/worksheets/sheet' . $number . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
            $workbookSheets .= '<sheet name="' . $this->xml($sheet['name']) . '" sheetId="' . $number . '" r:id="rId' . $number . '"/>';
            $workbookRels .= '<Relationship Id="rId' . $number . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $number . '.xml"/>';
            $zip->addFromString('xl/worksheets/sheet' . $number . '.xml', $this->sheetXml($sheet));
        }
        $workbookRels .= '<Relationship Id="rId' . ($sheetCount + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';

        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . $overrides . '</Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>');
        $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets>' . $workbookSheets . '</sheets>'
            . $this->definedNames() . '</workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . $workbookRels . '</Relationships>');
        // Style 0 = normal, 1 = header (bold, light fill, thin border), 2 = body cell (single line, thin border).
        $zip->addFromString('xl/styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FFDDE7F7"/><bgColor indexed="64"/></patternFill></fill></fills>'
            . '<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border>'
            . '<border><left style="thin"><color rgb="FFB7C3D6"/></left><right style="thin"><color rgb="FFB7C3D6"/></right><top style="thin"><color rgb="FFB7C3D6"/></top><bottom style="thin"><color rgb="FFB7C3D6"/></bottom><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="3"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="49" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment vertical="center"/></xf>'
            . '<xf numFmtId="49" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment vertical="center"/></xf></cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>');
        $zip->close();
    }

    private function sheetXml(array $sheet): string
    {
        $columnCount = max(1, count($sheet['headers']));
        $widths = array_map(static fn (string $header): int => mb_strlen($header), $sheet['headers']);
        foreach ($sheet['rows'] as $row) {
            foreach (array_values($row) as $column => $value) {
                $longestLine = max(array_map('mb_strlen', explode("\n", (string) $value)));
                $widths[$column] = max($widths[$column] ?? 0, $longestLine);
            }
        }
        $cols = '';
        foreach ($widths as $column => $width) {
            $cols .= '<col min="' . ($column + 1) . '" max="' . ($column + 1) . '" width="' . min(60, max(8, $width + 2)) . '" customWidth="1"/>';
        }

        $rowsXml = $this->rowXml(1, $sheet['headers'], 1);
        foreach ($sheet['rows'] as $index => $row) {
            $rowsXml .= $this->rowXml($index + 2, array_values($row), 2);
        }
        $lastCell = $this->columnName($columnCount) . (count($sheet['rows']) + 1);

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>'
            . '<cols>' . $cols . '</cols>'
            . '<sheetData>' . $rowsXml . '</sheetData>'
            . '<autoFilter ref="A1:' . $lastCell . '"/>'
            . '</worksheet>';
    }

    private function rowXml(int $rowNumber, array $values, int $style): string
    {
        $cells = '';
        foreach ($values as $column => $value) {
            $value = (string) $value;
            $reference = $this->columnName($column + 1) . $rowNumber;
            $cells .= $value === ''
                ? '<c r="' . $reference . '" s="' . $style . '"/>'
                : '<c r="' . $reference . '" s="' . $style . '" t="inlineStr"><is><t xml:space="preserve">' . $this->xml($value) . '</t></is></c>';
        }

        return '<row r="' . $rowNumber . '">' . $cells . '</row>';
    }

    /** Autofilter ranges must also be registered as hidden defined names. */
    private function definedNames(): string
    {
        $names = '';
        foreach ($this->sheets as $index => $sheet) {
            $last = $this->columnName(max(1, count($sheet['headers']))) . (count($sheet['rows']) + 1);
            $names .= '<definedName name="_xlnm._FilterDatabase" localSheetId="' . $index . '" hidden="1">\''
                . str_replace("'", "''", $this->xml($sheet['name'])) . '\'!$A$1:$' . preg_replace('/\d+$/', '', $last) . '$' . (count($sheet['rows']) + 1) . '</definedName>';
        }

        return '<definedNames>' . $names . '</definedNames>';
    }

    private function columnName(int $number): string
    {
        $name = '';
        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $name = chr(65 + $remainder) . $name;
            $number = intdiv($number - 1, 26);
        }

        return $name;
    }

    private function xml(string $value): string
    {
        // Strip characters XML 1.0 does not allow, then escape.
        $value = preg_replace('/[^\x{9}\x{A}\x{D}\x{20}-\x{D7FF}\x{E000}-\x{FFFD}]/u', '', $value) ?? '';

        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
