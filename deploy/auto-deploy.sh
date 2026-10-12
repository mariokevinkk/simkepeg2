#!/bin/bash
# Dipanggil Cron Job di cPanel (mis. tiap 5 menit).
# Menarik commit terbaru dari GitHub; jika ada yang baru, menjalankan deploy cPanel (.cpanel.yml).
# Hasil deploy terakhir dicatat di ~/deploy-last.log.

REPO="$HOME/repositories/simkepeg2"
LOG="$HOME/deploy-last.log"

cd "$REPO" || exit 1
OLD=$(git rev-parse HEAD)
git pull --ff-only -q origin main || { echo "$(date '+%F %T') git pull gagal" >> "$LOG"; exit 1; }
NEW=$(git rev-parse HEAD)

# Tidak ada commit baru: tidak perlu deploy.
[ "$OLD" = "$NEW" ] && exit 0

{
    echo "$(date '+%F %T') deploy $OLD -> $NEW"
    uapi VersionControlDeployment create repository_root="$REPO"
} >> "$LOG" 2>&1
