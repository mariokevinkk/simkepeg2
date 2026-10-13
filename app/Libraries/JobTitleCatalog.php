<?php

namespace App\Libraries;

final class JobTitleCatalog
{
    /** Daftar awal jabatan dan jenis ketenagaan yang dapat ditambah oleh admin. */
    public static function groups(): array
    {
        $skilled = ['Terampil', 'Mahir', 'Penyelia'];
        $expert = ['Ahli Pertama', 'Ahli Muda', 'Ahli Madya'];
        $titles = static fn (string $name, array $levels): array => array_map(
            static fn (string $level): string => $name . ' ' . $level,
            $levels,
        );

        return [
            'Dokter' => $titles('Dokter', [...$expert, 'Ahli Utama']),
            'Dokter Gigi' => $titles('Dokter Gigi', [...$expert, 'Ahli Utama']),
            'Perawat' => $titles('Perawat', [...$skilled, ...$expert]),
            'Bidan' => $titles('Bidan', [...$skilled, ...$expert]),
            'Perawat Gigi' => $titles('Perawat Gigi', [...$skilled, ...$expert]),
            'Asisten Penata Anestesi' => $titles('Asisten Penata Anestesi', $skilled),
            'Penata Anestesi' => $titles('Penata Anestesi', $expert),
            'Perekam Medis' => $titles('Perekam Medis', [...$skilled, ...$expert]),
            'Radiografer' => $titles('Radiografer', [...$skilled, ...$expert]),
            'Fisikawan Medis' => $titles('Fisikawan Medis', $expert),
            'Nutrisionis' => $titles('Nutrisionis', [...$skilled, ...$expert]),
            'Asisten Apoteker' => $titles('Asisten Apoteker', $skilled),
            'Apoteker' => $titles('Apoteker', $expert),
            'Pranata Laboratorium Kesehatan' => $titles('Pranata Laboratorium Kesehatan', [...$skilled, ...$expert]),
            'Sanitarian' => $titles('Sanitarian', [...$skilled, ...$expert]),
            'Teknisi Elektromedis' => $titles('Teknisi Elektromedis', [...$skilled, ...$expert]),
            'Fisioterapi' => $titles('Fisioterapi', [...$skilled, ...$expert]),
            'Terapi Wicara' => $titles('Terapi Wicara', [...$skilled, ...$expert]),
            'Okupasi Terapi' => $titles('Okupasi Terapi', [...$skilled, ...$expert]),
            'Ortotis Prostesis' => ['Ortotis Prostesis Terampil'],
            'Psikolog Klinis' => $titles('Psikolog Klinis', $expert),
            'Pembimbing Kesehatan Kerja' => $titles('Pembimbing Kesehatan Kerja', $expert),
            'Teknisi Transfusi Darah' => $titles('Teknisi Transfusi Darah', $skilled),
            'Penyuluh Kesehatan Masyarakat' => $titles('Penyuluh Kesehatan Masyarakat', [...$skilled, ...$expert]),
            'Pranata SDM Aparatur' => ['Pranata SDM Aparatur Terampil', 'Pranata SDM Aparatur Mahir'],
            'Analis SDM Aparatur' => $titles('Analis SDM Aparatur', $expert),
            'Analis Kebijakan' => $titles('Analis Kebijakan', $expert),
            'Pranata Komputer' => $titles('Pranata Komputer', $expert),
            'Perencana' => ['Perencana Ahli Muda', 'Perencana Ahli Madya'],
            'Pengelola Layanan Kesehatan' => ['Pengelola Layanan Kesehatan'],
            'Struktural' => ['Struktural'],
            'Tenaga Umum Non Kesehatan' => [
                'Penelaah Teknis Kebijakan',
                'Penata Layanan Operasional',
                'Pengolah Data dan Informasi',
                'Pengadministrasi Perkantoran',
                'Pengelola Layanan Operasional',
                'Operator Layanan Operasional',
                'Pengelola Umum Operasional',
            ],
        ];
    }

    public static function rows(): array
    {
        $rows = [];
        $order = 0;
        foreach (self::groups() as $group => $titles) {
            foreach ($titles as $title) {
                $rows[] = [
                    'group_name' => $group,
                    'title' => $title,
                    'is_active' => 1,
                    'sort_order' => ++$order,
                ];
            }
        }

        return $rows;
    }
}
