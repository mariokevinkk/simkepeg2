<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 class="page-title" style="margin: 0;">
        <?php 
            if(isset($role) && $role === 'admin') echo 'Data Pegawai - Bangsal Anggrek';
            elseif(isset($filter_bangsal) && !empty($filter_bangsal)) echo 'Data Pegawai - ' . esc($filter_bangsal);
            else echo 'Data Pegawai';
        ?>
    </h1>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-outline" style="color: #10b981; border-color: #10b981;"><i class="fa-solid fa-file-excel"></i> Download Excel</button>
        <button class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Pegawai</button>
    </div>
</div>

<div class="main-card" style="padding: 0; overflow: hidden;">
    <form method="GET" class="filter-row" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 10px;">
        <?php if(isset($role)): ?>
        <input type="hidden" name="role" value="<?= esc($role) ?>">
        <?php endif; ?>
        <div class="search-bar" style="width: 100%; max-width: 300px; background: var(--bg-surface); border: 1px solid var(--border);">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
            <input type="text" placeholder="Cari nama atau NIP...">
        </div>
        
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center; flex: 1; justify-content: flex-end;">
            <?php if(!isset($role) || $role !== 'admin'): ?>
            <select name="filter_bangsal" onchange="this.form.submit()" class="form-select" style="padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; cursor: pointer;">
                <option value="">Semua Bangsal/Unit</option>
                <option value="Bangsal Anggrek" <?= (isset($filter_bangsal) && $filter_bangsal === 'Bangsal Anggrek') ? 'selected' : '' ?>>Bangsal Anggrek</option>
                <option value="IGD" <?= (isset($filter_bangsal) && $filter_bangsal === 'IGD') ? 'selected' : '' ?>>IGD</option>
                <option value="ICU" <?= (isset($filter_bangsal) && $filter_bangsal === 'ICU') ? 'selected' : '' ?>>ICU</option>
                <option value="Poli Umum" <?= (isset($filter_bangsal) && $filter_bangsal === 'Poli Umum') ? 'selected' : '' ?>>Poli Umum</option>
            </select>
            <?php endif; ?>
            
            <select name="filter_status" onchange="this.form.submit()" class="form-select" style="padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; cursor: pointer;">
                <option value="">Semua Status Pegawai</option>
                <optgroup label="ASN">
                    <option value="PNS" <?= (isset($filter_status) && $filter_status === 'PNS') ? 'selected' : '' ?>>PNS</option>
                    <option value="CPNS" <?= (isset($filter_status) && $filter_status === 'CPNS') ? 'selected' : '' ?>>CPNS</option>
                    <option value="PPPK" <?= (isset($filter_status) && $filter_status === 'PPPK') ? 'selected' : '' ?>>PPPK</option>
                    <option value="PPPK Paruh Waktu" <?= (isset($filter_status) && $filter_status === 'PPPK Paruh Waktu') ? 'selected' : '' ?>>PPPK Paruh Waktu</option>
                </optgroup>
                <optgroup label="BLUD">
                    <option value="BLUD Tetap" <?= (isset($filter_status) && $filter_status === 'BLUD Tetap') ? 'selected' : '' ?>>BLUD Tetap</option>
                    <option value="BLUD Kontrak" <?= (isset($filter_status) && $filter_status === 'BLUD Kontrak') ? 'selected' : '' ?>>BLUD Kontrak</option>
                </optgroup>
                <optgroup label="MITRA">
                    <option value="Mitra Penuh Waktu" <?= (isset($filter_status) && $filter_status === 'Mitra Penuh Waktu') ? 'selected' : '' ?>>Mitra Penuh Waktu</option>
                    <option value="Mitra Paruh Waktu" <?= (isset($filter_status) && $filter_status === 'Mitra Paruh Waktu') ? 'selected' : '' ?>>Mitra Paruh Waktu</option>
                </optgroup>
            </select>

            <select name="filter_jabatan" onchange="this.form.submit()" class="form-select" style="padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; cursor: pointer;">
                <option value="">Semua Jabatan</option>
                <option value="Perawat" <?= (isset($filter_jabatan) && $filter_jabatan === 'Perawat') ? 'selected' : '' ?>>Perawat</option>
                <option value="Asisten Apoteker" <?= (isset($filter_jabatan) && $filter_jabatan === 'Asisten Apoteker') ? 'selected' : '' ?>>Asisten Apoteker</option>
                <option value="Apoteker" <?= (isset($filter_jabatan) && $filter_jabatan === 'Apoteker') ? 'selected' : '' ?>>Apoteker</option>
                <option value="Petugas Kebersihan" <?= (isset($filter_jabatan) && $filter_jabatan === 'Petugas Kebersihan') ? 'selected' : '' ?>>Petugas Kebersihan</option>
            </select>

            <select name="filter_jenjang" onchange="this.form.submit()" class="form-select" style="padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; cursor: pointer;">
                <option value="">Semua Jenjang</option>
                <option value="Terampil" <?= (isset($filter_jenjang) && $filter_jenjang === 'Terampil') ? 'selected' : '' ?>>Terampil</option>
                <option value="Mahir" <?= (isset($filter_jenjang) && $filter_jenjang === 'Mahir') ? 'selected' : '' ?>>Mahir</option>
                <option value="Penyelia" <?= (isset($filter_jenjang) && $filter_jenjang === 'Penyelia') ? 'selected' : '' ?>>Penyelia</option>
                <option value="Ahli Pertama" <?= (isset($filter_jenjang) && $filter_jenjang === 'Ahli Pertama') ? 'selected' : '' ?>>Ahli Pertama</option>
                <option value="Ahli Muda" <?= (isset($filter_jenjang) && $filter_jenjang === 'Ahli Muda') ? 'selected' : '' ?>>Ahli Muda</option>
                <option value="Ahli Madya" <?= (isset($filter_jenjang) && $filter_jenjang === 'Ahli Madya') ? 'selected' : '' ?>>Ahli Madya</option>
                <option value="Ahli Utama" <?= (isset($filter_jenjang) && $filter_jenjang === 'Ahli Utama') ? 'selected' : '' ?>>Ahli Utama</option>
            </select>

            <select name="filter_jpl" onchange="this.form.submit()" class="form-select" style="padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; cursor: pointer;">
                <option value="">Semua Capaian JPL</option>
                <option value="kurang" <?= (isset($filter_jpl) && $filter_jpl === 'kurang') ? 'selected' : '' ?>>Kurang dari 20 JPL</option>
                <option value="terpenuhi" <?= (isset($filter_jpl) && $filter_jpl === 'terpenuhi') ? 'selected' : '' ?>>Terpenuhi (>= 20 JPL)</option>
            </select>

            <button type="button" class="btn" style="background: var(--warning); color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: 500;" onclick="openReminderModal()">
                <i class="fa-solid fa-bell"></i> Pengingat Masal
            </button>

            <button type="submit" class="btn btn-outline" style="cursor: pointer; display: none;"><i class="fa-solid fa-filter"></i> Filter</button>
        </div>
    </form>
    
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NAMA & NIP</th>
                    <th>BANGSAL / UNIT</th>
                    <th>JENIS PEGAWAI</th>
                    <th>STATUS KEPEGAWAIAN</th>
                    <th>NAMA JABATAN</th>
                    <th>DATA SIP</th>
                    <th>JENJANG JABATAN</th>
                    <th>JENIS JENJANG</th>
                    <th>CAPAIAN JPL</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dummy_data = [
                    ['nama' => 'Ahmad Hidayat', 'nip' => '198501012010011001', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Terampil', 'jenis_jenjang' => 'Terampil', 'sip_no' => 'SIP/123/2021', 'sip_mulai' => '2021-08-20', 'sip_selesai' => '2026-08-25', 'capaian_jpl' => 15],
                    ['nama' => 'Siti Aminah', 'nip' => '199003022015022002', 'bangsal' => 'IGD', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Mahir', 'jenis_jenjang' => 'Terampil', 'sip_no' => 'SIP/456/2022', 'sip_mulai' => '2022-01-10', 'sip_selesai' => '2027-01-10', 'capaian_jpl' => 20],
                    ['nama' => 'Sri Mulyani', 'nip' => '197508261998032001', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'ASN', 'status_kep' => 'CPNS', 'jabatan' => 'Perawat', 'jenjang' => 'Penyelia', 'jenis_jenjang' => 'Terampil', 'sip_no' => 'SIP/789/2023', 'sip_mulai' => '2023-05-15', 'sip_selesai' => '2028-05-15', 'capaian_jpl' => 12],
                    ['nama' => 'Budi Santoso', 'nip' => '198210102021211005', 'bangsal' => 'IGD', 'jenis' => 'ASN', 'status_kep' => 'PPPK', 'jabatan' => 'Perawat', 'jenjang' => 'Ahli Pertama', 'jenis_jenjang' => 'Ahli', 'sip_no' => 'SIP/101/2021', 'sip_mulai' => '2021-09-01', 'sip_selesai' => '2026-09-01', 'capaian_jpl' => 25],
                    ['nama' => 'Eko Prasetyo', 'nip' => '198805122018031003', 'bangsal' => 'ICU', 'jenis' => 'ASN', 'status_kep' => 'PPPK Paruh Waktu', 'jabatan' => 'Perawat', 'jenjang' => 'Ahli Muda', 'jenis_jenjang' => 'Ahli', 'sip_no' => 'SIP/202/2019', 'sip_mulai' => '2019-03-20', 'sip_selesai' => '2024-03-20', 'capaian_jpl' => 5],
                    ['nama' => 'Dewi Lestari', 'nip' => '197012121995032001', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Ahli Madya', 'jenis_jenjang' => 'Ahli', 'sip_no' => 'SIP/303/2024', 'sip_mulai' => '2024-02-10', 'sip_selesai' => '2029-02-10', 'capaian_jpl' => 18],
                    ['nama' => 'Rina Wati', 'nip' => '196501011990012002', 'bangsal' => 'IGD', 'jenis' => 'ASN', 'status_kep' => 'PNS', 'jabatan' => 'Perawat', 'jenjang' => 'Ahli Utama', 'jenis_jenjang' => 'Ahli', 'sip_no' => 'SIP/404/2023', 'sip_mulai' => '2023-11-05', 'sip_selesai' => '2028-11-05', 'capaian_jpl' => 20],
                    ['nama' => 'Joko Widodo', 'nip' => 'BLUD2023055', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'BLUD', 'status_kep' => 'BLUD Tetap', 'jabatan' => 'Asisten Apoteker', 'jenjang' => '-', 'jenis_jenjang' => '-', 'sip_no' => 'SIPA/505/2023', 'sip_mulai' => '2023-01-20', 'sip_selesai' => '2028-01-20', 'capaian_jpl' => 0],
                    ['nama' => 'Agus Harimurti', 'nip' => 'MTR2023011', 'bangsal' => 'Poli Umum', 'jenis' => 'Mitra', 'status_kep' => 'Mitra Paruh Waktu', 'jabatan' => 'Petugas Kebersihan', 'jenjang' => '-', 'jenis_jenjang' => '-', 'sip_no' => '', 'sip_mulai' => '', 'sip_selesai' => '', 'capaian_jpl' => 0],
                    ['nama' => 'Susi Pudjiastuti', 'nip' => 'BLUD2020002', 'bangsal' => 'Bangsal Anggrek', 'jenis' => 'BLUD', 'status_kep' => 'BLUD Kontrak', 'jabatan' => 'Apoteker', 'jenjang' => '-', 'jenis_jenjang' => '-', 'sip_no' => 'SIPA/606/2021', 'sip_mulai' => '2021-12-15', 'sip_selesai' => '2026-12-15', 'capaian_jpl' => 20],
                ];

                // Jika admin bangsal, hanya tampilkan Bangsal Anggrek
                if(isset($role) && $role === 'admin') {
                    $dummy_data = array_filter($dummy_data, function($d) {
                        return $d['bangsal'] === 'Bangsal Anggrek';
                    });
                }
                
                // Jika superadmin memfilter bangsal tertentu
                if(isset($filter_bangsal) && !empty($filter_bangsal)) {
                    $dummy_data = array_filter($dummy_data, function($d) use ($filter_bangsal) {
                        return strtolower($d['bangsal']) === strtolower(urldecode($filter_bangsal));
                    });
                }
                
                if(isset($filter_status) && !empty($filter_status)) {
                    $dummy_data = array_filter($dummy_data, function($d) use ($filter_status) {
                        return strtolower($d['status_kep']) === strtolower(urldecode($filter_status));
                    });
                }

                if(isset($filter_jabatan) && !empty($filter_jabatan)) {
                    $dummy_data = array_filter($dummy_data, function($d) use ($filter_jabatan) {
                        return strtolower($d['jabatan']) === strtolower(urldecode($filter_jabatan));
                    });
                }

                if(isset($filter_jenjang) && !empty($filter_jenjang)) {
                    $dummy_data = array_filter($dummy_data, function($d) use ($filter_jenjang) {
                        return strtolower($d['jenjang']) === strtolower(urldecode($filter_jenjang));
                    });
                }

                if(isset($filter_jpl) && !empty($filter_jpl)) {
                    $dummy_data = array_filter($dummy_data, function($d) use ($filter_jpl) {
                        if ($filter_jpl === 'kurang') return $d['capaian_jpl'] < 20;
                        if ($filter_jpl === 'terpenuhi') return $d['capaian_jpl'] >= 20;
                        return true;
                    });
                }

                foreach($dummy_data as $row):
                ?>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);"><?= $row['nama'] ?></div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><?= $row['nip'] ?></div>
                    </td>
                    <td><span style="font-weight: 500;"><?= $row['bangsal'] ?></span></td>
                    <td><span style="font-weight: 500;"><?= $row['jenis'] ?></span></td>
                    <td><?= $row['status_kep'] ?></td>
                    <td><?= $row['jabatan'] ?></td>
                    <td>
                        <?php
                        if (!empty($row['sip_no'])) {
                            $today = new DateTime();
                            $expire = new DateTime($row['sip_selesai']);
                            $interval = $today->diff($expire);
                            $days_left = (int)$interval->format('%r%a');
                            
                            $badge = '';
                            if ($days_left < 0) {
                                $badge = '<span style="background: var(--danger); color: white; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-top: 4px; display: inline-flex; align-items: center; gap: 4px;"><i class="fa-solid fa-xmark"></i> Kedaluwarsa</span>';
                            } elseif ($days_left <= 30) {
                                $badge = '<span style="background: var(--warning); color: white; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-top: 4px; display: inline-flex; align-items: center; gap: 4px; font-weight: 600;"><i class="fa-solid fa-triangle-exclamation"></i> Segera Perbarui</span>';
                            } else {
                                $badge = '<span style="background: var(--success); color: white; font-size: 10px; padding: 2px 6px; border-radius: 4px; margin-top: 4px; display: inline-block;">Aktif</span>';
                            }
                            
                            echo '<div style="font-size: 12px; font-weight: 600; color: var(--text-main);">' . $row['sip_no'] . '</div>';
                            echo '<div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">' . date('d/m/Y', strtotime($row['sip_mulai'])) . ' - ' . date('d/m/Y', strtotime($row['sip_selesai'])) . '</div>';
                            echo $badge;
                        } else {
                            echo '<span style="color: var(--text-muted); font-size: 12px;">-</span>';
                        }
                        ?>
                    </td>
                    <td><?= $row['jenjang'] ?></td>
                    <td><?= $row['jenis_jenjang'] ?></td>
                    <td>
                        <?php if($row['capaian_jpl'] < 20): ?>
                            <span style="color: var(--danger); font-weight: bold;"><?= $row['capaian_jpl'] ?> / 20</span>
                        <?php else: ?>
                            <span style="color: var(--success); font-weight: bold;"><?= $row['capaian_jpl'] ?> / 20</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="status-badge status-active">Aktif</span></td>
                    <td>
                        <a href="<?= base_url('/view-pegawai?role=' . (isset($role) ? $role : '')) ?>" style="text-decoration: none;">
                            <i class="fa-solid fa-eye" style="color:var(--text-muted); cursor:pointer; margin-right: 12px;" title="Lihat Detail"></i>
                        </a>
                        <a href="<?= base_url('/edit-pegawai?role=' . (isset($role) ? $role : '')) ?>" style="text-decoration: none;">
                            <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right: 12px;" title="Edit Data"></i>
                        </a>
                        <?php if($row['capaian_jpl'] < 20): ?>
                            <i class="fa-solid fa-bell" style="color:var(--warning); cursor:pointer; margin-right: 12px;" title="Kirim Pengingat JPL" onclick="alert('Pengingat untuk memenuhi 20 JPL telah dikirim ke <?= $row['nama'] ?>.')"></i>
                        <?php endif; ?>
                        <?php if(!isset($role) || $role !== 'admin'): ?>
                        <i class="fa-solid fa-user-clock" style="color:var(--warning); cursor:pointer; margin-right: 12px;" title="Proses Pensiun"></i>
                        <?php endif; ?>
                        <i class="fa-solid fa-trash" style="color:var(--danger); cursor:pointer;"></i>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="flex-mobile-stack" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); color: var(--text-muted); font-size: 13px;">
        <span>
            <?= isset($role) && $role === 'admin' ? 'Menampilkan 1-10 dari 45 pegawai (Bangsal Anggrek)' : 'Menampilkan 1-10 dari 600 pegawai' ?>
        </span>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-outline" style="padding: 6px 12px;">Sebelummya</button>
            <button class="btn btn-primary" style="padding: 6px 12px;">1</button>
            <button class="btn btn-outline" style="padding: 6px 12px;">2</button>
            <button class="btn btn-outline" style="padding: 6px 12px;">Selanjutnya</button>
        </div>
    </div>
</div>

<!-- Modal Kirim Pengingat Masal -->
<div id="reminderModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div class="modal-content" style="background: var(--bg-surface); width: 100%; max-width: 420px; border-radius: 12px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="margin: 0; color: var(--text-main); font-size: 18px;">Kirim Pengingat Masal JPL</h3>
            <i class="fa-solid fa-xmark" onclick="closeReminderModal()" style="cursor: pointer; color: var(--text-muted); font-size: 18px;"></i>
        </div>
        
        <form onsubmit="event.preventDefault(); sendBulkReminder();">
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 6px;">Kirim pengingat untuk pegawai dengan JPL di bawah:</label>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <input type="number" id="jplThreshold" required min="1" value="20" style="width: 100px; padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-surface); color: var(--text-main); font-size: 14px; outline: none; box-sizing: border-box;">
                    <span style="font-size: 14px; color: var(--text-main); font-weight: 500;">Poin JPL</span>
                </div>
            </div>
            
            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.5; background: var(--bg-body); padding: 12px; border-radius: 8px; border-left: 3px solid var(--warning);">
                Sistem akan memfilter dan mengirimkan notifikasi secara otomatis ke akun masing-masing pegawai yang capaian JPL-nya berada di bawah nilai yang Anda tentukan di atas.
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn btn-outline" onclick="closeReminderModal()">Batal</button>
                <button type="submit" class="btn" style="background-color: var(--warning); color: white; border: none; font-weight: 500;"><i class="fa-solid fa-paper-plane"></i> Kirim Notifikasi</button>
            </div>
        </form>
    </div>
</div>

<script>
function openReminderModal() {
    document.getElementById('reminderModal').style.display = 'flex';
}

function closeReminderModal() {
    document.getElementById('reminderModal').style.display = 'none';
}

function sendBulkReminder() {
    const threshold = document.getElementById('jplThreshold').value;
    alert('Berhasil! Pengingat untuk memenuhi standar telah dikirimkan secara masal ke semua pegawai yang capaian JPL-nya di bawah ' + threshold + ' poin.');
    closeReminderModal();
}
</script>

<?= $this->endSection() ?>
