@push('styles')
    <style lang="scss">
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
    </style>
@endpush


<div class="mt-5 ">
  <div class="row g-2">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <x-cards.dashboard-card  count="{{ $totalUsers }}" icon="person-fill" >Users</x-cards.dashboard-card> 
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <x-cards.dashboard-card  count="{{ $totalMembers }}" icon="person-vcard-fill" >Members</x-cards.dashboard-card> 
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <x-cards.dashboard-card  count="{{ $totalBooks }}" icon="book-fill" >Books</x-cards.dashboard-card> 
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6">
        <x-cards.dashboard-card  count="{{ $totalBookCategories }}" icon="tags-fill" >Categories</x-cards.dashboard-card> 
    </div>
  </div>
  <div class="row mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Chart Borrow Book</h3>
      <select wire:model.live="selectedYear" class="form-select w-auto border-0 shadow-sm">
          @foreach($years as $year)
              <option class="border-0" value="{{ $year }}">{{ $year }}</option>
          @endforeach
      </select>
    </div>
    <canvas id="borrowBookChart" class="bg-white rounded-3 shadow"></canvas>
  </div>
</div>

@push('scripts')
    @assets
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endassets
<script>
  $(document).ready(function() {
      const ctx = $('#borrowBookChart');
      let chart;

      function renderChart(data) {

          if (chart) {
              chart.destroy();
          }
          chart = new Chart(ctx, {
              type: 'line',
              data: {
                  labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                  datasets: [{
                      label: 'Books Borrowed',
                      data: Object.values(data),
                      backgroundColor: 'rgba(147, 112, 219, 0.2)', 
                      borderColor: '#9370db', 
                      borderWidth: 2,
                      tension: 0.4, 
                      pointBackgroundColor: '#9370db', 
                      pointBorderColor: '#9370db', 
                      pointRadius: 5, 
                      pointHoverRadius: 7, 
                  }]
              },
              options: {
                  scales: {
                      y: {
                          beginAtZero: true
                      }
                  }
              }
          });
      }

      window.addEventListener('updateChart', function(event) {
          renderChart(event.detail[0]);
      });

      renderChart(@json($borrowData));
  });
</script>
@endpush

