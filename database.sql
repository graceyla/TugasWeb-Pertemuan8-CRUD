-- Database Tugas Rutin 8 - CRUD Inventaris
-- import lewat phpMyAdmin atau: mysql -u root < database.sql

DROP DATABASE IF EXISTS inventaris_db;
CREATE DATABASE inventaris_db;
USE inventaris_db;

-- tabel kategori
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL UNIQUE,
    deskripsi VARCHAR(255)
) ENGINE=InnoDB;

-- tabel supplier
CREATE TABLE supplier (
    id_supplier INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL,
    no_telp VARCHAR(20),
    alamat VARCHAR(255)
) ENGINE=InnoDB;

-- tabel produk (relasi ke kategori & supplier)
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    kode_produk VARCHAR(20) NOT NULL UNIQUE,
    nama_produk VARCHAR(100) NOT NULL,
    id_kategori INT NOT NULL,
    id_supplier INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_produk_kategori FOREIGN KEY (id_kategori)
        REFERENCES kategori(id_kategori) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_produk_supplier FOREIGN KEY (id_supplier)
        REFERENCES supplier(id_supplier) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- tabel log (buat bonus transaction pas delete)
CREATE TABLE log_aktivitas (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    aksi VARCHAR(20) NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ===== data seed =====

INSERT INTO kategori (nama_kategori, deskripsi) VALUES
('Elektronik', 'Barang elektronik kantor'),
('Alat Tulis', 'ATK seperti pulpen, kertas, dll'),
('Furniture', 'Meja, kursi, lemari'),
('Jaringan', 'Perangkat jaringan komputer'),
('Kebersihan', 'Alat dan bahan kebersihan');

INSERT INTO supplier (nama_supplier, no_telp, alamat) VALUES
('CV Maju Jaya', '081234567890', 'Jl. Merdeka No. 10, Bandung'),
('PT Sumber Elektronik', '082198765432', 'Jl. Gatot Subroto No. 5, Jakarta'),
('Toko Sinar Abadi', '085711223344', 'Jl. Diponegoro No. 21, Semarang'),
('UD Berkah Mebel', '087855667788', 'Jl. Raya Jepara No. 8, Jepara'),
('PT Netlink Indonesia', '081377889900', 'Jl. Sudirman No. 45, Surabaya');

INSERT INTO produk (kode_produk, nama_produk, id_kategori, id_supplier, stok, harga) VALUES
('PRD001', 'Laptop Asus Vivobook 14', 1, 2, 8, 7500000),
('PRD002', 'Pulpen Standard AE7 (1 box)', 2, 3, 40, 25000),
('PRD003', 'Kursi Kantor Ergonomis', 3, 4, 12, 850000),
('PRD004', 'Router TP-Link Archer C6', 4, 5, 6, 450000),
('PRD005', 'Sapu Ijuk', 5, 1, 20, 30000),
('PRD006', 'Printer Epson L3210', 1, 2, 4, 2300000),
('PRD007', 'Kertas HVS A4 80gr (1 rim)', 2, 3, 35, 55000);

INSERT INTO log_aktivitas (aksi, keterangan) VALUES
('INSERT', 'Data awal produk PRD001 dimasukkan'),
('INSERT', 'Data awal produk PRD002 dimasukkan'),
('INSERT', 'Data awal produk PRD003 dimasukkan'),
('INSERT', 'Data awal produk PRD004 dimasukkan'),
('INSERT', 'Data awal produk PRD005 dimasukkan');
