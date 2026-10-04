<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }
    .modal-box {
        background: var(--bg-surface);
        padding: 24px;
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 12px;
    }
    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
    }
    .btn-close {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text-muted);
        cursor: pointer;
    }
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }
</style>

<div class="modal-overlay" id="modalTambahAdmin">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Tambah Admin Bangsal</div>
            <button class="btn-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Nama Pengguna</label>
            <input type="text" class="form-control" placeholder="Masukkan nama admin...">
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Username / NIP</label>
            <input type="text" class="form-control" placeholder="Masukkan NIP atau username...">
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Hak Akses (Area Tugas)</label>
            <div class="form-control" style="height: auto; max-height: 140px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500;"><input type="checkbox" value="anggrek"> Bangsal Anggrek</label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500;"><input type="checkbox" value="mawar"> Bangsal Mawar</label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500;"><input type="checkbox" value="melati"> Bangsal Melati</label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500;"><input type="checkbox" value="igd"> IGD / UGD</label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500;"><input type="checkbox" value="icu"> ICU</label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 500;"><input type="checkbox" value="poli"> Poli Umum</label>
            </div>
            <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">Beri centang untuk memilih lebih dari satu area tugas.</small>
        </div>
        <div class="form-group" style="margin-bottom: 16px;">
            <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-main);">Password Default</label>
            <input type="text" class="form-control" value="123456" readonly style="background: var(--bg-body); cursor: not-allowed;">
            <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">Password default untuk admin baru adalah 123456</small>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal()">Batal</button>
            <button class="btn btn-primary" onclick="closeModal()">Simpan Admin</button>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalTambahAdmin').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('modalTambahAdmin').style.display = 'none';
    }
</script>
<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h1 class="page-title" style="margin: 0;">Manajemen Admin (Bangsal / Unit)</h1>
    <div style="display: flex; gap: 12px;" class="flex-mobile-stack">
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-user-plus"></i> Tambah Admin Baru</button>
    </div>
</div>

<div class="main-card" style="padding: 0; overflow: hidden;">
    <div class="filter-row" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
        <div class="search-bar" style="width: 100%; max-width: 300px; background: var(--bg-surface); border: 1px solid var(--border);">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
            <input type="text" placeholder="Cari nama atau username admin...">
        </div>
        <div style="display: flex; gap: 12px;">
            <select class="form-control" style="width: 180px; padding: 8px 12px; background: var(--bg-surface);">
                <option value="">Semua Bangsal</option>
                <option value="anggrek">Bangsal Anggrek</option>
                <option value="mawar">Bangsal Mawar</option>
                <option value="igd">IGD / UGD</option>
            </select>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NAMA PENGGUNA</th>
                    <th>USERNAME / NIP</th>
                    <th>ROLE / AKSES</th>
                    <th>AREA TUGAS (BANGSAL)</th>
                    <th>STATUS AKUN</th>
                    <th>TERAKHIR LOGIN</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $dummy_accounts = [
                    ['nama' => 'Budi Santoso', 'username' => 'budi_admin', 'role' => 'Admin Bangsal', 'bangsal' => 'Bangsal Anggrek', 'status' => 'Aktif', 'last_login' => 'Hari ini, 08:30'],
                    ['nama' => 'Siti Aminah', 'username' => 'siti_mawar', 'role' => 'Admin Bangsal', 'bangsal' => 'Bangsal Mawar', 'status' => 'Aktif', 'last_login' => 'Kemarin, 14:15'],
                    ['nama' => 'Agus Yulianto', 'username' => 'agus_ugd', 'role' => 'Admin Bangsal', 'bangsal' => 'UGD / IGD', 'status' => 'Aktif', 'last_login' => 'Hari ini, 07:10'],
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
                    <td><span style="font-weight: 500; color: var(--text-muted);"><?= $acc['username'] ?></span></td>
                    <td>
                        <span style="background: var(--primary-light); color: var(--primary); padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;"><i class="fa-solid fa-user-tie"></i> Admin Bangsal</span>
                    </td>
                    <td><span style="font-weight: 500;"><?= $acc['bangsal'] ?></span></td>
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
        <span>Menampilkan 3 dari 25 akun admin terdaftar</span>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-outline" style="padding: 6px 12px;">Sebelummya</button>
            <button class="btn btn-primary" style="padding: 6px 12px;">1</button>
            <button class="btn btn-outline" style="padding: 6px 12px;">Selanjutnya</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
