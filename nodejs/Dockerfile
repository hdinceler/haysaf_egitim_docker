# 1️⃣ Base image: Node.js LTS alpine (hafif ve stabil)
FROM node:20-alpine

# 2️⃣ Çalışma dizini
WORKDIR /app

# 3️⃣ Sistem bağımlılıkları (openssl, tini, build-base)
RUN apk add --no-cache bash tini git python3 make g++ openssl

# 4️⃣ Package.json ve package-lock.json kopyala
COPY package*.json ./

# 5️⃣ Production dependencies kur
RUN npm ci --only=production

# 6️⃣ Tüm uygulama dosyalarını kopyala
COPY . .

# 7️⃣ Ortam değişkeni
ENV NODE_ENV=production
ENV PORT=3000

# 8️⃣ Node.js server başlatıcısı (tini process manager ile)
ENTRYPOINT ["/sbin/tini", "--"]

# 9️⃣ Container start command
CMD ["node", "index.js"]

# 10️⃣ Port expose
EXPOSE 3000