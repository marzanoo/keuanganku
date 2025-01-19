<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.3.1/dist/fullcalendar.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.3.1/dist/fullcalendar.min.js"></script>

<div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Saldo Card -->
            <div class="col-xxl-4 col-xl-12">
                <div class="card info-card customers-card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
                            <li><a class="dropdown-item saldo-filter" data-filter="total" href="#">Total</a></li>
                            <li><a class="dropdown-item saldo-filter" data-filter="Rekening" href="#">Rekening</a></li>
                            <li><a class="dropdown-item saldo-filter" data-filter="Cash" href="#">Tunai</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Saldo <span id="saldoFilterText">| Total</span></h5>

                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cash"></i>
                            </div>
                            <div class="ps-3">
                                <!-- Saldo Amount yang akan di-update secara dinamis -->
                                <h6 id="saldoAmount">Rp 0</h6>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Menambahkan event listener untuk setiap pilihan filter
                document.querySelectorAll('.saldo-filter').forEach(item => {
                    item.addEventListener('click', function () {
                        const filter = this.getAttribute('data-filter'); // Ambil filter dari data-filter
                        const filterText = document.querySelector("#saldoFilterText");
                        filterText.textContent = `| ${this.textContent.trim()}`; // Ubah teks filter

                        // Panggil fungsi untuk fetch saldo berdasarkan filter
                        fetchSaldo(filter);
                    });
                });

                // Fungsi untuk mengambil saldo dari server dan mengupdate tampilan
                // Fungsi untuk memanggil server dan mengupdate saldo
                function fetchSaldo(filter) {
                    fetch('<?= base_url("Dashboard1/hitung_saldo") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ filter })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Debugging response data
                        console.log(data);  // Cek apakah data yang diterima benar

                        // Ambil saldo dari data yang diterima
                        const saldo = data.saldo;

                        // Format angka menjadi xxx.xxx.xxx,00
                        const formattedSaldo = new Intl.NumberFormat('id-ID', {
                            style: 'decimal',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(saldo);

                        // Update saldo yang ditampilkan di halaman
                        const saldoAmount = document.querySelector("#saldoAmount");
                        saldoAmount.textContent = `Rp ${formattedSaldo}`;
                    })
                    .catch(error => console.error('Error:', error));
                }
                // Panggil fungsi untuk mendapatkan saldo total saat halaman pertama kali dimuat
                fetchSaldo('total');

            </script>
            <!-- Penerimaan Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item penerimaan-filter" data-filter="hari_ini" href="#">Hari ini</a></li>
                    <li><a class="dropdown-item penerimaan-filter" data-filter="minggu_ini" href="#">Minggu ini</a></li>
                    <li><a class="dropdown-item penerimaan-filter" data-filter="bulan_ini" href="#">Bulan ini</a></li>
                    <li><a class="dropdown-item penerimaan-filter" data-filter="tahun_ini" href="#">Tahun ini</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Penerimaan <span id="penerimaanFilterText">| Hari ini</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-receipt"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="penerimaanAmount">Rp 0</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Pendapatan Card -->
            <script>
              document.querySelectorAll('.penerimaan-filter').forEach(item => {
                  item.addEventListener('click', function () {
                      const filter = this.getAttribute('data-filter'); // Ambil filter dari data-filter
                      const filterText = document.querySelector("#penerimaanFilterText");
                      filterText.textContent = `| ${this.textContent}`; // Ubah teks filter

                      // Panggil fungsi untuk fetch penerimaan berdasarkan filter
                      fetchPenerimaan(filter);
                  });
              });

              // Fungsi untuk memanggil server dan mengupdate penerimaan
              function fetchPenerimaan(filter) {
                  fetch('<?= base_url("Dashboard3/hitung_penerimaan") ?>', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json'
                      },
                      body: JSON.stringify({ filter })
                  })
                  .then(response => response.json())
                  .then(data => {
                      // Ambil penerimaan dari data yang diterima
                      const penerimaan = data.penerimaan;

                      // Format angka menjadi xxx.xxx.xxx,00
                      const formattedPenerimaan = new Intl.NumberFormat('id-ID', {
                          style: 'decimal',
                          minimumFractionDigits: 2,
                          maximumFractionDigits: 2
                      }).format(penerimaan);

                      // Update penerimaan yang ditampilkan di halaman
                      const penerimaanAmount = document.querySelector("#penerimaanAmount");
                      penerimaanAmount.textContent = `Rp ${formattedPenerimaan}`;
                  })
                  .catch(error => console.error('Error:', error));
              }

              // Panggil fungsi untuk mendapatkan penerimaan untuk hari ini saat halaman pertama kali dimuat
              fetchPenerimaan('hari_ini');

            </script>


            <!-- Pengeluaran Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item pengeluaran-filter" data-filter="hari_ini" href="#">Hari ini</a></li>
                    <li><a class="dropdown-item pengeluaran-filter" data-filter="minggu_ini" href="#">Minggu ini</a></li>
                    <li><a class="dropdown-item pengeluaran-filter" data-filter="bulan_ini" href="#">Bulan ini</a></li>
                    <li><a class="dropdown-item pengeluaran-filter" data-filter="tahun_ini" href="#">Tahun ini</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Pengeluaran <span id="pengeluaranFilterText">| Hari ini</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-calculator"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="pengeluaranAmount">Rp 0</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- End Transaksi -->
            <script>
              // Menambahkan event listener untuk setiap filter pengeluaran
              document.querySelectorAll('.pengeluaran-filter').forEach(item => {
                  item.addEventListener('click', function () {
                      const filter = this.getAttribute('data-filter'); // Ambil filter dari data-filter
                      const filterText = document.querySelector("#pengeluaranFilterText");
                      filterText.textContent = `| ${this.textContent}`; // Ubah teks filter

                      // Panggil fungsi untuk fetch pengeluaran berdasarkan filter
                      fetchPengeluaran(filter);
                  });
              });

              // Fungsi untuk memanggil server dan mengupdate pengeluaran
              function fetchPengeluaran(filter) {
                  fetch('<?= base_url("Dashboard1/hitung_pengeluaran") ?>', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json'
                      },
                      body: JSON.stringify({ filter })
                  })
                  .then(response => response.json())
                  .then(data => {
                      // Ambil jumlah pengeluaran dari data yang diterima
                      const pengeluaran = data.pengeluaran;

                      // Format angka menjadi xxx.xxx.xxx,00
                      const formattedPengeluaran = new Intl.NumberFormat('id-ID', {
                          style: 'decimal',
                          minimumFractionDigits: 2,
                          maximumFractionDigits: 2
                      }).format(pengeluaran);

                      // Update pengeluaran yang ditampilkan di halaman
                      const pengeluaranAmount = document.querySelector("#pengeluaranAmount");
                      pengeluaranAmount.textContent = `Rp ${formattedPengeluaran}`;
                  })
                  .catch(error => console.error('Error:', error));
              }

              // Panggil fungsi untuk mendapatkan pengeluaran dengan filter 'hari_ini' saat halaman pertama kali dimuat
              fetchPengeluaran('hari_ini');
            </script>

            <div class="col-lg-6">
                <!-- Card untuk Penerimaan -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
                            <li><a class="dropdown-item chart-penerimaan-filter" href="#" data-filter="hari_ini">Hari ini</a></li>
                            <li><a class="dropdown-item chart-penerimaan-filter" href="#" data-filter="minggu_ini">Minggu ini</a></li>
                            <li><a class="dropdown-item chart-penerimaan-filter" href="#" data-filter="bulan_ini">Bulan ini</a></li>
                        </ul>
                    </div>
                    <div class="card-body pb-0">
                        <h5 class="card-title">Penerimaan <span id="filterTextPenerimaan">| Hari ini</span></h5>

                        <!-- Tempat Pie Chart -->
                        <div id="penerimaanChart" style="min-height: 400px;" class="echart"></div>
                    </div>
                </div>
                <!-- End Card -->
            </div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                  const penerimaanChartElement = document.querySelector("#penerimaanChart");
                  const filterText = document.querySelector("#filterTextPenerimaan");

                  // Inisialisasi chart
                  const penerimaanChart = echarts.init(penerimaanChartElement);

                  // Fungsi untuk memuat data
                  function fetchPenerimaanData(filter) {
                      fetch('<?= base_url("Dashboard3/getFilteredData") ?>', {
                          method: 'POST',
                          headers: { 'Content-Type': 'application/json' },
                          body: JSON.stringify({ filter })
                      })
                      .then(response => response.json())
                      .then(data => {
                          // Format data untuk chart
                          const chartData = data.map(item => ({
                              value: item.value,
                              name: item.sumber
                          }));

                          // Perbarui teks filter
                          const filterTextMap = {
                              hari_ini: 'Hari ini',
                              minggu_ini: 'Minggu ini',
                              bulan_ini: 'Bulan ini'
                          };
                          filterTextPenerimaan.textContent = `| ${filterTextMap[filter]}`;

                          // Update chart
                          penerimaanChart.setOption({
                              tooltip: {
                                  trigger: 'item',
                                  formatter: '{a} <br/>{b}: {c} ({d}%)'
                              },
                              legend: {
                                  top: '5%',
                                  left: 'center',
                                  data: chartData.map(item => item.name)
                              },
                              series: [{
                                  name: 'Penerimaan',
                                  type: 'pie',
                                  radius: ['40%', '70%'],
                                  label: {
                                      show: false
                                  },
                                  emphasis: {
                                      label: {
                                          show: true,
                                          fontSize: '18',
                                          fontWeight: 'bold',
                                          formatter: '{b}\n{c} ({d}%)'
                                      }
                                  },
                                  labelLine: {
                                      show: false
                                  },
                                  data: chartData
                              }]
                          });
                      })
                      .catch(error => console.error('Error:', error));
                  }

                  // Muat data default (Hari ini)
                  fetchPenerimaanData('hari_ini');

                  // Event listener untuk filter
                  document.querySelectorAll('.chart-penerimaan-filter').forEach(item => {
                      item.addEventListener('click', function () {
                          const filter = this.getAttribute('data-filter');
                          fetchPenerimaanData(filter);
                      });
                  });
              });
          </script>
          <div class="col-lg-6">
            <!-- Card untuk Alokasi Pengeluaran -->
            <div class="card">
                <div class="filter">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                            <h6>Filter</h6>
                        </li>
                        <li><a class="dropdown-item chart-filter" href="#" data-filter="hari_ini">Hari ini</a></li>
                        <li><a class="dropdown-item chart-filter" href="#" data-filter="minggu_ini">Minggu ini</a></li>
                        <li><a class="dropdown-item chart-filter" href="#" data-filter="bulan_ini">Bulan ini</a></li>
                    </ul>
                </div>
                <div class="card-body pb-0">
                    <h5 class="card-title">Alokasi Pengeluaran <span id="filterTextPengeluaran">| Hari ini</span></h5>

                    <!-- Tempat Pie Chart -->
                    <div id="pengeluaranChart" style="min-height: 400px;" class="echart"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            const pengeluaranChartElement = document.querySelector("#pengeluaranChart");
                            const filterText = document.querySelector("#filterTextPengeluaran");

                            // Inisialisasi chart
                            const pengeluaranChart = echarts.init(pengeluaranChartElement);

                            // Fungsi untuk memuat data
                            function fetchFilteredData(filter) {
                                fetch('<?= base_url("Dashboard1/getFilteredData") ?>', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ filter })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const chartData = data.map(item => ({
                                        value: item.value,
                                        name: item.output
                                    }));

                                    // Update teks filter
                                    const filterTextMap = {
                                        hari_ini: 'Hari ini',
                                        minggu_ini: 'Minggu ini',
                                        bulan_ini: 'Bulan ini'
                                    };
                                    filterText.textContent = `| ${filterTextMap[filter]}`;

                                    // Update chart
                                    pengeluaranChart.setOption({
                                        tooltip: {
                                            trigger: 'item',
                                            formatter: '{a} <br/>{b}: {c} ({d}%)'
                                        },
                                        legend: {
                                            top: '5%',
                                            left: 'center',
                                            data: chartData.map(item => item.name)
                                        },
                                        series: [{
                                            name: 'Pengeluaran',
                                            type: 'pie',
                                            radius: ['40%', '70%'],
                                            label: {
                                                show: false
                                            },
                                            emphasis: {
                                                label: {
                                                    show: true,
                                                    fontSize: '18',
                                                    fontWeight: 'bold',
                                                    formatter: '{b}\n{c} ({d}%)'
                                                }
                                            },
                                            labelLine: {
                                                show: false
                                            },
                                            data: chartData
                                        }]
                                    });
                                })
                                .catch(error => console.error('Error:', error));
                            }

                            // Muat data default
                            fetchFilteredData('hari_ini');

                            // Event listener untuk filter
                            document.querySelectorAll('.chart-filter').forEach(item => {
                                item.addEventListener('click', function () {
                                    const filter = this.getAttribute('data-filter');
                                    fetchFilteredData(filter);
                                });
                            });
                        });
                    </script>
                </div>
            </div>
            <!-- End Card -->
        </div>
      </div>
    </section>