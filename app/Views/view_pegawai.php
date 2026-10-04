<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
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
                <img src="https://ui-avatars.com/api/?name=Ahmad+Hidayat&background=4361ee&color=fff&size=120" alt="Pegawai" style="border-radius: 50%; width: 100px; height: 100px; object-fit: cover; border: 4px solid var(--primary-light);">
                <button style="position: absolute; bottom: 0; right: 0; background: var(--primary); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <i class="fa-solid fa-camera"></i>
                </button>
            </div>
            <h3 style="margin-bottom: 4px; color: var(--text-main); font-size: 16px;">Ahmad Hidayat</h3>
            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">NIP. 198501012010011001</p>
            <span class="status-badge status-active">Aktif</span>
        </div>

        <div class="main-card" style="padding: 16px;">
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
    </div>

    <!-- Form Kanan -->
    <div class="main-card" style="padding: 32px; min-height: 500px;">
        
        <!-- TAB: DATA UTAMA -->
        <div id="tab-utama" class="tab-content active">
            <h4 class="section-title"><i class="fa-regular fa-id-card"></i> A. Data Utama</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <label class="form-label">NIP Lama</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="098765432" />
                </div>
                <div>
                    <label class="form-label">NIP Baru</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="198501012010011001" />
                </div>
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Nama Pegawai</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Ahmad Hidayat" />
                </div>
                <div>
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Yogyakarta" />
                </div>
                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="1985-01-01" />
                </div>
                <div>
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-control" disabled style="background: var(--bg-surface);">
                        <option selected>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Agama</label>
                    <select class="form-control" disabled style="background: var(--bg-surface);">
                        <option selected>Islam</option>
                        <option>Kristen</option>
                        <option>Katolik</option>
                        <option>Hindu</option>
                        <option>Buddha</option>
                        <option>Konghucu</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Status Pegawai</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Aktif" />
                </div>
                <div>
                    <label class="form-label">Jenis Kepegawaian</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="PNS" />
                </div>
                <div>
                    <label class="form-label">Status Perkawinan</label>
                    <select class="form-control" disabled style="background: var(--bg-surface);">
                        <option selected>Kawin</option>
                        <option>Belum Kawin</option>
                        <option>Cerai Hidup</option>
                        <option>Cerai Mati</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Kedudukan Pegawai</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Aktif" />
                </div>
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Alamat Rumah</label>
                    <textarea class="form-control" readonly style="background: var(--bg-surface);" rows="2">Jl. Kaliurang Km 5.5, Depok, Sleman, DI Yogyakarta</textarea>
                </div>
                <div>
                    <label class="form-label">No. Telepon / HP</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="081234567890" />
                </div>
                <div>
                    <label class="form-label">No. Darurat</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="081298765432" />
                </div>
                <div>
                    <label class="form-label">No. Karpeg</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="B 123456" />
                </div>
                <div>
                    <label class="form-label">Kartu ASN Virtual</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="ASN-123-456" />
                </div>
                <div>
                    <label class="form-label">No. ASKES / BPJS</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="000123456789" />
                </div>
                <div>
                    <label class="form-label">No. Kartu Taspen</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="123456789" />
                </div>
                <div>
                    <label class="form-label">No. Karis / Karsu</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="12345" />
                </div>
                <div>
                    <label class="form-label">NPWP</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="12.345.678.9-123.000" />
                </div>
                <div>
                    <label class="form-label">NIK</label>
                    <input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="3471101010850001" />
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" readonly style="background: var(--bg-surface);" value="ahmad.hidayat@example.com" />
                </div>
            </div>

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
                    <div><label class="form-label">Nomor SK</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="550/Pem.D/BP/D.2" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Tanggal SK</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2020-12-28" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Walikota Yogyakarta" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">TMT Pangkat, Golongan/Ruang</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2020-12-01" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Penata Muda, III/a" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Masa Kerja</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="0 tahun 0 bulan" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                </div>

                <h4 class="section-title" style="border: none; padding: 0;"><i class="fa-solid fa-user-tie"></i> E. Jabatan Terakhir</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 0;">
                    <div><label class="form-label">Nomor SK</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="101/Pem.D/BP/D.4/XII/2023" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Tanggal SK</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2023-12-27" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Walikota Yogyakarta" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">TMT Jabatan</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2023-12-27" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div style="grid-column: 1 / -1;"><label class="form-label">Jabatan Terakhir</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Analis Sumber Daya Manusia Aparatur Ahli Pertama" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Unit Kerja</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="BAGIAN UMUM RSUD KOTA YOGYAKARTA" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Eselon</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="-" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                </div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-school"></i> F. Pendidikan Umum Terakhir</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div><label class="form-label">Tingkat</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="S-1/Sarjana" /></div>
                <div><label class="form-label">Nama Pendidikan</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Ilmu Keperawatan" /></div>
                <div><label class="form-label">Fakultas</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Keperawatan" /></div>
                <div><label class="form-label">Instansi</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Universitas Gadjah Mada" /></div>
                <div><label class="form-label">Status Pendidikan</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Lulus" /></div>
                <div><label class="form-label">Tempat</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Yogyakarta" /></div>
                <div><label class="form-label">Kasek/Rektor/Dir</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="Prof. Dr. dr. X" /></div>
                <div><label class="form-label">No. Ijazah</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="IJZ/2007/001" /></div>
                <div><label class="form-label">Tanggal Ijazah</label><input type="date" class="form-control" readonly style="background: var(--bg-surface);" value="2007-08-15" /></div>
                <div><label class="form-label">Nilai</label><input type="text" class="form-control" readonly style="background: var(--bg-surface);" value="3.75" /></div>
            </div>
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
                            <td>Penata Muda, III/a</td>
                            <td>Gol. dari Pengadaan CPNS/PNS</td>
                            <td>01-12-2020</td>
                            <td>28-12-2020</td>
                            <td>550/Pem.D/BP/D.2</td>
                            <td>0 thn 0 bln</td>
                            <td>0</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
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
                            
                        </tr>
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

<style>
    @media (max-width: 992px) {
        #main-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<?= $this->endSection() ?>
