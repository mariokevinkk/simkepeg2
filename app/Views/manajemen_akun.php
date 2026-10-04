<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 class="page-title" style="margin: 0;">Manajemen Akun (Pegawai)</h1>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Tambah Akun Baru</button>
    </div>
</div>

<div class="main-card" style="padding: 0; overflow: hidden;">
    <div class="filter-row" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
        <div class="search-bar" style="width: 100%; max-width: 300px; background: var(--bg-surface); border: 1px solid var(--border);">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
            <input type="text" placeholder="Cari nama atau username...">
        </div>
        <div style="display: flex; gap: 12px;">
            <select class="form-control" style="width: 160px; padding: 8px 12px; background: var(--bg-surface);">
                <option value="">Semua Bangsal</option>
                <option value="poli">Poli Anak</option>
                <option value="farmasi">Instalasi Farmasi</option>
            </select>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NAMA PENGGUNA</th>
                    <th>NIP (USERNAME)</th>
                    <th>ROLE / AKSES</th>
                    <th>AREA TUGAS (BANGSAL)</th>
                    <th>TANGGAL LAHIR (PASSWORD DEFAULT)</th>
                    <th>STATUS AKUN</th>
                    <th>TERAKHIR LOGIN</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $dummy_accounts = [
                    ['nama' => 'Ahmad Hidayat', 'nip' => '198501012010011001', 'role' => 'Pegawai', 'bangsal' => 'Bangsal Anggrek', 'status' => 'Aktif', 'tgl_lahir' => '01 Januari 1985', 'last_login' => 'Hari ini, 08:15'],
                    ['nama' => 'Siti Aminah', 'nip' => '199003022015022002', 'role' => 'Pegawai', 'bangsal' => 'IGD', 'status' => 'Aktif', 'tgl_lahir' => '02 Maret 1990', 'last_login' => 'Kemarin, 14:10'],
                    ['nama' => 'Sri Mulyani', 'nip' => '197508261998032001', 'role' => 'Pegawai', 'bangsal' => 'Bangsal Anggrek', 'status' => 'Aktif', 'tgl_lahir' => '26 Agustus 1975', 'last_login' => 'Hari ini, 07:30'],
                    ['nama' => 'Budi Santoso', 'nip' => '198210102021211005', 'role' => 'Pegawai', 'bangsal' => 'IGD', 'status' => 'Aktif', 'tgl_lahir' => '10 Oktober 1982', 'last_login' => '2 Hari lalu'],
                    ['nama' => 'Eko Prasetyo', 'nip' => '198805122018031003', 'role' => 'Pegawai', 'bangsal' => 'ICU', 'status' => 'Aktif', 'tgl_lahir' => '12 Mei 1988', 'last_login' => 'Hari ini, 10:05'],
                    ['nama' => 'Dewi Lestari', 'nip' => '197012121995032001', 'role' => 'Pegawai', 'bangsal' => 'Bangsal Anggrek', 'status' => 'Aktif', 'tgl_lahir' => '12 Desember 1970', 'last_login' => '3 Jam lalu'],
                    ['nama' => 'Rina Wati', 'nip' => '196501011990012002', 'role' => 'Pegawai', 'bangsal' => 'IGD', 'status' => 'Aktif', 'tgl_lahir' => '01 Januari 1965', 'last_login' => 'Kemarin, 09:20'],
                    ['nama' => 'Joko Widodo', 'nip' => 'BLUD2023055', 'role' => 'Pegawai', 'bangsal' => 'Bangsal Anggrek', 'status' => 'Aktif', 'tgl_lahir' => '21 Juni 1990', 'last_login' => 'Hari ini, 08:00'],
                    ['nama' => 'Agus Harimurti', 'nip' => 'MTR2023011', 'role' => 'Pegawai', 'bangsal' => 'Poli Umum', 'status' => 'Nonaktif', 'tgl_lahir' => '15 Agustus 1995', 'last_login' => '1 Bulan lalu'],
                    ['nama' => 'Susi Pudjiastuti', 'nip' => 'BLUD2020002', 'role' => 'Pegawai', 'bangsal' => 'Bangsal Anggrek', 'status' => 'Aktif', 'tgl_lahir' => '11 Februari 1980', 'last_login' => 'Hari ini, 11:45'],
                ];
                
                foreach($dummy_accounts as $acc):
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($acc['nama']) ?>&background=random&color=fff" alt="Avatar" style="width: 32px; height: 32px; border-radius: 50%;">
                            <div style="font-weight: 600; color: var(--text-main);"><?= $acc['nama'] ?></div>
                        </div>
                    </td>
                    <td><span style="font-weight: 500; color: var(--text-muted);"><?= $acc['nip'] ?></span></td>
                    <td>
                        <span style="background: #e2e8f0; color: #475569; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;"><i class="fa-solid fa-user"></i> Pegawai</span>
                    </td>
                    <td><span style="font-weight: 500;"><?= $acc['bangsal'] ?></span></td>
                    <td><span style="font-weight: 500; color: var(--text-main);"><?= $acc['tgl_lahir'] ?></span></td>
                    <td>
                        <?php if($acc['status'] === 'Aktif'): ?>
                            <span class="status-badge status-active">Aktif</span>
                        <?php else: ?>
                            <span class="status-badge" style="background: #fee2e2; color: #ef4444;">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size: 13px; color: var(--text-muted);"><?= $acc['last_login'] ?></td>
                    <td>
                        <i class="fa-solid fa-pen-to-square" style="color:var(--primary); cursor:pointer; margin-right: 12px;" title="Edit Akun"></i>
                        <i class="fa-solid fa-key" style="color:var(--warning); cursor:pointer; margin-right: 12px;" title="Reset Password"></i>
                        <i class="fa-solid fa-trash" style="color:var(--danger); cursor:pointer;" title="Hapus Akun"></i>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="flex-mobile-stack" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); color: var(--text-muted); font-size: 13px;">
        <span>Menampilkan 10 dari 600 akun terdaftar</span>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-outline" style="padding: 6px 12px;">Sebelummya</button>
            <button class="btn btn-primary" style="padding: 6px 12px;">1</button>
            <button class="btn btn-outline" style="padding: 6px 12px;">Selanjutnya</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
