<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
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
                    <input type="checkbox" id="str_seumur_hidup_pegawai"> <label for="str_seumur_hidup_pegawai">Seumur Hidup</label>
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
                <option>SD/SMP Sederajat</option><option>SMA Sederajat</option><option>D3</option><option selected>S1</option><option>S2</option>
            </select>
        </div>
        <div><label class="form-label">Nama Sekolah / Perguruan Tinggi</label><input type="text" class="form-control" value="Universitas Gadjah Mada" /></div>
    </div>
</div>
<?= $this->endSection() ?>
