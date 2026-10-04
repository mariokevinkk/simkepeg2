<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 class="page-title" style="margin: 0;">Data Pensiun</h1>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-outline" style="color: #10b981; border-color: #10b981;"><i class="fa-solid fa-file-excel"></i> Download Rekap Pensiun</button>
    </div>
</div>

<!-- Countdown Pensiun Section -->
<div class="main-card" style="margin-bottom: 24px;">
    <div class="card-heading" style="margin-bottom: 20px; color: var(--text-main); font-size: 16px; padding: 20px 20px 0 20px;">
        <i class="fa-solid fa-bell" style="color: var(--warning); margin-right: 8px;"></i> Pegawai Mendekati Pensiun
    </div>
    
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NAMA & NIP</th>
                    <th>JABATAN</th>
                    <th>BATAS USIA PENSIUN</th>
                    <th>USIA SAAT INI</th>
                    <th>SISA WAKTU</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Dr. dr. Budi Setiawan, Sp.PD</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">196105201988031002</div>
                    </td>
                    <td>Dokter (Keahlian Utama)</td>
                    <td><span style="background: var(--warning); color: white; font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 20px;">65 Tahun</span></td>
                    <td><span style="font-weight: 600; color: var(--text-main); font-size: 13px;">65 Tahun 0 Bulan</span></td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <div style="background: var(--bg-surface); border: 1px solid var(--danger); color: var(--danger); padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 13px;">00<span style="font-size: 10px; font-weight: 500; margin-left: 2px;">Bln</span></div>
                            <div style="background: var(--bg-surface); border: 1px solid var(--danger); color: var(--danger); padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 13px;">15<span style="font-size: 10px; font-weight: 500; margin-left: 2px;">Hr</span></div>
                        </div>
                    </td>
                    <td>
                        <a href="<?= base_url('/view-pegawai?role=' . (isset($_GET['role']) ? $_GET['role'] : '')) ?>" style="text-decoration: none; margin-right: 12px;"><i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer;" title="Lihat Profil"></i></a>
                        <?php if(isset($_GET['role']) && $_GET['role'] === 'superadmin'): ?>
                        <button class="btn btn-primary" onclick="openPensiunModal('Dr. dr. Budi Setiawan, Sp.PD', '196105201988031002')" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-user-clock"></i> Proses Pensiun</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">dr. Sri Rahayu, M.Kes</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">196608151992032004</div>
                    </td>
                    <td>Perawat (Keahlian Madya)</td>
                    <td><span style="background: var(--warning); color: white; font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 20px;">60 Tahun</span></td>
                    <td><span style="font-weight: 600; color: var(--text-main); font-size: 13px;">60 Tahun 0 Bulan</span></td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <div style="background: var(--bg-surface); border: 1px solid var(--danger); color: var(--danger); padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 13px;">00<span style="font-size: 10px; font-weight: 500; margin-left: 2px;">Bln</span></div>
                            <div style="background: var(--bg-surface); border: 1px solid var(--danger); color: var(--danger); padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 13px;">26<span style="font-size: 10px; font-weight: 500; margin-left: 2px;">Hr</span></div>
                        </div>
                    </td>
                    <td>
                        <a href="<?= base_url('/view-pegawai?role=' . (isset($_GET['role']) ? $_GET['role'] : '')) ?>" style="text-decoration: none; margin-right: 12px;"><i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer;" title="Lihat Profil"></i></a>
                        <?php if(isset($_GET['role']) && $_GET['role'] === 'superadmin'): ?>
                        <button class="btn btn-primary" onclick="openPensiunModal('dr. Sri Rahayu, M.Kes', '196608151992032004')" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-user-clock"></i> Proses Pensiun</button>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Bambang Supriyanto</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">196811221995031001</div>
                    </td>
                    <td>Bidan (Ketrampilan Penyelia)</td>
                    <td><span style="background: var(--warning); color: white; font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 20px;">58 Tahun</span></td>
                    <td><span style="font-weight: 600; color: var(--text-main); font-size: 13px;">58 Tahun 0 Bulan</span></td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <div style="background: var(--bg-surface); border: 1px solid var(--danger); color: var(--danger); padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 13px;">00<span style="font-size: 10px; font-weight: 500; margin-left: 2px;">Bln</span></div>
                            <div style="background: var(--bg-surface); border: 1px solid var(--danger); color: var(--danger); padding: 4px 8px; border-radius: 6px; font-weight: 700; font-size: 13px;">12<span style="font-size: 10px; font-weight: 500; margin-left: 2px;">Hr</span></div>
                        </div>
                    </td>
                    <td>
                        <a href="<?= base_url('/view-pegawai?role=' . (isset($_GET['role']) ? $_GET['role'] : '')) ?>" style="text-decoration: none; margin-right: 12px;"><i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer;" title="Lihat Profil"></i></a>
                        <?php if(isset($_GET['role']) && $_GET['role'] === 'superadmin'): ?>
                        <button class="btn btn-primary" onclick="openPensiunModal('Bambang Supriyanto', '196811221995031001')" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-user-clock"></i> Proses Pensiun</button>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="main-card" style="padding: 0; overflow: hidden;">
    <div class="filter-row" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
        <div class="search-bar" style="width: 100%; max-width: 300px; background: var(--bg-surface); border: 1px solid var(--border);">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
            <input type="text" placeholder="Cari nama atau NIP pensiunan...">
        </div>
        <button class="btn btn-outline"><i class="fa-solid fa-filter"></i> Filter Data</button>
    </div>
    
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NAMA & NIP</th>
                    <th>JABATAN TERAKHIR</th>
                    <th>TANGGAL PENSIUN</th>
                    <th>JENIS PENSIUN</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Drs. Haryanto</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">196001011985031001</div>
                    </td>
                    <td>Kepala Bagian Umum</td>
                    <td>01 Januari 2024</td>
                    <td>Batas Usia Pensiun (BUP)</td>
                    <td><span class="status-badge" style="background:#e0e8ff; color:#4361ee;">Pensiun</span></td>
                    <td>
                        <a href="<?= base_url('/view-pegawai?role=' . (isset($_GET['role']) ? $_GET['role'] : '')) ?>" style="text-decoration: none; margin-right: 12px;"><i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer;" title="Lihat Profil"></i></a>
                        <button class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-file-pdf"></i> Surat Pensiun</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Sutiyem, S.Kep</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">196305101988022003</div>
                    </td>
                    <td>Perawat Madya</td>
                    <td>01 Juni 2023</td>
                    <td>Atas Permintaan Sendiri (APS)</td>
                    <td><span class="status-badge" style="background:#e0e8ff; color:#4361ee;">Pensiun</span></td>
                    <td>
                        <a href="<?= base_url('/view-pegawai?role=' . (isset($_GET['role']) ? $_GET['role'] : '')) ?>" style="text-decoration: none; margin-right: 12px;"><i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer;" title="Lihat Profil"></i></a>
                        <button class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-file-pdf"></i> Surat Pensiun</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Bambang Sugeng</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">196412121990011005</div>
                    </td>
                    <td>Staf Keuangan</td>
                    <td>01 Januari 2023</td>
                    <td>Batas Usia Pensiun (BUP)</td>
                    <td><span class="status-badge" style="background:#e0e8ff; color:#4361ee;">Pensiun</span></td>
                    <td>
                        <a href="<?= base_url('/view-pegawai?role=' . (isset($_GET['role']) ? $_GET['role'] : '')) ?>" style="text-decoration: none; margin-right: 12px;"><i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer;" title="Lihat Profil"></i></a>
                        <button class="btn btn-primary" style="padding: 4px 10px; font-size: 12px;"><i class="fa-solid fa-file-pdf"></i> Surat Pensiun</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex-mobile-stack" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); color: var(--text-muted); font-size: 13px;">
        <span>Menampilkan 1-3 dari 45 data pensiun</span>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-outline" style="padding: 6px 12px;">Sebelummya</button>
            <button class="btn btn-primary" style="padding: 6px 12px;">1</button>
            <button class="btn btn-outline" style="padding: 6px 12px;">Selanjutnya</button>
        </div>
    </div>
</div>

<!-- Modal Proses Pensiun -->
<div id="pensiunModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: var(--bg-surface); width: 100%; max-width: 500px; border-radius: 12px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: var(--text-main); font-size: 18px;">Proses Pensiun Pegawai</h3>
            <i class="fa-solid fa-xmark" onclick="closePensiunModal()" style="cursor: pointer; color: var(--text-muted); font-size: 18px;"></i>
        </div>
        
        <form id="formProsesPensiun" onsubmit="event.preventDefault(); submitPensiun();">
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">Nama Pegawai</label>
                <input type="text" id="modalNamaPegawai" readonly style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
            </div>
            
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">NIP</label>
                <input type="text" id="modalNipPegawai" readonly style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">Jenis Pensiun</label>
                <select required style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box; cursor: pointer;">
                    <option value="">Pilih Jenis Pensiun</option>
                    <option value="BUP">Batas Usia Pensiun (BUP)</option>
                    <option value="APS">Atas Permintaan Sendiri (APS)</option>
                    <option value="Janda/Duda">Pensiun Janda/Duda</option>
                    <option value="Uzur">Pensiun Uzur / Sakit</option>
                </select>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">Upload Surat Pensiun (SK) *</label>
                <div style="border: 1px dashed var(--border); border-radius: 8px; padding: 20px; text-align: center; background: var(--bg-surface);">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 24px; color: var(--primary); margin-bottom: 10px;"></i>
                    <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 10px;">Upload file PDF Surat Keputusan Pensiun (Max 2MB)</div>
                    <input type="file" required accept=".pdf" style="font-size: 13px; width: 100%;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-outline" onclick="closePensiunModal()">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Simpan & Proses</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPensiunModal(nama, nip) {
    document.getElementById('modalNamaPegawai').value = nama;
    document.getElementById('modalNipPegawai').value = nip;
    document.getElementById('pensiunModal').style.display = 'flex';
}

function closePensiunModal() {
    document.getElementById('pensiunModal').style.display = 'none';
}

function submitPensiun() {
    alert('Data pensiun dan Surat Pensiun berhasil diunggah! Status pegawai akan segera diubah menjadi Pensiun.');
    closePensiunModal();
}
</script>

<?= $this->endSection() ?>
