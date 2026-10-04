<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
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
            <img src="https://ui-avatars.com/api/?name=Ahmad+Hidayat&background=4361ee&color=fff&size=120" alt="Pegawai" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover; border: 4px solid var(--primary-light);">
            <button style="position: absolute; bottom: 0; right: 0; background: var(--primary); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <i class="fa-solid fa-camera"></i>
            </button>
        </div>
        <div>
            <h3 style="margin-bottom: 4px; color: var(--text-main); font-size: 20px;">Ahmad Hidayat</h3>
            <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 12px;">NIP. 198501012010011001</p>
            <span class="status-badge status-active">Status: Aktif</span>
        </div>
    </div>

    <!-- Form Container -->
    <div class="main-card" style="padding: 0; overflow: hidden; min-height: 500px;">
        
        <!-- Horizontal Tabs -->
        <div style="display: flex; border-bottom: 1px solid var(--border); background: var(--bg-surface); overflow-x: auto; white-space: nowrap;" class="horizontal-tabs">
            <div class="profile-nav-item active" onclick="switchTab('tab-utama', this)">
                <i class="fa-solid fa-user"></i> Data Utama
            </div>
            <div class="profile-nav-item" onclick="switchTab('tab-pangkat', this)">
                <i class="fa-solid fa-chart-line"></i> Riwayat Kepangkatan
            </div>
            <div class="profile-nav-item" onclick="switchTab('tab-jabatan', this)">
                <i class="fa-solid fa-briefcase"></i> Riwayat Jabatan
            </div>
            <div class="profile-nav-item" onclick="switchTab('tab-pendidikan', this)">
                <i class="fa-solid fa-graduation-cap"></i> Pendidikan & Pelatihan
            </div>
            <div class="profile-nav-item" onclick="switchTab('tab-keluarga', this)">
                <i class="fa-solid fa-users"></i> Data Keluarga
            </div>
            <div class="profile-nav-item" onclick="switchTab('tab-lainnya', this)">
                <i class="fa-solid fa-ellipsis"></i> Lainnya
            </div>
        </div>

        <div style="padding: 32px;">
            <!-- TAB: DATA UTAMA -->
        <div id="tab-utama" class="tab-content active">
            <h4 class="section-title"><i class="fa-regular fa-id-card"></i> A. Data Pribadi</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <!-- 1 & 2 -->
                <div><label class="form-label">NIP</label><input type="text" class="form-control" value="198501012010011001" /></div>
                <div><label class="form-label">NIK</label><input type="text" class="form-control" value="3471101010850001" /></div>
                
                <!-- 3, 4, 5 -->
                <div style="grid-column: 1 / -1; display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 24px;">
                    <div><label class="form-label">Gelar Depan</label><input type="text" class="form-control" /></div>
                    <div><label class="form-label">Nama Pegawai</label><input type="text" class="form-control" value="Ahmad Hidayat" /></div>
                    <div><label class="form-label">Gelar Belakang</label><input type="text" class="form-control" value="S.Kep., Ns." /></div>
                </div>

                <!-- 6, 7 -->
                <div><label class="form-label">Tempat Lahir</label><input type="text" class="form-control" value="Yogyakarta" /></div>
                <div><label class="form-label">Tanggal Lahir</label><input type="date" class="form-control" value="1985-01-01" /></div>
                
                <!-- 8, 9, 11 -->
                <div>
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-control">
                        <option selected>Laki-laki</option><option>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Agama</label>
                    <select class="form-control">
                        <option selected>Islam</option><option>Katolik</option><option>Kristen</option>
                        <option>Hindu</option><option>Budha</option><option>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status Perkawinan</label>
                    <select class="form-control">
                        <option selected>Nikah</option><option>Belum Nikah</option><option>Cerai Hidup</option><option>Cerai Mati</option>
                    </select>
                </div>
                
                <!-- 10. Status Kepegawaian -->
                <div>
                    <label class="form-label">Status Kepegawaian</label>
                    <select class="form-control">
                        <optgroup label="ASN">
                            <option selected>PNS</option><option>CPNS</option><option>PPPK</option><option>PPPK Paruh Waktu</option>
                        </optgroup>
                        <optgroup label="BLUD">
                            <option>BLUD Tetap</option><option>BLUD Kontrak</option>
                        </optgroup>
                        <optgroup label="Mitra">
                            <option>Mitra Penuh Waktu</option><option>Mitra Paruh Waktu</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-map-location-dot"></i> B. Kontak & Alamat</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <!-- 12. Alamat -->
                <div style="grid-column: 1 / -1;"><label class="form-label">Jalan / Pedukuhan</label><input type="text" class="form-control" value="Jl. Kaliurang Km 5.5" /></div>
                <div><label class="form-label">RT / RW</label><input type="text" class="form-control" placeholder="001/002" value="005/002" /></div>
                <div><label class="form-label">Desa / Kelurahan</label><input type="text" class="form-control" value="Caturtunggal" /></div>
                <div><label class="form-label">Kecamatan</label><input type="text" class="form-control" value="Depok" /></div>
                <div><label class="form-label">Kabupaten</label><input type="text" class="form-control" value="Sleman" /></div>
                <div><label class="form-label">Provinsi</label><input type="text" class="form-control" value="DI Yogyakarta" /></div>
                
                <!-- 13, 14, 15 -->
                <div><label class="form-label">No. Telepon / HP</label><input type="text" class="form-control" value="081234567890" /></div>
                <div><label class="form-label">Kontak Darurat</label><input type="text" class="form-control" value="081298765432" /></div>
                <div><label class="form-label">E-Mail</label><input type="email" class="form-control" value="ahmad.hidayat@example.com" /></div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-file-medical"></i> C. Legalitas Profesi (SIP & STR)</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <!-- SIP -->
                <div><label class="form-label">No. SIP</label><input type="text" class="form-control" value="SIP/123/2021" /></div>
                <div><label class="form-label">Tanggal Terbit SIP</label><input type="date" class="form-control" value="2021-01-10" /></div>
                <div>
                    <label class="form-label">Tanggal Exp / Berlaku SIP</label>
                    <input type="date" class="form-control" value="2026-01-10" />
                    <small style="color: var(--warning); display: block; margin-top: 4px;"><i class="fa-solid fa-circle-exclamation"></i> Segera perbaharui SIP jika < 6 bulan</small>
                </div>
                
                <!-- STR -->
                <div><label class="form-label">No. STR</label><input type="text" class="form-control" value="STR/987/2021" /></div>
                <div><label class="form-label">Tanggal Terbit STR</label><input type="date" class="form-control" value="2021-01-15" /></div>
                <div>
                    <label class="form-label">Tanggal Exp / Berlaku STR</label>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <input type="date" class="form-control" value="2026-01-15" />
                        <div style="white-space: nowrap;">
                            <input type="checkbox" id="str_seumur_hidup_admin"> <label for="str_seumur_hidup_admin">Seumur Hidup</label>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-user-doctor"></i> D. Ketenagaan & Jabatan</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <!-- 18. Jenis Ketenagaan -->
                <div>
                    <label class="form-label">Jenis Ketenagaan</label>
                    <select class="form-control">
                        <optgroup label="TENAGA MEDIS">
                            <option>DOKTER UMUM</option><option>DOKTER GIGI</option>
                        </optgroup>
                        <optgroup label="TENAGA KESEHATAN">
                            <option selected>PERAWAT</option><option>KPKL</option>
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
                            <option>Ahli Utama</option><option>Ahli Madya</option><option>Ahli Muda</option><option selected>Ahli Pertama</option>
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
                        <input type="text" class="form-control" value="Penata Muda / IIIa" disabled readonly style="background-color: var(--bg-body); cursor: not-allowed; font-weight: 500; color: var(--text-main); border-color: var(--border);" />
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

            <h4 class="section-title"><i class="fa-solid fa-graduation-cap"></i> E. Pendidikan</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <!-- 21 & 22 -->
                <div>
                    <label class="form-label">Strata Pendidikan</label>
                    <select class="form-control">
                        <option>SD/SMP Sederajat</option><option>SMA Sederajat</option><option>D3</option><option selected>S1</option><option>S2</option>
                    </select>
                </div>
                <div><label class="form-label">Nama Sekolah / Perguruan Tinggi</label><input type="text" class="form-control" value="Universitas Gadjah Mada" /></div>
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
                            <td>Penata Muda, III/a</td>
                            <td>Gol. dari Pengadaan CPNS/PNS</td>
                            <td>01-12-2020</td>
                            <td>28-12-2020</td>
                            <td>550/Pem.D/BP/D.2</td>
                            <td>0 thn 0 bln</td>
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
                                <b>Analis Sumber Daya Manusia Aparatur Ahli Pertama</b><br>
                                <span style="font-size: 12px; color: var(--text-muted);">BAGIAN UMUM, RSUD KOTA YOGYAKARTA</span>
                            </td>
                            <td>PENGANGKATAN JABATAN FUNGSIONAL</td>
                            <td>-</td>
                            <td>Penata Muda, III/a</td>
                            <td>4.498</td>
                            <td>
                                101/Pem.D/BP/D.4/XII/2023<br>
                                <span style="font-size: 12px; color: var(--text-muted);">27-12-2023, WALI KOTA YOGYAKARTA</span>
                            </td>
                            <td>27-12-2023</td>
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
                        <tr>
                            <td>1</td>
                            <td>S-1/Sarjana</td>
                            <td>Ilmu Keperawatan</td>
                            <td>Univ Gadjah Mada</td>
                            <td>Keperawatan</td>
                            <td>Yogyakarta</td>
                            <td>IJZ/2007/001</td>
                            <td>15-08-2007</td>
                            <td>3.75</td>
                            <td>Lulus</td>
                            <td style="white-space: nowrap;">
                                <a href="#" style="color: var(--primary); margin-right: 8px;" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <a href="#" style="color: var(--danger);" title="Hapus"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
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
        // Remove active class from all tabs
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.remove('active');
        });
        document.querySelectorAll('.profile-nav-item').forEach(el => {
            el.classList.remove('active');
        });

        // Add active class to clicked tab
        document.getElementById(tabId).classList.add('active');
        element.classList.add('active');
    }
</script>

<?= $this->endSection() ?>
