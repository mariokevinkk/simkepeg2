#!/bin/bash
# Deploy otomatis tanpa fitur Git Deploy: dijalankan Cron Job cPanel (mis. tiap 5 menit).
# Jika ada commit baru di GitHub (branch main), kode diunduh lalu disalin ke folder aplikasi.
# Tidak pernah menyentuh: .env, writable/ (dokumen pegawai, sesi, log), vendor/.
# Riwayat deploy dicatat di ~/deploy-last.log.

GITHUB_REPO="mariokevinkk/simkepeg2"
BRANCH="main"
APPDIR="$HOME/simkepeg2"
STATE="$HOME/.deploy-commit"
LOG="$HOME/deploy-last.log"

log() { echo "$(date '+%F %T') $*" >> "$LOG"; }

# Commit terbaru di GitHub (repo publik, tanpa login).
LATEST=$(curl -fsS --max-time 30 -H 'Accept: application/vnd.github.sha' \
    "https://api.github.com/repos/$GITHUB_REPO/commits/$BRANCH") || exit 0
case "$LATEST" in
    [0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f]*) ;;
    *) exit 0 ;;  # jawaban tidak dikenali (mis. batas akses GitHub): coba lagi di jadwal berikutnya
esac

# Sudah terpasang: tidak ada yang perlu dilakukan.
[ "$LATEST" = "$(cat "$STATE" 2>/dev/null)" ] && exit 0

TMP=$(mktemp -d) || exit 1
trap 'rm -rf "$TMP"' EXIT

if ! curl -fsSL --max-time 300 "https://codeload.github.com/$GITHUB_REPO/tar.gz/$LATEST" | tar -xz -C "$TMP"; then
    log "unduh gagal untuk commit $LATEST"
    exit 1
fi
SRC=$(find "$TMP" -mindepth 1 -maxdepth 1 -type d | head -n 1)
if [ ! -f "$SRC/app/Config/Paths.php" ]; then
    log "isi unduhan tidak lengkap untuk commit $LATEST"
    exit 1
fi

copy_dir() {  # copy_dir <sumber/> <tujuan/> [--delete]
    if command -v rsync >/dev/null 2>&1; then
        rsync -a $3 "$1" "$2"
    else
        mkdir -p "$2" && cp -R "$1". "$2"
    fi
}

mkdir -p "$APPDIR"
copy_dir "$SRC/app/" "$APPDIR/app/" --delete
copy_dir "$SRC/public/" "$APPDIR/public/"
cp "$SRC/composer.json" "$SRC/composer.lock" "$SRC/spark" "$SRC/preload.php" "$APPDIR/" 2>/dev/null
[ -f "$SRC/env.production.example" ] && cp "$SRC/env.production.example" "$APPDIR/"

# Situs yang memakai salinan public/ di public_html (Cara B) ikut diperbarui.
if [ -f "$HOME/public_html/index.php" ] && [ -f "$HOME/public_html/favicon-rsud.png" ]; then
    copy_dir "$SRC/public/" "$HOME/public_html/"
fi

echo "$LATEST" > "$STATE"
log "deploy berhasil: commit $LATEST"
