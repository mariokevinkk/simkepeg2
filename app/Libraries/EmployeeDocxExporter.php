<?php

namespace App\Libraries;

/**
 * Fills the official "Data Pegawai Kota Yogyakarta" Word form (app/Templates/isian-pegawai.docx)
 * with one employee's data: sections A-F go into the value column of the first table, and every
 * table G-X gets one row per record (an empty row when there are none).
 */
final class EmployeeDocxExporter
{
    private const W = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';

    /**
     * Columns of each repeatable table, by section letter, in the order of the Word form.
     * A column is a field key, a list of keys (one line each), or ['join' => sep, 'keys' => [...]].
     */
    private const TABLES = [
        'G' => ['source' => 'kepangkatan', 'columns' => ['pangkat_gol_ruang', 'jenis_kenaikan', 'tmt', 'tanggal_sk', 'nomor_sk', 'masa_kerja', 'jumlah_kredit']],
        'H' => ['source' => 'jabatan', 'columns' => [['nama_jabatan', 'sub_unit_kerja'], 'jenis_perubahan_jabatan', 'eselon', 'pangkat_gol_ruang', 'jumlah_kredit', ['join' => ', ', 'keys' => ['nomor_sk', 'tanggal_sk', 'pejabat_pengesah']], 'tmt', 'tanggal_pelantikan']],
        'I' => ['source' => 'tugas_tambahan', 'columns' => [['nama_jabatan', 'sub_unit_kerja'], 'jenis_perubahan_jabatan', 'eselon', 'pangkat_gol_ruang', 'jumlah_kredit', ['join' => ', ', 'keys' => ['nomor_sk', 'tanggal_sk', 'pejabat_pengesah']], 'tmt', 'tanggal_pelantikan']],
        'J' => ['source' => 'education', 'columns' => ['tingkat', ['nama', 'instansi', 'fakultas'], 'tempat', ['nomor_ijazah', 'tanggal_ijazah', 'nilai'], 'status']],
        'K' => ['source' => 'tanda_jasa', 'columns' => ['nama_tanda_jasa_satya_lencana', 'nomor_sk_tanggal', 'tahun', 'asal']],
        'L' => ['source' => 'prestasi', 'columns' => ['judul_penghargaan', 'jenjang', 'kategori', 'juara']],
        'M' => ['source' => 'karya_ilmiah', 'columns' => ['judul', 'nomor_isbn', 'tahun_publikasi']],
        'N' => ['source' => 'kunjungan_luar_negeri', 'columns' => ['negara_tujuan', 'jenis_penugasan', 'keterangan', 'surat_penugasan_yang_menetapkan', 'nomor_sk', 'tanggal', 'tgl_mulai', 'tgl_selesai']],
        'O' => ['source' => 'organisasi', 'columns' => ['jenis_organisasi', 'nama_organisasi', 'jabatan', ['join' => ' s.d. ', 'keys' => ['tanggal_mulai', 'tanggal_selesai']], 'tempat']],
        'P' => ['source' => 'pengalaman_kerja', 'columns' => ['instansi', 'jabatan', 'tahun', 'bulan']],
        'Q' => ['source' => 'penguasaan_bahasa', 'columns' => ['nama_bahasa', 'kemampuan']],
        'R' => ['source' => 'sanksi', 'columns' => ['hukuman_tingkat', ['nomor', 'tanggal', ['join' => ' s.d. ', 'keys' => ['mulai', 'selesai']]], 'pejabat_pengesah', 'keterangan']],
        'S' => ['source' => 'lain_lain', 'columns' => [['nama', 'tempat'], ['nomor', 'tanggal', ['join' => ' s.d. ', 'keys' => ['mulai', 'selesai']]], 'keterangan']],
        'T' => ['source' => 'pasangan', 'columns' => self::FAMILY],
        'U' => ['source' => 'anak', 'columns' => ['nama', 'status_keluarga', '@file', ['join' => ', ', 'keys' => ['tempat_lahir', 'tanggal_lahir']], 'status_perkawinan', 'tanggal_pernikahan', 'pekerjaan', 'nip_nrp', 'keterangan']],
        'V' => ['source' => 'orang_tua', 'columns' => self::FAMILY],
        'W' => ['source' => 'mertua', 'columns' => self::FAMILY],
        'X' => ['source' => 'saudara', 'columns' => self::FAMILY],
    ];

    private const FAMILY = ['nama', 'status_keluarga', ['join' => ', ', 'keys' => ['tempat_lahir', 'tanggal_lahir']], 'status_perkawinan', 'tanggal_pernikahan', 'pekerjaan', 'nip_nrp', 'keterangan'];

    private \DOMDocument $doc;
    private \DOMXPath $xpath;
    /** @var array<string, string> field key => type (only date matters here) */
    private array $fieldTypes = [];

    /**
     * Builds the filled document for an employee bundle (EmployeeModel::bundle()) and returns
     * the .docx file contents.
     */
    public function build(array $bundle): string
    {
        $template = APPPATH . 'Templates' . DIRECTORY_SEPARATOR . 'isian-pegawai.docx';
        $temp = tempnam(sys_get_temp_dir(), 'docx');
        if ($temp === false || ! copy($template, $temp)) {
            throw new \RuntimeException('Template Word tidak dapat dibaca.');
        }

        try {
            $zip = new \ZipArchive();
            if ($zip->open($temp) !== true) {
                throw new \RuntimeException('Template Word rusak.');
            }
            $this->doc = new \DOMDocument();
            $this->doc->preserveWhiteSpace = true;
            $this->doc->loadXML((string) $zip->getFromName('word/document.xml'));
            $this->xpath = new \DOMXPath($this->doc);
            $this->xpath->registerNamespace('w', self::W);
            $this->collectFieldTypes();

            $tables = $this->xpath->query('/w:document/w:body/w:tbl');
            foreach ($tables as $index => $table) {
                if ($index === 0) {
                    $this->fillSummaryTable($table, $bundle['pegawai'] ?? []);
                    continue;
                }
                $letter = substr(trim($this->text($this->rows($table)[0] ?? null)), 0, 1);
                if (isset(self::TABLES[$letter])) {
                    $this->fillRepeatTable($table, $letter, $this->records($bundle, self::TABLES[$letter]['source']));
                }
            }

            $zip->addFromString('word/document.xml', $this->doc->saveXML());
            $zip->close();

            return (string) file_get_contents($temp);
        } finally {
            @unlink($temp);
        }
    }

    private function collectFieldTypes(): void
    {
        $sections = array_merge(EmployeeProfileData::officialCoreSections(), [EmployeeProfileData::officialLatestEducationSection()]);
        foreach ($sections as $section) {
            foreach ($section['fields'] as $field) {
                $this->fieldTypes[$field[0]] = $field[2] ?? 'text';
            }
        }
        foreach (EmployeeProfileData::officialHistorySections() as $section) {
            foreach ($section['fields'] as $field) {
                $this->fieldTypes[$field[0]] = ($this->fieldTypes[$field[0]] ?? '') === 'date' ? 'date' : ($field[2] ?? 'text');
            }
        }
        foreach (EmployeeProfileData::educationFields() as $field) {
            $this->fieldTypes[$field[0]] = $field[2] ?? 'text';
        }
    }

    /** Sections A-F: rows "01 | label | : | value"; the label is matched within its section. */
    private function fillSummaryTable(\DOMElement $table, array $employee): void
    {
        $labels = [];
        foreach (array_merge(EmployeeProfileData::officialCoreSections(), [EmployeeProfileData::officialLatestEducationSection()]) as $section) {
            foreach ($section['fields'] as $field) {
                $labels[$section['letter']][$this->normalize($field[1])] = $field[0];
            }
        }

        $section = '';
        foreach ($this->rows($table) as $row) {
            $cells = $this->cells($row);
            if (count($cells) === 1) {
                $section = substr(trim($this->text($cells[0])), 0, 1);
                continue;
            }
            if (count($cells) < 4) {
                continue;
            }
            $key = $labels[$section][$this->normalize($this->text($cells[1]))] ?? null;
            if ($key === null) {
                continue;
            }
            $value = $this->format($key, $employee[$key] ?? '');
            if ($key === 'unit_kerja' && $value === '') {
                $value = (string) ($employee['unit_name'] ?? '');
            }
            if ($key === 'telepon_darurat' && trim((string) ($employee['telepon_darurat_hubungan'] ?? '')) !== '') {
                $value = trim($value . ' (' . $employee['telepon_darurat_hubungan'] . ')');
            }
            // The form centres a few value cells only; align every value to the left.
            foreach (iterator_to_array($this->xpath->query('./w:p/w:pPr/w:jc', $cells[3])) as $alignment) {
                $alignment->parentNode->removeChild($alignment);
            }
            $this->setCellLines($cells[3], explode("\n", $value), $cells[1]);
        }
    }

    /** Tables G-X: the last header row is the pattern for every data row. */
    private function fillRepeatTable(\DOMElement $table, string $letter, array $records): void
    {
        $headerRows = $letter === 'N' ? 3 : 2;
        $rows = $this->rows($table);
        if (count($rows) < $headerRows) {
            return;
        }
        foreach (array_slice($rows, $headerRows) as $leftover) {
            $table->removeChild($leftover);
        }
        $pattern = $rows[$headerRows - 1];
        $records = $records === [] ? [null] : array_values($records);

        foreach ($records as $index => $record) {
            $row = $pattern->cloneNode(true);
            $this->plainRow($row);
            $cells = $this->cells($row);
            $values = [$record === null ? '' : (string) ($index + 1)];
            foreach (self::TABLES[$letter]['columns'] as $column) {
                $values[] = $record === null ? [] : $this->columnLines($column, $record);
            }
            foreach ($cells as $position => $cell) {
                $lines = $values[$position] ?? [];
                $this->setCellLines($cell, is_array($lines) ? $lines : [$lines], null);
            }
            $table->appendChild($row);
        }
    }

    /** @return list<string> */
    private function columnLines($column, array $record): array
    {
        if ($column === '@file') {
            return [! empty($record['dokumen']['file']) ? 'Ada' : '-'];
        }
        if (is_string($column)) {
            return [$this->format($column, $record[$column] ?? '')];
        }
        if (isset($column['join'])) {
            $parts = [];
            foreach ($column['keys'] as $key) {
                $parts[] = $this->format($key, $record[$key] ?? '');
            }
            return [implode($column['join'], array_filter($parts, static fn (string $part): bool => $part !== ''))];
        }
        $lines = [];
        foreach ($column as $part) {
            $lines = array_merge($lines, $this->columnLines($part, $record));
        }

        return array_values(array_filter($lines, static fn (string $line): bool => $line !== ''));
    }

    private function records(array $bundle, string $source): array
    {
        return $source === 'education' ? ($bundle['pendidikan'] ?? []) : ($bundle['histories'][$source] ?? []);
    }

    private function format(string $key, $value): string
    {
        $value = trim((string) ($value ?? ''));
        if ($value !== '' && ($this->fieldTypes[$key] ?? '') === 'date') {
            $date = \DateTimeImmutable::createFromFormat('!Y-m-d', substr($value, 0, 10));
            if ($date !== false) {
                return $date->format('d-m-Y');
            }
        }

        return $value;
    }

    /** A copy of a header row turned into a data row: no shading, no bold, no vertical merges. */
    private function plainRow(\DOMElement $row): void
    {
        foreach (['.//w:tcPr/w:shd', './/w:tcPr/w:vMerge', './/w:rPr/w:b', './/w:rPr/w:bCs', './/w:rPr/w:caps'] as $path) {
            foreach (iterator_to_array($this->xpath->query($path, $row)) as $node) {
                $node->parentNode->removeChild($node);
            }
        }
        foreach (iterator_to_array($this->xpath->query('.//w:trPr/w:tblHeader', $row)) as $node) {
            $node->parentNode->removeChild($node);
        }
    }

    /**
     * Replaces a cell's text with the given lines (line breaks between them), keeping the first
     * paragraph's layout and the font of the cell's first run (or of $styleFrom when it has none).
     */
    private function setCellLines(\DOMElement $cell, array $lines, ?\DOMElement $styleFrom): void
    {
        $runStyle = $this->xpath->query('.//w:r/w:rPr', $cell)->item(0)
            ?? ($styleFrom !== null ? $this->xpath->query('.//w:r/w:rPr', $styleFrom)->item(0) : null);
        $runStyle = $runStyle?->cloneNode(true);
        if ($runStyle instanceof \DOMElement) {
            foreach (iterator_to_array($this->xpath->query('./w:b | ./w:bCs', $runStyle)) as $bold) {
                $runStyle->removeChild($bold);
            }
        }

        $paragraphs = iterator_to_array($this->xpath->query('./w:p', $cell));
        $paragraph = $paragraphs[0] ?? $cell->appendChild($this->doc->createElementNS(self::W, 'w:p'));
        foreach (array_slice($paragraphs, 1) as $extra) {
            $cell->removeChild($extra);
        }
        foreach (iterator_to_array($paragraph->childNodes) as $child) {
            if (! ($child instanceof \DOMElement && $child->localName === 'pPr')) {
                $paragraph->removeChild($child);
            }
        }

        $lines = array_values(array_filter(array_map('strval', $lines), static fn (string $line): bool => $line !== ''));
        if ($lines === []) {
            return;
        }
        $run = $this->doc->createElementNS(self::W, 'w:r');
        if ($runStyle !== null) {
            $run->appendChild($runStyle);
        }
        foreach ($lines as $index => $line) {
            if ($index > 0) {
                $run->appendChild($this->doc->createElementNS(self::W, 'w:br'));
            }
            $text = $this->doc->createElementNS(self::W, 'w:t');
            $text->setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:space', 'preserve');
            $text->appendChild($this->doc->createTextNode($line));
            $run->appendChild($text);
        }
        $paragraph->appendChild($run);
    }

    /** @return list<\DOMElement> */
    private function rows(\DOMElement $table): array
    {
        return iterator_to_array($this->xpath->query('./w:tr', $table));
    }

    /** @return list<\DOMElement> */
    private function cells(\DOMElement $row): array
    {
        return iterator_to_array($this->xpath->query('./w:tc', $row));
    }

    private function text(?\DOMNode $node): string
    {
        if ($node === null) {
            return '';
        }
        $text = '';
        foreach ($this->xpath->query('.//w:t', $node) as $part) {
            $text .= $part->textContent;
        }

        return $text;
    }

    private function normalize(string $label): string
    {
        return preg_replace('/\s+/', ' ', mb_strtolower(trim($label, " :\t\n"))) ?? '';
    }
}
