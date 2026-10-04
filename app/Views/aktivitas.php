<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex-mobile-stack" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="margin: 0;">Aktivitas & Verifikasi Dokumen</h1>
        <p style="color: var(--text-muted); font-size: 13px; margin: 4px 0 0 0;">Verifikasi berkas usulan kenaikan pangkat, sertifikat, dan berkas kepegawaian.</p>
    </div>
    <a href="<?= base_url('/riwayat-kepangkatan?role=' . (isset($role) ? $role : 'superadmin')) ?>" class="btn btn-outline" style="font-size: 13px; gap: 8px;">
        <i class="fa-solid fa-chart-line" style="color: var(--primary);"></i> Buka Modul Kepangkatan
    </a>
</div>

<!-- Category Tabs -->
<div style="display: flex; gap: 8px; margin-bottom: 20px;" class="flex-mobile-stack">
    <button class="btn btn-primary" id="filterBtnAll" onclick="filterAktivitas('all')" style="padding: 8px 16px; font-size: 13px;">Semua Dokumen (4)</button>
    <button class="btn btn-outline" id="filterBtnKP" onclick="filterAktivitas('kepangkatan')" style="padding: 8px 16px; font-size: 13px;">
        <i class="fa-solid fa-medal" style="color: var(--warning);"></i> Pengajuan Kepangkatan (2)
    </button>
    <button class="btn btn-outline" id="filterBtnLain" onclick="filterAktivitas('lain')" style="padding: 8px 16px; font-size: 13px;">Dokumen Lainnya (2)</button>
</div>

<div class="main-card" style="padding: 0; overflow: hidden;">
    <div class="filter-row" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border);">
        <div style="font-weight: 600; color: var(--text-main);">Daftar Dokumen & Usulan Menunggu Verifikasi Admin Kepegawaian</div>
        <div class="search-bar" style="width: 100%; max-width: 300px; background: var(--bg-surface); border: 1px solid var(--border);">
            <i class="fa-solid fa-magnifying-glass text-muted"></i>
            <input type="text" placeholder="Cari aktivitas..." onkeyup="searchAktivitas(this.value)">
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>WAKTU UPLOAD</th>
                    <th>PEGAWAI</th>
                    <th>JENIS DOKUMEN / USULAN</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody id="aktivitas-table-body">
                <!-- Usulan Ahmad Hidayat (Kepangkatan) -->
                <tr id="row-ahmad" data-kategori="kepangkatan">
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Hari ini, 14:30</div>
                        <div style="font-size: 12px; color: var(--primary); margin-top: 4px; font-weight: 600;"><i class="fa-solid fa-sparkles"></i> Baru Diajukan</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Ahmad Hidayat, S.Kep., Ns.</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Perawat (Penata Muda, III/a)</div>
                    </td>
                    <td>
                        <div>
                            <span class="status-badge" style="background: #e0e7ff; color: #3730a3; font-size: 11px; padding: 2px 6px;">Usulan Kenaikan Pangkat Reguler</span>
                            <div style="font-weight: 600; font-size: 13px; color: var(--text-main); margin-top: 4px;">Usulan ke: Penata Muda Tk. I (III/b)</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                            <i class="fa-solid fa-file-pdf" style="color: #ef4444; font-size: 16px;"></i>
                            <span style="font-size: 12px; color: var(--text-muted);">SK_Kenaikan_Pangkat_Ahmad_2026.pdf (1.8 MB)</span>
                        </div>
                    </td>
                    <td><span class="status-badge" style="background:#fef3c7; color:#f59e0b;"><span style="display:inline-block; width:6px; height:6px; background:#f59e0b; border-radius:50%; margin-right:4px;"></span> Menunggu Persetujuan Admin</span></td>
                    <td style="white-space: nowrap;">
                        <button class="btn btn-outline" style="padding: 6px 10px; font-size: 12px; margin-right: 6px;" onclick="lihatDokumen('SK_Kenaikan_Pangkat_Ahmad_2026.pdf')"><i class="fa-solid fa-eye"></i> Periksa SK</button>
                        <button class="btn btn-primary btn-verifikasi" style="padding: 6px 10px; font-size: 12px; background: var(--success); margin-right: 6px;" onclick="verifikasiDokumen('row-ahmad', 'Ahmad Hidayat (Kenaikan Pangkat III/b)')"><i class="fa-solid fa-check"></i> Setujui</button>
                        <button class="btn btn-outline" style="padding: 6px 8px; font-size: 12px; color: var(--danger); border-color: var(--border);" onclick="tolakAktivitas('row-ahmad')"><i class="fa-solid fa-xmark"></i></button>
                    </td>
                </tr>

                <!-- Usulan Dr. Andi Kurniawan (Kepangkatan) -->
                <tr id="row-2" data-kategori="kepangkatan">
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Kemarin, 15:45</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">1 Hari yang lalu</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Dr. Andi Kurniawan</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Dokter Spesialis (Madya, IV/a)</div>
                    </td>
                    <td>
                        <div>
                            <span class="status-badge" style="background: #e0e7ff; color: #3730a3; font-size: 11px; padding: 2px 6px;">Usulan Pangkat Fungsional</span>
                            <div style="font-weight: 600; font-size: 13px; color: var(--text-main); margin-top: 4px;">Usulan ke: Pembina Tingkat I (IV/b)</div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                            <i class="fa-solid fa-file-pdf" style="color: #ef4444; font-size: 16px;"></i>
                            <span style="font-size: 12px; color: var(--text-muted);">SK_Kenaikan_Pangkat_DrAndi.pdf</span>
                        </div>
                    </td>
                    <td><span class="status-badge" style="background:#fef3c7; color:#f59e0b;"><span style="display:inline-block; width:6px; height:6px; background:#f59e0b; border-radius:50%; margin-right:4px;"></span> Menunggu Persetujuan Admin</span></td>
                    <td style="white-space: nowrap;">
                        <button class="btn btn-outline" style="padding: 6px 10px; font-size: 12px; margin-right: 6px;" onclick="lihatDokumen('SK_Kenaikan_Pangkat_DrAndi.pdf')"><i class="fa-solid fa-eye"></i> Periksa SK</button>
                        <button class="btn btn-primary btn-verifikasi" style="padding: 6px 10px; font-size: 12px; background: var(--success); margin-right: 6px;" onclick="verifikasiDokumen('row-2', 'Dr. Andi Kurniawan')"><i class="fa-solid fa-check"></i> Setujui</button>
                        <button class="btn btn-outline" style="padding: 6px 8px; font-size: 12px; color: var(--danger); border-color: var(--border);" onclick="tolakAktivitas('row-2')"><i class="fa-solid fa-xmark"></i></button>
                    </td>
                </tr>

                <!-- Budi Santoso (Sertifikat) -->
                <tr id="row-1" data-kategori="lain">
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Hari ini, 09:30</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Beberapa jam lalu</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Budi Santoso</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Perawat Madya</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-file-pdf" style="color: #ef4444; font-size: 18px;"></i>
                            Sertifikat_Pelatihan_ICU.pdf
                        </div>
                    </td>
                    <td><span class="status-badge" style="background:#fef3c7; color:#f59e0b;">Menunggu Verifikasi</span></td>
                    <td style="white-space: nowrap;">
                        <button class="btn btn-outline" style="padding: 6px 10px; font-size: 12px; margin-right: 8px;" onclick="lihatDokumen('Sertifikat_Pelatihan_ICU.pdf')"><i class="fa-solid fa-eye"></i> Lihat</button>
                        <button class="btn btn-primary btn-verifikasi" style="padding: 6px 10px; font-size: 12px; background: var(--success);" onclick="verifikasiDokumen('row-1', 'Budi Santoso')"><i class="fa-solid fa-check"></i> Verifikasi</button>
                    </td>
                </tr>

                <!-- Siti Aminah (Foto KTP) -->
                <tr id="row-3" data-kategori="lain">
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Kemarin, 11:20</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">1 Hari yang lalu</div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main);">Siti Aminah</div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Bidan (Penyelia)</div>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-image" style="color: #3b82f6; font-size: 18px;"></i>
                            Foto_KTP_Terbaru.jpg
                        </div>
                    </td>
                    <td><span class="status-badge" style="background:#fef3c7; color:#f59e0b;">Menunggu Verifikasi</span></td>
                    <td style="white-space: nowrap;">
                        <button class="btn btn-outline" style="padding: 6px 10px; font-size: 12px; margin-right: 8px;" onclick="lihatDokumen('Foto_KTP_Terbaru.jpg')"><i class="fa-solid fa-eye"></i> Lihat</button>
                        <button class="btn btn-primary btn-verifikasi" style="padding: 6px 10px; font-size: 12px; background: var(--success);" onclick="verifikasiDokumen('row-3', 'Siti Aminah')"><i class="fa-solid fa-check"></i> Verifikasi</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function lihatDokumen(namaFile) {
        Swal.fire({
            title: 'Melihat Dokumen',
            text: 'Membuka preview untuk ' + namaFile,
            icon: 'info',
            confirmButtonColor: '#4361ee'
        });
    }

    function verifikasiDokumen(rowId) {
        Swal.fire({
            title: 'Verifikasi Dokumen?',
            text: "Dokumen ini akan diverifikasi dan masuk ke database pegawai.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Verifikasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Dummy action: remove the row to simulate it's been processed
                const row = document.getElementById(rowId);
                row.style.transition = "all 0.5s ease";
                row.style.opacity = "0";
                row.style.transform = "translateX(50px)";
                
                setTimeout(() => {
                    row.remove();
                    
                    // Check if table is empty
                    const tbody = document.getElementById('aktivitas-table-body');
                    if(tbody.children.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; padding: 30px; color: var(--text-muted);">Tidak ada dokumen yang perlu diverifikasi</td></tr>`;
                    }
                }, 500);

                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Dokumen berhasil diverifikasi dan masuk ke database.',
                    icon: 'success',
                    confirmButtonColor: '#4361ee'
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
