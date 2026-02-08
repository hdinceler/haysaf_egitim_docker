#!/bin/bash

# 1. Docker dizinine git
cd ~/Masaüstü/docker || { echo "Dizin bulunamadı!"; exit 1; }

# 2. Git reposu başlat (önce varsa temizle)
if [ ! -d .git ]; then
    git init
else
    echo ".git dizini zaten mevcut, kullanılıyor."
fi

# 3. Uzak repo ekle
git remote remove origin 2>/dev/null
git remote add origin https://github.com/hdinceler/haysaf_egitim_docker.git

# 4. Tüm dosyaları ekle
git add .

# 5. Commit oluştur
git commit -m "Docker ortam ve www dosyaları eklendi"

# 6. Ana dalı main olarak ayarla
git branch -M main

# 7. GitHub’a gönder
git push -u origin main
# Eğer eski veriyi silip zorla göndermek istersen:
# git push -u origin main --force

echo "✅ Tüm dosyalar GitHub'a gönderildi!"
