<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$employeeProfile = $employee_profile ?? \App\Libraries\EmployeeProfileData::get();
$employee = $employeeProfile['pegawai'];
$career = $employeeProfile['karir'];
$profession = $employeeProfile['profesi'];
$education = $employeeProfile['pendidikan'][0] ?? [];
$selectedOption = static fn (string $current, string $option): string => $current === $option ? 'selected' : '';
?>
<style>
    .profile-nav-item {
        padding: 12px 16px;
        border-radius: 8px;
        cursor: pointer;
        color: var(--text-muted);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
        margin-bottom: 4px;
    }
    .profile-nav-item:hover {
        background: var(--bg-body);
        color: var(--primary);
    }
    .profile-nav-item.active {
        background: var(--primary-light);
        color: var(--primary);
        font-weight: 600;
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
</style>

<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <a href="<?= base_url('/data-pegawai?role=' . (isset($role) ? $role : '')) ?>" class="btn btn-outline" style="padding: 8px 12px; border-radius: 50%; width: 40px; height: 40px; display: flex; justify-content: center; align-items: center;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="page-title" style="margin: 0;">Detail Pegawai</h1>
    </div>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-outline" onclick="window.print()" style="color: var(--primary); border-color: var(--primary);"><i class="fa-solid fa-print"></i> Cetak / Export PDF</button>
        <a href="<?= base_url('/edit-pegawai?role=' . (isset($role) ? $role : '')) ?>" class="btn btn-primary" style="text-decoration: none;"><i class="fa-solid fa-pen-to-square"></i> Edit Data</a>
    </div>
</div>

<div style="display: grid; grid-template-columns: 280px 1fr; gap: 24px;" id="main-grid">
    
    <!-- Sidebar Kiri (Navigasi & Profil) -->
    <div style="display: flex; flex-direction: column; gap: 16px;">
        <div class="main-card" style="padding: 24px; display: flex; flex-direction: column; align-items: center; text-align: center;">
            <div style="position: relative; margin-bottom: 16px;">
                <img src="https://ui-avatars.com/api/?name=<?= rawurlencode($employee['nama']) ?>&background=4361ee&color=fff&size=120" alt="Pegawai" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover; border: 4px solid var(--primary-light);">
                <button style="position: absolute; bottom: 0; right: 0; background: var(--primary); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="fa-solid fa-camera"></i>
                </button>
            </div>
            <h3 style="margin-bottom: 4px; color: var(--text-main); font-size: 16px;"><?= esc($employee['nama']) ?></h3>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">NIP. <?= esc($employee['nip']) ?></p>
            <span class="status-badge status-active"><?= esc($employee['status']) ?></span>
        </div>

        <div class="main-card" style="padding: 16px;">
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
    </div>

    <!-- Form Kanan -->
    <div class="main-card" style="padding: 32px; min-height: 500px;">
        
        <!-- TAB: DATA UTAMA -->
        <div id="tab-utama" class="tab-content active">
            <h4 class="section-title"><i class="fa-regular fa-id-card"></i> A. Data Utama</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label class="form-label">NIP Lama</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['nip_lama']) ?>" />
                </div>
                <div>
                    <label class="form-label">NIP Baru</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['nip']) ?>" />
                </div>
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Nama Pegawai</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['nama']) ?>" />
                </div>
                <div>
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['tempat_lahir']) ?>" />
                </div>
                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['tanggal_lahir']) ?>" />
                </div>
                <div>
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-control" disabled style="background: var(--bg-surface);">
                        <option <?= $selectedOption($employee['jenis_kelamin'], 'Laki-laki') ?>>Laki-laki</option>
                        <option <?= $selectedOption($employee['jenis_kelamin'], 'Perempuan') ?>>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Agama</label>
                    <select class="form-control" disabled style="background: var(--bg-surface);">
                        <option <?= $selectedOption($employee['agama'], 'Islam') ?>>Islam</option>
                        <option <?= $selectedOption($employee['agama'], 'Kristen') ?>>Kristen</option>
                        <option <?= $selectedOption($employee['agama'], 'Katolik') ?>>Katolik</option>
                        <option <?= $selectedOption($employee['agama'], 'Hindu') ?>>Hindu</option>
                        <option <?= $selectedOption($employee['agama'], 'Buddha') ?>>Buddha</option>
                        <option <?= $selectedOption($employee['agama'], 'Konghucu') ?>>Konghucu</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status Pegawai</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['status']) ?>" />
                </div>
                <div>
                    <label class="form-label">Jenis Kepegawaian</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['jenis_kepegawaian']) ?>" />
                </div>
                <div>
                    <label class="form-label">Status Perkawinan</label>
                    <select class="form-control" disabled style="background: var(--bg-surface);">
                        <option <?= $selectedOption($employee['status_perkawinan'], 'Kawin') ?>>Kawin</option>
                        <option <?= $selectedOption($employee['status_perkawinan'], 'Belum Kawin') ?>>Belum Kawin</option>
                        <option <?= $selectedOption($employee['status_perkawinan'], 'Cerai Hidup') ?>>Cerai Hidup</option>
                        <option <?= $selectedOption($employee['status_perkawinan'], 'Cerai Mati') ?>>Cerai Mati</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Kedudukan Pegawai</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['kedudukan']) ?>" />
                </div>
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Alamat Rumah</label>
                    <textarea class="form-control" readonly style="background: var(--bg-surface);" rows="2"><?= esc($employee['alamat']) ?></textarea>
                </div>
                <div>
                    <label class="form-label">No. Telepon / HP</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['telepon']) ?>" />
                </div>
                <div>
                    <label class="form-label">No. Darurat</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['telepon_darurat']) ?>" />
                </div>
                <div>
                    <label class="form-label">No. Karpeg</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['karpeg']) ?>" />
                </div>
                <div>
                    <label class="form-label">Kartu ASN Virtual</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['kartu_asn']) ?>" />
                </div>
                <div>
                    <label class="form-label">No. ASKES / BPJS</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['bpjs']) ?>" />
                </div>
                <div>
                    <label class="form-label">No. Kartu Taspen</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['taspen']) ?>" />
                </div>
                <div>
                    <label class="form-label">No. Karis / Karsu</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['karis_karsu']) ?>" />
                </div>
                <div>
                    <label class="form-label">NPWP</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['npwp']) ?>" />
                </div>
                <div>
                    <label class="form-label">NIK</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['nik']) ?>" />
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($employee['email']) ?>" />
                </div>
            </div>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'identitas']) ?>

            <h4 class="section-title"><i class="fa-solid fa-file-contract"></i> B. Pengangkatan Sebagai CPNS</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div><label class="form-label">Nomor Nota</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Nota/123/2010" /></div>
                <div><label class="form-label">Tanggal Nota</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2010-01-01" /></div>
                <div><label class="form-label">Nomor SK CPNS</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="SK/CPNS/001/2010" /></div>
                <div><label class="form-label">Tanggal SK CPNS</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2010-01-15" /></div>
                <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Walikota Yogyakarta" /></div>
                <div><label class="form-label">TMT CPNS</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2010-02-01" /></div>
                <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Pengatur, II/c" /></div>
                <div><label class="form-label">TMT Honorer</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="" /></div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-file-signature"></i> C. Pengangkatan Sebagai PNS</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div><label class="form-label">Nomor SK PNS</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="SK/PNS/050/2011" /></div>
                <div><label class="form-label">Tanggal SK PNS</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2011-01-15" /></div>
                <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Walikota Yogyakarta" /></div>
                <div><label class="form-label">TMT PNS</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2011-02-01" /></div>
                <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Pengatur, II/c" /></div>
                <div><label class="form-label">Sumpah / Janji</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Sudah" /></div>
            </div>

            <div style="background: var(--bg-body); border: 1px solid var(--border); border-radius: 8px; padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 8px; color: var(--warning); font-size: 13px; font-weight: 500; margin-bottom: 16px;">
                    <i class="fa-solid fa-circle-info"></i> Data Pangkat dan Jabatan terakhir tidak dapat diubah di sini. Update dilakukan melalui menu Riwayat Kepangkatan dan Riwayat Jabatan.
                </div>
                
                <h4 class="section-title" style="margin-top: 0; border: none; padding: 0;"><i class="fa-solid fa-medal"></i> D. Pangkat, Golongan / Ruang Terakhir</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div><label class="form-label">Nomor SK</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['pangkat_nomor']) ?>" /></div>
                    <div><label class="form-label">Tanggal SK</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['pangkat_tanggal']) ?>" /></div>
                    <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['pangkat_pejabat']) ?>" /></div>
                    <div><label class="form-label">TMT Pangkat, Golongan/Ruang</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['pangkat_tmt']) ?>" /></div>
                    <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['pangkat_nama']) ?>" /></div>
                    <div><label class="form-label">Masa Kerja</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['masa_kerja']) ?>" /></div>
                </div>

                <h4 class="section-title" style="border: none; padding: 0;"><i class="fa-solid fa-user-tie"></i> E. Jabatan Terakhir</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 0;">
                    <div><label class="form-label">Nomor SK</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['jabatan_nomor']) ?>" /></div>
                    <div><label class="form-label">Tanggal SK</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['jabatan_tanggal']) ?>" /></div>
                    <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['jabatan_pejabat']) ?>" /></div>
                    <div><label class="form-label">TMT Jabatan</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['jabatan_tmt']) ?>" /></div>
                    <div style="grid-column: 1 / -1;"><label class="form-label">Jabatan Terakhir</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['jabatan_nama']) ?>" /></div>
                    <div><label class="form-label">Unit Kerja</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['unit_kerja']) ?>" /></div>
                    <div><label class="form-label">Eselon</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['eselon']) ?>" /></div>
                </div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-school"></i> F. Pendidikan Umum Terakhir</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div><label class="form-label">Tingkat</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['tingkat']) ?>" /></div>
                <div><label class="form-label">Nama Pendidikan</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['nama']) ?>" /></div>
                <div><label class="form-label">Fakultas</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['fakultas']) ?>" /></div>
                <div><label class="form-label">Instansi</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['instansi']) ?>" /></div>
                <div><label class="form-label">Status Pendidikan</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['status']) ?>" /></div>
                <div><label class="form-label">Tempat</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['tempat']) ?>" /></div>
                <div><label class="form-label">Kasek/Rektor/Dir</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['pejabat']) ?>" /></div>
                <div><label class="form-label">No. Ijazah</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['nomor_ijazah']) ?>" /></div>
                <div><label class="form-label">Tanggal Ijazah</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['tanggal_ijazah']) ?>" /></div>
                <div><label class="form-label">Nilai</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($education['nilai']) ?>" /></div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-stethoscope"></i> Legalitas Profesi dan Kewenangan Klinis</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div><label class="form-label">Jenis Ketenagaan</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($career['jenis_ketenagaan']) ?>" /></div>
                <div><label class="form-label">Level Kewenangan Klinis</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($profession['kewenangan']) ?>" /></div>
                <div><label class="form-label">Nomor STR</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($profession['str_nomor']) ?>" /></div>
                <div><label class="form-label">Masa Berlaku STR</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= date('d M Y', strtotime($profession['str_berlaku'])) ?>" /></div>
                <div><label class="form-label">Nomor SIP / SIK</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($profession['sip_nomor']) ?>" /></div>
                <div><label class="form-label">Masa Berlaku SIP / SIK</label><input type="text" class="form-control" readonly style="background: #fff7ed; border-color: #fed7aa;" value="<?= date('d M Y', strtotime($profession['sip_berlaku'])) ?> - perlu diperbarui" /></div>
                <div><label class="form-label">Berita Acara Kredensial</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($profession['kredensial_nomor']) ?>" /></div>
                <div><label class="form-label">Masa Berlaku Kredensial</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Sampai <?= date('d M Y', strtotime($profession['kredensial_berlaku'])) ?>" /></div>
                <div><label class="form-label">Penugasan Klinis</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($profession['penugasan_klinis']) ?>" /></div>
                <div><label class="form-label">Evaluasi Kinerja Klinis Terakhir</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= date('F Y', strtotime($profession['evaluasi_terakhir'])) ?>" /></div>
                <div><label class="form-label">Sertifikat Kompetensi</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($profession['kompetensi']) ?>" /></div>
                <div><label class="form-label">Pelatihan dan Capaian JPL</label><input type="text" class="form-control" readonly style="background: #fff7ed; border-color: #fed7aa;" value="<?= esc($profession['pelatihan_jpl']) ?>" /></div>
                <div><label class="form-label">Penilaian Kinerja SKP</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="<?= esc($profession['nilai_skp']) ?>" /></div>
            </div>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'profesi']) ?>
        </div>

        <!-- TAB: RIWAYAT KEPANGKATAN -->
        <div id="tab-pangkat" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-chart-line"></i> G. Riwayat Kepangkatan</h4>
                
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
                            
                        </tr>
                    </tbody>
                </table>
            </div>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'kepangkatan']) ?>
        </div>

        <!-- TAB: RIWAYAT JABATAN -->
        <div id="tab-jabatan" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-briefcase"></i> H. Riwayat Jabatan</h4>
                
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
                            
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-clipboard-list"></i> I. Riwayat Tugas Tambahan</h4>
                
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
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 24px;">Belum ada riwayat tugas tambahan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'jabatan']) ?>
        </div>

        <!-- TAB: PENDIDIKAN & PELATIHAN -->
        <div id="tab-pendidikan" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-graduation-cap"></i> J. Riwayat Pendidikan</h4>
                
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
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?= view('employee_education_rows', ['employee_profile' => $employeeProfile, 'attachment_mode' => 'detail']) ?>
                    </tbody>
                </table>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-medal"></i> K. Tanda Jasa / Satya Lencana</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NAMA TANDA JASA / SATYA LENCANA</th><th>NOMOR SK, TANGGAL</th><th>TAHUN</th><th>ASAL</th></tr></thead>
                <tbody><tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-trophy"></i> L. Prestasi</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>JUDUL PENGHARGAAN</th><th>JENJANG</th><th>KATEGORI</th><th>JUARA</th></tr></thead>
                <tbody><tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-book-journal-whills"></i> M. Karya Ilmiah</h4>
            <table class="data-table">
                <thead><tr><th>NO</th><th>JUDUL</th><th>NOMOR ISBN</th><th>TAHUN PUBLIKASI</th></tr></thead>
                <tbody><tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
        </div>

        <!-- TAB: DATA KELUARGA -->
        <div id="tab-keluarga" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-ring"></i> T. Data Isteri/Suami</h4>
                
            </div>
            <div style="overflow-x: auto; margin-bottom: 32px;">
                <table class="data-table">
                    <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>1</td><td>Siti Aminah</td><td>ISTRI</td><td>Yogyakarta, 12-05-1988</td><td>NIKAH</td><td>20-10-2019</td><td>Wiraswasta</td><td>-</td><td>-</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h4 class="section-title" style="margin: 0; border: none; padding: 0;"><i class="fa-solid fa-child"></i> U. Data Anak</h4>
                
            </div>
            <div style="overflow-x: auto; margin-bottom: 32px;">
                <table class="data-table">
                    <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>FILE</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th></tr></thead>
                    <tbody>
                        <tr>
                            <td>1</td><td>Budi Hidayat</td><td>ANAK KANDUNG</td><td>-</td><td>Yogyakarta, 01-01-2020</td><td>BELUM KAWIN</td><td>-</td><td>Belum Bekerja</td><td>-</td><td>-</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-person-cane"></i> V. Data Orang Tua Kandung</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th></tr></thead>
                <tbody><tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-person-dress"></i> W. Data Mertua</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th></tr></thead>
                <tbody><tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-people-group"></i> X. Data Saudara</h4>
            <table class="data-table">
                <thead><tr><th>NO</th><th>NAMA</th><th>STATUS KELUARGA</th><th>TEMPAT, TGL LAHIR</th><th>STATUS PERKAWINAN</th><th>TGL PERNIKAHAN</th><th>PEKERJAAN</th><th>NIP/NRP</th><th>KET</th></tr></thead>
                <tbody><tr><td colspan="10" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'keluarga']) ?>
        </div>

        <!-- TAB: LAINNYA -->
        <div id="tab-lainnya" class="tab-content">
            <h4 class="section-title" style="margin-top: 0;"><i class="fa-solid fa-plane-departure"></i> N. Pengalaman Kunjungan Ke Luar Negeri</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NEGARA TUJUAN</th><th>JENIS PENUGASAN</th><th>LAMA</th><th>YANG MENETAPKAN</th><th>NO SK</th><th>MULAI</th><th>SELESAI</th></tr></thead>
                <tbody><tr><td colspan="9" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-sitemap"></i> O. Keanggotaan Organisasi</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>JENIS ORGANISASI</th><th>NAMA ORGANISASI</th><th>JABATAN</th><th>MULAI - SELESAI</th><th>TEMPAT</th></tr></thead>
                <tbody><tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-laptop-file"></i> P. Pengalaman Kerja</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>INSTANSI</th><th>JABATAN</th><th>TAHUN</th><th>BULAN</th></tr></thead>
                <tbody><tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'pengalaman']) ?>

            <h4 class="section-title"><i class="fa-solid fa-language"></i> Q. Penguasaan Bahasa</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>NAMA BAHASA</th><th>KEMAMPUAN</th></tr></thead>
                <tbody><tr><td colspan="4" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>

            <h4 class="section-title"><i class="fa-solid fa-gavel"></i> R. Riwayat Sanksi Administratif</h4>
            <table class="data-table" style="margin-bottom: 32px;">
                <thead><tr><th>NO</th><th>HUKUMAN</th><th>TINGKAT</th><th>NO & TGL SK</th><th>MULAI - SELESAI</th><th>PEJABAT PENGESAH</th></tr></thead>
                <tbody><tr><td colspan="7" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
            <?= view('employee_attachment_group', ['employee_profile' => $employeeProfile, 'document_group' => 'sanksi']) ?>

            <h4 class="section-title"><i class="fa-solid fa-ellipsis"></i> S. Lain-lain</h4>
            <table class="data-table">
                <thead><tr><th>NO</th><th>NAMA</th><th>TEMPAT</th><th>NOMOR</th><th>TANGGAL</th><th>MULAI - SELESAI</th><th>KETERANGAN</th></tr></thead>
                <tbody><tr><td colspan="8" style="text-align: center; color: var(--text-muted); padding: 16px;">Tidak ada data</td></tr></tbody>
            </table>
        </div>

    </div>
</div>

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

<style>
    @media (max-width: 992px) {
        #main-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<?= $this->endSection() ?>
