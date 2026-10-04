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
        <h1 class="page-title" style="margin: 0;">Dokumen Saya (Profil Pegawai)</h1>
    </div>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
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
                    <input type="text" class="form-control" value="098765432" />
                </div>
                <div>
                    <label class="form-label">NIP Baru</label>
                    <input type="text" class="form-control" value="198501012010011001" />
                </div>
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Nama Pegawai</label>
                    <input type="text" class="form-control" value="Ahmad Hidayat" />
                </div>
                <div>
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" value="Yogyakarta" />
                </div>
                <div>
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" value="1985-01-01" />
                </div>
                <div>
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="form-control">
                        <option selected>Laki-laki</option>
                        <option>Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Agama</label>
                    <select class="form-control">
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
                    <input type="text" class="form-control" value="Aktif" />
                </div>
                <div>
                    <label class="form-label">Jenis Kepegawaian</label>
                    <input type="text" class="form-control" value="PNS" />
                </div>
                <div>
                    <label class="form-label">Status Perkawinan</label>
                    <select class="form-control">
                        <option selected>Kawin</option>
                        <option>Belum Kawin</option>
                        <option>Cerai Hidup</option>
                        <option>Cerai Mati</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Kedudukan Pegawai</label>
                    <input type="text" class="form-control" value="Aktif" />
                </div>
                <div style="grid-column: 1 / -1;">
                    <label class="form-label">Alamat Rumah</label>
                    <textarea class="form-control" rows="2">Jl. Kaliurang Km 5.5, Depok, Sleman, DI Yogyakarta</textarea>
                </div>
                <div>
                    <label class="form-label">No. Telepon / HP</label>
                    <input type="text" class="form-control" value="081234567890" />
                </div>
                <div>
                    <label class="form-label">No. Darurat</label>
                    <input type="text" class="form-control" value="081298765432" />
                </div>
                <div>
                    <label class="form-label">No. Karpeg</label>
                    <input type="text" class="form-control" value="B 123456" />
                </div>
                <div>
                    <label class="form-label">Kartu ASN Virtual</label>
                    <input type="text" class="form-control" value="ASN-123-456" />
                </div>
                <div>
                    <label class="form-label">No. ASKES / BPJS</label>
                    <input type="text" class="form-control" value="000123456789" />
                </div>
                <div>
                    <label class="form-label">No. Kartu Taspen</label>
                    <input type="text" class="form-control" value="123456789" />
                </div>
                <div>
                    <label class="form-label">No. Karis / Karsu</label>
                    <input type="text" class="form-control" value="12345" />
                </div>
                <div>
                    <label class="form-label">NPWP</label>
                    <input type="text" class="form-control" value="12.345.678.9-123.000" />
                </div>
                <div>
                    <label class="form-label">NIK</label>
                    <input type="text" class="form-control" value="3471101010850001" />
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="ahmad.hidayat@example.com" />
                </div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-file-contract"></i> B. Pengangkatan Sebagai CPNS</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div><label class="form-label">Nomor Nota</label><input type="text" class="form-control" value="Nota/123/2010" /></div>
                <div><label class="form-label">Tanggal Nota</label><input type="date" class="form-control" value="2010-01-01" /></div>
                <div><label class="form-label">Nomor SK CPNS</label><input type="text" class="form-control" value="SK/CPNS/001/2010" /></div>
                <div><label class="form-label">Tanggal SK CPNS</label><input type="date" class="form-control" value="2010-01-15" /></div>
                <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" value="Walikota Yogyakarta" /></div>
                <div><label class="form-label">TMT CPNS</label><input type="date" class="form-control" value="2010-02-01" /></div>
                <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" value="Pengatur, II/c" /></div>
                <div><label class="form-label">TMT Honorer</label><input type="date" class="form-control" value="" /></div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-file-signature"></i> C. Pengangkatan Sebagai PNS</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div><label class="form-label">Nomor SK PNS</label><input type="text" class="form-control" value="SK/PNS/050/2011" /></div>
                <div><label class="form-label">Tanggal SK PNS</label><input type="date" class="form-control" value="2011-01-15" /></div>
                <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" value="Walikota Yogyakarta" /></div>
                <div><label class="form-label">TMT PNS</label><input type="date" class="form-control" value="2011-02-01" /></div>
                <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" value="Pengatur, II/c" /></div>
                <div><label class="form-label">Sumpah / Janji</label><input type="text" class="form-control" value="Sudah" /></div>
            </div>

            <div style="background: var(--bg-body); border: 1px solid var(--border); border-radius: 8px; padding: 20px; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 8px; color: var(--warning); font-size: 13px; font-weight: 500; margin-bottom: 16px;">
                    <i class="fa-solid fa-circle-info"></i> Data Pangkat dan Jabatan terakhir tidak dapat diubah di sini. Update dilakukan melalui menu Riwayat Kepangkatan dan Riwayat Jabatan.
                </div>
                
                <h4 class="section-title" style="margin-top: 0; border: none; padding: 0;"><i class="fa-solid fa-medal"></i> D. Pangkat, Golongan / Ruang Terakhir</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                    <div><label class="form-label">Nomor SK</label><input type="text" class="form-control" value="550/Pem.D/BP/D.2" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Tanggal SK</label><input type="date" class="form-control" value="2020-12-28" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" value="Walikota Yogyakarta" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">TMT Pangkat, Golongan/Ruang</label><input type="date" class="form-control" value="2020-12-01" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Pangkat, Golongan/Ruang</label><input type="text" class="form-control" value="Penata Muda, III/a" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Masa Kerja</label><input type="text" class="form-control" value="0 tahun 0 bulan" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                </div>

                <h4 class="section-title" style="border: none; padding: 0;"><i class="fa-solid fa-user-tie"></i> E. Jabatan Terakhir</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 0;">
                    <div><label class="form-label">Nomor SK</label><input type="text" class="form-control" value="101/Pem.D/BP/D.4/XII/2023" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Tanggal SK</label><input type="date" class="form-control" value="2023-12-27" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Pejabat Pengesah</label><input type="text" class="form-control" value="Walikota Yogyakarta" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">TMT Jabatan</label><input type="date" class="form-control" value="2023-12-27" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div style="grid-column: 1 / -1;"><label class="form-label">Jabatan Terakhir</label><input type="text" class="form-control" value="Analis Sumber Daya Manusia Aparatur Ahli Pertama" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Unit Kerja</label><input type="text" class="form-control" value="BAGIAN UMUM RSUD KOTA YOGYAKARTA" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                    <div><label class="form-label">Eselon</label><input type="text" class="form-control" value="-" readonly style="background: var(--bg-surface); cursor: not-allowed;" /></div>
                </div>
            </div>

            <h4 class="section-title"><i class="fa-solid fa-school"></i> F. Pendidikan Umum Terakhir</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div><label class="form-label">Tingkat</label><input type="text" class="form-control" value="S-1/Sarjana" /></div>
                <div><label class="form-label">Nama Pendidikan</label><input type="text" class="form-control" value="Ilmu Keperawatan" /></div>
                <div><label class="form-label">Fakultas</label><input type="text" class="form-control" value="Keperawatan" /></div>
                <div><label class="form-label">Instansi</label><input type="text" class="form-control" value="Universitas Gadjah Mada" /></div>
                <div><label class="form-label">Status Pendidikan</label><input type="text" class="form-control" value="Lulus" /></div>
                <div><label class="form-label">Tempat</label><input type="text" class="form-control" value="Yogyakarta" /></div>
                <div><label class="form-label">Kasek/Rektor/Dir</label><input type="text" class="form-control" value="Prof. Dr. dr. X" /></div>
                <div><label class="form-label">No. Ijazah</label><input type="text" class="form-control" value="IJZ/2007/001" /></div>
                <div><label class="form-label">Tanggal Ijazah</label><input type="date" class="form-control" value="2007-08-15" /></div>
                <div><label class="form-label">Nilai</label><input type="text" class="form-control" value="3.75" /></div>
            </div>
        </div>

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
