<?php defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Lap_transaksi extends CI_Controller {

	public function __construct() {
        parent::__construct();
        check_not_login();
        $this->load->model('m_lap_transaksi');
        $this->load->library('session');
    }
	
	public function index()
	{
        $data['outputs'] = $this->db->get('m_output')->result();
		$this->template->load('template', 'laporan_transaksi/v_laporan_transaksi', $data);
	}
	public function export_excel_pendapatan() {
        // Ambil data pendapatan dari model
        $data_pendapatan = $this->m_lap_transaksi->get_pendapatan();
    
        // Buat file spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Header kolom
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Akun ID');
        $sheet->setCellValue('C1', 'Jumlah');
        $sheet->setCellValue('D1', 'Sumber');
        $sheet->setCellValue('E1', 'Jenis');
    
        // Isi data pendapatan
        $row = 2;
        foreach ($data_pendapatan as $pendapatan) {
            $sheet->setCellValue('A' . $row, $pendapatan->tanggal);
            $sheet->setCellValue('B' . $row, $pendapatan->akun_id);
            $sheet->setCellValue('C' . $row, $pendapatan->jumlah);
            $sheet->setCellValue('D' . $row, $pendapatan->sumber);
            $sheet->setCellValue('E' . $row, $pendapatan->jenis);
            $row++;
        }
    
        // Menyimpan file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_pendapatan_' . date('Ymd') . '.xlsx';
    
        // Set header untuk download file
        ob_end_clean(); // Hapus buffer output jika ada
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        // Menyimpan ke output untuk diunduh
        $writer->save('php://output');
        exit(); // Hentikan eksekusi setelah pengunduhan
    }

    public function export_excel_pengeluaran_cash() {
        // Ambil nilai filter dari query string
        $startDateInput = $this->input->get('start_date');
        $endDateInput = $this->input->get('end_date');
        $outputFilter = $this->input->get('output') ?: null;
    
        // Tentukan tanggal
        if ($startDateInput && $endDateInput) {
            $startDate = $startDateInput;
            $endDate = $endDateInput;
        } else {
            list($startDate, $endDate) = $this->getDateRange('semua_data');
        }
    
        // Ambil data dari database
        $dataTrx = $this->m_lap_transaksi->getLaporanData($startDate, $endDate, $outputFilter);
        $dataRek = $this->m_lap_transaksi->getRekPengeluaran($startDate, $endDate);
    
        // Siapkan spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pengeluaran');
    
        // Header laporan
        $sheet->setCellValue('A1', 'Laporan Pengeluaran Cash');
        $sheet->setCellValue('A2', 'Periode: ' . $startDate . ' s/d ' . $endDate);
        $sheet->setCellValue('A3', 'Filter Output: ' . ($outputFilter ?: 'Semua Output'));
        $sheet->mergeCells('A1:H1'); // Gabungkan header untuk laporan
        
        $sheet->getColumnDimension('A')->setWidth(15); // Tanggal
        $sheet->getColumnDimension('B')->setWidth(40); // Deskripsi
        $sheet->getColumnDimension('C')->setWidth(20); // Output
        $sheet->getColumnDimension('D')->setWidth(20); // Akun
        $sheet->getColumnDimension('E')->setWidth(20); // Pajak
        $sheet->getColumnDimension('F')->setWidth(20); // Uang Masuk
        $sheet->getColumnDimension('G')->setWidth(20); // Uang Keluar
        $sheet->getColumnDimension('H')->setWidth(20); // Saldo
        $sheet->getColumnDimension('I')->setWidth(30); // Keterangan

        if (!$outputFilter) {
            // Header kolom (jika tidak ada filter)
            $sheet->setCellValue('A5', 'Tanggal');
            $sheet->setCellValue('B5', 'Deskripsi');
            $sheet->setCellValue('C5', 'Output');
            $sheet->setCellValue('D5', 'Akun');
            $sheet->setCellValue('E5', 'Pajak');
            $sheet->setCellValue('F5', 'Uang Masuk');
            $sheet->setCellValue('G5', 'Uang Keluar');
            $sheet->setCellValue('H5', 'Saldo');
            $sheet->setCellValue('I5', 'Keterangan');
            
            $sheet->getStyle('A5:I5')->getFont()->setBold(true);

            // Gabungkan data
            $combinedData = [];
            foreach ($dataRek as $rekItem) {
                if ($rekItem->jenis === 'Cash') {
                    $combinedData[] = (object) [
                        'tanggal' => $rekItem->tanggal,
                        'deskripsi' => $rekItem->deskripsi,
                        'output' => null,
                        'akun' => null,
                        'pajak' => null,
                        'uang_masuk' => $rekItem->jumlah2,
                        'uang_keluar' => null,
                        'saldo' => null,
                        'keterangan' => null
                    ];
                }
            }
            foreach ($dataTrx as $trxItem) {
                if ($trxItem->jenis === 'Cash') {
                    $combinedData[] = (object) [
                        'tanggal' => $trxItem->tanggal,
                        'deskripsi' => $trxItem->deskripsi,
                        'output' => $trxItem->output,
                        'akun' => $trxItem->akun_id,
                        'pajak' => $trxItem->nama_pajak,
                        'uang_masuk' => null,
                        'uang_keluar' => $trxItem->jumlah,
                        'saldo' => null,
                        'keterangan' => $trxItem->keterangan
                    ];
                }
            }
    
            // Urutkan dan hitung saldo
            usort($combinedData, function ($a, $b) {
                return strtotime($a->tanggal) - strtotime($b->tanggal);
            });
    
            $totalSaldo = 0;
            $totalUangKeluar = 0;  // Menambahkan variabel total uang keluar
            foreach ($combinedData as &$dataItem) {
                if ($dataItem->uang_masuk !== null) {
                    $totalSaldo += $dataItem->uang_masuk;
                }
                if ($dataItem->uang_keluar !== null) {
                    $totalSaldo -= $dataItem->uang_keluar;
                    $totalUangKeluar += $dataItem->uang_keluar; // Menambahkan perhitungan total uang keluar
                }
                $dataItem->saldo = $totalSaldo;
            }
            unset($dataItem);
    
            // Isi data ke Excel
            $row = 6;
            foreach ($combinedData as $dataItem) {
                $sheet->setCellValue('A' . $row, $dataItem->tanggal);
                $sheet->setCellValue('B' . $row, $dataItem->deskripsi);
                $sheet->setCellValue('C' . $row, $dataItem->output);
                $sheet->setCellValue('D' . $row, $dataItem->akun);
                $sheet->setCellValue('E' . $row, $dataItem->pajak);
                $sheet->setCellValueExplicit('F' . $row, $dataItem->uang_masuk, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit('G' . $row, $dataItem->uang_keluar, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit('H' . $row, $dataItem->saldo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValue('I' . $row, $dataItem->keterangan);
                $row++;
            }
            // Menambahkan baris untuk total uang keluar
            $sheet->mergeCells('A' . $row . ':F' . $row); // Menggabungkan cell A, B, C, D, dan E untuk Total Jumlah
            $sheet->setCellValue('A' . $row, 'Total Uang Keluar');
            $sheet->setCellValueExplicit('G' . $row, $totalUangKeluar, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true); // Membuat teks "Total Jumlah" bold
            $sheet->getStyle("A$row:H$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('F6:F' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle('G6:G' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle('H6:H' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle("A5:I" . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('B5:B' . $row)->getAlignment()->setWrapText(true);  // Deskripsi
        } else {
            // Header kolom (jika ada filter)
            $sheet->setCellValue('A5', 'Tanggal');
            $sheet->setCellValue('B5', 'Deskripsi');
            $sheet->setCellValue('C5', 'Output');
            $sheet->setCellValue('D5', 'Akun');
            $sheet->setCellValue('E5', 'Pajak');
            $sheet->setCellValue('F5', 'Bruto');
            $sheet->setCellValue('G5', 'Netto');
            $sheet->setCellValue('H5', 'Keterangan');
            
            $sheet->getStyle('A5:H5')->getFont()->setBold(true);

            // Isi data hanya dari `dataTrx`
            $totalJumlah = 0;
            $row = 6;
            foreach ($dataTrx as $trxItem) {
                if ($trxItem->jenis === 'Cash') {
                    $sheet->setCellValue('A' . $row, $trxItem->tanggal);
                    $sheet->setCellValue('B' . $row, $trxItem->deskripsi);
                    $sheet->setCellValue('C' . $row, $trxItem->output);
                    $sheet->setCellValue('D' . $row, $trxItem->akun_id);
                    $sheet->setCellValue('E' . $row, $trxItem->nama_pajak);
                    $sheet->setCellValueExplicit('F' . $row, $trxItem->jumlah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('G' . $row, $trxItem->total, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->setCellValue('H' . $row, $trxItem->keterangan);
                    $totalJumlah += $trxItem->jumlah;
                    $row++;
                }
                
            }
            $sheet->mergeCells('A' . $row . ':E' . $row); // Menggabungkan cell A, B, C, D, dan E untuk Total Jumlah
            $sheet->setCellValue('A' . $row, 'Total'); // Menulis "Total Jumlah" di kolom A
            $sheet->setCellValueExplicit('F' . $row, $totalJumlah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true); // Membuat teks "Total Jumlah" bold
            $sheet->getStyle("A$row:F$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('F6:F' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle('G6:G' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle("A5:H" . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('B5:B' . $row)->getAlignment()->setWrapText(true);  // Deskripsi
        }
    
        // Menyimpan file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_pengeluaran_' . date('Ymd') . '.xlsx';
    
        // Header untuk download
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
        exit();
    }

    public function export_excel_pengeluaran_rekening() {
        // Ambil nilai filter dari query string
        $startDateInput = $this->input->get('start_date');
        $endDateInput = $this->input->get('end_date');
        $outputFilter = $this->input->get('output') ?: null;
    
        // Tentukan tanggal
        if ($startDateInput && $endDateInput) {
            $startDate = $startDateInput;
            $endDate = $endDateInput;
        } else {
            list($startDate, $endDate) = $this->getDateRange('semua_data');
        }
    
        // Ambil data dari database
        $dataTrx = $this->m_lap_transaksi->getLaporanData($startDate, $endDate, $outputFilter);
        $dataRek1 = $this->m_lap_transaksi->getRekPengeluaran($startDate, $endDate, $outputFilter);
        $dataRek = $this->m_lap_transaksi->getRekPengeluaran1($startDate, $endDate);
    
        // Siapkan spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pengeluaran');
    
        // Header laporan
        $sheet->setCellValue('A1', 'Laporan Pengeluaran Rekening');
        $sheet->setCellValue('A2', 'Periode: ' . $startDate . ' s/d ' . $endDate);
        $sheet->setCellValue('A3', 'Filter Output: ' . ($outputFilter ?: 'Semua Output'));
        $sheet->mergeCells('A1:H1'); // Gabungkan header untuk laporan
        
        $sheet->getColumnDimension('A')->setWidth(15); // Tanggal
        $sheet->getColumnDimension('B')->setWidth(40); // Deskripsi
        $sheet->getColumnDimension('C')->setWidth(20); // Output
        $sheet->getColumnDimension('D')->setWidth(20); // Akun
        $sheet->getColumnDimension('E')->setWidth(20); // Pajak
        $sheet->getColumnDimension('F')->setWidth(20); // Uang Masuk
        $sheet->getColumnDimension('G')->setWidth(20); // Uang Keluar
        $sheet->getColumnDimension('H')->setWidth(20); // Saldo
        $sheet->getColumnDimension('I')->setWidth(30); // Keterangan
        
        if (!$outputFilter) {
            // Header kolom (jika tidak ada filter)
            $sheet->setCellValue('A5', 'Tanggal');
            $sheet->setCellValue('B5', 'Deskripsi');
            $sheet->setCellValue('C5', 'Output');
            $sheet->setCellValue('D5', 'Akun');
            $sheet->setCellValue('E5', 'Pajak');
            $sheet->setCellValue('F5', 'Uang Masuk');
            $sheet->setCellValue('G5', 'Uang Keluar');
            $sheet->setCellValue('H5', 'Saldo');
            $sheet->setCellValue('I5', 'Keterangan');

            $sheet->getStyle('A5:I5')->getFont()->setBold(true);
    
            // Gabungkan data
            $combinedData = [];
            foreach ($dataRek as $rekItem) {
                if ($rekItem->jenis === 'Rekening') {
                    $combinedData[] = (object) [
                        'tanggal' => $rekItem->tanggal,
                        'deskripsi' => $rekItem->deskripsi,
                        'output' => null,
                        'akun' => null,
                        'pajak' => null,
                        'uang_masuk' => $rekItem->jumlah2,
                        'uang_keluar' => null,
                        'saldo' => null,
                        'keterangan' => null
                    ];
                }
            }
            foreach ($dataRek1 as $rekItem) {
                if ($rekItem->jenis === 'Cash') {
                    $combinedData[] = (object) [
                        'tanggal' => $rekItem->tanggal,
                        'deskripsi' => $rekItem->deskripsi,
                        'output' => null,
                        'akun' => null,
                        'pajak' => null,
                        'uang_masuk' => null,
                        'uang_keluar' => $rekItem->jumlah2,
                        'saldo' => null,
                        'keterangan' => null
                    ];
                }
            }
            foreach ($dataTrx as $trxItem) {
                if ($trxItem->jenis === 'Rekening') {
                    $combinedData[] = (object) [
                        'tanggal' => $trxItem->tanggal,
                        'deskripsi' => $trxItem->deskripsi,
                        'output' => $trxItem->output,
                        'akun' => $trxItem->akun_id,
                        'pajak' => $trxItem->nama_pajak,
                        'uang_masuk' => null,
                        'uang_keluar' => $trxItem->jumlah,
                        'saldo' => null,
                        'keterangan' => $trxItem->keterangan
                    ];
                }
            }
    
            // Urutkan dan hitung saldo
            usort($combinedData, function ($a, $b) {
                return strtotime($a->tanggal) - strtotime($b->tanggal);
            });
    
            $totalSaldo = 0;
            $totalUangKeluar = 0;  // Menambahkan variabel total uang keluar
            foreach ($combinedData as &$dataItem) {
                if ($dataItem->uang_masuk !== null) {
                    $totalSaldo += $dataItem->uang_masuk;
                }
                if ($dataItem->uang_keluar !== null) {
                    $totalSaldo -= $dataItem->uang_keluar;
                    $totalUangKeluar += $dataItem->uang_keluar; // Menambahkan perhitungan total uang keluar
                }
                $dataItem->saldo = $totalSaldo;
            }
            unset($dataItem);
    
            // Isi data ke Excel
            $row = 6;
            foreach ($combinedData as $dataItem) {
                $sheet->setCellValue('A' . $row, $dataItem->tanggal);
                $sheet->setCellValue('B' . $row, $dataItem->deskripsi);
                $sheet->setCellValue('C' . $row, $dataItem->output);
                $sheet->setCellValue('D' . $row, $dataItem->akun);
                $sheet->setCellValue('E' . $row, $dataItem->pajak);
                $sheet->setCellValueExplicit('F' . $row, $dataItem->uang_masuk, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit('G' . $row, $dataItem->uang_keluar, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValueExplicit('H' . $row, $dataItem->saldo, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $sheet->setCellValue('I' . $row, $dataItem->keterangan);
                $row++;
            }
    
            // Menambahkan baris untuk total uang keluar
            $sheet->mergeCells('A' . $row . ':F' . $row); // Menggabungkan cell A, B, C, D, dan E untuk Total Jumlah
            $sheet->setCellValue('A' . $row, 'Total Uang Keluar');
            $sheet->setCellValueExplicit('G' . $row, $totalUangKeluar, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true); // Membuat teks "Total Jumlah" bold
            $sheet->getStyle("A$row:H$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('F6:F' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle('G6:G' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle('H6:H' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle("A5:I" . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('B5:B' . $row)->getAlignment()->setWrapText(true);  // Deskripsi
            
        } else {
            // Header kolom (jika ada filter)
            $sheet->setCellValue('A5', 'Tanggal');
            $sheet->setCellValue('B5', 'Deskripsi');
            $sheet->setCellValue('C5', 'Output');
            $sheet->setCellValue('D5', 'Akun');
            $sheet->setCellValue('E5', 'Pajak');
            $sheet->setCellValue('F5', 'Bruto');
            $sheet->setCellValue('G5', 'Netto');
            $sheet->setCellValue('H5', 'Keterangan');

            $sheet->getStyle('A5:H5')->getFont()->setBold(true);
    
            // Isi data hanya dari `dataTrx`
            $totalJumlah = 0;  // Menambahkan variabel total jumlah
            $row = 6;
            foreach ($dataTrx as $trxItem) {
                if ($trxItem->jenis === 'Rekening') {
                    $sheet->setCellValue('A' . $row, $trxItem->tanggal);
                    $sheet->setCellValue('B' . $row, $trxItem->deskripsi);
                    $sheet->setCellValue('C' . $row, $trxItem->output);
                    $sheet->setCellValue('D' . $row, $trxItem->akun_id);
                    $sheet->setCellValue('E' . $row, $trxItem->nama_pajak);
                    $sheet->setCellValueExplicit('F' . $row, $trxItem->jumlah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->setCellValueExplicit('G' . $row, $trxItem->total, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $sheet->setCellValue('H' . $row, $trxItem->keterangan);
                    $totalJumlah += $trxItem->jumlah;  // Menambahkan perhitungan total jumlah
                    $row++;
                }
                
            }
    
            // Menambahkan baris untuk total jumlah
            $sheet->mergeCells('A' . $row . ':E' . $row); // Menggabungkan cell A, B, C, D, dan E untuk Total Jumlah
            $sheet->setCellValue('A' . $row, 'Total'); // Menulis "Total Jumlah" di kolom A
            $sheet->setCellValueExplicit('F' . $row, $totalJumlah, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true); // Membuat teks "Total Jumlah" bold
            $sheet->getStyle("A$row:F$row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $sheet->getStyle('F6:F' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle('G6:G' . $row)->getNumberFormat()->setFormatCode('[$Rp-421] #,##0');
            $sheet->getStyle("A5:H" . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('B5:B' . $row)->getAlignment()->setWrapText(true);  // Deskripsi
        }
        
        // Menyimpan file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_pengeluaran_' . date('Ymd') . '.xlsx';
    
        // Header untuk download
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        $writer->save('php://output');
        exit();
    }
        
    
    
    
    
    
    
    
    

    private function getDateRange($filter) {
        $startDate = '';
        $endDate = date('Y-m-d'); // Hari ini

        if ($filter === 'hari_ini') {
            $startDate = $endDate;
        } elseif ($filter === 'minggu_ini') {
            $startDate = date('Y-m-d', strtotime('-7 days'));
        } elseif ($filter === 'bulan_ini') {
            $startDate = date('Y-m-01');
        }

        return [$startDate, $endDate];
    }
}
