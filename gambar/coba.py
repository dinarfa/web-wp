import math

# Data nilai mahasiswa smstr V
data = [54, 53, 55, 56, 57, 68, 74, 65, 64, 58, 58, 52, 53, 67, 64, 56, 63, 72, 66, 65,
        57, 55, 69, 68, 54, 66, 71, 64, 67, 56, 69, 65, 56, 69, 59, 64, 73, 69, 68, 58]

# Hitung jumlah data dan jumlah kelas
n = len(data)
k = math.ceil(1 + 3.3 * math.log10(n))

# Hitung panjang kelas
nilaiMin = min(data)
nilaiMax = max(data)
rangeKelas = math.ceil((nilaiMax - nilaiMin)/k)

# Buat kelas interval
batasBawah = [nilaiMin + i * rangeKelas for i in range(k) if (nilaiMin + i * rangeKelas) <= nilaiMax]
batasAtas = [bb + rangeKelas - 1 for bb in batasBawah]
kelas_interval = list(zip(batasBawah, batasAtas))

# fungsi hitung frekuensi
def hitung_frekuensi(data, kelas_interval):
  frekuensi = []
  for batas in kelas_interval:
    hitung = sum(1 for nilai in data if batas[0] <= nilai <= batas[1])
    frekuensi.append(hitung)
  return frekuensi

# fungsi hitung frekuensi kumulatif
def hitung_frekuensi_kumulatif(frekuensi):
  frekuensi_kumulatif = []
  total = 0
  for f in frekuensi:
        total += f
        frekuensi_kumulatif.append(total)
  return frekuensi_kumulatif


# Fungsi menghitung Mean
def hitung_mean(kelas_interval, frekuensi):
    total_fx = sum(((b[0] + b[1]) / 2) * f for b, f in zip(kelas_interval, frekuensi))
    # fx itu nilai titik tengah dikalikan dengan frekuensinya
    total_f = sum(frekuensi)
    return total_fx / total_f

# menghitung Median
def hitung_median(kelas_interval, frekuensi):
    total_f = sum(frekuensi)
    F = 0
    for i, f in enumerate(frekuensi):
        F += f
        if F >= total_f / 2:
            l_median = kelas_interval[i][0] - 0.5  #Batas bawah kelas median
            f_median = frekuensi[i]  #Frekuensi kelas median
            F_sebelum = F - f  #Frekuensi kumulatif sebelum kelas median
            range_kelas = kelas_interval[i][1] - kelas_interval[i][0] + 1
            return l_median + ((total_f / 2 - F_sebelum) / f_median) * range_kelas

# menghitung Modus
def hitung_modus(kelas_interval, frekuensi):
    max_f_index = frekuensi.index(max(frekuensi)) #ini cari frekuensi paling tinggi
    l_modus = kelas_interval[max_f_index][0] - 0.5 #BB nya dikurangi 0.5
    f_modus = frekuensi[max_f_index] 
    f_sebelum = frekuensi[max_f_index - 1] if max_f_index > 0 else 0
    f_setelah = frekuensi[max_f_index + 1] if max_f_index < len(frekuensi) - 1 else 0
    range_kelas = kelas_interval[max_f_index][1] - kelas_interval[max_f_index][0] + 1
    return l_modus + ((f_modus - f_sebelum) / ((f_modus - f_sebelum) + (f_modus - f_setelah))) * range_kelas

# Fungsi menghitung Kuartil
def hitung_kuartil(kelas_interval, frekuensi, k):
    total_f = sum(frekuensi)
    posisi = k * total_f / 4
    F = 0
    for i, f in enumerate(frekuensi):
        F += f
        if F >= posisi:
            l_kuartil = kelas_interval[i][0] - 0.5
            f_kuartil = frekuensi[i]
            F_sebelum = F - f
            range_kelas = kelas_interval[i][1] - kelas_interval[i][0] + 1
            return l_kuartil + ((posisi - F_sebelum) / f_kuartil) * range_kelas

# Fungsi menghitung Desil (D ke-78 untuk kasus saya)
def hitung_desil(kelas_interval, frekuensi, d):
    total_f = sum(frekuensi)
    posisi = d * total_f / 10
    F = 0
    for i, f in enumerate(frekuensi):
        F += f
        if F >= posisi:
            l_desil = kelas_interval[i][0] - 0.5
            f_desil = frekuensi[i]
            F_sebelum = F - f
            range_kelas = kelas_interval[i][1] - kelas_interval[i][0] + 1
            return l_desil + ((posisi - F_sebelum) / f_desil) * range_kelas

# Fungsi menghitung Persentil (P ke-n)
def hitung_persentil(kelas_interval, frekuensi, p):
    total_f = sum(frekuensi)
    posisi = p * total_f / 100
    F = 0
    for i, f in enumerate(frekuensi):
        F += f
        if F >= posisi:
            l_persentil = kelas_interval[i][0] - 0.5
            f_persentil = frekuensi[i]
            F_sebelum = F - f
            range_kelas = kelas_interval[i][1] - kelas_interval[i][0] + 1
            return l_persentil + ((posisi - F_sebelum) / f_persentil) * range_kelas

# dibawah ini fungsi2nya dipakai
frekuensi = hitung_frekuensi(data, kelas_interval)
frekuensi_kumulatif = hitung_frekuensi_kumulatif(frekuensi)

mean = hitung_mean(kelas_interval, frekuensi)
median = hitung_median(kelas_interval, frekuensi)
modus = hitung_modus(kelas_interval, frekuensi)

kuartil_2 = hitung_kuartil(kelas_interval, frekuensi, 2)
desil_8 = hitung_desil(kelas_interval, frekuensi, 8)
persentil_78 = hitung_persentil(kelas_interval, frekuensi, 78)

# Tabel distribusi frekuensi
print("Nama: Dinar Fauziah")
print("NIM : F52123078")
print("Kelas : C")
print("-" *65)
print("Kelas Interval | Frekuensi(f) | Frekuensi Kumulatif (F)")
for k, f, fk in zip(kelas_interval, frekuensi, frekuensi_kumulatif):
    print(f"{k} --> \t {f} -->\t\t{fk}")

# Hasil hitungann
print("_" *65)
print(f"Nilai Mean\t\t:{mean:.2f}")
print(f"Nilai Median\t\t:{median:.2f}")
print(f"Nilai Modus\t\t:{modus:.2f}")
print(f"Nilai Kuartil 2\t\t:{kuartil_2:.2f}")
print(f"Nilai Desil ke-8\t:{desil_8:.2f}")
print(f"Nilai Persentil ke-78\t:{persentil_78:.2f}")
