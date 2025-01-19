<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.3.1/dist/fullcalendar.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.3.1/dist/fullcalendar.min.js"></script>
<style>
  /* Styling untuk Container Kalender */
  .calendar-container {
    padding: 20px;
    text-align: center;
    border-radius: 10px;
    background-color: #f4f4f4;
  }

  /* Styling untuk Waktu */
  .time-display {
    font-size: 1.2em;
    margin-top: 10px;
    font-weight: bold;
    color: #333;
  }

  /* Styling untuk Kalender */
  .calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    grid-gap: 5px;
    margin-top: 20px;
    background-color: #fff;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  /* Styling untuk Hari dalam Kalender */
  .calendar .day {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
    text-align: center;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
    font-size: 1em;
    height: 50px; /* Menetapkan tinggi yang konsisten */
  }

  .calendar .day-name {
    font-weight: bold;
    color: #007bff;
    font-size: 0.9em;
  }

  .calendar .day-number {
    font-size: 1.2em;
    color: #333;
  }

  /* Styling untuk Navigasi Bulan */
  .calendar-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }

  .calendar-nav button {
    padding: 8px 15px;
    border: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1em;
    transition: background-color 0.3s;
  }

  .calendar-nav button:hover {
    background-color: #0056b3;
  }

  /* Highlight untuk Hari Ini */
  .today {
    background-color: #007bff;
    color: white !important;
  }

  /* Styling untuk tanggal dari bulan sebelumnya */
  .previous-month-day {
    color: #bbb;
  }

  /* Responsif untuk perangkat lebih kecil */
  @media (max-width: 768px) {
    .calendar-nav button {
      font-size: 0.9em;
      padding: 6px 12px;
    }

    .calendar .day {
      padding: 8px;
      font-size: 0.9em;
    }

    .calendar .day-name {
      font-size: 0.8em;
    }

    .calendar .day-number {
      font-size: 1em;
    }
  }

  /* Responsif untuk perangkat sangat kecil */
  @media (max-width: 480px) {
    .calendar-nav button {
      font-size: 0.8em;
      padding: 5px 10px;
    }

    .calendar .day {
      padding: 6px;
      font-size: 0.8em;
    }

    .calendar .day-name {
      font-size: 0.7em;
    }

    .calendar .day-number {
      font-size: 0.9em;
    }
  }
</style>


<div class="pagetitle">
      <h1>Dashboard Penerimaan</h1>
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
                    fetch('<?= base_url("Dashboard3/hitung_saldo") ?>', {
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
                  fetch('<?= base_url("Dashboard3/hitung_pengeluaran") ?>', {
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
                            <li><a class="dropdown-item chart-filter" href="#" data-filter="hari_ini">Hari ini</a></li>
                            <li><a class="dropdown-item chart-filter" href="#" data-filter="minggu_ini">Minggu ini</a></li>
                            <li><a class="dropdown-item chart-filter" href="#" data-filter="bulan_ini">Bulan ini</a></li>
                        </ul>
                    </div>
                    <div class="card-body pb-0">
                        <h5 class="card-title">Penerimaan <span id="filterText">| Hari ini</span></h5>

                        <!-- Tempat Pie Chart -->
                        <div id="penerimaanChart" style="min-height: 400px;" class="echart"></div>
                    </div>
                </div>
                <!-- End Card -->
            </div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                  const penerimaanChartElement = document.querySelector("#penerimaanChart");
                  const filterText = document.querySelector("#filterText");

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
                          filterText.textContent = `| ${filterTextMap[filter]}`;

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
                  document.querySelectorAll('.chart-filter').forEach(item => {
                      item.addEventListener('click', function () {
                          const filter = this.getAttribute('data-filter');
                          fetchPenerimaanData(filter);
                      });
                  });
              });
          </script>
        <div class="col-6">
          <div class="card">
            <div class="calendar-container">
              <!-- Waktu Saat Ini -->
              <div id="current-time" class="time-display"></div>

              <!-- Kalender -->
              <div id="calendar" class="calendar"></div>
            </div>
          </div>
        </div>
        <script>
          // Mendapatkan elemen DOM
          const calendarEl = document.getElementById('calendar');
          const timeDisplayEl = document.getElementById('current-time');

          // Mendapatkan tanggal dan waktu saat ini
          function updateTime() {
            const currentTime = new Date();
            const hours = currentTime.getHours().toString().padStart(2, '0');
            const minutes = currentTime.getMinutes().toString().padStart(2, '0');
            const seconds = currentTime.getSeconds().toString().padStart(2, '0');
            
            const timeString = `${hours}:${minutes}:${seconds}`;
            timeDisplayEl.textContent = `Waktu Saat Ini: ${timeString}`;
          }

          // Menampilkan bulan dan tahun di kalender
          function renderCalendar(month, year) {
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDayOfMonth = new Date(year, month, 1).getDay();
            const currentDate = new Date();
            const today = currentDate.getDate();
            const todayMonth = currentDate.getMonth();
            const todayYear = currentDate.getFullYear();

            calendarEl.innerHTML = ''; // Kosongkan kalender sebelumnya
            
            // Navigasi Bulan
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
            const calendarNav = document.createElement('div');
            calendarNav.classList.add('calendar-nav');
            calendarNav.innerHTML = `
              <button id="prev-month">←</button>
              <span>${monthNames[month]} ${year}</span>
              <button id="next-month">→</button>
            `;
            calendarEl.appendChild(calendarNav);
            
            // Nama hari
            const dayNames = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
            const daysRow = document.createElement('div');
            dayNames.forEach(day => {
              const dayName = document.createElement('div');
              dayName.classList.add('day', 'day-name');
              dayName.textContent = day;
              daysRow.appendChild(dayName);
            });
            calendarEl.appendChild(daysRow);
            
            // Hari-hari dalam bulan
            let currentDay = 1;
            let dayCounter = 0;

            // Tanggal bulan sebelumnya yang masih ada di minggu pertama
            const prevMonthDays = [];
            if (firstDayOfMonth !== 0) {
              const prevMonth = new Date(year, month, 0); // Bulan sebelumnya
              const prevMonthDaysCount = prevMonth.getDate();
              for (let i = prevMonthDaysCount - (firstDayOfMonth - 1); i <= prevMonthDaysCount; i++) {
                prevMonthDays.push(i);
              }
            }
            
            for (let i = 0; i < 6; i++) { // Minggu ada 6 baris maksimal
              const weekRow = document.createElement('div');
              for (let j = 0; j < 7; j++) {
                const dayCell = document.createElement('div');
                dayCell.classList.add('day');
                
                if (i === 0 && prevMonthDays.length > 0) {
                  const prevDay = prevMonthDays.shift();
                  dayCell.innerHTML = `<span class="day-number previous-month-day">${prevDay}</span>`;
                  weekRow.appendChild(dayCell);
                } else if (currentDay <= daysInMonth) {
                  dayCell.innerHTML = `<span class="day-number">${currentDay}</span>`;
                  // Tambahkan kelas 'today' jika hari ini
                  if (currentDay === today && month === todayMonth && year === todayYear) {
                    dayCell.classList.add('today');
                  }
                  weekRow.appendChild(dayCell);
                  currentDay++;
                }
              }
              calendarEl.appendChild(weekRow);
            }

            // Event untuk navigasi bulan
            document.getElementById('prev-month').onclick = () => {
              month = (month - 1 + 12) % 12;
              if (month === 11) year--;
              renderCalendar(month, year);
            };
            
            document.getElementById('next-month').onclick = () => {
              month = (month + 1) % 12;
              if (month === 0) year++;
              renderCalendar(month, year);
            };
          }

          // Panggil fungsi untuk memperbarui waktu setiap detik
          setInterval(updateTime, 1000);

          // Render kalender untuk bulan ini
          const currentDate = new Date();
          renderCalendar(currentDate.getMonth(), currentDate.getFullYear());

        </script>
      </div>
    </section>