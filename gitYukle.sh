#!/bin/bash

set -e  # hata olursa dur

# Script'in bulunduğu dizin (gerçek yol)
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REMOTE_URL="git@github.com:hdinceler/haysaf_egitim_docker.git"
BRANCH="main"

echo "📁 Proje dizinine giriliyor..."
cd "$PROJECT_DIR" || { echo "❌ Dizin bulunamadı"; exit 1; }

echo "🧹 Eski git tamamen siliniyor..."
rm -rf .git
rm -f .gitmodules
rm -rf .git/modules

echo "🧹 www submodule / symlink kontrolü ve temizliği..."

# Eğer www symlink ise gerçek klasör haline getir
if [ -L www ]; then
    echo "⚠️ www bir symlink, kaldırılıyor..."
    rm www
    mkdir www
fi

# Eğer www daha önce submodule ise (gizli git izi varsa)
rm -rf www/.git

echo "🆕 Git başlatılıyor..."
git init
git branch -M "$BRANCH"

echo "🔗 Remote ekleniyor..."
git remote add origin "$REMOTE_URL"

echo "➕ Tüm dosyalar (www dahil) ekleniyor..."
git add .

echo "📝 Commit atılıyor..."
git commit -m "Yereldeki yapı zorla yüklendi (www normal klasör)"

echo "🚀 GitHub’daki her şeyin ÜZERİNE yazılıyor (FORCE)..."
git push -u origin "$BRANCH" --force

echo "✅ GitHub repo yereldeki hâle birebir getirildi"
echo "📂 www artık normal klasör, GitHub’da tıklanabilir"