@push('styles')
    <style lang="scss">
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
    .selected-year {
        cursor: pointer;
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
      <select wire:model.change="selectedYear" class="selected-year form-select w-auto border-0 shadow-sm">
          @foreach($years as $year)
              <option class="border-0" value="{{ $year }}">{{ $year }}</option>
          @endforeach
      </select>
    </div>
    <canvas id="borrowBookChart" class="bg-white rounded-3 shadow"></canvas>
    {{-- <div class="position-relative">
        <canvas id="borrowBookChart" class="bg-white rounded-3 shadow"></canvas>
        <div class="position-absolute top-50 start-50 translate-middle" wire:loading wire:target="selectedYear" style="display: none">
            <div class="spinner-border text-primary" role="status"></div>
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> --}}
  </div>
  <div class="row mt-5">
    <h3 class="mb-4">Top 10 Tables</h3>
    <div class="col-lg-6">
        <x-tables.table tableClass="table-striped" :columns="['Rank', 'Full Name', 'Borrow Count']"> 
            @foreach($topMembers as $index => $member)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $member->member->full_name }}</td>
                    <td>{{ $member->borrow_count }}</td>
                </tr>
            @endforeach
        </x-tables.table>
    </div>
    <div class="col-lg-6">    
        <x-tables.table tableClass="table-striped" :columns="['Rank', 'Title', 'Borrow Count']">
            <tbody>
                @foreach($topBooks as $index => $book)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ Str::limit($book->title, 40) }}</td>
                        <td>{{ $book->borrow_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </x-tables.table>
    </div>
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

