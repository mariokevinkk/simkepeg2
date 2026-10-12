<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$employeeProfile = $employee_profile ?? \App\Libraries\EmployeeProfileData::get();
$employee = $employeeProfile['pegawai'];
$career = $employeeProfile['karir'];
$profession = $employeeProfile['profesi'];
$education = $employeeProfile['pendidikan'][0] ?? [];
?>
<style>
    .section-title {
        margin: 32px 0 16px 0;
        color: var(--text-main);
        border-bottom: 1px solid var(--border);
        padding-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title:first-child {
        margin-top: 0;
    }
</style>

<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 class="page-title" style="margin: 0;">Data Utama Pegawai</h1>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
    </div>
</div>

<div class="main-card" style="padding: 32px; margin-bottom: 32px;">
    <h4 class="section-title"><i class="fa-regular fa-id-card"></i> A. Data Pribadi</h4>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <div><label class="form-label">NIP Lama</label><input type="text" class="form-control" value="<?= esc($employee['nip_lama']) ?>" /></div>
        <div><label class="form-label">NIP</label><input type="text" class="form-control" value="<?= esc($employee['nip']) ?>" /></div>
        <div><label class="form-label">NIK</label><input type="text" class="form-control" value="<?= esc($employee['nik']) ?>" /></div>
        
        <!-- 3, 4, 5 -->
        <div style="grid-column: 1 / -1; display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 24px;">
            <div><label class="form-label">Gelar Depan</label><input type="text" class="form-control" value="<?= esc($employee['gelar_depan']) ?>" /></div>
            <div><label class="form-label">Nama Pegawai</label><input type="text" class="form-control" value="<?= esc($employee['nama']) ?>" /></div>
            <div><label class="form-label">Gelar Belakang</label><input type="text" class="form-control" value="<?= esc($employee['gelar_belakang']) ?>" /></div>
        </div>

        <!-- 6, 7 -->
        <div><label class="form-label">Tempat Lahir</label><input type="text" class="form-control" value="<?= esc($employee['tempat_lahir']) ?>" /></div>
        <div><label class="form-label">Tanggal Lahir</label><input type="date" class="form-control" value="<?= esc($employee['tanggal_lahir']) ?>" /></div>
        
        <!-- 8, 9, 11 -->
        <div>
            <label class="form-label">Jenis Kelamin</label>
            <select class="form-control">
                <option <?= $employee['jenis_kelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option><option <?= $employee['jenis_kelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
        <div>
            <label class="form-label">Agama</label>
            <select class="form-control">
                <option <?= $employee['agama'] === 'Islam' ? 'selected' : '' ?>>Islam</option><option <?= $employee['agama'] === 'Katolik' ? 'selected' : '' ?>>Katolik</option><option <?= $employee['agama'] === 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                <option <?= $employee['agama'] === 'Hindu' ? 'selected' : '' ?>>Hindu</option><option <?= $employee['agama'] === 'Buddha' ? 'selected' : '' ?>>Buddha</option><option <?= $employee['agama'] === 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
            </select>
        </div>
        <div>
            <label class="form-label">Status Perkawinan</label>
            <select class="form-control">
                <option <?= $employee['status_perkawinan'] === 'Kawin' ? 'selected' : '' ?>>Kawin</option><option <?= $employee['status_perkawinan'] === 'Belum Kawin' ? 'selected' : '' ?>>Belum Kawin</option><option <?= $employee['status_perkawinan'] === 'Cerai Hidup' ? 'selected' : '' ?>>Cerai Hidup</option><option <?= $employee['status_perkawinan'] === 'Cerai Mati' ? 'selected' : '' ?>>Cerai Mati</option>
            </select>
        </div>
        
        <!-- 10. Status Kepegawaian -->
        <div>
            <label class="form-label">Status Kepegawaian</label>
            <select class="form-control">
                <optgroup label="ASN">
                    <option <?= $employee['jenis_kepegawaian'] === 'PNS' ? 'selected' : '' ?>>PNS</option><option <?= $employee['jenis_kepegawaian'] === 'CPNS' ? 'selected' : '' ?>>CPNS</option><option <?= $employee['jenis_kepegawaian'] === 'PPPK' ? 'selected' : '' ?>>PPPK</option><option <?= $employee['jenis_kepegawaian'] === 'PPPK Paruh Waktu' ? 'selected' : '' ?>>PPPK Paruh Waktu</option>
                </optgroup>
                <optgroup label="BLUD">
                    <option <?= $employee['jenis_kepegawaian'] === 'BLUD Tetap' ? 'selected' : '' ?>>BLUD Tetap</option><option <?= $employee['jenis_kepegawaian'] === 'BLUD Kontrak' ? 'selected' : '' ?>>BLUD Kontrak</option>
                </optgroup>
                <optgroup label="Mitra">
                    <option <?= $employee['jenis_kepegawaian'] === 'Mitra Penuh Waktu' ? 'selected' : '' ?>>Mitra Penuh Waktu</option><option <?= $employee['jenis_kepegawaian'] === 'Mitra Paruh Waktu' ? 'selected' : '' ?>>Mitra Paruh Waktu</option>
                </optgroup>
            </select>
        </div>
        <div><label class="form-label">Status Pegawai</label><input type="text" class="form-control" value="<?= esc($employee['status']) ?>" /></div>
        <div><label class="form-label">Kedudukan Pegawai</label><input type="text" class="form-control" value="<?= esc($employee['kedudukan']) ?>" /></div>
    </div>

    <h4 class="section-title"><i class="fa-solid fa-map-location-dot"></i> B. Kontak & Alamat</h4>
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <!-- 12. Alamat -->
        <div style="grid-column: 1 / -1;"><label class="form-label">Jalan / Pedukuhan</label><input type="text" class="form-control" value="<?= esc($employee['jalan']) ?>" /></div>
        <div><label class="form-label">RT / RW</label><input type="text" class="form-control" placeholder="001/002" value="<?= esc($employee['rt_rw']) ?>" /></div>
        <div><label class="form-label">Desa / Kelurahan</label><input type="text" class="form-control" value="<?= esc($employee['kelurahan']) ?>" /></div>
        <div><label class="form-label">Kecamatan</label><input type="text" class="form-control" value="<?= esc($employee['kecamatan']) ?>" /></div>
        <div><label class="form-label">Kabupaten</label><input type="text" class="form-control" value="<?= esc($employee['kabupaten']) ?>" /></div>
        <div><label class="form-label">Provinsi</label><input type="text" class="form-control" value="<?= esc($employee['provinsi']) ?>" /></div>
        
        <!-- 13, 14, 15 -->
        <div><label class="form-label">No. Telepon / HP</label><input type="text" class="form-control" value="<?= esc($employee['telepon']) ?>" /></div>
        <div><label class="form-label">Kontak Darurat</label><input type="text" class="form-control" value="<?= esc($employee['telepon_darurat']) ?>" /></div>
        <div><label class="form-label">E-Mail</label><input type="email" class="form-control" value="<?= esc($employee['email']) ?>" /></div>
        <div><label class="form-label">No. KARPEG</label><input type="text" class="form-control" value="<?= esc($employee['karpeg']) ?>" /></div>
        <div><label class="form-label">Kartu ASN Virtual</label><input type="text" class="form-control" value="<?= esc($employee['kartu_asn']) ?>" /></div>
        <div><label class="form-label">No. ASKES / BPJS</label><input type="text" class="form-control" value="<?= esc($employee['bpjs']) ?>" /></div>
        <div><label class="form-label">No. Kartu Taspen</label><input type="text" class="form-control" value="<?= esc($employee['taspen']) ?>" /></div>
        <div><label class="form-label">No. KARIS / KARSU</label><input type="text" class="form-control" value="<?= esc($employee['karis_karsu']) ?>" /></div>
        <div><label class="form-label">NPWP</label><input type="text" class="form-control" value="<?= esc($employee['npwp']) ?>" /></div>
    </div>
    <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'identitas', 'document_mode' => 'edit']) ?>

    <h4 class="section-title"><i class="fa-solid fa-file-medical"></i> C. Legalitas Profesi (SIP & STR)</h4>
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <!-- SIP -->
        <div><label class="form-label">No. SIP</label><input type="text" class="form-control" value="<?= esc($profession['sip_nomor']) ?>" /></div>
        <div><label class="form-label">Tanggal Terbit SIP</label><input type="date" class="form-control" value="<?= esc($profession['sip_terbit']) ?>" /></div>
        <div>
            <label class="form-label">Tanggal Exp / Berlaku SIP</label>
            <input type="date" class="form-control" value="<?= esc($profession['sip_berlaku']) ?>" />
            <small style="color: var(--warning); display: block; margin-top: 4px;"><i class="fa-solid fa-circle-exclamation"></i> Segera perbaharui SIP jika < 6 bulan</small>
        </div>
        
        <!-- STR -->
        <div><label class="form-label">No. STR</label><input type="text" class="form-control" value="<?= esc($profession['str_nomor']) ?>" /></div>
        <div><label class="form-label">Tanggal Terbit STR</label><input type="date" class="form-control" value="<?= esc($profession['str_terbit']) ?>" /></div>
        <div>
            <label class="form-label">Tanggal Exp / Berlaku STR</label>
            <div style="display: flex; gap: 8px; align-items: center;">
                <input type="date" class="form-control" value="<?= esc($profession['str_berlaku']) ?>" />
                <div style="white-space: nowrap;">
                    <input type="checkbox" id="str_seumur_hidup_pegawai"> <label for="str_seumur_hidup_pegawai">Seumur Hidup</label>
                </div>
            </div>
        </div>
    </div>
    <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'profesi', 'document_mode' => 'edit']) ?>

    <h4 class="section-title"><i class="fa-solid fa-user-doctor"></i> D. Ketenagaan & Jabatan</h4>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <!-- 18. Jenis Ketenagaan -->
        <div>
            <label class="form-label">Jenis Ketenagaan</label>
            <select class="form-control">
                <optgroup label="TENAGA MEDIS">
                    <option <?= $career['jenis_ketenagaan'] === 'DOKTER UMUM' ? 'selected' : '' ?>>DOKTER UMUM</option><option <?= $career['jenis_ketenagaan'] === 'DOKTER GIGI' ? 'selected' : '' ?>>DOKTER GIGI</option>
                </optgroup>
                <optgroup label="TENAGA KESEHATAN">
                    <option <?= $career['jenis_ketenagaan'] === 'PERAWAT' ? 'selected' : '' ?>>PERAWAT</option><option <?= $career['jenis_ketenagaan'] === 'KPKL' ? 'selected' : '' ?>>KPKL</option>
                </optgroup>
                <optgroup label="TENAGA UMUM">
                    <option <?= $career['jenis_ketenagaan'] === 'Struktural' ? 'selected' : '' ?>>Struktural</option>
                </optgroup>
            </select>
        </div>
        
        <!-- 19. Jabatan -->
        <div>
            <label class="form-label">Jabatan & Jenjang</label>
            <select class="form-control">
                <optgroup label="Jenjang Keahlian">
                <option <?= $career['jenjang_jabatan'] === 'Ahli Utama' ? 'selected' : '' ?>>Ahli Utama</option><option <?= $career['jenjang_jabatan'] === 'Ahli Madya' ? 'selected' : '' ?>>Ahli Madya</option><option <?= $career['jenjang_jabatan'] === 'Ahli Muda' ? 'selected' : '' ?>>Ahli Muda</option><option <?= $career['jenjang_jabatan'] === 'Ahli Pertama' ? 'selected' : '' ?>>Ahli Pertama</option>
                </optgroup>
                <optgroup label="Jenjang Ketrampilan">
                    <option>Penyelia</option><option>Mahir</option><option>Terampil</option>
                </optgroup>
                <optgroup label="Umum">
                    <option>Penelaah Teknis Kebijakan</option><option>Penata Layanan Operasional</option>
                    <option>Pengolah Data dan Informasi</option><option>Pengadministrasi Perkantoran</option>
                    <option>Pengelola Layanan Operasional</option><option>Operator Layanan Operasional</option>
                    <option>Pengelola Umum Operasional</option>
                </optgroup>
            </select>
        </div>
        
        <!-- 20. Pangkat / Golongan -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <label class="form-label" style="margin-bottom: 0;">Pangkat / Golongan</label>
                <span style="background: #fef3c7; color: #b45309; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="fa-solid fa-lock"></i> Terkunci
                </span>
            </div>
            <div style="position: relative;">
                <input type="text" class="form-control" value="<?= esc($career['pangkat_nama']) ?>" disabled readonly style="background-color: var(--bg-body); cursor: not-allowed; font-weight: 500; color: var(--text-main); border-color: var(--border);" />
                <i class="fa-solid fa-lock" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px;"></i>
            </div>
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 10px 12px; margin-top: 8px; font-size: 12px; line-height: 1.4; color: #1e40af;">
                <div style="display: flex; align-items: flex-start; gap: 8px;">
                    <i class="fa-solid fa-circle-info" style="margin-top: 2px; color: #3b82f6;"></i>
                    <div>
                        Pangkat/golongan tidak dapat diubah langsung dari form ini. Perubahan pangkat wajib melalui proses pengajuan dengan melampirkan berkas SK dan persetujuan Admin Kepegawaian.
                        <div style="margin-top: 6px;">
                            <a href="<?= base_url('/riwayat-kepangkatan?role=' . (isset($role) ? $role : 'pegawai')) ?>&action=ajukan" class="btn btn-primary" style="padding: 4px 10px; font-size: 11px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; border-radius: 6px;">
                                <i class="fa-solid fa-file-circle-plus"></i> Ajukan Kenaikan Pangkat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="section-title"><i class="fa-solid fa-graduation-cap"></i> E. Pendidikan</h4>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <!-- 21 & 22 -->
        <div>
            <label class="form-label">Strata Pendidikan</label>
            <select class="form-control">
            <option <?= $education['tingkat'] === 'SD/SMP Sederajat' ? 'selected' : '' ?>>SD/SMP Sederajat</option><option <?= $education['tingkat'] === 'SMA Sederajat' ? 'selected' : '' ?>>SMA Sederajat</option><option <?= $education['tingkat'] === 'D3' ? 'selected' : '' ?>>D3</option><option <?= $education['tingkat'] === 'S1' ? 'selected' : '' ?>>S1</option><option <?= $education['tingkat'] === 'S2' ? 'selected' : '' ?>>S2</option>
            </select>
        </div>
        <div><label class="form-label">Nama Sekolah / Perguruan Tinggi</label><input type="text" class="form-control" value="<?= esc($education['instansi']) ?>" /></div>
        <div><label class="form-label">Nama Pendidikan</label><input type="text" class="form-control" value="<?= esc($education['nama']) ?>" /></div>
        <div><label class="form-label">Fakultas</label><input type="text" class="form-control" value="<?= esc($education['fakultas']) ?>" /></div>
        <div><label class="form-label">Tempat Pendidikan</label><input type="text" class="form-control" value="<?= esc($education['tempat']) ?>" /></div>
        <div><label class="form-label">Status Pendidikan</label><input type="text" class="form-control" value="<?= esc($education['status']) ?>" /></div>
        <div><label class="form-label">Kasek / Rektor / Direktur</label><input type="text" class="form-control" value="<?= esc($education['pejabat']) ?>" /></div>
        <div><label class="form-label">No. Ijazah</label><input type="text" class="form-control" value="<?= esc($education['nomor_ijazah']) ?>" /></div>
        <div><label class="form-label">Tanggal Ijazah</label><input type="date" class="form-control" value="<?= esc($education['tanggal_ijazah']) ?>" /></div>
        <div><label class="form-label">Nilai</label><input type="text" class="form-control" value="<?= esc($education['nilai']) ?>" /></div>
    </div>
    <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'pendidikan', 'document_mode' => 'edit']) ?>
</div>
<?= $this->endSection() ?>
