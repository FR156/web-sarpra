# Web-Sarpra

Web-Sarpra adalah platform website untuk memantau dan mengelola peminjaman alat di sekolah. Web-Sarpra memudahkan sekolah dalam proses dan pendataan peminjaman alat. Platform ini dibuat menggunakan Laravel 12 dan database MySQL. 

## Fitur
- Login terkontrol dengan kredensial yang diberikan oleh admin
- Fitur peminjaman terstruktur yang memudahkan proses pendataan peminjaman alat
- Dashboard petugas dan admin untuk melihat ringkasan data peminjaman dan alat
- Pengelolaan barang dan alat
- Manajemen pengguna 
- Laporan data peminjaman dan alat 
- Monitoring log sistem

## ERD 
![ERD](doc-image/ERD_Web-Sarpra.png)

## Dokumentasi Fungsi / Prosedur


## Debugging 
Error pada penetapan denda pada saat pengembalian barang peminjaman: 
![error](doc-images/Debugging/fine_error/1.png)
pada saat menetapkan denda, data tidak masuk di database
![error](doc-images/Debugging/fine_error/2.png)
![error](doc-images/Debugging/fine_error/3.png)
ternyata ada yang salah pada beberapa baris kode, terutama di bagian fine_amount dan fine_reason tidak didaftarkan pada fungsi update record
setelah diperbaiki, maka fitur denda sudah bisa berjalan dengan benar 
![error](doc-images/Debugging/fine_error/4.png)
dan sudah bisa masuk ke database 
![error](doc-images/Debugging/fine_error/5.png)

## Pengujian dan Tangkapan Layar Hasil Uji 
1. Auth (Login) Admin & staff
![img](doc-images/Auth_Cycles/welcome.png)
Klik tombol Login di atas kanan, lalu akan diarahkan ke page login dari laravel/breeze seperti ini
![img](doc-images/Auth_Cycles/Admin%20&%20Staff/1.png)
lalu masukkan kredensial loginnya
![img](doc-images/Auth_Cycles/Admin%20&%20Staff/2.png)
jika salah, maka akan ada error dan diminta untuk memasukkan kembali kredensial yang benar
![img](doc-images/Auth_Cycles/Admin%20&%20Staff/3.png)
jika benar, akan langsung diarahkan ke page dashboard admin / staff
![img](doc-images/Auth_Cycles/Admin%20&%20Staff/4.png)
Admin / staff juga bisa mengubah passwordnya.

2. Auth (Login) Peminjam 
![img](doc-images/Auth_Cycles/Borrower/1.png)
Masukkan kredensial peminjam yang benar
![img](doc-images/Auth_Cycles/Borrower/2.png)
lalu akan diarahkan ke page dashboard peminjam, dan peminjam bisa meminjam barang

3. Menambahkan barang dan unit
Pada dashboard admin, klik menu navigasi barang, lalu klik tombol New Item untuk menambah barang
![img](doc-images/Item_Cycles/1.png)
lalu masukan informasi atau data barang
![img](doc-images/Item_Cycles/2.png)
Setelah barang dibuat, maka admin bisa menambahkan unit dengan menekan tombol Add Unit
![img](doc-images/Item_Cycles/3.png)
masukkan banyak unit yang mau ditambah
![img](doc-images/Item_Cycles/4.png)
lalu akan secara otomatis ter-generate unit sesuai dengan jumlah yang dimasukkan

4. Pinjam dan kembalikan alat
![img](doc-images/Loan_Cycles/Borrower/1.png)
Peminjam membuka page katalog barang untuk memilih barang yang akan dipinjam
![img](doc-images/Loan_Cycles/Borrower/2.png)
lalu masukkan jumlah barang, dan klik tombol tambah ke keranjang
![img](doc-images/Loan_Cycles/Borrower/3.png)
pada page keranjang, masukan alasan, tanggal memulai, dan mengembalikan barang, lalu klik tombol ajukan peminjaman
![img](doc-images/Loan_Cycles/Borrower/4.png)
maka akan secara otomatis diarahkan ke page daftar peminjaman milik peminjam
![img](doc-images/Loan_Cycles/Borrower/5.png)
pada tabel itu juga tertera informasi tambahan seperti status dan denda.
Lalu pada sisi approver, buka panel dashboard
![img](doc-images/Loan_Cycles/Approver/1.png)
pilih peminjaman yang telah direquest oleh peminjam
![img](doc-images/Loan_Cycles/Approver/2.png)
lalu approver bisa memilih assign unit secara otomatis, atau assign unit sendiri (manual), untuk case ini, assign manual
![img](doc-images/Loan_Cycles/Approver/3.png)
pilih unit yang mau di assign sesuai yang dibutuhkan
![img](doc-images/Loan_Cycles/Approver/4.png)
lalu peminjaman bisa dimulai
![img](doc-images/Loan_Cycles/Approver/5.png)
setelah peminjaman siap, approver bisa membuat record bukti bahwa peminjaman dikembalikan, bisa juga ditetapkan biaya denda dan alasannya
![img](doc-images/Loan_Cycles/Approver/6.png)
setelah itu, status pembayaran denda bisa diubah 

5. Mengelola User
![img](doc-images/User_Management/1.png)
masuk ke page user, lalu klik tombol Add user
![img](doc-images/User_Management/2.png)
masukan informasi, role, dan kredensial user
![img](doc-images/User_Management/3.png)
lalu user yang baru akan muncul di tabel

6. Ekspor laporan
![img](doc-images/Report/1.png)
masuk ke page laporan untuk melihat ringkasan singkat dan membuat laporan dengan cara menekan tombol Ekspor sesuai yang dibutuhkan
![img](doc-images/Report/2.png)
lalu laporan yang sudah diekspor ke pdf akan diunduh