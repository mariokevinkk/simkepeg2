<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php $isAdmin = isset($role) && $role === 'admin'; ?>

<h1 class="page-title"><?= $isAdmin ? 'Profil Bangsal' : 'Pengaturan Sistem' ?></h1>

<div class="main-card" style="max-width: 800px;">
    <h3 style="font-size: 18px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
        <?= $isAdmin ? 'Informasi Bangsal' : 'Profil Instansi' ?>
    </h3>
    
    <div style="display: grid; gap: 20px;">
        <?php if($isAdmin): ?>
        <!-- Profil Bangsal Form (Admin Bangsal) -->
        <div class="settings-grid-2">
            <div>
                <label class="form-label">Nama Bangsal / Unit</label>
                <input type="text" class="form-control" value="Bangsal Anggrek">
            </div>
            <div>
                <label class="form-label">Kepala Bangsal / Karu</label>
                <input type="text" class="form-control" value="Ns. Rini Setyowati, S.Kep">
            </div>
        </div>

        <div>
            <label class="form-label">Fokus Layanan & Deskripsi</label>
            <textarea class="form-control" rows="3">Layanan rawat inap untuk pasien penyakit dalam, khusus dewasa. Memiliki 45 bed.</textarea>
        </div>

        <div class="settings-grid-2">
            <div>
                <label class="form-label">Ekstensi Telepon Bangsal</label>
                <input type="text" class="form-control" value="105">
            </div>
        </div>
        
        <?php else: ?>
        <!-- Profil Instansi Form (Super Admin) -->
        <div class="settings-grid-2">
            <div>
                <label class="form-label">Nama Instansi</label>
                <input type="text" class="form-control" value="RSUD Kota Yogyakarta">
            </div>
        </div>

        <div>
            <label class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" rows="3">Jl. Ki Ageng Pemanahan No.1-6, Sorosutan, Kec. Umbulharjo, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55162</textarea>
        </div>

        <div class="settings-grid-2">
            <div>
                <label class="form-label">Email Kontak</label>
                <input type="email" class="form-control" value="rsud@jogjakota.go.id">
            </div>
            <div>
                <label class="form-label">Telepon</label>
                <input type="text" class="form-control" value="(0274) 371195">
            </div>
        </div>
        <?php endif; ?>

        <div style="margin-top: 16px; display: flex; justify-content: flex-end;">
            <button class="btn btn-primary" style="width: 100%; max-width: 250px; justify-content: center;">Simpan Perubahan</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
