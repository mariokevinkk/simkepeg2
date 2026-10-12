<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$employeeProfile = $employee_profile ?? \App\Libraries\EmployeeProfileData::get();
$employee = $employeeProfile['pegawai'];
$career = $employeeProfile['karir'];
$profession = $employeeProfile['profesi'];
$educationRecords = $employeeProfile['pendidikan'];
$education = $educationRecords[0] ?? [];
?>
<style>
    .profile-nav-item {
        padding: 16px 24px;
        cursor: pointer;
        color: var(--text-muted);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
    }
    .profile-nav-item:hover {
        color: var(--primary);
        background: rgba(67, 97, 238, 0.04);
    }
    .profile-nav-item.active {
        color: var(--primary);
        font-weight: 600;
        border-bottom: 2px solid var(--primary);
        background: transparent;
    }
    .horizontal-tabs::-webkit-scrollbar {
        height: 6px;
    }
    .horizontal-tabs::-webkit-scrollbar-thumb {
        background-color: var(--border);
        border-radius: 10px;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
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
    .data-table th {
        background: var(--bg-body);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        color: var(--text-muted);
    }
    .employee-data-group {
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--bg-surface);
        margin: 20px 0;
        padding: 0 20px;
    }
    .employee-data-group summary {
        color: var(--text-main);
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        list-style: none;
        padding: 16px 0;
    }
    .employee-data-group summary::-webkit-details-marker {
        display: none;
    }
    .employee-data-group summary::before {
        color: var(--primary);
        content: '\\f0da';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 9px;
    }
    .employee-data-group[open] summary::before {
        content: '\\f0d7';
    }
</style>

<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <a href="<?= base_url('/data-pegawai?role=' . (isset($role) ? $role : '')) ?>" class="btn btn-outline" style="padding: 8px 12px; border-radius: 50%; width: 40px; height: 40px; display: flex; justify-content: center; align-items: center;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="page-title" style="margin: 0;">Detail & Edit Pegawai</h1>
    </div>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-outline" style="color: var(--danger); border-color: var(--danger);"><i class="fa-solid fa-trash"></i> Hapus Data</button>
        <button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
    </div>
</div>

<div style="display: flex; flex-direction: column; gap: 24px;">
    
    <!-- Header Profil -->
    <div class="main-card" style="padding: 24px; display: flex; align-items: center; gap: 24px;">
        <div style="position: relative;">
            <img src="https://ui-avatars.com/api/?name=<?= rawurlencode($employee['nama']) ?>&background=4361ee&color=fff&size=120" alt="Pegawai" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover; border: 4px solid var(--primary-light);">
            <button style="position: absolute; bottom: 0; right: 0; background: var(--primary); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <i class="fa-solid fa-camera"></i>
            </button>
        </div>
        <div>
            <h3 style="margin-bottom: 4px; color: var(--text-main); font-size: 20px;"><?= esc($employee['nama']) ?></h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 12px;">NIP. <?= esc($employee['nip']) ?></p>
            <span class="status-badge status-active">Status: <?= esc($employee['status']) ?></span>
        </div>
    </div>

    <!-- Form Container -->
    <div class="main-card" style="padding: 0; overflow: hidden; min-height: 500px;">
        
        <!-- Horizontal Tabs -->
        <div style="display: flex; border-bottom: 1px solid var(--border); background: var(--bg-surface); overflow-x: auto; white-space: nowrap;" class="horizontal-tabs">
            <div class="profile-nav-item active" data-employee-tab="tab-utama" onclick="switchTab('tab-utama', this)">
                <i class="fa-solid fa-user"></i> Data Utama
            </div>
            <div class="profile-nav-item" data-employee-tab="tab-pangkat" onclick="switchTab('tab-pangkat', this)">
                <i class="fa-solid fa-chart-line"></i> Riwayat Kepangkatan
            </div>
            <div class="profile-nav-item" data-employee-tab="tab-jabatan" onclick="switchTab('tab-jabatan', this)">
                <i class="fa-solid fa-briefcase"></i> Riwayat Jabatan
            </div>
            <div class="profile-nav-item" data-employee-tab="tab-pendidikan" onclick="switchTab('tab-pendidikan', this)">
                <i class="fa-solid fa-graduation-cap"></i> Pendidikan & Pelatihan
            </div>
            <div class="profile-nav-item" data-employee-tab="tab-keluarga" onclick="switchTab('tab-keluarga', this)">
                <i class="fa-solid fa-users"></i> Data Keluarga
            </div>
            <div class="profile-nav-item" data-employee-tab="tab-lainnya" onclick="switchTab('tab-lainnya', this)">
                <i class="fa-solid fa-ellipsis"></i> Lainnya
            </div>
        </div>

        <div style="padding: 32px;">
            <!-- TAB: DATA UTAMA -->
        <div id="tab-utama" class="tab-content active">
            <h4 class="section-title"><i class="fa-regular fa-id-card"></i> A. Identitas Pegawai</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div><label class="form-label">NIP Lama</label><input type="text" class="form-control" value="<?= esc($employee['nip_lama']) ?>" /></div>
                <div><label class="form-label">NIP Baru</label><input type="text" class="form-control" value="<?= esc($employee['nip']) ?>" /></div>
                
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
                
                <div>
                    <label class="form-label">Jenis Kepegawaian</label>
                    <select class="form-control">
                        <optgroup label="ASN">
                            <option <?= $employee['jenis_kepegawaian'] === 'PNS' ? 'selected' : '' ?>>PNS</option><option>CPNS</option><option>PPPK</option><option>PPPK Paruh Waktu</option>
                        </optgroup>
                        <optgroup label="BLUD">
                            <option>BLUD Tetap</option><option>BLUD Kontrak</option>
                        </optgroup>
                        <optgroup label="Mitra">
                            <option>Mitra Penuh Waktu</option><option>Mitra Paruh Waktu</option>
                        </optgroup>
                    </select>
                </div>
                <div><label class="form-label">Status Pegawai</label><input type="text" class="form-control" value="<?= esc($employee['status']) ?>" /></div>
                <div><label class="form-label">Kedudukan Pegawai</label><input type="text" class="form-control" value="<?= esc($employee['kedudukan']) ?>" /></div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-map-location-dot"></i> A. Alamat, Kontak, dan Nomor Identitas</h4>
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
                <div><label class="form-label">NIK</label><input type="text" class="form-control" value="<?= esc($employee['nik']) ?>" /></div>
                <div><label class="form-label">No. KARPEG</label><input type="text" class="form-control" value="<?= esc($employee['karpeg']) ?>" /></div>
                <div><label class="form-label">Kartu ASN Virtual</label><input type="text" class="form-control" value="<?= esc($employee['kartu_asn']) ?>" /></div>
                <div><label class="form-label">No. ASKES / BPJS</label><input type="text" class="form-control" value="<?= esc($employee['bpjs']) ?>" /></div>
                <div><label class="form-label">No. Kartu Taspen</label><input type="text" class="form-control" value="<?= esc($employee['taspen']) ?>" /></div>
                <div><label class="form-label">No. KARIS / KARSU</label><input type="text" class="form-control" value="<?= esc($employee['karis_karsu']) ?>" /></div>
                <div><label class="form-label">NPWP</label><input type="text" class="form-control" value="<?= esc($employee['npwp']) ?>" /></div>
            </div>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'identitas', 'document_mode' => 'edit']) ?>

            <details class="employee-data-group">
                <summary>B. Pengangkatan Sebagai CPNS</summary>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding-bottom: 20px;">
                    <div><label class="form-label">Nomor Nota</label><input type="text" class="form-control" value="Nota/123/2010" /></div>
                    <div><label class="form-label">Tanggal Nota</label><input type="date" class="form-control" value="2010-01-01" /></div>
                    <div><label class="form-label">Nomor SK CPNS</label><input type="text" class="form-control" value="SK/CPNS/001/2010" /></div>
                    <div><label class="form-label">Tanggal SK CPNS</label><input type="date" class="form-control" value="2010-01-15" /></div>
                    <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" value="Walikota Yogyakarta" /></div>
                    <div><label class="form-label">TMT CPNS</label><input type="date" class="form-control" value="2010-02-01" /></div>
                    <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" value="Pengatur, II/c" /></div>
                    <div><label class="form-label">TMT Honorer</label><input type="date" class="form-control" /></div>
                </div>
            </details>

            <details class="employee-data-group">
                <summary>C. Pengangkatan Sebagai PNS</summary>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding-bottom: 20px;">
                    <div><label class="form-label">Nomor SK PNS</label><input type="text" class="form-control" value="SK/PNS/050/2011" /></div>
                    <div><label class="form-label">Tanggal SK PNS</label><input type="date" class="form-control" value="2011-01-15" /></div>
                    <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" value="Walikota Yogyakarta" /></div>
                    <div><label class="form-label">TMT PNS</label><input type="date" class="form-control" value="2011-02-01" /></div>
                    <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" value="Pengatur, II/c" /></div>
                    <div><label class="form-label">Sumpah / Janji</label><select class="form-control"><option selected>Sudah</option><option>Belum</option></select></div>
                </div>
            </details>

            <details class="employee-data-group">
                <summary>D-F. Pangkat, Jabatan, dan Pendidikan Umum Terakhir</summary>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding-bottom: 20px;">
                    <div><label class="form-label">No. SK Pangkat Terakhir</label><input type="text" class="form-control" value="<?= esc($career['pangkat_nomor']) ?>" /></div>
                    <div><label class="form-label">Tanggal SK Pangkat</label><input type="date" class="form-control" value="<?= esc($career['pangkat_tanggal']) ?>" /></div>
                    <div><label class="form-label">TMT Pangkat, Golongan/Ruang</label><input type="date" class="form-control" value="<?= esc($career['pangkat_tmt']) ?>" /></div>
                    <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" value="<?= esc($career['pangkat_nama']) ?>" /></div>
                    <div><label class="form-label">Masa Kerja</label><input type="text" class="form-control" value="<?= esc($career['masa_kerja']) ?>" /></div>
                    <div><label class="form-label">Pejabat Pengesah Pangkat</label><input type="text" class="form-control" value="<?= esc($career['pangkat_pejabat']) ?>" /></div>
                    <div><label class="form-label">No. SK Jabatan Terakhir</label><input type="text" class="form-control" value="<?= esc($career['jabatan_nomor']) ?>" /></div>
                    <div><label class="form-label">Tanggal SK Jabatan</label><input type="date" class="form-control" value="<?= esc($career['jabatan_tanggal']) ?>" /></div>
                    <div><label class="form-label">TMT Jabatan</label><input type="date" class="form-control" value="<?= esc($career['jabatan_tmt']) ?>" /></div>
                    <div><label class="form-label">Jabatan Terakhir</label><input type="text" class="form-control" value="<?= esc($career['jabatan_nama']) ?>" /></div>
                    <div><label class="form-label">Unit Kerja</label><input type="text" class="form-control" value="<?= esc($career['unit_kerja']) ?>" /></div>
                    <div><label class="form-label">Eselon</label><input type="text" class="form-control" value="<?= esc($career['eselon']) ?>" /></div>
                    <div><label class="form-label">Tingkat Pendidikan</label><input type="text" class="form-control" value="<?= esc($education['tingkat']) ?>" /></div>
                    <div><label class="form-label">Nama Pendidikan</label><input type="text" class="form-control" value="<?= esc($education['nama']) ?>" /></div>
                    <div><label class="form-label">Fakultas dan Instansi</label><input type="text" class="form-control" value="<?= esc($education['fakultas'] . ' - ' . $education['instansi']) ?>" /></div>
                    <div><label class="form-label">Status dan Tempat Pendidikan</label><input type="text" class="form-control" value="<?= esc($education['status'] . ' - ' . $education['tempat']) ?>" /></div>
                    <div><label class="form-label">Kasek / Rektor / Direktur</label><input type="text" class="form-control" value="<?= esc($education['pejabat']) ?>" /></div>
                    <div><label class="form-label">No. dan Tanggal Ijazah</label><input type="text" class="form-control" value="<?= esc($education['nomor_ijazah']) ?>" /></div>
                    <div><label class="form-label">Nilai</label><input type="text" class="form-control" value="<?= esc($education['nilai']) ?>" /></div>
                </div>
            </details>

            <h4 class="section-title"><i class="fa-solid fa-file-medical"></i> Legalitas Profesi dan Kewenangan Klinis</h4>
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
                            <input type="checkbox" id="str_seumur_hidup_admin"> <label for="str_seumur_hidup_admin">Seumur Hidup</label>
                        </div>
                    </div>
                </div>
                <div><label class="form-label">Nomor Berita Acara Kredensial</label><input type="text" class="form-control" value="<?= esc($profession['kredensial_nomor']) ?>" /></div>
                <div><label class="form-label">Masa Berlaku Kredensial</label><input type="date" class="form-control" value="<?= esc($profession['kredensial_berlaku']) ?>" /></div>
                <div><label class="form-label">Level Kewenangan Klinis</label><input type="text" class="form-control" value="<?= esc($profession['kewenangan']) ?>" /></div>
                <div><label class="form-label">Penugasan Klinis / Clinical Appointment</label><input type="text" class="form-control" value="<?= esc($profession['penugasan_klinis']) ?>" /></div>
                <div><label class="form-label">Evaluasi Kinerja Klinis Terakhir</label><input type="date" class="form-control" value="<?= esc($profession['evaluasi_terakhir']) ?>" /></div>
                <div><label class="form-label">Sertifikat Kompetensi</label><input type="text" class="form-control" value="<?= esc($profession['kompetensi']) ?>" /></div>
                <div><label class="form-label">Sertifikat Pelatihan dan JPL</label><input type="text" class="form-control" value="<?= esc($profession['pelatihan_jpl']) ?>" /></div>
                <div><label class="form-label">Nilai SKP</label><input type="text" class="form-control" value="<?= esc($profession['nilai_skp']) ?>" /></div>
            </div>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'profesi', 'document_mode' => 'edit']) ?>

            <h4 class="section-title"><i class="fa-solid fa-user-doctor"></i> Ketenagaan dan Jabatan</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <!-- 18. Jenis Ketenagaan -->
                <div>
                    <label class="form-label">Jenis Ketenagaan</label>
                    <select class="form-control">
                        <optgroup label="TENAGA MEDIS">
                            <option>DOKTER UMUM</option><option>DOKTER GIGI</option>
                        </optgroup>
                        <optgroup label="TENAGA KESEHATAN">
                            <option <?= $career['jenis_ketenagaan'] === 'PERAWAT' ? 'selected' : '' ?>>PERAWAT</option><option <?= $career['jenis_ketenagaan'] === 'KPKL' ? 'selected' : '' ?>>KPKL</option>
                        </optgroup>
                        <optgroup label="TENAGA UMUM">
                            <option>Struktural</option>
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
                                Data pangkat pegawai tidak dapat diubah manual di form data utama. Perubahan dilakukan melalui alur pengajuan & verifikasi berkas SK di <a href="javascript:void(0)" onclick="switchTab('tab-pangkat')" style="color: var(--primary); font-weight: 600; text-decoration: underline;">Tab Riwayat Kepangkatan</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-graduation-cap"></i> Ringkasan Pendidikan</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <!-- 21 & 22 -->
                <div>
                    <label class="form-label">Strata Pendidikan</label>
                    <select class="form-control">
                        <option <?= $education['tingkat'] === 'SD/SMP Sederajat' ? 'selected' : '' ?>>SD/SMP Sederajat</option><option <?= $education['tingkat'] === 'SMA Sederajat' ? 'selected' : '' ?>>SMA Sederajat</option><option <?= $education['tingkat'] === 'D3' ? 'selected' : '' ?>>D3</option><option <?= $education['tingkat'] === 'S1' ? 'selected' : '' ?>>S1</option><option <?= $education['tingkat'] === 'S2' ? 'selected' : '' ?>>S2</option>
                    </select>
                </div>
                <div><label class="form-label">Nama Sekolah / Perguruan Tinggi</label><input type="text" class="form-control" value="<?= esc($education['instansi']) ?>" /></div>
            </div>
        </div>

        <!-- TAB: RIWAYAT KEPANGKATAN -->
        <div id="tab-pangkat" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-chart-line"></i> G. Riwayat Kepangkatan</h4>
                <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Riwayat</button>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>PANGKAT, GOL/RUANG</th>
                            <th>JENIS KENAIKAN</th>
                            <th>TMT</th>
                            <th>TGL SK</th>
                            <th>NO SK</th>
                            <th>MASA KERJA</th>
                            <th>KREDIT</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><?= esc($career['pangkat_nama']) ?></td>
                            <td>Gol. dari Pengadaan CPNS/PNS</td>
                            <td><?= date('d-m-Y', strtotime($career['pangkat_tmt'])) ?></td>
                            <td><?= date('d-m-Y', strtotime($career['pangkat_tanggal'])) ?></td>
                            <td><?= esc($career['pangkat_nomor']) ?></td>
                            <td><?= esc($career['masa_kerja']) ?></td>
                            <td>0</td>
                            <td style="white-space: nowrap;">
                                <a href="#" style="color: var(--primary); margin-right: 8px;" title="Lihat PDF"><i class="fa-solid fa-file-pdf"></i></a>
                                <a href="#" style="color: var(--primary); margin-right: 8px;" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <a href="#" style="color: var(--danger);" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'kepangkatan', 'document_mode' => 'edit']) ?>
        </div>

        <!-- TAB: RIWAYAT JABATAN -->
        <div id="tab-jabatan" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-briefcase"></i> H. Riwayat Jabatan</h4>
                <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Riwayat</button>
            </div>
            
            <div style="overflow-x: auto; margin-bottom: 32px;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th style="min-width: 200px;">NAMA JABATAN / UNIT KERJA</th>
                            <th>JENIS PERUBAHAN</th>
                            <th>ESELON</th>
                            <th>GOL/RUANG</th>
                            <th>KREDIT</th>
                            <th>NO SK, TGL SK, PEJABAT</th>
                            <th>TMT</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <b><?= esc($career['jabatan_nama']) ?></b><br>
                                <span style="font-size: 12px; color: var(--text-muted);"><?= esc($career['unit_kerja']) ?></span>
                            </td>
                            <td>PENGANGKATAN JABATAN FUNGSIONAL</td>
                            <td><?= esc($career['eselon']) ?></td>
                            <td><?= esc($career['pangkat_nama']) ?></td>
                            <td>4.498</td>
                            <td>
                                <?= esc($career['jabatan_nomor']) ?><br>
                                <span style="font-size: 12px; color: var(--text-muted);"><?= date('d-m-Y', strtotime($career['jabatan_tanggal'])) ?>, <?= esc(strtoupper($career['jabatan_pejabat'])) ?></span>
                            </td>
                            <td><?= date('d-m-Y', strtotime($career['jabatan_tmt'])) ?></td>
                            <td style="white-space: nowrap;">
                                <a href="#" style="color: var(--primary); margin-right: 8px;" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <a href="#" style="color: var(--danger);" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <b>Analis Kepegawaian Pertama / Ahli Pertama</b><br>
                                <span style="font-size: 12px; color: var(--text-muted);">BAGIAN UMUM, RSUD KOTA YOGYAKARTA</span>
                            </td>
                            <td>PENATAAN PEGAWAI</td>
                            <td>-</td>
                            <td>Penata Muda, III/a</td>
                            <td>-</td>
                            <td>
                                64/Pem.D/BP/D.4/VII/2023<br>
                                <span style="font-size: 12px; color: var(--text-muted);">31-07-2023, Pj. WALI KOTA YOGYAKARTA</span>
                            </td>
                            <td>01-08-2023</td>
                            <td style="white-space: nowrap;">
                                <a href="#" style="color: var(--primary); margin-right: 8px;" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <a href="#" style="color: var(--danger);" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-clipboard-list"></i> I. Riwayat Tugas Tambahan</h4>
                <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Riwayat</button>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th style="min-width: 200px;">NAMA JABATAN / UNIT KERJA</th>
                            <th>JENIS PERUBAHAN</th>
                            <th>ESELON</th>
                            <th>GOL/RUANG</th>
                            <th>KREDIT</th>
                            <th>NO SK, TGL SK, PEJABAT</th>
                            <th>TMT</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada riwayat tugas tambahan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'jabatan', 'document_mode' => 'edit']) ?>
        </div>

        <!-- TAB: PENDIDIKAN & PELATIHAN -->
        <div id="tab-pendidikan" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-graduation-cap"></i> J. Riwayat Pendidikan</h4>
                <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Pendidikan</button>
            </div>
            <div style="overflow-x: auto; margin-bottom: 32px;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TINGKAT</th>
                            <th>NAMA PENDIDIKAN</th>
                            <th>INSTANSI</th>
                            <th>FAKULTAS</th>
                            <th>TEMPAT</th>
                            <th>NOMOR IJAZAH</th>
                            <th>TANGGAL IJAZAH</th>
                            <th>NILAI</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= view('employee_education_rows', ['employee_profile' => $employeeProfile, 'show_actions' => true, 'attachment_mode' => 'edit']) ?>
                    </tbody>
                </table>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-medal"></i> K. Tanda Jasa / Satya Lencana</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NAMA TANDA JASA / SATYA LENCANA</th><th>NOMOR SK, TANGGAL</th><th>TAHUN</th><th>ASAL</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-trophy"></i> L. Prestasi</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>JUDUL PENGHARGAAN</th><th>JENJANG</th><th>KATEGORI</th><th>JUARA</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-book-journal-whills"></i> M. Karya Ilmiah</h4>
            <table class="data-table">
                <thead><tr><th>NO</th><th>JUDUL</th><th>NOMOR ISBN</th><th>TAHUN PUBLIKASI</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
        </div>

        <!-- TAB: DATA KELUARGA -->
        <div id="tab-keluarga" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-ring"></i> T. Data Isteri/Suami</h4>
                <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Data</button>
            </div>
            <div style="overflow-x: auto; margin-bottom: 32px;">
                <table class="data-table">
                    <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>1</td><td>Siti Aminah</td><td>ISTRI</td><td>Yogyakarta, 12-05-1988</td><td>NIKAH</td><td>20-10-2019</td><td>Wiraswasta</td><td>-</td><td>-</td>
                            <td style="white-space: nowrap;"><a href="#" style="color: var(--primary); margin-right: 8px;"><i class="fa-solid fa-pen"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-child"></i> U. Data Anak</h4>
                <button class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Tambah Data</button>
            </div>
            <div style="overflow-x: auto; margin-bottom: 32px;">
                <table class="data-table">
                    <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>FILE</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>1</td><td>Budi Hidayat</td><td>ANAK KANDUNG</td><td>-</td><td>Yogyakarta, 01-01-2020</td><td>BELUM KAWIN</td><td>-</td><td>Belum Bekerja</td><td>-</td><td>-</td>
                            <td style="white-space: nowrap;"><a href="#" style="color: var(--primary); margin-right: 8px;"><i class="fa-solid fa-pen"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-person-cane"></i> V. Data Orang Tua Kandung</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-person-dress"></i> W. Data Mertua</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-people-group"></i> X. Data Saudara</h4>
            <table class="data-table">
                <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'keluarga', 'document_mode' => 'edit']) ?>
        </div>

        <!-- TAB: LAINNYA -->
        <div id="tab-lainnya" class="tab-content">
            <h4 class="section-title" style="margin-top: 0;"><i class="fa-solid fa-plane-departure"></i> N. Pengalaman Kunjungan Ke Luar Negeri</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NEGARA TUJUAN</th><th>JENIS PENUGASAN</th><th>LAMA</th><th>YANG MENETAPKAN</th><th>NO SK</th><th>MULAI</th><th>SELESAI</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="9" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-sitemap"></i> O. Keanggotaan Organisasi</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>JENIS ORGANISASI</th><th>NAMA ORGANISASI</th><th>JABATAN</th><th>MULAI - SELESAI</th><th>TEMPAT</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-laptop-file"></i> P. Pengalaman Kerja</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>INSTANSI</th><th>JABATAN</th><th>TAHUN</th><th>BULAN</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'pengalaman', 'document_mode' => 'edit']) ?>

            <h4 class="section-title"><i class="fa-solid fa-language"></i> Q. Penguasaan Bahasa</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NAMA BAHASA</th><th>KEMAMPUAN</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-gavel"></i> R. Riwayat Sanksi Administratif</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>HUKUMAN</th><th>TINGKAT</th><th>NO & TGL SK</th><th>MULAI - SELESAI</th><th>PEJABAT PENGESAH</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'sanksi', 'document_mode' => 'edit']) ?>
            <h4 class="section-title"><i class="fa-solid fa-ellipsis"></i> S. Lain-lain</h4>
            <table class="data-table">
                <thead><tr><th>NO</th><th>NAMA</th><th>TEMPAT</th><th>NOMOR</th><th>TANGGAL</th><th>MULAI - SELESAI</th><th>KETERANGAN</th><th>AKSI</th></tr></thead>
                <tbody><tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
        </div>

        </div> <!-- End of padding: 32px -->
    </div> <!-- End of main-card -->
</div> <!-- End of display: flex -->

<script>
    function switchTab(tabId, element) {
        const targetTab = document.getElementById(tabId);
        if (!targetTab) {
            return;
        }

        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.remove('active');
        });
        document.querySelectorAll('.profile-nav-item').forEach(el => {
            el.classList.remove('active');
        });

        targetTab.classList.add('active');
        if (element) {
            element.classList.add('active');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const requestedTab = new URLSearchParams(window.location.search).get('tab');
        if (!requestedTab) {
            return;
        }

        const tabId = 'tab-' + requestedTab;
        const tabButton = document.querySelector('[data-employee-tab="' + tabId + '"]');
        if (document.getElementById(tabId)) {
            switchTab(tabId, tabButton);
        }
    });
</script>

<?= $this->endSection() ?>
